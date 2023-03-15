<?php
/**
 * Copyright (c) 2015 Jens Segers
 * The MIT License (MIT)
 * @laravel-admin-extensions/log-viewer
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Backend\Encore\LogViewer;
use View;

class LogController extends Controller
{
    public function index($file = null, Request $request)
    {
        if ($file === null) {
            $file = (new LogViewer())->getLastModifiedLog();
        }

        $offset = $request->get('offset');
        $viewer = new LogViewer($file);


        $view = View::make('backend.logs.index')
            ->with('logs', $viewer->fetch($offset))
            ->with('logFiles', $viewer->getLogFiles())
            ->with('fileName', $viewer->file)
            ->with('end', $viewer->getFilesize())
            ->with('tailPath', route('backend.log-viewer-tail', ['file' => $viewer->file]))
            ->with('prevUrl', $viewer->getPrevPageUrl())
            ->with('nextUrl', $viewer->getNextPageUrl())
            ->with('filePath',  $viewer->getFilePath())
            ->with('size',  fileSizeConverter($viewer->getFilesize()));

        if($request->ajax()) {
            $sections = $view->renderSections();
            return $sections['content'];
        }

        return $view;
    }
    public function tail($file, Request $request)
    {
        $offset = $request->get('offset');
        $viewer = new LogViewer($file);
        list($pos, $logs) = $viewer->tail($offset);
        return compact('pos', 'logs');
    }
    protected static function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2).' '.$units[$i];
    }
}