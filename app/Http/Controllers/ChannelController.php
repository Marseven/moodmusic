<?php

namespace App\Http\Controllers;

use App\Actions\Channel\CrupdateChannel;
use App\Channel;
use App\Http\Requests\CrupdateChannelRequest;
use Arr;
use Cache;
use Common\Core\BaseController;
use Common\Database\Paginator;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends BaseController
{
    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Channel $channel, Request $request)
    {
        $this->channel = $channel;
        $this->request = $request;
    }

    public function index(): Response
    {
        $userId = $this->request->get('userId');
        $this->authorize('index', [Channel::class, $userId]);

        $paginator = new Paginator($this->channel, $this->request->all());

        if ($userId = $paginator->param('userId')) {
            $paginator->where('user_id', $userId);
        }

        if ($channelIds = $paginator->param('channelIds')) {
            $paginator->query()->whereIn('id', explode(',', $channelIds));
        }

        $pagination = $paginator->paginate();

        return $this->success(['pagination' => $pagination]);
    }

    public function show(Channel $channel): Response
    {
        $this->authorize('show', $channel);

        $channel->loadContent($this->request->all());

        if ($this->request->get('returnContentOnly')) {
            return response()->json(['pagination' => $channel->content]);
        } else {
            return $this->success(['channel' => $channel]);
        }
    }

    public function store(CrupdateChannelRequest $request): Response
    {
        $this->authorize('store', Channel::class);

        $channel = app(CrupdateChannel::class)->execute($request->all());

        return $this->success(['channel' => $channel]);
    }

    public function update(Channel $channel, CrupdateChannelRequest $request): Response
    {
        $this->authorize('store', $channel);

        $channel = app(CrupdateChannel::class)->execute($request->all(), $channel);

        Cache::forget("channels.$channel->id");

        return $this->success(['channel' => $channel]);
    }

    public function destroy(Collection $channels): Response
    {
        $channels = $channels->filter(function(Channel $channel) {
            return !Arr::get($channel->config, 'preventDeletion');
        });

        $channelIds = $channels->pluck('id');
        $this->authorize('store', [Channel::class, $channelIds]);

        DB::table('channelables')->whereIn('channel_id', $channelIds)->delete();
        $this->channel->whereIn('id', $channelIds)->delete();

        foreach ($channelIds as $channelId) {
            Cache::forget("channels.$channelId");
        }

        return $this->success();
    }

    public function detachItem(Channel $channel)
    {
        $this->authorize('update', $channel);

        $modelType = $this->request->get('item')['model_type'];
       
        // track => tracks
        $relation = Str::plural($modelType);

        $channel->$relation()->detach($this->request->get('item')['id']);

        Cache::forget("channels.$channel->id");

        return $this->success();
    }

    public function attachItem(Channel $channel)
    {
        $this->authorize('update', $channel);

        $modelType = $this->request->get('item')['model_type'];
        $modelId = (int) $this->request->get('item')['id'];

        // track => tracks
        $relation = Str::plural($modelType);

        if ($modelType === Channel::MODEL_TYPE && $modelId === $channel->id) {
            return $this->error(__("Channel can't be attached to itself."));
        }

        $relationId = $this->request->get('item')['id'];
        if ( ! $channel->$relation()->find($relationId)) {
            $channel->$relation()->attach($relationId);
        }

        Cache::forget("channels.$channel->id");

        return $this->success();
    }

    /**
     * @param Channel $channel
     * @return JsonResponse
     */
    public function changeOrder(Channel $channel) {

        $this->authorize('update', $channel);

        $this->validate($this->request, [
            'ids'   => 'array|min:1',
            'ids.*' => 'integer'
        ]);

        $queryPart = '';
        foreach($this->request->get('ids') as $order => $id) {
            $queryPart .= " when id=$id then $order";
        }

        DB::table('channelables')
            ->where('channel_id', $channel->id)
            ->whereIn('id', $this->request->get('ids'))
            ->update(['order' => DB::raw("(case $queryPart end)")]);

        Cache::forget("channels.$channel->id");

        return $this->success();
    }
}
