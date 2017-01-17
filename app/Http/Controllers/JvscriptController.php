<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Script,
    App\History;
use Validator;

class JvscriptController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //_TODO : filter status 2
        $scripts = Script::all();

        return view('index', ['scripts' => $scripts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeScript(Request $request) {
//        $user = Auth::user();
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255|unique:scripts',
                    'js_url' => "required|url",
                    'repo_url' => "url",
                    'photo_url' => "url",
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
            while ($this->slugExist($slug)) {
                $slug = $baseSlug . "-" . $i++;
            }
            $script->slug = $slug;
            $script->save();

            /**
             * todo redirect to script awaiting 
             */
            return redirect(route('ajout-form'))->with("message", "Merci, votre script est en attente de validation.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function showScript($slug) {
        $script = Script::where('slug', $slug)->first();
        if (!$script) {
            abort(404);
        }
        return view('script.show', ['script' => $script]);
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
            $history = History::where(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => $slug, 'action' => 'note']);
            if ($history->count() == 0) {
                History::create(['ip' => $_SERVER['REMOTE_ADDR'], 'what' => $slug, 'action' => 'note']);
                $script->note = ( $script->note * $script->note_count + $note ) / ($script->note_count + 1);
                $script->note_count++;
                $script->save();
            }
        }
        return redirect(route('script.show', $slug));
    }

    public function slugExist($slug) {
        return Script::where('slug', $slug)->count() > 0;
    }

    static public
            function slugify($text) {
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
