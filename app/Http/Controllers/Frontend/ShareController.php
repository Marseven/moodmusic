<?php
/**
 * Created by PhpStorm.
 * User: lechchut
 * Date: 8/12/19
 * Time: 11:12 AM
 */

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use View;

class ShareController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function embed()
    {

        $view = View::make('share.embed');

        return $view;
    }
}