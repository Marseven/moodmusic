<?php

namespace App\Http\Requests;

use App\Album;
use App\Purchase;
use App\Track;
use Common\Core\BaseFormRequest;

class PurchaseRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'purchasable_type' => 'required|string|in:track,album',
            'purchasable_id' => 'required|integer|min:1',
            'gateway' => 'required|string|in:ebilling,stripe,paypal',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $type = $this->input('purchasable_type');
            $id = $this->input('purchasable_id');

            $model = $type === 'track'
                ? Track::find($id)
                : Album::find($id);

            if (!$model) {
                $validator->errors()->add('purchasable_id', __('Item not found.'));
                return;
            }

            if (!$model->price || $model->price <= 0) {
                $validator->errors()->add('purchasable_id', __('This item is not for sale.'));
                return;
            }

            if ($this->user()) {
                $alreadyPurchased = Purchase::where('user_id', $this->user()->id)
                    ->where('purchasable_type', $type === 'track' ? Track::class : Album::class)
                    ->where('purchasable_id', $id)
                    ->where('status', 'completed')
                    ->exists();

                if ($alreadyPurchased) {
                    $validator->errors()->add('purchasable_id', __('You already own this item.'));
                }
            }
        });
    }
}
