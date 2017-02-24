<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Script,
    App\Skin,
    App\User,
    App\Comment,
    App\History;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notify;
use Auth;
use App;
use App\Notifications\notifyStatus;

class JvscriptController extends Controller {

    //_TODO : retenir le filtre/sort en session/cookie utilisateur 
    /**
     * Create a new controller instance.     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');

        if (App::environment('local', 'testing')) {
            $this->recaptcha_key = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
        } else { //prod
            $this->recaptcha_key = env('RECAPTCHA_KEY', '');
        }

        $this->discord_url = env('DISCORD_URL', '');
        $this->min_time_comment = 30; //limite de temps entre chaque commentaire
    }

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
     * Admin index
     */
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
     * Admin comment
     */
    public function adminComments(Request $request) {
        $this->adminOrFail();
        $comments = Comment::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.comments', ['comments' => $comments]);
    }

    /**
     * Admin delete comment
     */
    public function adminDeleteComment($comment_id) {
        $this->adminOrFail();
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
        $this->adminOrFail();
        return \App\User::select('id', 'name')->get();
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
        }
        $comment = Comment::findOrFail($comment_id);
        $this->ownerOradminOrFail($comment->user_id);
        $comment->delete();
        return redirect(route("$item.show", $slug) . "#comments");
    }

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
            if (!App::environment('testing', 'local','production') && !$resp->isSuccess()) {
                $request->flash();
                return redirect(route("$item.show", $slug) . "#comments")->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }
            //Anti spam 30 secondes
            if ($user->comments()->where('created_at', '>', \Carbon\Carbon::now()->subSeconds($this->min_time_comment))->count()) {
                $request->flash();
                return redirect(route("$item.show", $slug) . "#comments")->withErrors(['comment' => "Veuillez attendre $this->min_time_comment secondes entre chaque commentaire svp."]);
            }
            $comment = $request->input('comment');
            $model->comments()->create(['comment' => $comment, 'user_id' => $user->id]);
            //_TODO : notify autor 
            return redirect(route("$item.show", $slug) . "#comments");
        }
    }

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
            if (!App::environment('testing') && !$resp->isSuccess()) {
                $request->flash();
                return redirect(route('script.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }

            $script = Script::create($request->all());
            $script->slug = $this->slugifyScript($script->name);

            if ($request->input("is_autor") == 'on') {
                $script->user_id = $user->id; //owner du script               
                $script->autor = $user->name;
            }
            $script->poster_user_id = $user->id;
            $script->save();

            $message = "[new script] Nouveau script posté sur le site : " . route('script.show', ['slug' => $script->slug]);
            $this->sendDiscord($message, $this->discord_url);

            return redirect(route('script.form'))->with("message", "Merci, votre script est en attente de validation.");
        }
    }

    /**
     * Store a skin in db
     */
    public function storeSkin(Request $request) {
        $user = Auth::user();
        $messages = [
            'skin_url.regex' => 'Le champ :attribute doit être un lien du format \'https://userstyles.org/styles/...\'',
        ];
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:50|unique:skins|not_in:ajout',
                    'description' => 'required',
                    "autor" => "max:255",
                    'skin_url' => "required|url|max:255|regex:/^https:\/\/userstyles\.org\/styles\/.*/",
                    'repo_url' => "url|max:255",
                    'photo_url' => "url|max:255",
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
            if (!App::environment('testing') && !$resp->isSuccess()) {
                $request->flash();
                return redirect(route('skin.form'))->withErrors(['recaptcha' => 'Veuillez valider le captcha svp.']);
            }

            $script = Skin::create($request->all());
            $script->slug = $this->slugifySkin($script->name);

            if ($request->input("is_autor") == 'on') {
                $script->user_id = $user->id; //owner script
                $script->autor = $user->name;
            }
            $script->poster_user_id = $user->id;
            $script->save();

            $message = "[new skin] Nouveau skin posté sur le site : " . route('skin.show', ['slug' => $script->slug]);
            $this->sendDiscord($message, $this->discord_url);

            return redirect(route('skin.form'))->with("message", "Merci, votre skin est en attente de validation.");
        }
    }

    /**
     * admin or owner
     */
    public function updateScript(Request $request, $slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->ownerOradminOrFail($script->user_id);

        $messages = [
            'js_url.regex' => 'Le lien du script doit terminer par \'.js\'',
        ];
        $validator = Validator::make($request->all(), [
                    "autor" => "max:255",
                    'js_url' => "required|url|max:255|regex:/.*\.js$/",
                    'repo_url' => "url|max:255",
                    'photo_url' => "url|max:255",
                    'don_url' => "url|max:255",
                    'user_id' => "exists:users,id",
                    'sensibility' => "in:0,1,2",
                    'last_update' => "date_format:d/m/Y",
                    'website_url' => "url|max:255",
                    'topic_url' => "url|max:255|regex:/^https?:\/\/www\.jeuxvideo\.com\/forums\/.*/",
                        ], $messages);

        //update only this fields
        $toUpdate = ['sensibility', 'autor', 'description', 'js_url', 'repo_url', 'photo_url', 'don_url', 'website_url', 'topic_url', 'version', 'last_update'];
        if (Auth::user()->isAdmin()) {
            $toUpdate = ['sensibility', 'autor', 'description', 'js_url', 'repo_url', 'photo_url', 'don_url', 'website_url', 'topic_url', 'user_id', 'version', 'last_update'];
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
            $script->fill($request->only($toUpdate));
            $script->version = $request->input('version');
            if ($request->has('last_update')) {
                $script->last_update = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('last_update'));
            }
            $script->save();
            return redirect(route('script.show', ['slug' => $slug]));
        }
    }

    public function updateSkin(Request $request, $slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->ownerOradminOrFail($skin->user_id);

        $messages = [
            'skin_url.regex' => 'Le champ :attribute doit être un lien du format \'https://userstyles.org/styles/...\'',
        ];
        $validator = Validator::make($request->all(), [
                    'skin_url' => "required|url|max:255|regex:/^https:\/\/userstyles\.org\/styles\/.*/",
                    'repo_url' => "url|max:255",
                    'photo_url' => "url|max:255",
                    'user_id' => "exists:users,id",
                    'don_url' => "url|max:255",
                    'last_update' => "date_format:d/m/Y",
                    'website_url' => "url|max:255",
                    'topic_url' => "url|max:255|regex:/^https?:\/\/www\.jeuxvideo\.com\/forums\/.*/",
                        ], $messages);
        //update only this fields
        $toUpdate = ['sensibility', 'autor', 'description', 'js_url', 'repo_url', 'photo_url', 'don_url', 'website_url', 'topic_url', 'version', 'last_update'];
        if (Auth::user()->isAdmin()) {
            $toUpdate = ['sensibility', 'autor', 'description', 'js_url', 'repo_url', 'photo_url', 'don_url', 'website_url', 'topic_url', 'user_id', 'version', 'last_update'];
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
            $skin->version = $request->input('version');
            if ($request->has('last_update')) {
                $skin->last_update = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('last_update'));
            }
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
            if ($script->user_id != null) {
//                Mail::to($script->poster_user()->first()->email)->send(new Notify($script));
                $script->poster_user()->first()->notify(new notifyStatus($script));
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
            if ($skin->user_id != null) {
//                Mail::to($skin->poster_user()->first()->email)->send(new Notify($skin));
                $skin->poster_user()->first()->notify(new notifyStatus($skin));
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
            if ($script->user_id != null) {
//                Mail::to($script->poster_user()->first()->email)->send(new Notify($script));
                $script->poster_user()->first()->notify(new notifyStatus($script));
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
            if ($skin->user_id != null) {
//                Mail::to($skin->poster_user()->first()->email)->send(new Notify($skin));
                $skin->poster_user()->first()->notify(new notifyStatus($skin));
            }
        }
        return redirect(route('skin.show', ['slug' => $slug]));
    }

    /**
     * Install script : count & redirect 
     */
    public function installScript($slug, Request $request) {
        $script = Script::where('slug', $slug)->firstOrFail();

        //if no history install_count +1
        $history = History::where(['ip' => $request->ip(), 'what' => $slug, 'action' => 'install']);
        if ($history->count() == 0) {
            History::create(['ip' => $request->ip(), 'what' => $slug, 'action' => 'install']);
            $script->install_count++;
            $script->save();
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

    /**
     * Install script : count & redirect 
     */
    public function installSkin($slug, Request $request) {
        $skin = Skin::where('slug', $slug)->firstOrFail();

        //if no history install_count +1
        $history = History::where(['ip' => $request->ip(), 'what' => "skin_$slug", 'action' => 'install']);
        if ($history->count() == 0) {
            History::create(['ip' => $request->ip(), 'what' => "skin_$slug", 'action' => 'install']);
            $skin->install_count++;
            $skin->save();
        }
        return redirect($skin->skin_url);
    }

    /**
     * Note script : note & redirect 
     */
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
            $this->sendDiscord($message, $this->discord_url);

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
        $comments = $script->comments()->orderBy('created_at', 'desc')->paginate(10);
        //affiche les non validés seulement si admin
        if (!$script->isValidated() && !(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }
        $Parsedown = new \Parsedown();
        $Parsedown->setMarkupEscaped(true);
        $script->description = $Parsedown->text($script->description);

        return view('script.show', ['script' => $script, 'comments' => $comments]);
    }

    public function showSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $comments = $skin->comments()->orderBy('created_at', 'desc')->paginate(10);
        //affiche les non validés seulement si admin
        if (!$skin->isValidated() && !(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }
        $Parsedown = new \Parsedown();
        $Parsedown->setMarkupEscaped(true);
        $skin->description = $Parsedown->text($skin->description);

        return view('skin.show', ['skin' => $skin, 'comments' => $comments]);
    }

    public function editScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->ownerOradminOrFail($script->user_id);
        return view('script.edit', ['script' => $script]);
    }

    public function editSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->ownerOradminOrFail($skin->user_id);
        return view('skin.edit', ['skin' => $skin]);
    }

    public function deleteScript($slug) {
        $script = Script::where('slug', $slug)->firstOrFail();
        $this->ownerOradminOrFail($script->user_id);
        $script->delete();
        $message = "[delete script] Script supprimé par " . Auth::user()->name . " : $script->name | $script->slug ";
        $this->sendDiscord($message, $this->discord_url);
        if (Auth::user()->isAdmin())
            return redirect(route('admin_index'));
        return redirect(route('index'));
    }

    public function deleteSkin($slug) {
        $skin = Skin::where('slug', $slug)->firstOrFail();
        $this->ownerOradminOrFail($skin->user_id);
        $skin->delete();
        $message = "[delete script] Script supprimé par " . Auth::user()->name . " : $skin->name | $skin->slug ";
        $this->sendDiscord($message, $this->discord_url);

        if (Auth::user()->isAdmin())
            return redirect(route('admin_index'));
        return redirect(route('index'));
    }

    /**
     * Usefull functions 
     */
    public function adminOrFail() {
        if (!(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }
    }

    public function ownerOradminOrFail($user_id) {
        //si c'est l'owner de l'objet (script/skin) on laisse passer
        if (!(Auth::check() && Auth::user()->id == $user_id)) {
            $this->adminOrFail();
        }
    }

    public function slugifyScript($name) {
        $slug = $this->slugify($name);
        $i = 1;
        $baseSlug = $slug;
        while (Script::where('slug', $slug)->count() > 0) {
            $slug = $baseSlug . "-" . $i++;
        }
        return $slug;
    }

    public function slugifySkin($name) {
        $slug = $this->slugify($name);
        $i = 1;
        $baseSlug = $slug;
        while (Skin::where('slug', $slug)->count() > 0) {
            $slug = $baseSlug . "-" . $i++;
        }
        return $slug;
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

    public function githubDate($url) {

//        return $date;
    }

    public function crawlInfo() {
        set_time_limit(600);
        $scripts = Script::where("status", 1)->orderBy('last_update', 'asc')->get();
        foreach ($scripts as $script) {
            echo "start   : " . $script->name . "\n";
            if (preg_match('/https:\/\/github\.com\/(.*)\/(.*)\/raw\/(.*)\/(.*)\.js/i', $script->js_url, $match) || preg_match('/https:\/\/raw\.githubusercontent\.com\/(.*)\/(.*)\/(.*)\/(.*)\.js/i', $script->js_url, $match)) {
                $url_crawl = "https://github.com/$match[1]/$match[2]/blob/$match[3]/$match[4].js";
                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/<relative-time datetime="(.*Z)">/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $date = \Carbon\Carbon::parse($date);
                    $script->last_update = $date;
                    $script->save();
                    echo $script->js_url . "|$url_crawl|$date\n";
                } else {
                    echo "fail : " . $script->js_url . "|$url_crawl\n";
                }
            } else if (preg_match('/https:\/\/(.*)\.github\.io\/(.*)\/(.*)\.js/i', $script->js_url, $match)) {
                //GITHUB PAGES
                $url_crawl = "https://github.com/$match[1]/$match[2]/blob/master/$match[3].js";
                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/<relative-time datetime="(.*Z)">/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $date = \Carbon\Carbon::parse($date);
                    $script->last_update = $date;
                    $script->save();
                    echo $script->js_url . "|$url_crawl|$date\n";
                } else {
                    echo "fail : " . $script->js_url . "|$url_crawl\n";
                }
            } elseif (preg_match('/https:\/\/openuserjs\.org\/install\/(.*)\/(.*)\.user\.js/i', $script->js_url, $match) || preg_match('/https:\/\/openuserjs\.org\/src\/scripts\/(.*)\/(.*)\.user\.js/i', $script->js_url, $match)) {
                $url_crawl = "https://openuserjs.org/scripts/$match[1]/$match[2]";
                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/<time class="script-updated" datetime="(.*Z)" title=/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $date = \Carbon\Carbon::parse($date);
                    $script->last_update = $date;
                    $script->save();
                    echo $script->js_url . "|$url_crawl|$date\n";
                } else if (preg_match('/<b>Published:<\/b> <time datetime="(.*Z)"/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $date = \Carbon\Carbon::parse($date);
                    $script->last_update = $date;
                    $script->save();
                    echo $script->js_url . "|$url_crawl|$date\n";
                } else {
                    echo "fail : " . $script->js_url . "|$url_crawl\n";
                }
                //get version openuserjs in same page
                if (preg_match('/<code>(.*)<\/code>/i', $crawl_content, $match)) {
                    $script->version = $match[1];
                    $script->save();
                    echo $script->js_url . "|$url_crawl|version : $script->version\n";
                }
            } elseif (preg_match('/https:\/\/greasyfork.org\/scripts\/(.*)\/code\/(.*)\.user\.js/i', $script->js_url, $match)) {
                $url_crawl = "https://greasyfork.org/fr/scripts/$match[1]";
                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/updated-date"><span><time datetime="(.*)">(.*)<\/time>/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $date = \Carbon\Carbon::parse($date);
                    $script->last_update = $date;
                    $script->save();
                    echo $script->js_url . "|$url_crawl|$date\n";
                } else {
                    echo "fail : " . $script->js_url . "|$url_crawl\n";
                }
            }

            //===GET  VERSION===
            $url_crawl = $script->js_url;

            if (!str_contains($url_crawl, 'openuserjs')) {
                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/\/\/\s*@version\s*(.*)/i', $crawl_content, $match_date)) {
                    $version = $match_date[1];
                    $script->version = $version;
                    $script->save();
                    echo $script->js_url . "|version : $version\n";
                } else {
                    echo "fail version : " . $script->js_url . "\n";
                }
            }
//            sleep(1);
        }

        $scripts = Skin::where("status", 1)->orderBy('last_update', 'asc')->get();
        foreach ($scripts as $script) {
            $url_crawl = $script->skin_url;
            $crawl_content = @file_get_contents($url_crawl);
            if (preg_match('/<th>Updated<\/th>\n\s*<td>(.*)<\/td>/i', $crawl_content, $match_date)) {
                $date = $match_date[1];
                $date = \Carbon\Carbon::parse($date);
                $script->last_update = $date;
                $script->save();
                echo $script->js_url . "|$url_crawl|$date\n";
            } else {
                echo "fail : " . $script->js_url . "|$url_crawl\n";
            }
        }
    }

}
