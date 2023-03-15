<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-25
 * Time: 10:18
 */

use App\Jobs\GetAlbumDetails;
use App\Models\Genre;
use App\Models\Mood;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Category;
use App\Models\Song;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Playlist;
use App\Models\Podcast;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Radio;
use App\Models\Love;
use App\Models\Activity;
use App\Models\Meta;
use App\Models\Collection;
use App\Models\Episode;
use Illuminate\Support\Facades\URL;

if (!function_exists('removeGlitchFromArrayList')) {
    function removeGlitchFromArrayList($list)
    {
        $list = array_filter(explode(',', $list));
        return implode(',', $list);
    }
}

if (!function_exists('thousandsCurrencyFormat')) {
    function thousandsCurrencyFormat($num)
    {
        if ($num > 1000) {

            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int)$x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];

            return $x_display;

        }

        return $num;
    }
}

if (!function_exists('getContentFromHTML')) {
    function getContentFromHTML($content, $from, $end)
    {
        if ((($content and $from) and $end)){
            $r = explode($from, $content);
            if (isset($r[1])){
                $r = explode($end, $r[1]);
                return $r[0];
            }
            return '';
        }
    }
}

if (!function_exists('pushNotification')) {
    function pushNotification($toUserId, $notificationableId, $notificationableType, $action, $objectId = null)
    {
        if($toUserId == auth()->user()->id) {
            return array();
        }

        $notification = new Notification();
        $notification->user_id = $toUserId;
        $notification->object_id = $objectId;
        $notification->notificationable_id = $notificationableId;
        $notification->notificationable_type = $notificationableType;
        $notification->action = $action;
        $notification->hostable_id = auth()->user()->id;
        $notification->hostable_type = (new User)->getMorphClass();
        $notification->save();

        return $notification;
    }
}

if (!function_exists('pushNotificationMentioned')) {
    function pushNotificationMentioned($content, $notificationableId, $notificationableType, $action, $objectId = null)
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML($content);
        $tags = $dom->getElementsByTagName('tag');

        foreach ($tags as $tag) {
            if($tag->getAttribute('data-id')) {
                pushNotification(
                    $tag->getAttribute('data-id'),
                    $notificationableId,
                    $notificationableType,
                    $action,
                    $objectId
                );
            }
        }
    }
}

if (!function_exists('groupPermission')) {
    function groupPermission($access)
    {
        if( ! $access ) return;
        $data = array ();
        $groups = explode( "||", $access );
        foreach ( $groups as $group ) {
            list ( $groupid, $groupvalue ) = explode( ":", $group );
            $data[$groupid] = $groupvalue;
        }
        return $data;
    }
}

if (!function_exists('abortNoPermission')) {
    function abortNoPermission()
    {
        $view = view()->make('commons.abort-no-permission');

        if(request()->ajax()) {
            $sections = $view->renderSections();
            die($sections['content']);
        }

        die($view);
    }
}

if (!function_exists('br2nl')) {
    function br2nl( $input )
    {
        return preg_replace('#<br[/\s]*>#si', "\n", $input);;
    }
}


if (!function_exists('mentionToLink')) {
    function mentionToLink($string, $withAt = true)
    {
        return preg_replace_callback("/<tag\sdata-id=\"(.+?)\"\sdata-username=\"(.+?)\">(.+?)<\/tag>/is", function ($matches) use ($withAt) {
            if($withAt) {
                return "<a href=\"" . route('frontend.user', ['username' => $matches[2]]) . "\">@$matches[3]</a>";
            } else {
                return "<a href=\"" . route('frontend.user', ['username' => $matches[2]]) . "\">$matches[3]</a>";
            }
        }, $string);
    }
}

if (!function_exists('hashtagToLink')) {
    function hashtagToLink($string)
    {
        return preg_replace_callback("/#(\w+)/", function ($matches) {
            return "<a href=\"" . route('frontend.hashtag', ['slug' => $matches[1]]) . "\">#$matches[1]</a>";
        }, $string);
    }
}

if (!function_exists('humanTime')) {
    function humanTime($timestamp)
    {
        return intval($timestamp) > 3600 ? date('H:i:s', intval($timestamp)) : date('i:s', intval($timestamp));
    }
}

if (!function_exists('nl2li')) {
    function nl2li($str)
    {
        if (!isset($str)) return false;
        $arr = explode("\r\n", $str);
        $li = array_map(function ($s) {
            return '<li>' . $s . '</li>';
        }, $arr);
        return implode($li);
    }
}
if (!function_exists('htmlLink')) {
    function htmlLink($title, $url, $class = null)
    {
        if($class) {
            return '<a href="' . $url . '" class="' . $class .'">' . $title .'</a>';
        } else {
            return '<a href="' . $url . '">' . $title .'</a>';
        }
    }
}

if (!function_exists('timeElapsedString')) {
    function timeElapsedString($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . " ago" : 'just now';
    }
}

if (!function_exists('timeElapsedShortString')) {
    function timeElapsedShortString($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array(
            'y' => 'y',
            'm' => 'm',
            'w' => 'w',
            'd' => 'd',
            'h' => 'h',
            'i' => 'm',
            's' => 's',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . $v;
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) : 'just now';
    }
}

