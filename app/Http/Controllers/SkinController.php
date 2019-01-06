<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Script,
    App\Model\Skin,
    App\Model\User,
    App\Model\History;
use Validator;
use Auth;
use App;
use App\Notifications\notifyStatus;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreSkin;

class SkinController extends Controller {

    /**
     * Store a skin in db
     */
    public function storeSkin(StoreSkin $request) {
        $user = Auth::user();
        //captcha validation
        $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
        $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
        if (!App::environment('testing') && !$resp->isSuccess()) {
            $request->flash();
            return redirect(route('skin.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
        }

        $skin = Skin::create($request->all());
        $skin->slug = $this->slugifySkin($skin->name);

        if ($request->input("is_autor") == 'on') {
            $skin->user_id = $user->id; //owner 
            $skin->autor = $user->name;
        }
        $skin->poster_user_id = $user->id;

        //_TODO : supprimer ancienne image si existe
        //store photo_file or photo_url  storage
        if ($request->file('photo_file')) {
            $this->lib->storeImage($skin, $request->file('photo_file'));
        } else if ($request->has('photo_url')) {
            $file = @file_get_contents($request->input('photo_url'));
            $this->lib->storeImage($skin, $file);
        }
        $skin->save();

        $message = "[new skin] Nouveau skin posté sur le site : " . route('skin.show', ['slug' => $skin->slug]);
        $this->lib->sendDiscord($message, $this->discord_url);

        return redirect(route('skin.form'))->with("message", "Merci, votre skin est en attente de validation.");
    }

    public function updateSkin(Request $request, $slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($skin->user_id);

        $messages = [
            'skin_url.regex' => 'Le champ :attribute doit être un lien du format \'https://userstyles.org/styles/...\'',
            'photo_url.image_url' => "L'url de l'image est invalide."
        ];
        $validator = Validator::make($request->all(), [
                    'skin_url' => "required|url|max:255|regex:/^https:\/\/userstyles\.org\/styles\/.*/",
                    'repo_url' => "url|max:255",
                    'photo_url' => "url|max:255|image_url",
                    'user_id' => "exists:users,id",
                    'don_url' => "url|max:255",
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
            $skin->fill($request->only($toUpdate));
            if ($request->has('last_update')) {
                $skin->last_update = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('last_update'));
            }

            //gestion photo
            if ($request->file('photo_file')) {
                Storage::delete('public/images/' . $skin->photoShortLink());
                Storage::delete('public/images/small-' . $skin->photoShortLink());
                $this->lib->storeImage($skin, $request->file('photo_file'));
            } else if ($request->has('photo_url')) {
                $file = @file_get_contents($request->input('photo_url'));
                Storage::delete('public/images/' . $skin->photoShortLink());
                Storage::delete('public/images/small-' . $skin->photoShortLink());
                $this->lib->storeImage($skin, $file);
            }

            $skin->save();
            return redirect(route('skin.show', ['slug' => $slug]));
        }
    }

    public function validateSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->lib->adminOrFail();

        if ($skin->status != 1) {
            $skin->status = 1;
            $skin->save();
            if ($skin->poster_user_id != null) {
                $skin->poster_user()->first()->notify(new notifyStatus($skin));
            }
        }
        return redirect(route('skin.show', ['slug' => $slug]));
    }

    public function refuseSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->lib->adminOrFail();

        if ($skin->status != 2) {
            $skin->status = 2;
            $skin->save();
            if ($skin->poster_user_id != null) {
                $skin->poster_user()->first()->notify(new notifyStatus($skin));
            }
        }
        return redirect(route('skin.show', ['slug' => $slug]));
    }

    /**
     * Install skin : count & redirect 
     */
    public function installSkin($slug, Request $request) {
        $skin = Skin::where('slug', $slug)->firstOrFail();

        //if no history install_count +1
        // protection referer to count       
        if ($request->method() == 'POST' && str_contains($request->headers->get('referer'), $slug)) {
            $history = History::where(['ip' => $request->ip(), 'what' => "skin_$slug", 'action' => 'install']);
            if ($history->count() == 0) {
                History::create(['ip' => $request->ip(), 'what' => "skin_$slug", 'action' => 'install']);
                $skin->install_count++;
                $skin->save();
            }
        }
        return redirect($skin->skin_url);
    }

    public function noteSkin($slug, $note, Request $request) {
        $note = intval($note);
        if ($note > 0 && $note <= 5) {
            $skin = Skin::where('slug', $slug)->firstOrFail();
            //if no history note_count +1
            $history = History::where(['ip' => $request->ip(), 'what' => "skin_$slug", 'action' => 'note']);
            if ($history->count() == 0) {
                History::create(['ip' => $request->ip(), 'what' => "skin_$slug", 'action' => 'note']);
                $skin->note = ( $skin->note * $skin->note_count + $note ) / ($skin->note_count + 1);
                $skin->note_count++;
                $skin->save();
            }
        }
        return redirect(route('skin.show', $slug));
    }

    public function deleteSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($skin->user_id);
        $skin->comments()->delete();
        //suprimes les images
        if ($skin->photoShortLink()) {
            Storage::delete('public/images/' . $skin->photoShortLink());
            Storage::delete('public/images/small-' . $skin->photoShortLink());
        }

        $skin->delete();
        $message = "[delete skin] Skin supprimé par " . Auth::user()->name . " : $skin->name | $skin->slug ";
        $this->lib->sendDiscord($message, $this->discord_url);

        if (Auth::user()->isAdmin())
            return redirect(route('admin_index'));
        return redirect(route('index'));
    }

    public function slugifySkin($name) {
        $slug = $this->lib->slugify($name);
        $i = 1;
        $baseSlug = $slug;
        while (Skin::where('slug', $slug)->count() > 0) {
            $slug = $baseSlug . "-" . $i++;
        }
        return $slug;
    }

    /**
     * ============
     * Some Views bellow 
     * ============
     */
    public function showSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $comments = $skin->comments()->orderBy('created_at', 'desc')->paginate(10);
        //si pas validé, on affiche seulement si admin/owner
        if (!$skin->isValidated() && $this->lib->ownerOradminOrFail($skin->user_id)) {
            abort(404);
        }
        $Parsedown = new \Parsedown();
        $Parsedown->setMarkupEscaped(true);
        $skin->description = $Parsedown->text($skin->description);

        return view('skin.show', ['skin' => $skin, 'comments' => $comments, 'show_captcha' => $this->lib->limitComment($this->min_time_captcha)]);
    }

    public function editSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($skin->user_id);
        return view('skin.edit', ['skin' => $skin]);
    }

}
