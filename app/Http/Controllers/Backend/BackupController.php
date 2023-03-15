<?php
/**
 * Copyright (c) 2015 Jens Segers
 * The MIT License (MIT)
 * @laravel-admin-extensions/back-up
 */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Commands\ListCommand;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;
use View;

class BackupController
{
    /**
     * Index interface.
     *
     * @return View
     */
    public function index(Request $request)
    {

            $view = View::make('backend.backup.index')
                ->with('backups', $this->getExists());

            if($request->ajax()) {
                $sections = $view->renderSections();
                return $sections['content'];
            }

            return $view;
    }
    public function getExists()
    {
        $statuses = BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitorBackups'));
        $listCommand = new ListCommand();
        $rows = $statuses->map(function (BackupDestinationStatus $backupDestinationStatus) use ($listCommand) {
            return $listCommand->convertToRow($backupDestinationStatus);
        })->all();
        foreach ($statuses as $index => $status) {
            $name = $status->backupDestination()->backupName();
            $files = array_map('basename', $status->backupDestination()->disk()->allFiles($name));
            $rows[$index]['files'] = array_slice(array_reverse($files), 0, 30);
        }
        return $rows;
    }
    /**
     * Download a backup zip file.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function download(Request $request)
    {
        $disk = $request->get('disk');
        $file = $request->get('file');
        $storage = Storage::disk($disk);
        $fullPath = $storage->getDriver()->getAdapter()->applyPathPrefix($file);
        if (File::isFile($fullPath)) {
            return response()->download($fullPath);
        }
        return response('', 404);
    }
    /**
     * Run `backup:run` command.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function run()
    {
        try {
            ini_set('max_execution_time', 300);
            // start the backup process
            Artisan::call('backup:run');
            $output = Artisan::output();
            return response()->json([
                'status'  => true,
                'message' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    /**
     * Delete a backup file.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $disk = Storage::disk($request->get('disk'));
        $file = $request->get('file');
        if ($disk->exists($file)) {
            $disk->delete($file);
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        }
        return response()->json([
            'status'  => false,
            'message' => trans('admin.delete_failed'),
        ]);
    }
}