<?php

namespace App\Http\Controllers;

use App;
use App\Model\Idea;
use Auth;
use Illuminate\Http\Request;
use Validator;
use View; 

class BoxController extends Controller
{
    public function index(Request $request)
    {
       
        if ($request->has('id_idea') && $request->has('page')) {
            $idea = Idea::where(["status" => 1, "id" => $request->input('id_idea')])->firstOrFail();
            return [
                'html' => View::make('global.comments-idea', ['idea' => $idea, 'comments' => $idea->comments()->latest()->paginate(5), 'commentClass' => ' ', 'recaptcha' => 1])->render(),
                'count' => $idea->comments()->count()
            ];
        }
        $ideas = Idea::where("status", 1)->get()->sortByDesc(function ($idea, $key) {
            //order by likes score
            return $idea->likes()->where('liked', 1)->count() - $idea->likes()->where('liked', 0)->count();
        });
        return view('box.index', ['ideas' => $ideas]);
    }

    public function formAjout(Request $request)
    {
        return view('box.form');
    }

    public function showIdea(Request $request, $id)
    {
        $idea = Idea::findOrFail($id);
        $this->lib->adminOrFail();
        $comments = $idea->comments()->latest()->paginate(10);
        //affiche les non validés seulement si admin
        if (!$idea->isValidated() && !(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }
        return view('box.show', ['idea' => $idea, 'comments' => $comments, 'show_captcha' => $this->lib->limitComment($this->min_time_captcha)]);
    }

    /**
     * Store an idea in db
     */
    public function storeIdea(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
                    'title' => 'unique:ideas|required|max:46',
                    'description' => 'required',
                    'type' => 'in:0,1',
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request,
                $validator
            );
        } else { //sucess > insert
            //captcha validation
            $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
            if (!App::environment('testing', 'local') && !$resp->isSuccess()) {
                $request->flash();
                return redirect(route('box.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }

            $request->merge(['user_id' => $user->id]);
            $request->merge(['status' => 1]);
            $idea = Idea::create($request->all());

            $message = "[new idée] Nouvelle idée posté sur le site : '$idea->title' " . route('box.index');
            $this->lib->sendDiscord($message, $this->discord_url);

            return redirect(route('box.index'))->with("message", "Merci, votre idée a été ajoutée.");
        }
    }

    public function likeBox($id, $dislike = false)
    {
        $idea = Idea::findOrFail($id);
        $user = Auth::user();
        //_TODO : if relike same = delete (cancel)
        $liked = $dislike ? false : true;
        $alreadyLiked = $idea->likes()->where(['user_id' => $user->id, 'liked' => $liked]);
        if ($alreadyLiked->count()) {
            $alreadyLiked->delete();
        } else {
            $like = $idea->likes()->firstOrNew(['user_id' => $user->id]);
            $like->liked = $liked;
            $like->save();
        }
        return redirect(route('box.index'));
    }

    public function validateBox($id)
    {
        $idea = Idea::findOrFail($id);
        $this->lib->adminOrFail();
        echo "before status = $idea->status";
        if ($idea->status != 1) {
            $idea->status = 1;
            $idea->save();
        }
        return redirect(route('box.show', ['id' => $id]));
    }

    public function refuseBox($id)
    {
        $idea = Idea::findOrFail($id);
        $this->lib->adminOrFail();
        echo "before status = $idea->status";
        if ($idea->status != 2) {
            $idea->status = 2;
            $idea->save();
        }
        return redirect(route('box.show', ['id' => $id]));
    }

    public function deleteBox($id)
    {
        $idea = Idea::findOrFail($id);
        $this->lib->ownerOradminOrFail($idea->user_id);
        $idea->comments()->delete();
        $idea->delete();
        $message = "[delete idea] Idea supprimé par " . Auth::user()->name . " : $idea->title ";
        $this->lib->sendDiscord($message, $this->discord_url);
        return redirect(route('box.index'));
    }
}
