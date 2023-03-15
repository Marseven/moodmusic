<?php
/**
 * Copyright (c) 2015 Jens Segers
 * The MIT License (MIT)
 * @laravel-admin-extensions/media-manager
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Backend\Encore\MediaManager;
use View;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $path = $request->get('path', '/');
        $manager = new MediaManager('local', $path);

        $view = View::make('backend.media.index')
            ->with('list', $manager->ls())
            ->with('nav', $manager->navigation())
            ->with('url', $manager->urls());

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }
        return $view;
    }
    public function download(Request $request)
    {
        $file = $request->get('file');
        $manager = new MediaManager('local', $file);
        return $manager->download();
    }
    public function upload(Request $request)
    {
        $files = $request->file('files');
        $dir = $request->get('dir', '/');
        $manager = new MediaManager('local', $dir);
        try {
            if ($manager->upload($files)) {
                return response()->json([
                    'status'  => true,
                    'message' => ('Upload succeeded.'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
        return back();
    }
    public function delete(Request $request)
    {
        $files = $request->get('files');
        $manager = new MediaManager('local');
        try {
            if ($manager->delete($files)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Delete succeeded',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function move(Request $request)
    {
        $path = $request->get('path');
        $new = $request->get('new');
        $manager = new MediaManager('local', $path);
        try {
            if ($manager->move($new)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Move succeeded',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function newFolder(Request $request)
    {
        $dir = $request->get('dir');
        $name = $request->get('name');
        $manager = new MediaManager('local', $dir);
        try {
            if ($manager->newFolder($name)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'New folder created.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}