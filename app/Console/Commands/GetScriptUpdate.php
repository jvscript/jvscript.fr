<?php

namespace App\Console\Commands;

use App\Model\Script;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetScriptUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-script-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update script';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->crawlInfo();
    }

    private function crawlInfo()
    {
        $scripts = Script::where("status", 1)
            ->where('updated_at', '<', \Carbon\Carbon::now()->subDay(1))
            ->orderBy('updated_at', 'asc')
            ->get();

        foreach ($scripts as $script) {
            $this->info("Script : " . $script->name);

            $newDate = null;

            //transform github blob url to raw
            $pattern = '/(https:\/\/github\.com\/[^\/]+\/[^\/]+)\/blob\/(.+)/';
            if (preg_match($pattern, $script->js_url)) {
                $replacement = '$1/raw/$2';
                $raw_url = preg_replace($pattern, $replacement, $script->js_url);
                $script->js_url = $raw_url;
                $script->save();
                $this->warn("fixed : " .  $raw_url);
            }

            if (
                preg_match('/https:\/\/github\.com\/(.*)\/(.*)\/raw\/(.*)\/(.*)\.js/i', $script->js_url, $match)
                || preg_match('/https:\/\/raw\.githubusercontent\.com\/(.*)\/(.*)\/(.*)\/(.*)\.js/i', $script->js_url, $match)
            ) {
                $owner = $match[1];
                $repo = $match[2];
                $branch = $match[3];
                $file_path = ($match[4] . '.js');
                //replace space by %20 in file path
                $file_path = str_replace(' ', '%20', $file_path);
                //replace + by %2B in file path
                $file_path = str_replace('+', '%2B', $file_path);
                $url_crawl = "https://github.com/$owner/$repo/raw/$branch/$file_path";
                $api_url = "https://api.github.com/repos/$owner/$repo/commits?path=$file_path&sha=$branch";

                $client = new Client();
                $headers = [
                    "Authorization: Bearer " . env('GITHUB_TOKEN'),
                    "User-Agent: My-GitHub-App"  // GitHub requires a user-agent string
                ];
                try {
                    $response = $client->request('GET', $api_url, ['headers' => $headers]);
                    if ($response->getStatusCode() == 200) {
                        $commits = json_decode($response->getBody()->getContents(), true);
                        if (!empty($commits) && isset($commits[0]['commit']['committer']['date'])) {
                            $date = $commits[0]['commit']['committer']['date'];
                            $newDate = \Carbon\Carbon::parse($date);
                            //TODO: front : update il y a XX mois ou XX jours
                        } else {
                            $this->error("fail github get date : " . $script->js_url . " |  $api_url");
                            // die;
                        }
                    }
                } catch (\Exception $ex) {
                    $this->error("fail: Could not fetch data from GitHub API | " . $api_url .  $ex->getMessage());
                    // die;
                }
            } elseif (preg_match('/https:\/\/(.*)\.github\.io\/(.*)\/(.*)\.js/i', $script->js_url, $match)) {
                //GITHUB PAGES
                $url_crawl = "https://github.com/$match[1]/$match[2]/blob/master/$match[3].js";
                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/<relative-time datetime="(.*Z)">/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $newDate = \Carbon\Carbon::parse($date);
                } else {
                    $this->error("fail date : " . $script->js_url . " | $url_crawl");
                    // die;
                }
            } elseif (preg_match('/https:\/\/openuserjs\.org\/install\/(.*)\/(.*)\.user\.js/i', $script->js_url, $match) || preg_match('/https:\/\/openuserjs\.org\/src\/scripts\/(.*)\/(.*)\.user\.js/i', $script->js_url, $match)) {
                $url_crawl = "https://openuserjs.org/scripts/$match[1]/$match[2]";
                //remove .min at the end of url 
                $url_crawl = str_replace('.min', '', $url_crawl);

                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/<time class="script-updated" datetime="(.*Z)" title=/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $newDate = \Carbon\Carbon::parse($date);
                } elseif (preg_match('/<b>Published:<\/b> <time datetime="(.*Z)"/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $newDate = \Carbon\Carbon::parse($date);
                } else {
                    $this->error("fail date : " . $script->js_url . " | $url_crawl");
                    // die;
                }
                //get version openuserjs in same page
                if (preg_match('/<code>([0-9.]+).*<\/code>/i', $crawl_content, $match)) {
                    $script->version = strip_tags($match[1]);
                    $script->save();
                }
            } elseif (preg_match('/https:\/\/(?:update\.)?greasyfork\.org\/scripts\/([^\/]+)(?:\/code)?\/(.*)\.user\.js/i', $script->js_url, $match)) {
                $url_crawl = "https://greasyfork.org/fr/scripts/$match[1]";
                $crawl_content = @file_get_contents($url_crawl);
                if (preg_match('/script-show-updated-date.* datetime="(.*)" prefix="/i', $crawl_content, $match_date)) {
                    $date = $match_date[1];
                    $newDate = \Carbon\Carbon::parse($date);
                } else {
                    $this->error("fail date : " . $script->js_url . " | $url_crawl");
                    // die;
                }
            }

            if (
                $newDate &&
                $newDate->toDateString() != ($script->last_update !== null ? $script->last_update->toDateString() : null)
            ){
                $this->warn("date changed : $script->last_update => $newDate");
                $script->update(['last_update' => $newDate]);
            }

            //===GET  VERSION===
            $url_crawl = $script->js_url;

            if (!str_contains($url_crawl, 'openuserjs')) {
                $client = new Client();
                try {
                    $response = $client->request('GET', $url_crawl, ['timeout' => 3]);
                    $content = $response->getBody()->getContents();
                    if (preg_match('/\/\/\s*@version\s+([\d\.\w\-_]+)/i', $content, $match_date)) {
                        $version = $match_date[1];
                        $script->version = $version;
                        $script->save();
                        if ($script->wasCHanged()) {
                            $this->info("version updated : $version");
                            if ($script->last_update === NULL) {
                                $script->update(['last_update' => \Carbon\Carbon::now()]);
                                $this->warn("version changed but last_update is null : $version");
                            } 
                        }                       
                    } else {
                        $this->error("fail version : " . $script->js_url);
                        Log::error("fail version : " . $script->name . " | " . $script->js_url);
                        // die;
                    }
                } catch (\Exception $ex) {
                    $this->error("fail: Could not fetch data  | " . $url_crawl .  $ex->getMessage());
                    Log::error("Could not fetch data  | " . $url_crawl .  $ex->getMessage());
                    // die;
                }
            }
            $script->touch();
            $this->info("");
        }

        //update skin date
        // $scripts = Skin::where("status", 1)->orderBy('last_update', 'asc')->get();
        // foreach ($scripts as $script) {
        //     $url_crawl = $script->skin_url;
        //     $crawl_content = @file_get_contents($url_crawl);
        //     if (preg_match('/<th>Updated<\/th>\n\s*<td>(.*)<\/td>/i', $crawl_content, $match_date)) {
        //         $date = $match_date[1];
        //         $date = \Carbon\Carbon::parse($date);
        //         $script->last_update = $date;
        //         $script->save();
        //         echo $script->js_url . "|$url_crawl|$date\n";
        //     } else {
        //         echo "fail : " . $script->js_url . " | $url_crawl\n";
        //         die;
        //     }
        // }
    }
}
