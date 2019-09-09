<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ip = request()->ip();
        if (\App::environment('local', 'testing')) {
            $this->recaptcha_key = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
        } else { //prod
            $this->recaptcha_key = env('RECAPTCHA_KEY', '');
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $recaptcha = new \ReCaptcha\ReCaptcha($this->recaptcha_key);
        $result = $recaptcha->verify($attribute, $this->ip);
        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Veuillez valider le captcha svp.';
    }
}
