<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Script,
    App\Skin,
    App\History;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;

class JvscriptController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    public function index(Request $request) {
        if ($request->ajax()) {
            if ($request->has('search')) {
                $search = $request->input('search');
                if (strlen(trim($search)) > 0) {
                    $scripts = Script::where('name', 'like', "%$search%")->orWhere('autor', 'like', "%$search%")->get();
                    $skins = Skin::where('name', 'like', "%$search%")->orWhere('autor', 'like', "%$search%")->get();
                    return view('ajax.index', ['scripts' => $scripts, 'skins' => $skins]);
                }
            }
            $scripts = Script::all();
            $skins = Skin::all();
            return view('ajax.index', ['scripts' => $scripts, 'skins' => $skins]);
        }
        //_TODO : filter status 2
        $scripts = Script::all();
        $skins = Skin::all();
        return view('index', ['scripts' => $scripts, 'skins' => $skins]);
    }

    /**
     * Store a script in db
     */
    public function storeScript(Request $request) {
//        $user = Auth::user();
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255|unique:scripts',
                    'js_url' => "required|url",
                    'repo_url' => "url",
                    'photo_url' => "url",
                    'don_url' => "url",
                    "user_email" => "email"
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else { //sucess > insert  
            $script = Script::create($request->all());
            $slug = $this->slugify($script->name);
            $i = 1;
            $baseSlug = $slug;
            while ($this->slugExistScript($slug)) {
                $slug = $baseSlug . "-" . $i++;
            }
            $script->slug = $slug;
            $script->save();

            /**
             * todo redirect to script awaiting 
             */
            return redirect(route('script.form'))->with("message", "Merci, votre script est en attente de validation.");
        }
    }

    /**
     * Store a skin in db
     */
    public function storeSkin(Request $request) {
//        $user = Auth::user();
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255|unique:skins',
                    'skin_url' => "required|url",
                    'repo_url' => "url",
                    'photo_url' => "url",
                    'don_url' => "url",
                    "user_email" => "email"
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else { //sucess > insert  
            $script = Skin::create($request->all());
            $slug = $this->slugify($script->name);
            $i = 1;
            $baseSlug = $slug;
            while ($this->slugExistSkin($slug)) {
                $slug = $baseSlug . "-" . $i++;
            }
            $script->slug = $slug;
            $script->save();

            /**
             * todo redirect to script awaiting 
             */
            return redirect(route('skin.form'))->with("message", "Merci, votre skin est en attente de validation.");
        }
    }

    /**
     * Install script : count & redirect 
     */
    public function installScript($slug) {
        $script = Script::where('slug', $slug)->first();
        if (!$script) {
            abort(404);
        }
        //if no history install_count +1
        $history = History::where(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => $slug, 'action' => 'install']);
        if ($history->count() == 0) {
            History::create(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => $slug, 'action' => 'install']);
            $script->install_count++;
            $script->save();
        }
        return redirect($script->js_url);
    }

    /**
     * Note script : note & redirect 
     */
    public function noteScript($slug, $note) {
        $note = intval($note);
        if ($note > 0 && $note <= 5) {
            $script = Script::where('slug', $slug)->first();
            if (!$script) {
                abort(404);
            }
            //if no history note_count +1
            $history = History::where(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => "script_$slug", 'action' => 'note']);
            if ($history->count() == 0) {
                History::create(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => "script_$slug", 'action' => 'note']);
                $script->note = ( $script->note * $script->note_count + $note ) / ($script->note_count + 1);
                $script->note_count++;
                $script->save();
            }
        }
        return redirect(route('script.show', $slug));
    }

    /**
     * Install script : count & redirect 
     */
    public function installSkin($slug) {
        $skin = Skin::where('slug', $slug)->first();
        if (!$skin) {
            abort(404);
        }
        //if no history install_count +1
        $history = History::where(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => "skin_$slug", 'action' => 'install']);
        if ($history->count() == 0) {
            History::create(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => "skin_$slug", 'action' => 'install']);
            $skin->install_count++;
            $skin->save();
        }
        return redirect($skin->skin_url);
    }

    /**
     * Note script : note & redirect 
     */
    public function noteSkin($slug, $note) {
        $note = intval($note);
        if ($note > 0 && $note <= 5) {
            $skin = Skin::where('slug', $slug)->first();
            if (!$skin) {
                abort(404);
            }
            //if no history note_count +1
            $history = History::where(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => "skin_$slug", 'action' => 'note']);
            if ($history->count() == 0) {
                History::create(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => "skin_$slug", 'action' => 'note']);
                $skin->note = ( $skin->note * $skin->note_count + $note ) / ($skin->note_count + 1);
                $skin->note_count++;
                $skin->save();
            }
        }
        return redirect(route('skin.show', $slug));
    }

    /**
     * Contact send (mail)
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
        } else { //sucess > insert  
            Mail::to(env('ADMIN_EMAIL', 'contact@jvscript.io'))->send(new Contact($request->input('email'), $request->input('message_body')));

            return redirect(route('contact.form'))->with("message", "Merci, votre message a été envoyé.");
        }

        return redirect(route('contact.form'));
    }

    /**
     * ============
     * Views bellow 
     * ============
     */
    public function formScript() {
        return view('script.form');
    }

    public function formSkin() {
        return view('skin.form');
    }

    public function showScript($slug) {
        $script = Script::where('slug', $slug)->first();
        if (!$script) {
            abort(404);
        }
        return view('script.show', ['script' => $script]);
    }

    public function showSkin($slug) {
        $skin = Skin::where('slug', $slug)->first();
        if (!$skin) {
            abort(404);
        }
        return view('skin.show', ['skin' => $skin]);
    }

    public function slugExistScript($slug) {
        return Script::where('slug', $slug)->count() > 0;
    }

    public function slugExistSkin($slug) {
        return Skin::where('slug', $slug)->count() > 0;
    }

    static public function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}
