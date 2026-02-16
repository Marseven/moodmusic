<?php

namespace App\Http\Controllers;

use App\OriginalContentCategory;
use App\Track;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class OriginalContentController extends BaseController
{
    public function index(Request $request, string $categoryName)
    {
        $category = OriginalContentCategory::where('name', $categoryName)
            ->where('is_active', true)
            ->firstOrFail();

        $tracks = Track::where('is_original_content', true)
            ->where('original_content_category_id', $category->id)
            ->with(['artists', 'genres', 'originalContentCategory'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('perPage', 30));

        return $this->success([
            'pagination' => $tracks,
            'category' => $category,
        ]);
    }

    public function categories()
    {
        $categories = OriginalContentCategory::where('is_active', true)
            ->orderBy('position')
            ->get();

        return $this->success(['categories' => $categories]);
    }
}
