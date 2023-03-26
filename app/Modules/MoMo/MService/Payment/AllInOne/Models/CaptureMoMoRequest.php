<?php


namespace App\Modules\MoMo\MService\Payment\AllInOne\Models;

use App\Modules\MoMo\MService\Payment\Shared\Constants\RequestType;

class CaptureMoMoRequest extends AIORequest
{
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->setRequestType(RequestType::CAPTURE_MOMO_WALLET);
    }
}