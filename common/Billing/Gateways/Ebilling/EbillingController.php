<?php namespace Common\Billing\Gateways\Ebilling;

use Common\Billing\Subscription;
use Common\Core\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EbillingController extends BaseController
{
    public function __construct(
        protected Request $request,
        protected Subscription $subscription,
        protected Ebilling $ebilling
    ) {
        $this->middleware('auth');
    }

    public function storeSubscriptionDetailsLocally(): Response|JsonResponse
    {
        $data = $this->validate($this->request, [
            'ebilling_subscription_id' => 'required|string',
        ]);

        $this->ebilling->storeSubscriptionDetailsLocally(
            $data['ebilling_subscription_id'],
            Auth::user(),
        );

        return $this->success();
    }
}
