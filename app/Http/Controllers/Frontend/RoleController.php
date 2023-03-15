<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-28
 * Time: 15:44
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $buffer = new \stdClass();
        $buffer->option_stream = Role::getValue('option_stream');
        $buffer->option_hd_stream = Role::getValue('option_hd_stream');
        $buffer->option_download = Role::getValue('option_download');
        $buffer->option_download_hd = Role::getValue('option_download_hd');
        $buffer->option_play_without_purchased = Role::getValue('option_play_without_purchased');
        $buffer->option_track_skip_limit = Role::getValue('option_track_skip_limit');
        $buffer->ad_support = Role::getValue('ad_support');
        $buffer->ad_frequency = Role::getValue('ad_frequency');
        $buffer->dob_signup = config('settings.dob_signup');
        $buffer->gender_signup = config('settings.gender_signup');

        return response()->json($buffer);
    }
}
