<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Script,
    App\Skin,
    App\History;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notify;
use Auth;

class JvscriptController extends Controller {

    //_TODO : retenir le filtre/sort en session/cookie utilisateur 
    //_TODO : suppression script/skin
    //_TODO : ranger les methodes
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
        $this->recaptcha_key = env('RECAPTCHA_KEY', '');
        $this->discord_url = env('DISCORD_URL', '');
    }

    public function index(Request $request, $keyword = null) {
        $keyword = $keyword == null ? '' : $keyword;
        $scripts = Script::where("status", 1)->get();
        $skins = Skin::where("status", 1)->get();

        $collection = collect([$scripts, $skins]);
        $collapsed = $collection->collapse();
        $scripts = $collapsed->all(); //
        $scripts = $collapsed->sortByDesc('note');

        return view('index', ['scripts' => $scripts, 'keyword' => $keyword]);
    }

    public function admin(Request $request) {
        $this->adminOrFail();
        $scripts = Script::all();
        $skins = Skin::all();

        $collection = collect([$scripts, $skins]);
        $collapsed = $collection->collapse();
        $scripts = $collapsed->all(); //
        $scripts = $collapsed->sortByDesc('created_at');

        return view('admin.index', ['scripts' => $scripts]);
    }

    /**
     * Store a script in db
     */
    public function storeScript(Request $request) {
        // $user = Auth::user();
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
            //captcha validation
            $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                $request->flash();
                return redirect(route('script.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }

            $script = Script::create($request->all());
            $slug = $this->slugify($script->name);
            $i = 1;
            $baseSlug = $slug;
            while ($this->slugExistScript($slug)) {
                $slug = $baseSlug . "-" . $i++;
            }
            $script->slug = $slug;
            $script->save();

            $message = "[new script] Nouveau script posté sur le site : " . route('script.show', ['slug' => $script->slug]);
            $this->sendDiscord($message, $this->discord_url);

            return redirect(route('script.form'))->with("message", "Merci, votre script est en attente de validation.");
        }
    }

    public function updateScript(Request $request, $slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->adminOrFail();

        $validator = Validator::make($request->all(), [
                    'js_url' => "required|url",
                    'repo_url' => "url",
                    'photo_url' => "url",
                    'don_url' => "url"
        ]);
        //update only this fields
        $toUpdate = ['sensibility', 'autor', 'description', 'js_url', 'repo_url', 'photo_url', 'don_url'];

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else {
            $script->fill($request->only($toUpdate));
            $script->save();
            return redirect(route('script.show', ['slug' => $slug]));
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
            //captcha validation
            $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                $request->flash();
                return redirect(route('skin.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }

            $script = Skin::create($request->all());
            $slug = $this->slugify($script->name);
            $i = 1;
            $baseSlug = $slug;
            while ($this->slugExistSkin($slug)) {
                $slug = $baseSlug . "-" . $i++;
            }
            $script->slug = $slug;
            $script->save();

            $message = "[new skin] Nouveau skin posté sur le site : " . route('skin.show', ['slug' => $script->slug]);
            $this->sendDiscord($message, $this->discord_url);

            return redirect(route('skin.form'))->with("message", "Merci, votre skin est en attente de validation.");
        }
    }

    public function updateSkin(Request $request, $slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->adminOrFail();

        $validator = Validator::make($request->all(), [
                    'skin_url' => "required|url",
                    'repo_url' => "url",
                    'photo_url' => "url",
                    'don_url' => "url"
        ]);
        //update only this fields
        $toUpdate = ['autor', 'description', 'skin_url', 'repo_url', 'photo_url', 'don_url'];

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
            );
        } else {
            $skin->fill($request->only($toUpdate));
            $skin->save();
            return redirect(route('skin.show', ['slug' => $slug]));
        }
    }

    public function validateScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->adminOrFail();

        if ($script->status != 1) {
            $script->status = 1;
            $script->save();
            if ($script->user_email != null) {
                Mail::to($script->user_email)->send(new Notify($script));
            }
        }
        return redirect(route('script.show', ['slug' => $slug]));
    }

    public function validateSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->adminOrFail();

        if ($skin->status != 1) {
            $skin->status = 1;
            $skin->save();
            if ($skin->user_email != null) {
                Mail::to($skin->user_email)->send(new Notify($skin));
            }
        }
        return redirect(route('skin.show', ['slug' => $slug]));
    }

    public function refuseScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->adminOrFail();

        if ($script->status != 2) {
            $script->status = 2;
            $script->save();
            if ($script->user_email != null) {
                Mail::to($script->user_email)->send(new Notify($script));
            }
        }
        return redirect(route('script.show', ['slug' => $slug]));
    }

    public function refuseSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->adminOrFail();

        if ($skin->status != 2) {
            $skin->status = 2;
            $skin->save();
            if ($skin->user_email != null) {
                Mail::to($skin->user_email)->send(new Notify($skin));
            }
        }
        return redirect(route('skin.show', ['slug' => $slug]));
    }

    /**
     * Install script : count & redirect 
     */
    public function installScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();

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
            $script = Script::where('slug', $slug)->firstOrFail();
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
        $skin = Skin::where('slug', $slug)->firstOrFail();

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
            $skin = Skin::where('slug', $slug)->firstOrFail();
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
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
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
            $this->sendDiscord($message, $this->discord_url);

//            Mail::to(env('ADMIN_EMAIL', 'contact@jvscript.io'))->send(new Contact($request->input('email'), $request->input('message_body')));

            return redirect(route('contact.form'))->with("message", "Merci, votre message a été envoyé.");
        }

        return redirect(route('contact.form'));
    }

    /**
     * ============
     * Some Views bellow 
     * ============
     */
    public function formScript() {
        return view('script.form');
    }

    public function formSkin() {
        return view('skin.form');
    }

    public function showScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();

        //affiche les non validés seulement si admin
        if (!$script->isValidated() && !(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }

        return view('script.show', ['script' => $script]);
    }

    public function showSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();

        //affiche les non validés seulement si admin
        if (!$skin->isValidated() && !(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }

        return view('skin.show', ['skin' => $skin]);
    }

    public function editScript($slug) {
        $this->adminOrFail();
        $script = Script::where('slug', $slug)->firstOrFail();
        return view('script.edit', ['script' => $script]);
    }

    public function editSkin($slug) {
        $this->adminOrFail();

        $skin = Skin::where('slug', $slug)->firstOrFail();
        return view('skin.edit', ['skin' => $skin]);
    }

    /**
     * Usefull functions 
     */
    public function adminOrFail() {
        if (!(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }
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

    public function sendDiscord($content, $url) {
        if (empty($content)) {
            throw new NoContentException('No content provided');
        }
        if (empty($url)) {
            throw new NoURLException('No URL provided');
        }
        $data = array("content" => $content);
        $data_string = json_encode($data);
        $opts = array(
            'http' => array(
                'method' => "POST",
                "name" => "jvscript.io",
                "user_name" => "jvscript.io",
                'header' => "Content-Type: application/json\r\n",
                'content' => $data_string
            )
        );

        $context = stream_context_create($opts);
        file_get_contents($url, false, $context);
    }

}
