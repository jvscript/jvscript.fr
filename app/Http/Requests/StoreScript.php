<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScript extends FormRequest
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
            'js_url.regex' => 'Le lien du script doit terminer par \'.js\'',
            'topic_url.regex' => 'Le lien du topic devrait être de type http://www.jeuxvideo.com/forums/...',
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
            'name' => 'required|max:50|unique:scripts|not_in:ajout',
            'description' => 'required',
            "autor" => "max:255",
            'js_url' => "required|url|max:255|regex:/.*\.js$/",
            'repo_url' => "url|max:255",
            'photo_url' => "url|max:255|image_url",
            'photo_file' => 'nullable|mimes:jpeg,jpg,png,gif',
            'don_url' => "url|max:255",
            'website_url' => "url|max:255",
            'topic_url' => "url|max:255|regex:/^https?:\/\/www\.jeuxvideo\.com\/forums\/.*/",
            'g-recaptcha-response' => [$recaptchaRequired, new \App\Rules\Recaptcha],
        ];
    }
}
