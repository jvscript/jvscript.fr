<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\StoreSkin;
use App\Http\Requests\UpdateSkin;
use App\Model\Skin;
use App\Model\User;
use Auth;

class SkinController extends ScriptController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Skin;
        $this->modelName = 'skin';
    }

    /**
     * Store a skin in db
     */
    public function storeSkin(StoreSkin $request)
    {
        $user = Auth::user();
        $skin = Skin::create($request->all());
        $skin->slug = $this->slugify($skin->name);

        if ($request->input("is_autor") == 'on') {
            $skin->user_id = $user->id; //owner
            $skin->autor = $user->name;
        }
        $skin->poster_user_id = $user->id;

        if ($request->file('photo_file')) {
            $this->lib->storeImage($skin, $request->file('photo_file'));
        } elseif ($request->filled('photo_url')) {
            $file = @file_get_contents($request->input('photo_url'));
            $this->lib->storeImage($skin, $file);
        }
        $skin->save();

        $message = "[new skin] Nouveau skin posté sur le site : " . route('skin.show', ['slug' => $skin->slug]);
        $this->lib->sendDiscord($message, $this->discord_url);
        if (!App::environment('testing', 'local')) {
            \Mail::raw($message, function ($message) {
                $message->to(env('ADMIN_EMAIL'))->subject("Nouveau skin");
            });
        }
        return redirect(route('skin.form'))->with("message", "Merci, votre skin est en attente de validation.");
    }

    public function updateSkin(UpdateSkin $request, $slug)
    {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->lib->ownerOradminOrFail($skin->user_id);

        //update only this fields
        $toUpdate = ['name', 'autor', 'description', 'skin_url', 'repo_url', 'don_url', 'website_url', 'topic_url', 'version'];
        if (Auth::user()->isAdmin()) {
            $toUpdate[] = 'user_id';
            if ($request->input('user_id') == '') {
                $request->merge(['user_id' => null]);
            } else {
                //force username of owner
                $request->merge(['autor' => User::find($request->input('user_id'))->name]);
            }
        }

        if ($skin->name != $request->input('name')) {
            $slug = $skin->slug = $this->slugify($request->input('name'));
        }

        $skin->fill($request->only($toUpdate));
        if ($request->filled('last_update')) {
            $skin->last_update = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('last_update'));
        }

        if ($request->file('photo_file')) {
            $this->lib->storeImage($skin, $request->file('photo_file'));
        } elseif ($request->filled('photo_url')) {
            $file = @file_get_contents($request->input('photo_url'));
            $this->lib->storeImage($skin, $file);
        }

        $skin->save();
        return redirect(route('skin.show', ['slug' => $slug]));
    }
}
