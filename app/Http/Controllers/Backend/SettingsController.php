<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 16:14
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use View;
use Config;
use File;
use Artisan;
use Cache;

class SettingsController
{
    public function index()
    {
        if( ! $handle = opendir(resource_path('views/frontend')) ) {
            die( "Cannot open folder views/frontend in resources folder." );
        }

        $skins = array();

        while ( false !== ($file = readdir( $handle )) ) {
            if( is_dir( resource_path('views/frontend/' . $file)) and ($file != "." and $file != "..") ) {
                $skins[$file] = $file;
            }
        }

        $storage = array();

        for ($index = 0; $index < count(config('filesystems.disks')); $index++) {
            $storage[array_keys(config('filesystems.disks'))[$index]] = (array_keys(config('filesystems.disks'))[$index]) . " (driver " . config('filesystems.disks')[array_keys(config('filesystems.disks'))[$index]]['driver'] . ")";
        }

        $languages = Cache::get('languages');

        if (env('IMPORT_MUSIC_MODULE')){
            $youtube_dl_version = @shell_exec(config('settings.youtube_dl_path', '/usr/local/bin/youtube-dl') . ' --version');
            if($youtube_dl_version) {
                $youtube_dl = "<span class=\"text-success\"><strong>Working ( version: " . $youtube_dl_version . ")</strong></span>";
            } else {
                $youtube_dl = "<span class=\"text-danger\"><strong>Not working</strong></span>";
            }
        }

        if (env('IMPORT_MUSIC_MODULE')){
            return view('backend.settings.index')
                ->with('skins', $skins)
                ->with('languages', is_array($languages) ? array_reverse($languages) : array())
                ->with('storage', $storage)
                ->with('youtube_dl', $youtube_dl);
        } else {
            return view('backend.settings.index')
                ->with('skins', $skins)
                ->with('languages', is_array($languages) ? array_reverse($languages) : array())
                ->with('storage', $storage);
        }


    }

