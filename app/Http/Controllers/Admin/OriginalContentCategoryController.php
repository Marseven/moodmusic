<?php

namespace App\Http\Controllers\Admin;

use App\OriginalContentCategory;
use Common\Core\BaseController;
use Illuminate\Http\Request;

class OriginalContentCategoryController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $categories = OriginalContentCategory::orderBy('position')
            ->paginate($request->input('perPage', 15));

        return $this->success(['pagination' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:original_content_categories,name',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'position' => 'integer',
            'is_active' => 'boolean',
        ]);

        $category = OriginalContentCategory::create($data);

        return $this->success(['category' => $category]);
    }

    public function update(Request $request, OriginalContentCategory $originalContentCategory)
    {
        $data = $request->validate([
            'name' => 'string|max:100|unique:original_content_categories,name,' . $originalContentCategory->id,
            'display_name' => 'string|max:100',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'position' => 'integer',
            'is_active' => 'boolean',
        ]);

        $originalContentCategory->update($data);

        return $this->success(['category' => $originalContentCategory]);
    }

    public function destroy(string $ids)
    {
        $ids = explode(',', $ids);
        OriginalContentCategory::whereIn('id', $ids)->delete();

        return $this->success();
    }
}
