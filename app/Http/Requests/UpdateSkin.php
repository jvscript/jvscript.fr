<?php

namespace App\Http\Requests;

use App\Model\Skin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rule;

class UpdateSkin extends FormRequest
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
            'photo_url.image_url' => "L'url de l'image est invalide."
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentSkin = Skin::where('slug', $this->route('slug'))->firstOrFail();
        return [
            'name' => [
                'required',
                'max:50',
                 Rule::unique('skins')->ignore($currentSkin->id),  
                'not_in:ajout',
            ],
            'skin_url' => ['required', 'url', 'max:255', 'regex:/^https:\/\/userstyles\.(org|world)\/styles?\/.*/'],
            'repo_url' => "url|max:255",
            'photo_url' => "url|max:255|image_url",
            'photo_file' => 'nullable|mimes:jpeg,jpg,png,gif',
            'user_id' => "exists:users,id",
            'don_url' => "url|max:255",
            'last_update' => "date_format:d/m/Y",
            'website_url' => "url|max:255",
            'topic_url' => "url|max:255|regex:/^https?:\/\/www\.jeuxvideo\.com\/forums\/.*/",
        ];
    }
}
