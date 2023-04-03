<?php

namespace App\Http\Controllers;

use App\Actions\Backstage\ApproveBackstageRequest;
use App\Actions\Backstage\CrupdateBackstageRequest;
use App\BackstageRequest;
use App\Http\Requests\CrupdateBackstageRequestRequest;
use App\Notifications\BackstageRequestWasHandled;
use Common\Core\BaseController;
use Common\Database\Paginator;
use Common\Files\FileEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BackstageRequestController extends BaseController
{
    /**
     * @var BackstageRequest
     */
    private $backstageRequest;

    /**
     * @var Request
     */
    private $request;

    public function __construct(BackstageRequest $backstageRequest, Request $request)
    {
        $this->backstageRequest = $backstageRequest;
        $this->request = $request;
    }

    public function index(): Response
    {
        $userId = $this->request->get('userId');
        $this->authorize('index', [BackstageRequest::class, $userId]);

        $paginator = (new Paginator($this->backstageRequest, $this->request->all()))
            ->with(['user', 'artist'])
            ->setDefaultOrderColumns("FIELD(status, 'pending', 'approved', 'denied') ASC");

        $paginator->filterColumns = ['type', 'status', 'created_at', 'requester' => function(Builder $builder, $userId) {
            if ($userId) {
                $builder->where('user_id', $userId);
            }
        }];

        $pagination = $paginator->paginate();

        return $this->success(['pagination' => $pagination]);
    }

    public function show(BackstageRequest $backstageRequest): Response
    {
        $this->authorize('show', $backstageRequest);

        $backstageRequest->load(['user', 'artist']);

        $request = $backstageRequest->toArray();

        if (isset($request['data']['passportScanEntryId'])) {
            $request['data']['passportScanEntry'] = FileEntry::find($request['data']['passportScanEntryId']);
        }

        return $this->success(['request' => $request]);
    }

    public function store(CrupdateBackstageRequestRequest $request): Response
    {
        $this->authorize('store', BackstageRequest::class);

        $backstageRequest = app(CrupdateBackstageRequest::class)->execute($request->all());

        return $this->success(['request' => $backstageRequest]);
    }

    public function update(BackstageRequest $backstageRequest, CrupdateBackstageRequestRequest $request): Response
    {
        $this->authorize('store', $backstageRequest);

        $backstageRequest = app(CrupdateBackstageRequest::class)->execute($request->all(), $backstageRequest);

        return $this->success(['request' => $backstageRequest]);
    }

    public function destroy(string $ids): Response
    {
        $backstageRequestIds = explode(',', $ids);
        $this->authorize('store', [BackstageRequest::class, $backstageRequestIds]);

        $this->backstageRequest->whereIn('id', $backstageRequestIds)->delete();

        return $this->success();
    }

    public function approve(BackstageRequest $backstageRequest): Response
    {
        $this->authorize('handle', BackstageRequest::class);

        $backstageRequest = App(ApproveBackstageRequest::class)
            ->execute($backstageRequest, $this->request->all());

        return $this->success(['request' => $backstageRequest]);
    }

    public function deny(BackstageRequest $backstageRequest): Response
    {
        $this->authorize('handle', BackstageRequest::class);

        $backstageRequest->fill(['status' => 'denied'])->save();

        $backstageRequest->user->notify(new BackstageRequestWasHandled($backstageRequest, $actionParams['notes'] ?? null));

        return $this->success(['request' => $backstageRequest]);
    }
}
