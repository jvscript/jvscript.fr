<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\StoreScript;
use App\Http\Requests\UpdateScript;
use App\Model\History;
use App\Model\Script;
use App\Model\User;
use App\Notifications\notifyStatus;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScriptController extends Controller
{

    //_TODO : retenir le filtre/sort en session/cookie utilisateur
    //_TODO : Event create / update : move code

    public function __construct()
    {
        parent::__construct();
        $this->model = new Script;
        $this->modelName = 'script';
    }

    /**
     * Store a script in db
     */
    public function storeScript(StoreScript $request)
    {
        $user = Auth::user();
        $script = Script::create($request->all());
        $script->slug = $this->slugify($script->name);

        if ($request->input("is_autor") == 'on') {
            $script->user_id = $user->id; //owner du script
            $script->autor = $user->name;
        }
        $script->poster_user_id = $user->id;

        //store photo_file or photo_url  storage
        if ($request->file('photo_file')) {
            $this->lib->storeImage($script, $request->file('photo_file'));
        } elseif ($request->filled('photo_url')) {
            $file = @file_get_contents($request->input('photo_url'));
            $this->lib->storeImage($script, $file);
        }

        $script->save();

        $message = "[new script] Nouveau script posté sur le site : " . route('script.show', ['slug' => $script->slug]);
        $this->lib->sendDiscord($message, $this->discord_url);
        if (!App::environment('testing', 'local')) {
            \Mail::raw($message, function ($message) {
                $message->to(env('ADMIN_EMAIL'))->subject("Nouveau script");
            });
        }

        return redirect(route('script.form'))->with("message", "Merci, votre script est en attente de validation.");
    }

    /**
     * admin or owner
     */
    public function updateScript(UpdateScript $request, $slug)
    {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($script->user_id);
        //update only this fields
        $toUpdate = ['autor', 'description', 'js_url', 'repo_url', 'don_url', 'website_url', 'topic_url', 'version'];
        if (Auth::user()->isAdmin()) {
            array_push($toUpdate, "user_id", "sensibility");
            if ($request->input('user_id') == '') {
                $request->merge(['user_id' => null]);
            } else {
                //force username of owner
                $request->merge(['autor' => User::find($request->input('user_id'))->name]);
            }
        }

        $script->fill($request->only($toUpdate));
        if ($request->filled('last_update')) {
            $script->last_update = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('last_update'));
        }

        if ($request->file('photo_file')) {
            $this->lib->storeImage($script, $request->file('photo_file'));
        } elseif ($request->filled('photo_url')) {
            $file = @file_get_contents($request->input('photo_url'));
            $this->lib->storeImage($script, $file);
        }

        $script->save();
        return redirect(route('script.show', ['slug' => $slug]));
    }

    public function validateItem($slug)
    {
        $item = $this->model::where('slug', $slug)->firstOrFail();
        $this->lib->adminOrFail();

        if ($item->status != 1) {
            $item->status = 1;
            $item->save();
            if ($item->poster_user_id != null) {
                $item->poster_user()->first()->notify(new notifyStatus($item));
            }
        }
        return redirect(route($this->modelName . '.show', ['slug' => $slug]));
    }

    public function refuse($slug)
    {
        $item = $this->model::where('slug', $slug)->firstOrFail();
        $this->lib->adminOrFail();

        if ($item->status != 2) {
            $item->status = 2;
            $item->save();
            if ($item->poster_user_id != null) {
                $item->poster_user()->first()->notify(new notifyStatus($item));
            }
        }
        return redirect(route($this->modelName . '.show', ['slug' => $slug]));
    }

    /**
     * Install : count & redirect
     */
    public function install($slug, Request $request)
    {
        $item = $this->model::where('slug', $slug)->firstOrFail();

        // protection referer to count
        if ($request->method() == 'POST' && str_contains($request->headers->get('referer'), $slug)) {
            $history = History::where(['ip' => $request->ip(), 'what' => $this->modelName . '_' . $slug, 'action' => 'install']);
            if ($history->count() == 0) {
                History::create(['ip' => $request->ip(), 'what' => $this->modelName . '_' . $slug, 'action' => 'install']);
                $item->install_count++;
                $item->save();
            }
        }
        return redirect($item->url);
    }

    /**
     * Note & redirect
     */
    public function note($slug, $note, Request $request)
    {
        $note = intval($note);
        if ($note > 0 && $note <= 5) {
            $item = $this->model::where('slug', $slug)->firstOrFail();
            //if no history note_count +1
            $history = History::where(['ip' => $request->ip(), 'what' => $this->modelName . '_' . $slug, 'action' => 'note']);
            if ($history->count() == 0) {
                History::create(['ip' => $request->ip(), 'what' => $this->modelName . '_' . $slug, 'action' => 'note']);
                $item->note = ($item->note * $item->note_count + $note) / ($item->note_count + 1);
                $item->note_count++;
                $item->save();
            }
        }
        return redirect(route($this->modelName . '.show', $slug));
    }

    public function delete($slug)
    {
        $item = $this->model::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($item->user_id);
        $item->comments()->delete();
        //suprimes les images
        if ($item->photoShortLink()) {
            Storage::delete('public/images/' . $item->photoShortLink());
            Storage::delete('public/images/small-' . $item->photoShortLink());
        }
        $item->delete();
        $message = "[delete $this->modelName] $this->modelName supprimé par " . Auth::user()->name . " : $item->name | $item->slug ";
        $this->lib->sendDiscord($message, $this->discord_url);
        if (Auth::user()->isAdmin()) {
            return redirect(route('admin_index'));
        }
        return redirect(route('index'));
    }

    public function slugify($name)
    {
        $slug = str_slug($name);
        $i = 1;
        $baseSlug = $slug;
        while ($this->model::where('slug', $slug)->count() > 0) {
            $slug = $baseSlug . "-" . $i++;
        }
        return $slug;
    }

    /**
     * ============
     * Views bellow
     * ============
     */
    public function show($slug)
    {
        $item = $this->model::where('slug', $slug)->firstOrFail();
        $comments = $item->comments()->orderBy('created_at', 'desc')->paginate(10);
        //si pas validé, on affiche seulement si admin/owner
        if (!$item->isValidated() && $this->lib->ownerOradminOrFail($item->user_id)) {
            abort(404);
        }
        $Parsedown = new \Parsedown();
        $Parsedown->setMarkupEscaped(true);
        $item->description = $Parsedown->text($item->description);

        return view($this->modelName . '.show', [$this->modelName => $item, 'comments' => $comments, 'show_captcha' => $this->lib->limitComment($this->min_time_captcha)]);
    }

    public function edit($slug)
    {
        $item = $this->model::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($item->user_id);
        return view($this->modelName . '.edit', [$this->modelName => $item]);
    }

    public function crawlInfo()
    {
        $this->lib->adminOrFail();
        $this->lib->crawlInfo();
    }
}
