<?php

namespace App\Http\Resources\V2\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->getTranslation('name'),
            'logo'                  => uploaded_asset($this->logo),
            'product_upload_limit'  => $this->product_upload_limit,
            'amount'                => ($this->amount > 0) ? single_price($this->amount) : translate('Free'),
            'price'                 =>$this->amount,
            'duration'              => $this->duration,
        ];
    }
}
