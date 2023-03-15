<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-05-30
 * Time: 10:08
 */

namespace App\Http\Controllers\Frontend;

use App\Models\SongTag;
use Illuminate\Http\Request;
use View;

class TagController
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {

       $tag = SongTag::where('tag', $this->request->route('tag'))->firstOrFail();
       $tag->setRelation('songs', $tag->songs()->paginate(20));

        if( $this->request->is('api*') )
        {
            return response()->json(array(
                'tag' => $tag,
            ));
        }

        $view = View::make('tag.index')
            ->with('tag', $tag);

        if($this->request->ajax()) {
            $sections = $view->renderSections();

            if($this->request->input('page') && intval($this->request->input('page')) > 1)
            {
                return $sections['pagination'];
            } else {
                return $sections['content'];
            }
        }

        return $view;
    }
}