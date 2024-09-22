<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkin extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'skin_url.regex' => 'Le champ :attribute doit être un lien du format \'https://userstyles.org/styles/...\'',
            'topic_url.regex' => 'Le lien du topic devrait être du format : http://www.jeuxvideo.com/forums/...',
            'photo_url.image_url' => "L'url de l'image est invalide.",
            'g-recaptcha-response.required' => "Veuillez valider le captcha svp."
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $recaptchaRequired = \App::environment('production') ? 'required' : 'sometimes';
        return [
            'name' => 'required|max:50|unique:skins|not_in:ajout',
            'description' => 'required',
            "autor" => "max:255",
            'skin_url' => ['required', 'url', 'max:255', 'regex:/^https:\/\/userstyles\.(org|world)\/styles?\/.*/'],
            'repo_url' => "url|max:255",
            'photo_url' => "url|max:255|image_url",
            'photo_file' => "image",
            'don_url' => "url|max:255",
            'website_url' => "url|max:255",
            'topic_url' => "url|max:255|regex:/^https?:\/\/www\.jeuxvideo\.com\/forums\/.*/",
            'g-recaptcha-response' => [$recaptchaRequired, new \App\Rules\Recaptcha],
        ];
    }
}
