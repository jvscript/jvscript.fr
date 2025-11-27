<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Lib\Lib;
use App\Model\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'fax' => 'max:0',
            'cf-turnstile-response' => 'required'
        ];
        if (app()->environment('testing')) {
            $rules['cf-turnstile-response'] = 'nullable';
        }
        $validator = Validator::make($data, $rules, [
            'cf-turnstile-response.required' => 'Veuillez compléter le challenge de sécurité.'
        ]);

        // Valider le token Turnstile
        $validator->after(function ($validator) use ($data) {
            if (!$this->validateTurnstile($data['cf-turnstile-response'] ?? '')) {
                $validator->errors()->add('cf-turnstile-response', 'La vérification de sécurité a échoué. Veuillez réessayer.');
            }
        });

        return $validator;
    }

    /**
     * Valide le token Turnstile auprès de Cloudflare
     *
     * @param string $token
     * @return bool
     */
    protected function validateTurnstile($token)
    {
        if (app()->environment('testing')) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $client = new Client();
            $response = $client->post(config('turnstile.verify_url'), [
                'form_params' => [
                    'secret' => config('turnstile.secret_key'),
                    'response' => $token,
                    'remoteip' => request()->ip()
                ]
            ]);

            $body = json_decode($response->getBody(), true);

            return isset($body['success']) && $body['success'] === true;
        } catch (\Exception $e) {
            Log::error('Turnstile validation error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $lib = new Lib();
        $message = "[Inscription] Nouveau user : " . $data['name'] . '/' . $data['email'];
        $lib->sendDiscord($message, config('services.discord.webhook_url'));
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
