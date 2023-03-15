<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-24
 * Time: 15:27
 */

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;
use DB;
use View;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Post;
use Cache;
use Analytics;
use Spatie\Analytics\Period;

use NiNaCoder\Updater\UpdaterManager;

class DashboardController
{
    public function index(Request $request)
    {
        $dashboard = (object)array();
        $dashboard->total_songs = DB::table('songs')->count();
        $dashboard->total_artists = DB::table('artists')->count();
        $dashboard->total_albums = DB::table('albums')->count();
        $dashboard->total_playlists = DB::table('playlists')->count();

        $maxFileSize = (@ini_get('post_max_size') != '') ? @ini_get('post_max_size') : "Unknown";
        $safeMode = (@ini_get('safe_mode') == 1) ? "<span class=\"text-danger\">Safe mode IS <strong>ON!</strong>  We required off, please set <strong>safe mode</strong> to <strong>off</strong></span>" : "<span class=\"text-success\">Safe mode IS <strong>OFF!</strong></span>";

        if ( function_exists( 'shell_exec' ) ) {
            $ffmpeg = @shell_exec(env('FFMPEG_PATH') . ' -version') ? "<span class=\"text-success\"><strong>Supported</strong></span>" : "<span class=\"text-danger\"><strong>Not supported</strong></span>";
        } else {
            $ffmpeg = "<span class=\"text-danger\"><strong>Not supported</strong></span>";
        }

        if (env('IMPORT_MUSIC_MODULE')){
            $youtube_dl_version = @shell_exec(config('settings.youtube_dl_path', '/usr/local/bin/youtube-dl') . ' --version');
            if($youtube_dl_version) {
                $youtube_dl = "<span class=\"text-success\"><strong>Supported ( version: " . $youtube_dl_version . ")</strong></span>";
            } else {
                $youtube_dl = "<span class=\"text-danger\"><strong>Not supported</strong></span>";
            }
        }

        $maxMemory = (@ini_get( 'memory_limit' ) != '') ? @ini_get( 'memory_limit' ) : "Unknown";
        if ( function_exists( 'gd_info' ) ) {

            $array = gd_info ();
            $gdVersion = "";

            foreach ($array as $key=>$val) {

                if ($val===true) {
                    $val="Enabled";
                }

                if ($val===false) {
                    $val="Disabled";
                }

                $gdVersion .= $key.":&nbsp;{$val}, ";
            }

        } else $gdVersion = "Undefined";

        $results = DB::select( DB::raw("select version()") );
        $mysqlVersion =  $results[0]->{'version()'};

        if(intval($maxFileSize) < 20) $maxFileSize = "<span class=\"text-danger\">{$maxFileSize}. For best performance please set it to 20MB or higher</span>";
        else $maxFileSize = " <span class=\"text-success\">{$maxFileSize}</span>";

        $dashboard->information = "";
        $dashboard->information .="<tr><td>Music Engine Version</td><td><strong>" . env('APP_VERSION') . "</strong></td></tr>\n";
        $dashboard->information .="<tr><td>PHP Version </td><td>".PHP_VERSION."</td></tr>\n";
        $dashboard->information .="<tr><td>MYSQL Version </td><td>". $mysqlVersion ."</td></tr>\n";
        $dashboard->information .="<tr><td>FFMPEG</td><td>".$ffmpeg."</td></tr>\n";

        if(env('IMPORT_MUSIC_MODULE')) {
            $dashboard->information .="<tr><td>youtube-dl (<a href='https://youtube-dl.org/' target='_blank'>more information</a>)</td><td>".$youtube_dl."</td></tr>\n";
        }

        $dashboard->information .="<tr><td>Post Max Size</td><td><strong>".$maxFileSize."</strong>. The maximum upload file size.</td></tr>\n";
        $dashboard->information .="<tr><td>Max Memory Allow</td><td>".$maxMemory."</td></tr>\n";
        $dashboard->information .="<tr><td>Safemode</td><td>".$safeMode."</td></tr>\n";
        $dashboard->information .="<tr><td>Server Time</td><td>".date("D, F j, Y H:i:s", time())."</td></tr>\n";
        $dashboard->information .="<tr><td>Server IP</td><td>".getenv("REMOTE_ADDR")."</td></tr>\n";
        $dashboard->information .="<tr><td>Browser</td><td>".getenv("HTTP_USER_AGENT")."</td></tr>\n";
        $dashboard->information .="<tr><td>Information About GD:</td><td>". $gdVersion ."</td></tr>\n";
        $dashboard->information .="<tr><td>Request URI</td><td>".getenv("REQUEST_URI")."</td></tr>\n";
        $dashboard->information .="<tr><td>Referer</td><td>".getenv("HTTP_REFERER")."</td></tr>\n";
        $dashboard->information .="<tr><td>RAM Allocated </td><td>" . fileSizeConverter(@memory_get_usage(true)) ."</td></tr>\n";
        $dashboard->information .="<tr><td>OS</td><td>".PHP_OS."</td></tr>\n";
        $dashboard->information .="<tr><td>Server</td><td>".getenv("SERVER_SOFTWARE")."</td></tr>\n";
        $dashboard->information .="<tr><td>Server Name</td><td>".getenv("SERVER_NAME")."</td></tr>\n";
        $dashboard->information .="<tr><td>Upload Max File Size:</td><td>". fileSizeConverter( str_replace( array ('M', 'm' ), '', intval(@ini_get( 'upload_max_filesize' ) ))  * 1024 * 1024 ) ."</td></tr>\n";
        $dashboard->information .="<tr><td>Available disk space:</td><td>". fileSizeConverter( @disk_free_space( "." )) ."</td></tr>\n";


        $dashboard->server = new \stdClass();
        $dashboard->server->post_max_size = (@ini_get( 'post_max_size' ) != '') ? intval(@ini_get( 'post_max_size' )) : "Unknown";
        $dashboard->server->upload_max_filesize = intval(str_replace( array ('M', 'm' ), '', intval(@ini_get( 'upload_max_filesize' ) )));
        $dashboard->server->disk_free_space = fileSizeConverter( @disk_free_space( "." ));
        $dashboard->server->memory_limit = intval($maxMemory);
        $dashboard->server->max_execution_time = @ini_get('max_execution_time');
        $dashboard->server->max_input_time = @ini_get('max_input_time');




        /**
         * Get site statistics
         */

        $dashboard->statistics = new \stdClass();
        $dashboard->statistics->system_status = config('settings.site_offline') ? '<span class="badge badge-danger">Off</span>' : '<span class="badge badge-success">On</span>';
        $dashboard->statistics->site_url = env('APP_URL');
        $dashboard->statistics->total_posts = DB::table('posts')->count();
        $dashboard->statistics->awaiting_posts = DB::table('posts')->where('approved', 0)->count();
        $dashboard->statistics->total_comments = DB::table('comments')->count();
        $dashboard->statistics->awaiting_comments = DB::table('comments')->where('approved', 0)->count();
        $dashboard->statistics->total_users = DB::table('users')->count();
        $dashboard->statistics->banned_users = DB::table('users')->where('banned', 1)->count();
        $dashboard->statistics->cache_path = storage_path('framework/cache');
        $dashboard->statistics->total_artists = DB::table('artists')->count();
        $dashboard->statistics->awaiting_artists = DB::table('artists')->count();
        $dashboard->statistics->total_subscriptions = DB::table('subscriptions')->count();

        /** Get recent users */

        $dashboard->recentUsers = User::limit(5)->latest()->get();
        $dashboard->recentPosts = Post::limit(5)->latest()->get();
        $dashboard->subscriptions = Subscription::limit(8)->get();

        /** Get orders charts */

        $from = Carbon::now()->timestamp - (15 * 24 * 60 * 60);
        $from = date("Y-m-d H:i:s", $from);

        $subscriptions_data = DB::table('subscriptions')
            ->select(DB::raw('sum(amount) AS earnings'), DB::raw('DATE(created_at) as date'))
            ->where('subscriptions.created_at', '<=', Carbon::now()->format('Y/m/d H:i:s'))
            ->where('subscriptions.created_at', '>=', $from)
            ->where('subscriptions.payment_status', 1)
            ->groupBy('date')
            ->get();


        $rows = insertMissingData($subscriptions_data, ['earnings'], $from, date("Y-m-d"));
        $dashboard->orders_data = new \stdClass();
        foreach ($rows as $item) {
            $item = (array) $item;
            $dashboard->orders_data->earnings[] = $item['earnings'];
            $dashboard->orders_data->period[] = Carbon::parse($item['date'])->format('F j');
        }

        /** get plan data */
        $dashboard->plans = DB::table('services')
            ->select('id', 'title')
            ->get();

        //If google analytic is enabled

        if(config('settings.google_analytics')) {
            if (Cache::has('analytics')) {
                $dashboard->analytics = Cache::get('analytics');
            } else {

                $dashboard->analytics = new \stdClass();

                $dashboard->analytics->streamsPerCountry = Analytics::performQuery(
                    Period::months(1),
                    'ga:sessions',
                    [
                        'metrics' => 'ga:sessions',
                        'dimensions' => 'ga:country',
                        'sort' => '-ga:sessions',
                        'max-results' => '10',

                    ]
                );

                $dashboard->analytics->countryIsoCode = Analytics::performQuery(
                    Period::months(1),
                    'ga:sessions',
                    [
                        'metrics' => 'ga:sessions',
                        'dimensions' => 'ga:countryIsoCode',

                    ]
                );

                $visitorByCountryIsoCode = array();

                foreach ($dashboard->analytics->countryIsoCode->rows as $row) {
                    $visitorByCountryIsoCode[] = array(
                        $row[0] => intval($row[1])
                    );

                }

                $visitorByCountryIsoCode = json_encode($visitorByCountryIsoCode);
                $visitorByCountryIsoCode = str_replace("[{", "{", $visitorByCountryIsoCode);
                $visitorByCountryIsoCode = str_replace("}]", "}", $visitorByCountryIsoCode);
                $visitorByCountryIsoCode = str_replace("}]", "}", $visitorByCountryIsoCode);
                $visitorByCountryIsoCode = str_replace("{\"", "\"", $visitorByCountryIsoCode);
                $visitorByCountryIsoCode = str_replace("},\"", ",\"", $visitorByCountryIsoCode);
                $visitorByCountryIsoCode = "{" . $visitorByCountryIsoCode;

                $dashboard->analytics->visitorByCountryIsoCode = $visitorByCountryIsoCode;

                $dashboard->analytics->userAgeBracket = Analytics::performQuery(
                    Period::months(1),
                    'ga:sessions',
                    [
                        'metrics' => 'ga:sessions',
                        'dimensions' => 'ga:userAgeBracket,ga:userGender',

                    ]
                );

                $dashboard->analytics->topBrowsers = Analytics::performQuery(
                    Period::months(1),
                    'ga:sessions',
                    [
                        'metrics' => 'ga:sessions',
                        'dimensions' => 'ga:browser',
                        'sort' => '-ga:sessions',
                        'max-results' => '5',
                    ]
                );

                $dashboard->analytics->userType = Analytics::performQuery(
                    Period::months(1),
                    'ga:sessions',
                    [
                        'metrics' => 'ga:sessions',
                        'dimensions' => 'ga:userType',
                        'sort' => '-ga:sessions',
                        'max-results' => '5',
                    ]
                );

                Cache::put('analytics', $dashboard->analytics, Carbon::now()->addHour());
            }
        }


        $stats = Order::select(DB::raw('sum(amount) AS revenue'), DB::raw('sum(commission) AS commission'))->first();
        $stats->album = Order::select(DB::raw('count(*) AS count'), DB::raw('sum(amount) AS revenue'))->where('orderable_type', 'App\Models\Album')->first();
        $stats->song = Order::select(DB::raw('count(*) AS count'), DB::raw('sum(amount) AS revenue'))->where('orderable_type', 'App\Models\Song')->first();

        return view('backend.dashboard.dashboard')
            ->with('dashboard', $dashboard)
            ->with('stats', $stats);
    }
    public function checkForUpdate(Request $request, UpdaterManager $updater)
    {
        if ($updater->source()->isNewVersionAvailable()) {
            return response()->json([
                'success' => true,
                'new_version' => $updater->source()->getVersionAvailable()
            ]);
        } else {
            return response()->json([
                'success' => false
            ], 404);
        }
    }
}