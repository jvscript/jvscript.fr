<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Script,
    App\User,
    App\History;
use Validator;
use Auth;
use App;
use App\Notifications\notifyStatus;
use Illuminate\Support\Facades\Storage;

class ScriptController extends Controller {
    //_TODO : retenir le filtre/sort en session/cookie utilisateur 
    //_TODO : séparation des concerns : role, slugify (event save)

    /**
     * Store a script in db
     */
    public function storeScript(Request $request) {
        $user = Auth::user();
        $messages = [
            'js_url.regex' => 'Le lien du script doit terminer par \'.js\'',
        ];
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:50|unique:scripts|not_in:ajout',
                    'description' => 'required',
                    "autor" => "max:255",
                    'js_url' => "required|url|max:255|regex:/.*\.js$/",
                    'repo_url' => "url|max:255",
                    'photo_url' => "url|max:255",
                    'photo_file' => "image",
                    'don_url' => "url|max:255",
                    'website_url' => "url|max:255",
                    'topic_url' => "url|max:255|regex:/^https?:\/\/www\.jeuxvideo\.com\/forums\/.*/",
                        ], $messages);

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else { //sucess > insert  
            //captcha validation
            $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
            if (!App::environment('testing', 'local') && !$resp->isSuccess()) {
                $request->flash();
                return redirect(route('script.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }
            //check image url
            if ($request->has('photo_url')) {
                if (!$this->lib->isImage($request->input('photo_url'))) {
                    $request->flash();
                    return redirect(route('script.form'))->withErrors(['photo_url' => "L'url de l'image est invalide."]);
                }
            }

            $script = Script::create($request->all());
            $script->slug = $this->slugifyScript($script->name);

            if ($request->input("is_autor") == 'on') {
                $script->user_id = $user->id; //owner du script               
                $script->autor = $user->name;
            }
            $script->poster_user_id = $user->id;

            //store photo_file or photo_url  storage
            if ($request->file('photo_file')) {
                $this->lib->storeImage($script, $request->file('photo_file'));
            } else if ($request->has('photo_url')) {
                $file = @file_get_contents($request->input('photo_url'));
                $this->lib->storeImage($script, $file);
            }

            $script->save();

            $message = "[new script] Nouveau script posté sur le site : " . route('script.show', ['slug' => $script->slug]);
            $this->lib->sendDiscord($message, $this->discord_url);

            return redirect(route('script.form'))->with("message", "Merci, votre script est en attente de validation.");
        }
    }

    /**
     * admin or owner
     */
    public function updateScript(Request $request, $slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($script->user_id);

        $messages = [
            'js_url.regex' => 'Le lien du script doit terminer par \'.js\'',
        ];
        $validator = Validator::make($request->all(), [
                    "autor" => "max:255",
                    'js_url' => "required|url|max:255|regex:/.*\.js$/",
                    'repo_url' => "url|max:255",
                    'photo_url' => "url|max:255",
                    'photo_file' => "image",
                    'don_url' => "url|max:255",
                    'user_id' => "exists:users,id",
                    'sensibility' => "in:0,1,2",
                    'last_update' => "date_format:d/m/Y",
                    'website_url' => "url|max:255",
                    'topic_url' => "url|max:255|regex:/^https?:\/\/www\.jeuxvideo\.com\/forums\/.*/",
                        ], $messages);

        //update only this fields
        $toUpdate = ['sensibility', 'autor', 'description', 'js_url', 'repo_url', 'don_url', 'website_url', 'topic_url', 'version'];
        if (Auth::user()->isAdmin()) {
            $toUpdate = ['sensibility', 'autor', 'description', 'js_url', 'repo_url', 'don_url', 'website_url', 'topic_url', 'user_id', 'version'];
            if ($request->input('user_id') == '') {
                $request->merge(['user_id' => null]);
            } else {
                //force username of owner 
                $request->merge(['autor' => User::find($request->input('user_id'))->name]);
            }
        }

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else {
            //check image url
            if ($request->has('photo_url')) {
                if (!$this->lib->isImage($request->input('photo_url'))) {
                    $request->flash();
                    return redirect(route('script.form'))->withErrors(['photo_url' => "L'url de l'image est invalide."]);
                }
            }

            $script->fill($request->only($toUpdate));
            if ($request->has('last_update')) {
                $script->last_update = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('last_update'));
            }

            //gestion photo
            if ($request->file('photo_file')) {
                Storage::delete('public/images/' . $script->photoShortLink());
                Storage::delete('public/images/small-' . $script->photoShortLink());
                $this->lib->storeImage($script, $request->file('photo_file'));
            } else if ($request->has('photo_url')) {
                if ($this->lib->isImage($request->input('photo_url'))) {
                    $file = @file_get_contents($request->input('photo_url'));
                    Storage::delete('public/images/' . $script->photoShortLink());
                    Storage::delete('public/images/small-' . $script->photoShortLink());
                    $this->lib->storeImage($script, $file);
                } else {
                    $script->photo_url = null;
                }
            }

            $script->save();
            return redirect(route('script.show', ['slug' => $slug]));
        }
    }

    public function validateScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->lib->adminOrFail();

        if ($script->status != 1) {
            $script->status = 1;
            $script->save();
            if ($script->poster_user_id != null) {
                $script->poster_user()->first()->notify(new notifyStatus($script));
            }
        }
        return redirect(route('script.show', ['slug' => $slug]));
    }

    public function refuseScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->lib->adminOrFail();

        if ($script->status != 2) {
            $script->status = 2;
            $script->save();
            if ($script->poster_user_id != null) {
                $script->poster_user()->first()->notify(new notifyStatus($script));
            }
        }
        return redirect(route('script.show', ['slug' => $slug]));
    }

    /**
     * Install script : count & redirect 
     */
    public function installScript($slug, Request $request) {
        $script = Script::where('slug', $slug)->firstOrFail();

        // protection referer to count
        if ($request->method() == 'POST' && str_contains($request->headers->get('referer'), $slug)) {
            $history = History::where(['ip' => $request->ip(), 'what' => $slug, 'action' => 'install']);
            if ($history->count() == 0) {
                History::create(['ip' => $request->ip(), 'what' => $slug, 'action' => 'install']);
                $script->install_count++;
                $script->save();
            }
        }
        return redirect($script->js_url);
    }

    /**
     * Note script : note & redirect 
     */
    public function noteScript($slug, $note, Request $request) {
        $note = intval($note);
        if ($note > 0 && $note <= 5) {
            $script = Script::where('slug', $slug)->firstOrFail();
            //if no history note_count +1
            $history = History::where(['ip' => $request->ip(), 'what' => "script_$slug", 'action' => 'note']);
            if ($history->count() == 0) {
                History::create(['ip' => $request->ip(), 'what' => "script_$slug", 'action' => 'note']);
                $script->note = ( $script->note * $script->note_count + $note ) / ($script->note_count + 1);
                $script->note_count++;
                $script->save();
            }
        }
        return redirect(route('script.show', $slug));
    }

    public function deleteScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($script->user_id);
        $script->comments()->delete();
        //suprimes les images
        if ($script->photoShortLink()) {
            Storage::delete('public/images/' . $script->photoShortLink());
            Storage::delete('public/images/small-' . $script->photoShortLink());
        }
        $script->delete();
        $message = "[delete script] Script supprimé par " . Auth::user()->name . " : $script->name | $script->slug ";
        $this->lib->sendDiscord($message, $this->discord_url);
        if (Auth::user()->isAdmin())
            return redirect(route('admin_index'));
        return redirect(route('index'));
    }

    public function slugifyScript($name) {
        $slug = $this->lib->slugify($name);
        $i = 1;
        $baseSlug = $slug;
        while (Script::where('slug', $slug)->count() > 0) {
            $slug = $baseSlug . "-" . $i++;
        }
        return $slug;
    }

    /**
     * ============
     * Some Views bellow 
     * ============
     */
    public function showScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $comments = $script->comments()->orderBy('created_at', 'desc')->paginate(10);
        //si pas validé, on affiche seulement si admin/owner
        if (!$script->isValidated() && $this->lib->ownerOradminOrFail($script->user_id)) {
            abort(404);
        }
        $Parsedown = new \Parsedown();
        $Parsedown->setMarkupEscaped(true);
        $script->description = $Parsedown->text($script->description);

        return view('script.show', ['script' => $script, 'comments' => $comments, 'show_captcha' => $this->lib->limitComment($this->min_time_captcha)]);
    }

    public function editScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($script->user_id);
        return view('script.edit', ['script' => $script]);
    }

    public function crawlInfo() {
        $this->lib->adminOrFail();
        $this->lib->crawlInfo();
    }

}