    public function save(Request $request)
    {

        $request->validate([
            'admin_path' => 'required|string|alpha_dash',
            'mail_driver' => 'nullable|string|regex:/^\S*$/u',
            'mail_host' => 'nullable|string|regex:/^\S*$/u',
            'mail_port' => 'nullable|string|regex:/^\S*$/u',
            'mail_username' => 'nullable|string|regex:/^\S*$/u',
            'mail_password' => 'nullable|string|regex:/^\S*$/u',
            'mail_encryption' => 'nullable|string|regex:/^\S*$/u',
            'amazon_s3_key_id' => 'nullable|string|regex:/^\S*$/u',
            'amazon_s3_secret' => 'nullable|string|regex:/^\S*$/u',
            'amazon_s3_region' => 'nullable|string|regex:/^\S*$/u',
            'amazon_s3_url' => 'nullable|string|regex:/^\S*$/u',
            'facebook_app_id' => 'nullable|string|regex:/^\S*$/u',
            'facebook_app_secret' => 'nullable|string|regex:/^\S*$/u',
            'facebook_app_callback_url' => 'nullable|string|regex:/^\S*$/u',
            'twitter_app_id' => 'nullable|string|regex:/^\S*$/u',
            'twitter_app_secret' => 'nullable|string|regex:/^\S*$/u',
            'twitter_app_callback_url' => 'nullable|string|regex:/^\S*$/u',
            'google_client_id' => 'nullable|string|regex:/^\S*$/u',
            'google_client_secret' => 'nullable|string|regex:/^\S*$/u',
            'google_app_callback_url' => 'nullable|string|regex:/^\S*$/u',
            'locale' => 'nullable|string|alpha_dash',
        ]);

        $save_con = $request->input('save_con');

        $array = \config('settings');

        foreach ( $save_con as $name => $value ) {
            $array[$name] = $value;
            config(["settings.{$name}" => $value]);
        }

        $data = var_export($array, 1);
        /** Clear config cache */

        config(["app.locale" => isset($save_con['locale']) ? $save_con['locale'] : 'en']);

        if(isset($save_con['locale']) && (env('APP_LOCALE') != $save_con['locale'])) {
            $this->envUpdate ("APP_LOCALE", isset($save_con['locale']) ? $save_con['locale'] : 'en');
        }

        $this->envUpdate ("MAIL_DRIVER", $request->input('mail_driver'));
        $this->envUpdate ("MAIL_HOST", $request->input('mail_host'));
        $this->envUpdate ("MAIL_PORT", $request->input('mail_port'));
        $this->envUpdate ("MAIL_USERNAME",$request->input('mail_username'));
        $this->envUpdate ("MAIL_PASSWORD", $request->input('mail_password'));
        $this->envUpdate ("MAIL_ENCRYPTION", $request->input('mail_encryption'));

        $this->envUpdate ("AWS_ACCESS_KEY_ID", $request->input('amazon_s3_key_id'));
        $this->envUpdate ("AWS_SECRET_ACCESS_KEY", $request->input('amazon_s3_secret'));
        $this->envUpdate ("AWS_DEFAULT_REGION", $request->input('amazon_s3_region'));
        $this->envUpdate ("AWS_BUCKET",$request->input('amazon_s3_bucket_name'));
        $this->envUpdate ("AWS_URL", $request->input('amazon_s3_url'));

        $this->envUpdate ("FACEBOOK_APP_ID", $request->input('facebook_app_id'));
        $this->envUpdate ("FACEBOOK_APP_SECRET",$request->input('facebook_app_secret'));
        $this->envUpdate ("FACEBOOK_APP_CALLBACK_URL", $request->input('facebook_app_callback_url'));

        $this->envUpdate ("TWITTER_APP_ID", $request->input('twitter_app_id'));
        $this->envUpdate ("TWITTER_APP_SECRET",$request->input('twitter_app_secret'));
        $this->envUpdate ("TWITTER_APP_CALLBACK_URL", $request->input('twitter_app_callback_url'));

        $this->envUpdate ("GOOGLE_CLIENT_ID", $request->input('google_client_id'));
        $this->envUpdate ("GOOGLE_CLIENT_SECRET",$request->input('google_client_secret'));
        $this->envUpdate ("GOOGLE_CLIENT_CALLBACK_URL", $request->input('google_app_callback_url'));

        $this->envUpdate ("SIGN_IN_WITH_APPLE_CLIENT_ID", $request->input('apple_app_id'));
        $this->envUpdate ("SIGN_IN_WITH_APPLE_CLIENT_SECRET",$request->input('apple_app_secret'));
        $this->envUpdate ("SIGN_IN_WITH_APPLE_REDIRECT", $request->input('apple_app_callback_url'));

        $this->envUpdate ("DISCORD_CLIENT_ID", $request->input('discord_app_id'));
        $this->envUpdate ("DISCORD_CLIENT_SECRET",$request->input('discord_app_secret'));
        $this->envUpdate ("DISCORD_REDIRECT", $request->input('discord_callback_url'));

        $this->envUpdate ("APP_ADMIN_EMAIL", $save_con['admin_mail']);

        \Cookie::queue(
            \Cookie::forget('darkMode')
        );

        if(env('APP_ADMIN_PATH') != $request->input('admin_path')) {
            $this->envUpdate ("APP_ADMIN_PATH", $request->input('admin_path'));
            Artisan::call('route:clear');
            Artisan::call('route:cache');
        }

        Artisan::call('config:clear');

        if(File::put(config_path('settings.php'), "<?php\n return $data ;")) {
            return redirect()->route('backend.settings')->with('status', 'success')->with('message', 'Settings successfully updated!');
        } else {
            die('Permission denied! Please set CHMOD config/settings.php to 666');
        }
    }

    /**
     * Update Laravel Env file Key's Value
     * @param string $key
     * @param string $value
     */
    public static function envUpdate($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
            ));
        }
    }
}