<?php

namespace App\Http\Controllers;

use App;
use App\Model\Comment;
use App\Model\Idea;
use App\Model\Script;
use App\Model\Skin;
use App\Notifications\ScriptComment;
use Auth;
use Illuminate\Http\Request;
use Validator;
use View;

class CommentController extends Controller
{
    private function dispatchModel($route, $slug)
    {
        if (\Illuminate\Support\Str::contains($route, "script")) {
            $item = 'script';
            $model = Script::where('slug', $slug)->firstOrFail();
        } elseif (\Illuminate\Support\Str::contains($route, "skin")) {
            $item = 'skin';
            $model = Skin::where('slug', $slug)->firstOrFail();
        } elseif (\Illuminate\Support\Str::contains($route, "box")) {
            $item = 'box';
            $model = Idea::findOrFail($slug);
        }
        return ['item' => $item, 'model' => $model];
    }

    /**
     * Store comment
     */
    public function storeComment($slug, Request $request)
    {
        $user = Auth::user();
        $route = \Request::route()->getName();
        $dispatcher = $this->dispatchModel($route, $slug);
        $item = $dispatcher['item'];
        $model = $dispatcher['model'];

        $validator = Validator::make($request->all(), ['comment' => "required|max:255"]);

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request,
                $validator
            );
        } else {
        
            //Anti spam 30 secondes
            if ($this->lib->limitComment($this->min_time_comment)) {
                $request->flash();
                if ($item == 'box') {//Return ajax error
                    return [
                        'html' => View::make('global.comments-idea', ['idea' => $model, 'comments' => $model->comments()->latest()->paginate(5), 'commentClass' => ' ', 'recaptcha' => 1])
                                ->withErrors(['comment' => "Veuillez attendre $this->min_time_comment secondes entre chaque commentaire svp."])->render(),
                        'count' => $model->comments()->count()
                    ];
                }
                return redirect(route("$item.show", $slug) . "#comments")->withErrors(['comment' => "Veuillez attendre $this->min_time_comment secondes entre chaque commentaire svp."]);
            }
           
            $comment = $request->input('comment');
            $model->comments()->create(['comment' => $comment, 'user_id' => $user->id]);

            //notify user script/skin note box
            if ($item != 'box' && $model->user_id != null && $user->id != $model->user_id) {
                $model->user()->first()->notify(new ScriptComment($model));
            }
            if ($item == 'box') {//ajax return
                return [
                    'html' => View::make('global.comments-idea', ['idea' => $model, 'comments' => $model->comments()->latest()->paginate(5), 'commentClass' => ' ', 'recaptcha' => 1])->render(),
                    'count' => $model->comments()->count()
                ];
            }
            return redirect(route("$item.show", $slug) . "#comments");
        }
    }

    /**
     * Delete comment
     */
    public function deleteComment($slug, $comment_id, Request $request)
    {
        $user = Auth::user();
        $route = \Request::route()->getName();
        $dispatcher = $this->dispatchModel($route, $slug);
        $item = $dispatcher['item'];
        $model = $dispatcher['model'];

        $comment = Comment::findOrFail($comment_id);
        $this->lib->ownerOradminOrFail($comment->user_id, null);
        $comment->delete();
        if ($item == 'box') {//jax return
            return [
                'html' => View::make('global.comments-idea', ['idea' => $model, 'comments' => $model->comments()->latest()->paginate(5), 'commentClass' => ' ', 'recaptcha' => 1])->render(),
                'count' => $model->comments()->count()
            ];
        }
        return redirect(route("$item.show", $slug) . "#comments");
    }
}
