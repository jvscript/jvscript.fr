<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Script,
    App\Skin,
    App\Comment;
use Validator;
use Auth;
use App;
use App\Lib\Lib;

class UserController extends Controller {

    public function index(Request $request, $keyword = null) {
        $keyword = $keyword == null ? '' : $keyword;
        $scripts = Script::where("status", 1)->get();
        $skins = Skin::where("status", 1)->get();

        $collection = collect([$scripts, $skins]);
        $collapsed = $collection->collapse();
        $scripts = $collapsed->all(); //
        $scripts = $collapsed->sortByDesc('install_count');

        return view('index', ['scripts' => $scripts, 'keyword' => $keyword]);
    }

    /**
     * admin index
     */
    public function admin(Request $request) {
        $this->lib->adminOrFail();
        $scripts = Script::all();
        $skins = Skin::all();

        $collection = collect([$scripts, $skins]);
        $collapsed = $collection->collapse();
        $scripts = $collapsed->all(); //
        $scripts = $collapsed->sortByDesc('created_at');

        return view('admin.index', ['scripts' => $scripts]);
    }

    /**
     * Admin comment
     */
    public function adminComments(Request $request) {
        $this->lib->adminOrFail();
        $comments = Comment::latest()->paginate(20);
        return view('admin.comments', ['comments' => $comments]);
    }

    /**
     * Admin delete comment
     */
    public function adminDeleteComment($comment_id) {
        $this->lib->adminOrFail();
        $comment = Comment::findOrFail($comment_id);
        $comment->delete();
        return redirect(route("admin.comments"));
    }

    public function mesScripts(Request $request) {
        $user = Auth::user();
        $scripts = $user->scripts()->get();
        $skins = $user->skins()->get();

        $collection = collect([$scripts, $skins]);
        $collapsed = $collection->collapse();
        $scripts = $collapsed->all(); //
        $scripts = $collapsed->sortByDesc('created_at');

        return view('moncompte.index', ['scripts' => $scripts]);
    }

    public function ajaxUsers(Request $request) {
        $this->lib->adminOrFail();
        return \App\User::select('id', 'name')->get();
    }

    /**
     * Contact send (discord bot)
     */
    public function contactSend(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'email',
                    'message_body' => "required"
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else {
            //captcha validation
            $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
            if (!App::environment('testing') && !$resp->isSuccess()) {
                $request->flash();
                return redirect(route('contact.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }

            //send discord 
            $this->discord_url;
            $message = "[contact form] ";
            if ($request->input('email')) {
                $message .= "Email : " . $request->input('email') . '.';
            }
            $message .= "Message : " . $request->input('message_body');
            $this->lib->sendDiscord($message, $this->discord_url);

            return redirect(route('contact.form'))->with("message", "Merci, votre message a été envoyé.");
        }

        return redirect(route('contact.form'));
    }

}
