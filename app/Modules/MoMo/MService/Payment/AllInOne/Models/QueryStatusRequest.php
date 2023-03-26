<?php


namespace App\Modules\MoMo\MService\Payment\AllInOne\Models;

use App\Modules\MoMo\MService\Payment\Shared\Constants\RequestType;

class QueryStatusRequest extends AIORequest
{
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->setRequestType(RequestType::TRANSACTION_STATUS);
    }

}