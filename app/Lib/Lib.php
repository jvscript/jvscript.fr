<?php

namespace App\Lib;

use App\Script,
    App\Skin;

class Lib {

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
