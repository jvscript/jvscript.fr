<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Script,
    App\Skin,
    App\Idea,
    App\Comment;
use Validator;
use Auth;
use App;
use App\Lib\Lib;
use App\Notifications\ScriptComment;

class CommentController extends Controller {

    /**
     * Store comment
     */
    public function storeComment($slug, Request $request) {
        $user = Auth::user();
        $route = \Request::route()->getName();
        if (str_contains($route, "script")) {
            $item = 'script';
            $model = Script::where('slug', $slug)->firstOrFail();
        } else if (str_contains($route, "skin")) {
            $item = 'skin';
            $model = Skin::where('slug', $slug)->firstOrFail();
        } else if (str_contains($route, "box")) {
            $item = 'box';
            $model = Idea::findOrFail($slug);
        }

        $validator = Validator::make($request->all(), ['comment' => "required|max:255"]);

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else {
            //captcha validation
            $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
            //Anti spam 30 secondes
            if ($this->lib->limitComment($this->min_time_comment)) {
                if ($item == 'box') {//Return ajax error
                    return view('global.comments-idea', ['idea' => $model, 'comments' => $model->comments()->latest()->paginate(5), 'commentClass' => ' ', 'recaptcha' => 1])
                                    ->withErrors(['comment' => "Veuillez attendre $this->min_time_comment secondes entre chaque commentaire svp."]);
                }
                $request->flash();
                return redirect(route("$item.show", $slug) . "#comments")->withErrors(['comment' => "Veuillez attendre $this->min_time_comment secondes entre chaque commentaire svp."]);
            }
            //anti spam 60 secondes : besoin validation captcha (bypass captcha comment boite à idée ajax)
            if ($item != 'box' && $this->lib->limitComment($this->min_time_captcha)) {
                if (!App::environment('testing') && !$resp->isSuccess()) {
                    $request->flash();
                    return redirect(route("$item.show", $slug) . "#comments")->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
                }
            }
            $comment = $request->input('comment');
            $model->comments()->create(['comment' => $comment, 'user_id' => $user->id]);

            //notify user script/skin note box
            if ($item != 'box' && $model->user_id != null && $user->id != $model->user_id) {
                $model->user()->first()->notify(new ScriptComment($model));
            }
            if ($item == 'box') {//jax return
                return view('global.comments-idea', ['idea' => $model, 'comments' => $model->comments()->latest()->paginate(5), 'commentClass' => ' ', 'recaptcha' => 1]);
            }
            return redirect(route("$item.show", $slug) . "#comments");
        }
    }

    /**
     * Delete comment
     */
    public function deleteComment($slug, $comment_id, Request $request) {
        $user = Auth::user();
        $route = \Request::route()->getName();
        if (str_contains($route, "script")) {
            $item = 'script';
            $model = Script::where('slug', $slug)->firstOrFail();
        } else if (str_contains($route, "skin")) {
            $item = 'skin';
            $model = Skin::where('slug', $slug)->firstOrFail();
        } else if (str_contains($route, "box")) {
            $item = 'box';
            $model = Idea::findOrFail($slug);
        }
        $comment = Comment::findOrFail($comment_id);
        $this->lib->ownerOradminOrFail($comment->user_id);
        $comment->delete();
        return redirect(route("$item.show", $slug) . "#comments");
    }

}
