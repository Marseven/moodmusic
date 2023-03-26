<?php


namespace App\Modules\MoMo\MService\Payment\Pay\Processors;

use App\Modules\MoMo\MService\Payment\Pay\Models\POSPayRequest;
use App\Modules\MoMo\MService\Payment\Pay\Models\POSPayResponse;
use App\Modules\MoMo\MService\Payment\Shared\Constants\Parameter;
use App\Modules\MoMo\MService\Payment\Shared\Constants\RequestType;
use App\Modules\MoMo\MService\Payment\Shared\SharedModels\Environment;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Converter;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Encoder;
use App\Modules\MoMo\MService\Payment\Shared\Utils\HttpClient;
use App\Modules\MoMo\MService\Payment\Shared\Utils\MoMoException;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Process;

class POSPay extends Process
{
    public function __construct(Environment $environment)
    {
        parent::__construct($environment);
    }

    public
    static function process(Environment $env, $paymentCode, int $amount, $publicKey, $partnerRefId, $description = null, $storeId = null, $storeName = null)
    {
        $posPay = new POSPay($env);

        try {
            $posPayRequest = $posPay->createPOSPayRequest($paymentCode, $amount, $publicKey, $partnerRefId, $description, $storeId, $storeName);
            $posPayResponse = $posPay->execute($posPayRequest);
            return $posPayResponse;

        } catch (MoMoException $exception) {
            $posPay->logger->error($exception->getErrorMessage());
        }
    }

    public function createPOSPayRequest($paymentCode, int $amount, $publicKey, $partnerRefId, $description = null, $storeId = null, $storeName = null): POSPayRequest
    {

        $jsonArr = array(
            Parameter::PARTNER_CODE => $this->getPartnerInfo()->getPartnerCode(),
            Parameter::PARTNER_REF_ID => $partnerRefId,
            Parameter::AMOUNT => $amount,
            Parameter::PAYMENT_CODE => $paymentCode,
            Parameter::STORE_ID => $storeId,
            Parameter::STORE_NAME => $storeName
        );

        $hash = Encoder::encryptRSA($jsonArr, $publicKey);
        $this->logger->debug("[POSPayRequest] rawData: " . Converter::arrayToJsonStrNoNull($jsonArr)
            . ', [Signature] -> ' . $hash);

        $arr = array(
            Parameter::PARTNER_CODE => $this->getPartnerInfo()->getPartnerCode(),
            Parameter::PARTNER_REF_ID => $partnerRefId,
            Parameter::HASH => $hash,
            Parameter::VERSION => RequestType::VERSION,
            Parameter::PAY_TYPE => RequestType::APP_PAY_TYPE,
            Parameter::DESCRIPTION => $description,
        );

        return new POSPayRequest($arr);
    }

    public function execute($posPayRequest)
    {
        try {
            $data = Converter::objectToJsonStrNoNull($posPayRequest);
            $response = HttpClient::HTTPPost($this->getEnvironment()->getMomoEndpoint(), $data, $this->getLogger());

            if ($response->getStatusCode() != 200) {
                throw new MoMoException('[POSPayRequest][' . $posPayRequest->getMomoTransId() . '] -> Error API');
            }

            $posPayResponse = new POSPayResponse(json_decode($response->getBody(), true));
            $this->logger->info($data);

            return $posPayResponse;
        } catch (MoMoException $exception) {
            $this->logger->error($exception->getErrorMessage());
        }
        return null;
    }
}