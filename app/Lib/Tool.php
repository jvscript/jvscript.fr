<?php

namespace App\Lib;

use App\Script,
    App\Skin;
use Illuminate\Support\Facades\Storage;

class Tool {

//    public function storeExternalImages() {
//        set_time_limit(60);
//        $scripts = Script::all();
//        $skins = Skin::all();
//
//        $this->lib = new \App\Lib\Lib();
//        $this->loopItem($scripts);
//        $this->loopItem($skins);
//    }
//
//    public function loopItem($scripts) {
//        $controller = new \App\Http\Controllers\JvscriptController();
//        foreach ($scripts as $script) {
//            //if match http in photo url store locally
//            if (str_contains($script->photoShortLink(), 'http://') || str_contains($script->photo_url, 'https://')) {
//                if ($this->lib->isImage($script->photoShortLink())) {
//                    $file = @file_get_contents($script->photoShortLink());
//                    echo "storing " . $script->photoShortLink() . " <br>";
//                    Storage::delete('public/images/' . $script->photoShortLink());
//                    Storage::delete('public/images/small-' . $script->photoShortLink());
//                    $controller->storeImage($script, $file);
//                }
//            }
//        }
//    }

}
