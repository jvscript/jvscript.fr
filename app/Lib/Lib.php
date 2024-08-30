<?php

namespace App\Lib;

use App\Model\Script;
use App\Model\Skin;
use Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Lib
{
    /**
     * Usefull functions
     */

    /**
     * Renvoie true si l'user doit être limité
     * @param int $seconds
     * @return boolean limited comment
     */
    public function limitComment($seconds)
    {
        $user = Auth::user();
        if (!$user) {
            return true;
        }
        return $user->comments()->where('created_at', '>', \Carbon\Carbon::now()->subSeconds($seconds))->count();
    }

    public function adminOrFail()
    {
        if (!(Auth::check() && Auth::user()->isAdmin())) {
            abort(404);
        }
    }

    public function ownerOradminOrFail($user_id)
    {
        //si c'est l'owner de l'objet (script/skin) on laisse passer
        if (!(Auth::check() && Auth::user()->id == $user_id)) {
            $this->adminOrFail();
        }
    }

    public function storeImage($item, $file)
    {
        Storage::delete('public/images/' . $item->photoShortLink());
        Storage::delete('public/images/small-' . $item->photoShortLink());
        $filename = $item->slug;
        $filename = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/', '-', $filename));

        $img = Image::make($file);

        if ($img->mime() != 'image/png') {
            $img->encode('jpg');
            $filename = $filename . ".jpg";
        } else {
            $filename = $filename . ".png";
        }

        //== RESIZE NORMAL ==
        $img->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->resize(null, 1000, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        \File::exists(storage_path('app/public/images/')) or \File::makeDirectory(storage_path('app/public/images/'));
        $img->save(storage_path('app/public/images/') . $filename, 90);

        //== RESIZE MINIATURE ==
        $img->resize(345, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->resize(null, 345, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save(storage_path('app/public/images/small-') . $filename, 85);

        //store photo in DB
        $item->photo_url = $filename;
        $item->save();
    }

    public function sendDiscord($content, $url)
    {
        $data = ["content" => $content];
        $data_string = json_encode($data);
        $opts = [
            'http' => [
                'method' => "POST",
                "name" => "jvscript.io",
                "user_name" => "jvscript.io",
                'header' => "Content-Type: application/json\r\n",
                'content' => $data_string
            ]
        ];

        try {
            $context = stream_context_create($opts);
            file_get_contents($url, false, $context);
        } catch (\Exception $ex) {
            return;
        }
    }

    public function isImage($path)
    {
        try {
            if (!is_array(getimagesize($path))) {
                return false;
            }

            $a = getimagesize($path);

            $image_type = $a[2];

            if (in_array($image_type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP])) {
                return true;
            }
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

}
