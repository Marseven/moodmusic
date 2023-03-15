<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-22
 * Time: 18:13
 */

namespace App\Http\Controllers\Backend\Encore;

use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Support\Str;

class Scheduling
{
    /**
     * @var string out put file for command.
     */
    protected $sendOutputTo;
    /**
     * Get all events in console kernel.
     *
     * @return array
     */
    protected function getKernelEvents()
    {
        app()->make('Illuminate\Contracts\Console\Kernel');
        return app()->make('Illuminate\Console\Scheduling\Schedule')->events();
    }
    /**
     * Get all formatted tasks.
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getTasks()
    {
        $tasks = [];
        foreach ($this->getKernelEvents() as $event) {
            $tasks[] = [
                'task'          => $this->formatTask($event),
                'expression'    => $event->expression,
                'nextRunDate'   => $event->nextRunDate()->format('Y-m-d H:i:s'),
                'description'   => $event->description,
                'readable'      => CronSchedule::fromCronString($event->expression)->asNaturalLanguage(),
            ];
        }
        return $tasks;
    }
    /**
     * Format a giving task.
     *
     * @param $event
     *
     * @return array
     */
    protected function formatTask($event)
    {
        if ($event instanceof CallbackEvent) {
            return [
                'type' => 'closure',
                'name' => 'Closure',
            ];
        }
        if (Str::contains($event->command, '\'artisan\'')) {
            $exploded = explode(' ', $event->command);
            return [
                'type' => 'artisan',
                'name' => 'artisan '.implode(' ', array_slice($exploded, 2)),
            ];
        }
        return [
            'type' => 'command',
            'name' => $event->command,
        ];
    }
    /**
     * Run specific task.
     *
     * @param int $id
     *
     * @return string
     */
    public function runTask($id)
    {
        set_time_limit(0);
        /** @var \Illuminate\Console\Scheduling\Event $event */
        $event = $this->getKernelEvents()[$id - 1];
        $event->sendOutputTo($this->getOutputTo());
        $event->run(app());
        return $this->readOutput();
    }
    /**
     * @return string
     */
    protected function getOutputTo()
    {
        if (!$this->sendOutputTo) {
            $this->sendOutputTo = storage_path('app/task-schedule.output');
        }
        return $this->sendOutputTo;
    }
    /**
     * Read output info from output file.
     *
     * @return string
     */
    protected function readOutput()
    {
        return file_get_contents($this->getOutputTo());
    }
}