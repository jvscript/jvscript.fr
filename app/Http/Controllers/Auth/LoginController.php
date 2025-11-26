<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Redirect;
use Socialite;

class LoginController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

    use AuthenticatesUsers;


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'name';
    }

    public function logout(Request $request)
    {
        $redirectUrl = url()->previous(); 
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($redirectUrl); 
    }

    private function redirectTo()
    {
        return session('redir');
    }

    public function showLoginForm()
    {
        session(['redir' => url()->previous()]);
        return view('auth.login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is now applied via routes or method attributes
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('github')->user();
        } catch (Exception $e) {
            return Redirect::to('auth/github');
        }

        $authUser = $this->findOrCreateUser($user);
        if ($authUser == "error.email") {
            return redirect(url('/login'))->withErrors(['github' => 'Votre email \'' . $user->email . '\' est déjà utilisée pour un compte classique en BDD.']);
        }

        Auth::login($authUser, true);

        return Redirect::to('/');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $githubUser
     * @return User
     */
    private function findOrCreateUser($githubUser)
    {
        if ($authUser = User::where('github_id', $githubUser->id)->first()) {
            return $authUser;
        }

        //if email existe en base pour un compte non github
        //->where('github_id',null)
        if (User::where('email', $githubUser->email)->count() > 0) {
            return "error.email";
        }

        //if github name existe déjà en base, generate unique name
        $baseName = $name = $githubUser->nickname;
        $i = 1;
        while (User::where('name', $name)->count() > 0) {
            $name = $baseName . "-" . $i++;
        }

        return User::create([
            'name' => $name,
            'email' => $githubUser->email,
            'github_id' => $githubUser->id,
            'password' => bcrypt(\Illuminate\Support\Str::random(7))
        ]);
    }
}