if (!function_exists('includeModuleAPIRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeModuleAPIRouteFiles()
    {
        $directories = \Illuminate\Support\Facades\File::directories(app_path('Modules'));
        foreach ($directories as $directory)
        {
            if(\Illuminate\Support\Facades\File::isDirectory($directory) && \Illuminate\Support\Facades\File::isDirectory($directory . '/routes/Api')) {
                includeRouteFiles($directory . '/routes/Api/');
            }
        }
    }
}

if (!function_exists('includeModuleRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeModuleRouteFiles()
    {
        $directories = \Illuminate\Support\Facades\File::directories(app_path('Modules'));
        foreach ($directories as $directory)
        {
            if(\Illuminate\Support\Facades\File::isDirectory($directory) && \Illuminate\Support\Facades\File::isDirectory($directory . '/routes')) {
                includeRouteFiles($directory . '/routes/');
            }
        }
    }
}

if (!function_exists('includeAdminModuleRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeAdminModuleRouteFiles()
    {
        $directories = \Illuminate\Support\Facades\File::directories(app_path('Modules'));
        foreach ($directories as $directory)
        {
            if(\Illuminate\Support\Facades\File::isDirectory($directory) && \Illuminate\Support\Facades\File::isDirectory($directory . '/admin-routes')) {
                includeRouteFiles($directory . '/admin-routes/');
            }
        }
    }
}

if (!function_exists('includeRouteFiles')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function includeRouteFiles($folder)
    {
        $directory = $folder;
        $handle = opendir($directory);
        $directory_list = [$directory];

        while (false !== ($filename = readdir($handle))) {
            if ($filename != '.' && $filename != '..' && is_dir($directory.$filename)) {
                array_push($directory_list, $directory.$filename.'/');
            }
        }

        foreach ($directory_list as $directory) {
            foreach (glob($directory.'*.php') as $filename) {
                require $filename;
            }
        }
    }
}

if (!function_exists('generateUuid')) {
    /**
     * Generate UUID.
     *
     * @return unique Id
     */

    function generateUuid($string, $len = 10)
    {
        $hex = md5($string);
        $pack = pack('H*', $hex);
        $uid = base64_encode($pack);
        $uid = preg_replace('/[^a-zA-Z 0-9]+/', "", $uid);
        if ($len < 4)
            $len = 4;
        if ($len > 128)
            $len = 128;
        while (strlen($uid) < $len)
            $uid = $uid . gen_uuid(22);
        return substr($uid, 0, $len);
    }
}
if (!function_exists('randomFolderName')) {
    function randomFolderName($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces [] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}

if (!function_exists('moodFromIds')) {
    function moodFromIds($mood)
    {
        if ($mood) {
            return (new Mood)->get('id IN (' . $mood . ')', '', 4);
        } else {
            return (Object)array();
        }
    }
}

if (!function_exists('genreFromIds')) {
    function genreFromIds($genre)
    {
        if ($genre) {
            return (new Genre)->get('id IN (' . $genre . ')', '', 4);
        } else {
            return (Object)array();
        }
    }
}

if (!function_exists('moodSelection')) {
    function moodSelection($categoryid = 0, $parent_id = 0, $nocat = TRUE, $sublevelmarker = '', $returnstring = '', $limit = false)
    {
        $rows = Mood::all();
        $cat_info = array();

        foreach ($rows as $row) {
            $cat_info[$row->id] = array();
            $cat_info[$row->id] = json_decode(json_encode($row), true);;
        }

        if (count($cat_info)) {
            foreach ($cat_info as $key) {
                $cat[$key['id']] = $key['name'];
                $cat_parent_id[$key['id']] = $key['parent_id'];
            }
        }

        if ($parent_id == 0) {
            //Disable for select2
            //if( $nocat ) $returnstring .= '<option value="0"></option>';
        } else {
            $sublevelmarker .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        if (isset($cat_parent_id)) {

            $root_category = @array_keys($cat_parent_id, $parent_id);

            if (is_array($root_category)) {
                foreach ($root_category as $id) {
                    $category_name = $cat[$id];
                    $color = "black";
                    $returnstring .= "<option style=\"color: {$color}\" value=\"" . $id . '" ';

                    if (is_array($categoryid)) {
                        foreach ($categoryid as $element) {
                            if ($element == $id) $returnstring .= 'SELECTED';
                        }
                    } elseif ($categoryid == $id) $returnstring .= 'SELECTED';
                    $returnstring .= '>' . $sublevelmarker . $category_name . '</option>';
                }
            }
        }
        return $returnstring;
    }
}

if (!function_exists('genreSelection')) {
    function genreSelection($categoryid = 0, $parent_id = 0, $nocat = true, $sublevelmarker = '', $returnstring = '', $limit = false)
    {
        $rows = Genre::where('discover', 1)->get();

        $cat_info = array();

        foreach ($rows as $row) {
            $cat_info[$row->id] = array();
            $cat_info[$row->id] = json_decode(json_encode($row), true);;
        }

        if (count($cat_info)) {
            foreach ($cat_info as $key) {
                $cat[$key['id']] = $key['name'];
                $cat_parent_id[$key['id']] = $key['parent_id'];
            }
        }

        if ($parent_id == 0) {
            //Disable for select2
            //if( $nocat ) $returnstring .= '<option value="0"></option>';
        } else {
            $sublevelmarker .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        if (isset($cat_parent_id)) {

            $root_category = @array_keys($cat_parent_id, $parent_id);

            if (is_array($root_category)) {
                foreach ($root_category as $id) {
                    $category_name = $cat[$id];
                    $color = "black";
                    $returnstring .= "<option style=\"color: {$color}\" value=\"" . $id . '" ';

                    if (is_array($categoryid)) {
                        foreach ($categoryid as $element) {
                            if ($element == $id) $returnstring .= 'SELECTED';
                        }
                    } elseif ($categoryid == $id) $returnstring .= 'SELECTED';
                    $returnstring .= '>' . $sublevelmarker . $category_name . '</option>';
                }
            }
        }
        return $returnstring;
    }
}

if (!function_exists('radioCategorySelection')) {
    function radioCategorySelection($categoryid = 0, $parent_id = 0, $nocat = TRUE, $sublevelmarker = '', $returnstring = '')
    {
        $rows = Radio::all();
        $cat_info = array();

        foreach ($rows as $row) {
            $cat_info[$row->id] = array();
            $cat_info[$row->id] = json_decode(json_encode($row), true);;
        }

        if (count($cat_info)) {
            foreach ($cat_info as $key) {
                $cat[$key['id']] = $key['name'];
                $cat_parent_id[$key['id']] = $key['parent_id'];
            }
        }

        if ($parent_id == 0) {
            $sublevelmarker = '';
            //if( $nocat ) $returnstring .= '<option value="0"></option>';
        } else {
            $sublevelmarker .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        if (isset($cat_parent_id)) {

            $root_category = @array_keys($cat_parent_id, $parent_id);

            if (is_array($root_category)) {
                foreach ($root_category as $id) {
                    $category_name = $cat[$id];
                    $color = "black";
                    $returnstring .= "<option value=\"" . $id . '" ';

                    if (is_array($categoryid)) {
                        foreach ($categoryid as $element) {
                            if ($element == $id) $returnstring .= 'SELECTED';
                        }
                    } elseif ($categoryid == $id) $returnstring .= 'SELECTED';
                    $returnstring .= '>' . $sublevelmarker . $category_name . '</option>';
                    $returnstring = radioCategorySelection( $categoryid, $id, $nocat, $sublevelmarker, $returnstring );
                }
            }
        }
        return $returnstring;
    }
}

if (!function_exists('podcastCategorySelection')) {
    function podcastCategorySelection($categoryid = 0, $parent_id = 0, $nocat = TRUE, $sublevelmarker = '', $returnstring = '')
    {
        $rows = \App\Models\PodcastCategory::all();
        $cat_info = array();

        foreach ($rows as $row) {
            $cat_info[$row->id] = array();
            $cat_info[$row->id] = json_decode(json_encode($row), true);;
        }

        if (count($cat_info)) {
            foreach ($cat_info as $key) {
                $cat[$key['id']] = $key['name'];
                $cat_parent_id[$key['id']] = $key['parent_id'];
            }
        }

        if ($parent_id == 0) {
            $sublevelmarker = '';
            //if( $nocat ) $returnstring .= '<option value="0"></option>';
        } else {
            $sublevelmarker .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        if (isset($cat_parent_id)) {

            $root_category = @array_keys($cat_parent_id, $parent_id);

            if (is_array($root_category)) {
                foreach ($root_category as $id) {
                    $category_name = $cat[$id];
                    $color = "black";
                    $returnstring .= "<option value=\"" . $id . '" ';

                    if (is_array($categoryid)) {
                        foreach ($categoryid as $element) {
                            if ($element == $id) $returnstring .= 'SELECTED';
                        }
                    } elseif ($categoryid == $id) $returnstring .= 'SELECTED';
                    $returnstring .= '>' . $sublevelmarker . $category_name . '</option>';
                    $returnstring = podcastCategorySelection( $categoryid, $id, $nocat, $sublevelmarker, $returnstring );
                }
            }
        }
        return $returnstring;
    }
}


if (!function_exists('categorySelection')) {
    function categorySelection($categoryid = 0, $parent_id = 0, $nocat = TRUE, $sublevelmarker = '', $returnstring = '')
    {
        $rows = Category::all();
        $cat_info = array();

        foreach ($rows as $row) {
            $cat_info[$row->id] = array();
            $cat_info[$row->id] = json_decode(json_encode($row), true);;
        }

        if (count($cat_info)) {
            foreach ($cat_info as $key) {
                $cat[$key['id']] = $key['name'];
                $cat_parent_id[$key['id']] = $key['parent_id'];
            }
        }

        if ($parent_id == 0) {
            $sublevelmarker = '';
            //if( $nocat ) $returnstring .= '<option value="0"></option>';
        } else {
            $sublevelmarker .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        if (isset($cat_parent_id)) {

            $root_category = @array_keys($cat_parent_id, $parent_id);

            if (is_array($root_category)) {
                foreach ($root_category as $id) {
                    $category_name = $cat[$id];
                    $color = "black";
                    $returnstring .= "<option value=\"" . $id . '" ';

                    if (is_array($categoryid)) {
                        foreach ($categoryid as $element) {
                            if ($element == $id) $returnstring .= 'SELECTED';
                        }
                    } elseif ($categoryid == $id) $returnstring .= 'SELECTED';


                    $returnstring .= '>' . $sublevelmarker . $category_name . '</option>';


                    $returnstring = categorySelection( $categoryid, $id, $nocat, $sublevelmarker, $returnstring );
                }
            }
        }
        return $returnstring;
    }
}

if (!function_exists('makeChannelDropDown')) {
    function makeChannelDropDown($options, $name, $selected)
    {
        $output = "<select name=\"$name\" class=\"form-control slide-show-type\"><option disabled selected value></option>";
        foreach ($options as $value => $description) {
            $output .= "<option value=\"$value\"";
            if ($selected == $value) {
                $output .= " selected ";
            }
            $output .= ">$description</option>";
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeCountryDropDown')) {
    function makeCountryDropDown($name, $class, $selected = null)
    {
        $countries = DB::table('country')->get();
        $output = "<select name=\"$name\" class=\"$class\" style=\"display: none\"><option disabled selected value></option>";
        foreach ($countries as $country) {
            $output .= "<option value=\"$country->code\"";
            if ($selected == $country->code) {
                $output .= " selected ";
            }
            $output .= ">$country->name</option>";
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeCityDropDown')) {
    function makeCityDropDown($countryCode, $name, $class, $selected = null)
    {
        $cities = DB::table('city')->where('country_code', $countryCode)->get();
        $output = "<select name=\"$name\" class=\"$class\" style=\"display: none\"><option disabled selected value></option>";
        foreach ($cities as $city) {
            $output .= "<option value=\"$city->id\"";
            if ($selected == $city->id) {
                $output .= " selected ";
            }
            $output .= ">$city->name</option>";
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeRegionDropDown')) {
    function makeRegionDropDown($name, $class, $selected = null)
    {
        $regions = DB::table('regions')->get();
        $output = "<select name=\"$name\" class=\"$class\"><option disabled selected value></option>";
        foreach ($regions as $region) {
            $output .= "<option value=\"$region->id\"";
            if ($selected == $region->id) {
                $output .= " selected ";
            }
            $output .= ">$region->name</option>";
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeCountryLanguageDropDown')) {
    function makeCountryLanguageDropDown($countryCode, $name, $class, $selected = null)
    {
        $languages = DB::table('countrylanguage')->where('country_code', $countryCode)->get();
        $output = "<select name=\"$name\" class=\"$class\" style=\"display: none\"><option disabled selected value></option>";
        foreach ($languages as $language) {
            $output .= "<option value=\"$language->id\"";
            if ($selected == $language->id) {
                $output .= " selected ";
            }
            $output .= ">$language->name</option>";
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeSlideShowDropDown')) {
    function makeSlideShowDropDown($options, $name, $selected)
    {
        $output = "<select name=\"$name\" class=\"form-control slide-show-type\"><option disabled selected value></option>";
        foreach ($options as $value => $description) {
            $output .= "<option value=\"$value\"";
            if ($selected == $value) {
                $output .= " selected ";
            }
            $output .= ">$description</option>";
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeRolesDropDown')) {
    function makeRolesDropDown($name, $selected = null, $required = "")
    {
        $roles = Role::all();

        $output = "<select name=\"$name\" class=\"form-control select2-active\" {$required}>";

        $output .= "<option></option>";
        foreach ($roles as $role) {
            if($role->id != 6) {
                $output .= "<option value=\"{$role->id}\"";
                if ($selected == $role->id) {
                    $output .= " selected ";
                }
                $output .= ">{$role->name}</option>";
            }
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeDropDown')) {
    function makeDropDown($options, $name, $selected = null, $disableFirstOption = true)
    {
        $output = "<select name=\"$name\" class=\"form-control select2-active select2\">";
        if(! $disableFirstOption ) {
            $output .= "<option disabled selected value></option>";
        }
        foreach ($options as $value => $description) {
            $output .= "<option value=\"$value\"";
            if ($selected == $value) {
                $output .= " selected ";
            }
            $output .= ">$description</option>";
        }
        $output .= "</select>";
        return $output;
    }
}

if (!function_exists('makeTagSelector')) {
    function makeTagSelector($name, $tags = "")
    {
        $output = "<select name=\"$name\" class=\"form-control select2-tags\"  multiple=\"multiple\">";
        if($tags) {
            if(! is_array($tags) ) {
                $tags= explode(',', $tags);
            }
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    $output .= "<option value=\"{$tag}\"";
                    $output .= " selected ";
                    $output .= ">{$tag}</option>";
                }
            }
        }
        $output .= "</select>";
        return $output;
    }
}

function makeCheckBox($name, $selected = false) {

    $selected ? $selected = "checked" : $selected = "";

    return "<input type=\"hidden\" name=\"{$name}\" value=\"0\" /><input type=\"checkbox\" name=\"{$name}\" value=\"1\" {$selected}>";

}
if (!function_exists('makeActivity')) {
    function makeActivity($host_id, $object_id, $object_type, $action, $events, $collapse = true, $schedule = null)
    {
        if($schedule) {
            Activity::insert(
                [
                    'user_id' => $host_id,
                    'activityable_id' => $object_id,
                    'activityable_type' => $object_type,
                    'events' => $events,
                    'action' => $action,
                    'created_at' => $schedule
                ]
            );
        } else {
            $row = Activity::where('user_id', $host_id)
                ->where('activityable_type', $object_type)
                ->where('action', $action)
                ->latest('id');

            if($action == 'addToPlaylist') {
                $row = $row->where('activityable_id', $object_id);
            }

            $row = $row->first();

            if (isset($row->created_at) && Carbon::now()->lt(Carbon::parse($row->created_at)->addHour(2)) && $collapse) {
                $new_list = $events . "," . $row->events;
                $new_list = explode(",", $new_list);
                $new_list = array_unique($new_list);
                array_filter($new_list);
                $new_list = implode(",", $new_list);
                Activity::where('id', $row->id)
                    ->update([
                        'events' => $new_list,
                        'updated_at' => Carbon::now()
                    ]);
            } else {
                Activity::insert(
                    [
                        'user_id' => $host_id,
                        'activityable_id' => $object_id,
                        'activityable_type' => $object_type,
                        'events' => $events,
                        'action' => $action
                    ]
                );
            }

            if($action == "followUser") {
                $notification = new Notification();
                $notification->user_id = $object_id;
                $notification->notificationable_id = $object_id;
                $notification->notificationable_type = (new User)->getMorphClass();
                $notification->action = 'followUser';
                $notification->hostable_id = auth()->user()->id;
                $notification->hostable_type = (new User)->getMorphClass();;
                $notification->save();
            } else if($action == "collectSong") {
                $song = Song::findOrFail($object_id);
                $song->increment('plays');
                if(isset($song->artists[0])) {
                    $artist_id = $song->artists[0]->id;
                    //Insert song statistic
                    DB::statement("INSERT INTO " . DB::getTablePrefix() . "popular (`song_id`, `artist_id`, `collections`, `created_at`) VALUES (" . intval($object_id). ", '" . $artist_id . "', '1', '" . Carbon::now() . "') ON DUPLICATE KEY UPDATE collections=collections+1");
                }
            } else if($action == "favoriteSong") {
                $song = Song::findOrFail($object_id);
                $song->increment('plays');
                if(isset($song->artists[0])) {
                    $artist_id = $song->artists[0]->id;
                    //Insert song statistic
                    DB::statement("INSERT INTO " . DB::getTablePrefix() . "popular (`song_id`, `artist_id`, `favorites`, `created_at`) VALUES (" . intval($object_id). ", '" . $artist_id . "', '1', '" . Carbon::now() . "') ON DUPLICATE KEY UPDATE favorites=favorites+1");
                }
            }
        }
    }
}

if (!function_exists('makeLibrary')) {
    function makeLibrary($action, $object_id, $object_type)
    {
        if ($action == "love") {
            switch ($object_type) {
                case 'song':
                    $loveAbleType = (new Song)->getMorphClass();
                    Song::where('id', $object_id)->increment('collectors');
                    auth()->user()->increment('collection_count');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'collectSong', $object_id);
                    break;
                default:
                    $loveAbleType = null;
            }

            try {
                $collection = new Collection();
                $collection->user_id = auth()->user()->id;
                $collection->collectionable_id = $object_id;
                $collection->collectionable_type = $loveAbleType;
                $collection->save();
            } catch (\Exception $e) {

            }



        } else {
            switch ($object_type) {
                case 'song':
                    $loveAbleType = (new Song)->getMorphClass();
                    Song::where('id', $object_id)->decrement('collectors');
                    auth()->user()->decrement('collection_count');
                    break;
                default:
                    $loveAbleType = null;
            }

            Collection::where('user_id', auth()->user()->id)
                ->where('collectionable_id', $object_id)
                ->where('collectionable_type', $loveAbleType)
                ->delete();
        }
    }
}

if (!function_exists('makeFavorite')) {
    function makeFavorite($action, $object_id, $object_type)
    {
        if ($action == "love") {
            switch ($object_type) {
                case 'song':
                    $loveAbleType = (new Song)->getMorphClass();
                    Song::where('id', $object_id)->increment('loves');
                    auth()->user()->increment('favorite_count');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'favoriteSong', $object_id);
                    break;
                case 'album':
                    $loveAbleType = (new Album)->getMorphClass();
                    Album::where('id', $object_id)->increment('loves');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'favoriteAlbum', $object_id, false);
                    break;
                case 'artist':
                    $loveAbleType = (new Artist)->getMorphClass();
                    Artist::where('id', $object_id)->increment('loves');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'followArtist', $object_id);
                    break;
                case 'playlist':
                    $loveAbleType = (new Playlist)->getMorphClass();
                    Playlist::where('id', $object_id)->increment('loves');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'followPlaylist', $object_id);
                    break;
                case 'podcast':
                    $loveAbleType = (new Podcast)->getMorphClass();
                    Podcast::where('id', $object_id)->increment('loves');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'followPodcast', $object_id);
                    break;
                case 'episode':
                    $loveAbleType = (new Episode())->getMorphClass();
                    Episode::where('id', $object_id)->increment('loves');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'favoriteEpisode', $object_id);
                    break;
                case 'user':
                    $loveAbleType = (new User)->getMorphClass();
                    auth()->user()->increment('following_count');
                    User::where('id', $object_id)->increment('follower_count');
                    makeActivity(auth()->user()->id, $object_id, $loveAbleType, 'followUser', $object_id);
                    break;
                default:
                    $loveAbleType = null;
            }

            try {
                Love::updateOrCreate([
                    'user_id' => auth()->user()->id,
                    'loveable_id' => $object_id,
                    'loveable_type' =>  $loveAbleType
                ]);
            } catch (\Exception $e) {

            }

        } else {
            switch ($object_type) {
                case 'song':
                    $loveAbleType = (new Song)->getMorphClass();
                    Song::where('id', $object_id)->decrement('loves');
                    auth()->user()->decrement('favorite_count');
                    break;
                case 'album':
                    $loveAbleType = (new Album)->getMorphClass();
                    Album::where('id', $object_id)->decrement('loves');
                    break;
                case 'artist':
                    $loveAbleType = (new Artist)->getMorphClass();
                    Artist::where('id', $object_id)->decrement('loves');
                    break;
                case 'playlist':
                    $loveAbleType = (new Playlist)->getMorphClass();
                    Playlist::where('id', $object_id)->decrement('loves');
                    break;
                case 'user':
                    $loveAbleType = (new User)->getMorphClass();
                    auth()->user()->increment('following_count');
                    User::where('id', $object_id)->decrement('follower_count');
                    break;
                default:
                    $loveAbleType = null;
            }

            Love::where('user_id', auth()->user()->id)
                ->where('loveable_id', $object_id)
                ->where('loveable_type', $loveAbleType)
                ->delete();
        }
    }
}

if (!function_exists('sortArrayByDate')) {
    function sortArrayByDate($a, $b)
    {
        return strtotime($a['date']) - strtotime($b['date']);
    }
}

if (!function_exists('insertMissingData')) {
    function insertMissingData($originalData, $types, $start_date, $end_date)
    {
        $dates = [];
        $data = [];
        foreach ($originalData as $x => $d) {
            $d = (array) $d;
            $dates[] = $d['date'];
            $data[] = $d;
        }

        sort($dates);
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );
        foreach ($period as $d) {
            $key = $d->format('Y-m-d');
            if (!in_array($key, $dates)) {
                $array = array();
                foreach ($types as $type) {
                    $array[$type] = 0;
                }
                $array['date'] = $key;
                $data[] = $array;
            }
        }
        usort($data, "sortArrayByDate");

        return $data;
    }
}

if (!function_exists('insertMissingDate')) {
    function insertMissingDate($data, $action, $start_date, $end_date)
    {
        $dates = [];
        foreach ($data as $x => $d) {
            $dates[] = $d->created_at;
        }
        sort($dates);
        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            new DateTime($end_date)
        );
        foreach ($period as $d) {
            $key = $d->format('Y-m-d');
            if (!in_array($key, $dates)) {
                $data[] = array(
                    $action => 0,
                    'created_at' => $key,
                );
            }
        }

        $data = (array) $data;
        usort($data, "sortArrayByDate");
        return $data[0];
    }
}

if (!function_exists('objectsToHtmlLink')) {
    function objectsToHtmlLink($objects)
    {
        $objectLink = "";
        foreach($objects as $key => $item) {
            $title = isset($item->name) ? $item->name : $item->title;
            $objectLink .= '<a href="' . $item->url . '">' . $title . '</a>';

            if ($key !== count($objects) - 1)
            {
                $objectLink .= ", ";
            }
        }
        return $objectLink;
    }
}

if (!function_exists('fileSizeConverter')) {
    function fileSizeConverter($size)
    {
        $bytes = intval($size);
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        if($size == 0) {
            return '';
        }

        return round($bytes, 2).' '.$units[$i];
    }
}

if (!function_exists('clearUrlForMetatags')) {
    function clearUrlForMetatags($a)
    {
        if (!$a) return '';

        if (strpos($a, "//") === 0) $a = "http:" . $a;
        $a = parse_url($a);

        if (isset($a['query'])){
            $a = $a['path'] . '?' . $a['query'];
        } else {
            if(isset($a['path'])) $a = $a['path'];
        }

        $a = preg_replace('#[/]+#i', '/', $a);

        if ($a[0] != '/') $a = '/' . $a;

        return $a;
    }
}

if (!function_exists('detectEncoding')) {

    function detectEncoding($string)
    {
        static $list = array('utf-8', 'windows-1251');

        foreach ($list as $item) {

            if (function_exists('mb_convert_encoding')) {

                $sample = mb_convert_encoding($string, $item, $item);

            } elseif (function_exists('iconv')) {

                $sample = iconv($item, $item, $string);

            }

            if (md5($sample) == md5($string)) return $item;

        }

        return null;
    }
};

if (!function_exists('getMetatags')) {
    function getMetatags($item = null)
    {
        $custom_metatags = array ();
        $page_header_info = array();

        if(! Cache::has('metatags')) {
            $rows = Meta::orderBy('priority', 'desc')->get();

            foreach ( $rows AS $row) {
                if( strpos ( $row->url, "*" ) !== false ) {
                    $row->url = preg_quote(urldecode($row->url), '%');
                    $row->url = '%^'.str_replace('\*', '(.*)', $row->url).'%i';
                    $custom_metatags['regex'][$row->url] = array('page_title' => $row->page_title, 'page_description' => stripslashes($row->page_description), 'page_keywords' => $row->page_keywords, 'artwork_url' => $row->artwork_url);
                } else {
                    $row->url = urldecode($row->url);
                    $custom_metatags['simple'][$row->url] = array('page_title' => $row->page_title, 'page_description' => stripslashes($row->page_description), 'page_keywords' => $row->page_keywords, 'artwork_url' => $row->artwork_url);
                }

            }

            Cache::forever('metatags', $custom_metatags);
        } else {
            $custom_metatags = Cache::get('metatags');
        }

        $r_uri = preg_replace( '#[/]+#i', '/', urldecode($_SERVER['REQUEST_URI']) );

        /** site char set */

        $charset = 'utf-8';

        /* end charset */


        $url_charset = detectEncoding($r_uri);

        if ( $url_charset AND $url_charset != $charset ) {

            if( function_exists( 'mb_convert_encoding' ) ) {
                $r_uri = mb_convert_encoding( $r_uri, $charset, $url_charset );
            } elseif( function_exists( 'iconv' ) ) {
                $r_uri = iconv($url_charset, $charset, $r_uri);
            }

        }

        if(is_array($custom_metatags['simple']) AND count($custom_metatags['simple']) AND isset($custom_metatags['simple'][$r_uri]) ) {
            if( $custom_metatags['simple'][$r_uri]['page_title'] ) $page_header_info['title'] = $custom_metatags['simple'][$r_uri]['page_title'];
            if( $custom_metatags['simple'][$r_uri]['page_description'] ) $page_header_info['description'] = $custom_metatags['simple'][$r_uri]['page_description'];
            if( $custom_metatags['simple'][$r_uri]['page_keywords'] ) $page_header_info['keywords'] = $custom_metatags['simple'][$r_uri]['page_keywords'];
            if( isset($custom_metatags['simple'][$r_uri]['artwork_url']) ) $page_header_info['artwork_url'] = $custom_metatags['simple'][$r_uri]['artwork_url'];
        } elseif(isset($custom_metatags['regex']) && is_array($custom_metatags['regex']) AND count($custom_metatags['regex'])) {
            foreach ($custom_metatags['regex'] as $key => $value) {
                if(preg_match($key, $r_uri)){
                    if( $value['page_title'] ) $page_header_info['title'] = $value['page_title'];
                    if( $value['page_description'] ) $page_header_info['description'] = $value['page_description'];
                    if( $value['page_keywords'] ) $page_header_info['keywords'] = $value['page_keywords'];
                    if( $value['artwork_url'] ) $page_header_info['artwork_url'] = $value['artwork_url'];
                }
            }
        }

        if(isset($page_header_info['title'])) {
            $data = array();

            isset($item->title) && $data = array_merge($data, array('title' => $item->title));
            isset($item->artists) && $data = array_merge($data, array('artist' => implode(',',$item->artists->map(function ($row){
                return $row->name;
            })->toArray())));

            isset($item->user->name) && $data = array_merge($data, array('user' => $item->user->name));
            isset($item->name) && $data = array_merge($data, array('name' => $item->name));
            isset($item->term) && $data = array_merge($data, array('term' => $item->term));
            isset($item->artist) && $data = array_merge($data, array('artist' => $item->artist->name));
            isset($item->podcast) && $data = array_merge($data, array('podcast' => $item->podcast->title));
            isset($item->podcast) && isset($item->podcast->artist) && $data = array_merge($data, array('artist' => $item->podcast->artist->name));

            $page_header_info['title'] = metaParse($data, $page_header_info['title']);
            isset($page_header_info['description']) && $page_header_info['description'] = metaParse($data, $page_header_info['description']);;

            try {
                MetaTag::set('title', htmlspecialchars_decode($page_header_info['title']));
                isset($page_header_info['description']) && MetaTag::set('description',  htmlspecialchars_decode($page_header_info['description']));
                isset($page_header_info['keywords']) ? MetaTag::set('keywords',  htmlspecialchars_decode($page_header_info['keywords'])) : MetaTag::set('keywords',  keywordGenerator(isset($page_header_info['description']) ? htmlspecialchars_decode($page_header_info['description']) : htmlspecialchars_decode($page_header_info['title'])));

                if(isset($item->artwork_url)){
                    MetaTag::set('image', url($item->artwork_url));
                } else if(isset($page_header_info['artwork_url'])){
                    MetaTag::set('image', url($page_header_info['artwork_url']));
                }
            } catch (\Exception $e) {

            }
        }
    }
}

if (!function_exists('metaParse')) {
    function metaParse($data, $content)
    {
        $parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
            list($shortCode, $index) = $matches;
            if (isset($data[$index])) {
                return $data[$index];
            } else {
                /**
                 * for testing only
                 */
                //throw new Exception("Shortcode {$shortCode} not found in template id {$this->id}", 1);
            }

        }, $content);

        return $parsed;
    }
}

if (!function_exists('parseJsonArray')) {
    function parseJsonArray($jsonArray, $parentID = 0)
    {
        $return = array();
        foreach ($jsonArray as $subArray) {
            $returnSubSubArray = array();
            if (isset($subArray['children'])) {
                $returnSubSubArray = parseJsonArray($subArray['children'], $subArray['id']);
            }
            $return[] = array('id' => $subArray['id'], 'parent_id' => $parentID);
            $return = array_merge($return, $returnSubSubArray);
        }

        return $return;
    }
}

if (!function_exists('keywordGenerator')) {

    function keywordGenerator($content)
    {
        $content = strip_tags($content);
        $keyword_count = 20;
        $newarr = array();

        $quotes = array("\x22", "\x60", "\t", "\n", "\r", ",", ".", "/", "¬", "#", ";", ":", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"');
        $fastquotes = array("\x22", "\x60", "\t", "\n", "\r", '"', "\\", '\r', '\n', "/", "{", "}", "[", "]");

        $content = str_replace("&nbsp;", " ", $content);
        $content = str_replace('<br />', ' ', $content);
        $content = preg_replace("#&(.+?);#", "", $content);
        $content = trim(str_replace(" ,", "", stripslashes($content)));

        $content = str_replace($fastquotes, '', $content);

        $content = str_replace($quotes, ' ', $content);

        $arr = explode(" ", $content);

        foreach ($arr as $word) {
            if (iconv_strlen($word, "utf-8")  > 4) $newarr[] = $word;
        }

        $arr = array_count_values($newarr);
        arsort($arr);

        $arr = array_keys($arr);

        $offset = 0;

        $arr = array_slice($arr, $offset, $keyword_count);

        $keywords = implode(", ", $arr);

        return $keywords;
    }
}

if (!function_exists('handleSpotifySong')) {

    function handleSpotifySong($item, $artists, $artwork_url = null)
    {

        $artistIds = array();
        foreach ($artists as $artist) {
            $artistIds[] = $artist->id;
        }

        $unique_song_id = intval(crc32($item['id']) / 100);

        dispatch(new \App\Jobs\SaveSongDetails($item, $unique_song_id, $artistIds, $artwork_url));

        $song = new stdClass();
        $song->id = $unique_song_id;
        $song->title = $item['name'];
        $song->artists = $artists;
        $song->duration = intval($item['duration_ms']/1000);
        $song->explicit = boolval($item['explicit']);
        $song->approved = 1;
        $song->artwork_url = $artwork_url ? $artwork_url : (isset($item['album']['images'][1]) ? $item['album']['images'][1]['url'] : null);
        $song->mp3 = 0;
        $song->hls = 0;
        $song->hd = 0;
        $song->loves = 0;
        $song->streamable = Role::getValue('option_stream') ? true : false;
        $song->collectors = 0;
        $song->plays = 0;
        $song->favorite = 0;
        $song->permalink_url = route('frontend.song', ['id' => $unique_song_id, 'slug' => str_slug($item['name']) ? str_slug($item['name']) : str_replace(' ', '-', $item['name'])]);
        $song->artists = $artists;
        $song->stream_url = URL::temporarySignedRoute('frontend.song.stream.mp3', now()->addDay(), [
            'id' => $unique_song_id
        ]);

        return $song;
    }
}

if (!function_exists('handleSpotifyArtists')) {

    function handleSpotifyArtists($items)
    {
        $artists = array();

        foreach ($items as $artist_item){
            $unique_artist_id = intval(crc32($artist_item['id']) / 100);

            dispatch(new \App\Jobs\SaveArtistDetails($artist_item, $unique_artist_id));

            $artist = new Artist();
            $artist->id = $unique_artist_id;
            $artist->name = $artist_item['name'];

            $artists[] = $artist;
        }

        return $artists;
    }
}

if (!function_exists('handleSpotifyAlbum')) {

    function handleSpotifyAlbum($artists, $item)
    {

        $artistIds = array();
        foreach ($artists as $artist) {
            $artistIds[] = $artist->id;
        }

        $unique_album_id = intval(crc32($item['id']) / 100);

        dispatch(new \App\Jobs\SaveAlbumDetails($item, $unique_album_id, $artistIds));

        $album = new stdClass();
        $album->id = $unique_album_id;
        $album->title = $item['name'];
        $album->artists = $artists;
        $album->approved = 1;
        $album->artwork_url = (isset($item['images']) && isset($item['images'][1])) ? $item['images'][1]['url'] : null;
        $album->permalink_url = route('frontend.album', ['id' => $unique_album_id, 'slug' => str_slug($item['name']) ? str_slug($item['name']) : str_replace(' ', '-', $item['name'])]);

        return $album;
    }

    function handleSpotifyPlaylist($item)
    {

        $unique_playlist_id = intval(crc32($item['id']) / 100);

        dispatch(new \App\Jobs\SavePlaylistDetails($item, $unique_playlist_id));

        $playlist = new stdClass();
        $playlist->id = $unique_playlist_id;
        $playlist->title = $item['name'];
        $playlist->user = null;
        $playlist->artwork_url = $item['images'][0]['url'];
        $playlist->permalink_url = route('frontend.playlist', ['id' => $unique_playlist_id, 'slug' => str_slug($item['name']) ? str_slug($item['name']) : str_replace(' ', '-', $item['name'])]);

        return $playlist;
    }
}