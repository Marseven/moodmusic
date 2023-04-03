<?php

namespace Common\Admin\Search;

use Artisan;
use Common\Core\BaseController;
use Laravel\Scout\Console\ImportCommand;
use Str;
use TeamTNT\Scout\Console\ImportCommand as TntImportCommand;
use Yab\MySQLScout\Commands\ManageIndexes;
use Yab\MySQLScout\Services\IndexService;

class SearchSettingsController extends BaseController
{
    protected $indexService;

    public function __construct()
    {
        $this->middleware('isAdmin');
        @ini_set("memory_limit", "-1");
        @set_time_limit(0);
    }

    public function getSearchableModels()
    {
        $models = $this->searchableModels();

        $models = array_map(function(string $model) {
            return [
                'model' => $model,
                'name' => Str::plural(last(explode('\\', $model))),
            ];
        }, $models);

        return $this->success(['models' => $models]);
    }

    public function import()
    {
        if ($selectedDriver = request('driver')) {
            config()->set('scout.driver', $selectedDriver);
        }
        $driver = config('scout.driver');
        $model = request('model') ? addslashes(request('model')) : null;

        if ($driver === 'mysql') {
            Artisan::registerCommand(app(ManageIndexes::class));
            Artisan::call("scout:mysql-index \"$model\"");
        } else {
            Artisan::registerCommand(app(ImportCommand::class));
            if ($model) {
                Artisan::call("scout:import \"$model\"");
            } else {
                foreach ($this->searchableModels() as $model) {
                    $model = addslashes($model);
                    Artisan::call("scout:import \"$model\"");
                }
            }
        }

        return $this->success(['output' => nl2br(Artisan::output())]);
    }

    private function searchableModels(): array
    {
        return app(IndexService::class)->getAllSearchableModels([app_path(), base_path('common')]);
    }
}
