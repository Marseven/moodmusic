<?php


namespace App\Modules\MoMo\MService\Payment\AllInOne\Processors;

use App\Modules\MoMo\MService\Payment\AllInOne\Models\PayATMRequest;
use App\Modules\MoMo\MService\Payment\AllInOne\Models\PayATMResponse;
use App\Modules\MoMo\MService\Payment\Shared\Constants\Parameter;
use App\Modules\MoMo\MService\Payment\Shared\Constants\RequestType;
use App\Modules\MoMo\MService\Payment\Shared\SharedModels\Environment;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Converter;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Encoder;
use App\Modules\MoMo\MService\Payment\Shared\Utils\HttpClient;
use App\Modules\MoMo\MService\Payment\Shared\Utils\MoMoException;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Process;

class PayATM extends Process
{

    public function __construct(Environment $environment)
    {
        parent::__construct($environment);
    }

    public static function process(Environment $env, $orderId, $orderInfo, string $amount, $extraData, $requestId, $notifyUrl, $returnUrl, $bankCode)
    {
        $payATM = new PayATM($env);

        try {
            $payATMRequest = $payATM->createPayATMRequest($orderId, $orderInfo, $amount, $extraData, $requestId, $notifyUrl, $returnUrl, $bankCode);
            $payATMResponse = $payATM->execute($payATMRequest);

            return $payATMResponse;

        } catch (MoMoException $exception) {
            $payATM->logger->error($exception->getErrorMessage());
        }
    }

    public function createPayATMRequest($orderId, $orderInfo, string $amount, $extraData, $requestId, $notifyUrl, $returnUrl, $bankCode): PayATMRequest
    {

        $rawData = Parameter::PARTNER_CODE . "=" . $this->getPartnerInfo()->getPartnerCode() .
            "&" . Parameter::ACCESS_KEY . "=" . $this->getPartnerInfo()->getAccessKey() .
            "&" . Parameter::REQUEST_ID . "=" . $requestId .
            "&" . Parameter::BANK_CODE . "=" . $bankCode .
            "&" . Parameter::AMOUNT . "=" . $amount .
            "&" . Parameter::ORDER_ID . "=" . $orderId .
            "&" . Parameter::ORDER_INFO . "=" . $orderInfo .
            "&" . Parameter::RETURN_URL . "=" . $returnUrl .
            "&" . Parameter::NOTIFY_URL . "=" . $notifyUrl .
            "&" . Parameter::EXTRA_DATA . "=" . $extraData .
            "&" . Parameter::REQUEST_TYPE . "=" . RequestType::PAY_WITH_ATM;

        $signature = Encoder::hashSha256($rawData, $this->getPartnerInfo()->getSecretKey());

        $this->logger->debug('[PayATMRequest] rawData: ' . $rawData
                                         . ', [Signature] -> ' . $signature);

        $arr = array(
            Parameter::PARTNER_CODE => $this->getPartnerInfo()->getPartnerCode(),
            Parameter::ACCESS_KEY => $this->getPartnerInfo()->getAccessKey(),
            Parameter::REQUEST_ID => $requestId,
            Parameter::AMOUNT => $amount,
            Parameter::ORDER_ID => $orderId,
            Parameter::ORDER_INFO => $orderInfo,
            Parameter::RETURN_URL => $returnUrl,
            Parameter::NOTIFY_URL => $notifyUrl,
            Parameter::BANK_CODE => $bankCode,
            Parameter::SIGNATURE => $signature,
            Parameter::EXTRA_DATA => $extraData,
        );

        return new PayATMRequest($arr);
    }

    public function execute($payATMRequest)
    {
        try {
            $data = Converter::objectToJsonStrNoNull($payATMRequest);

            $response = HttpClient::HTTPPost($this->getEnvironment()->getMomoEndpoint(), $data, $this->getLogger());

            if ($response->getStatusCode() != 200) {
                throw new MoMoException('[PayATMResponse][' . $payATMRequest->getOrderId() . '] -> Error API');
            }

            $payATMResponse = new PayATMResponse(json_decode($response->getBody(), true));

            return $this->checkResponse($payATMResponse);

        } catch (MoMoException $exception) {
            $this->logger->error($exception->getErrorMessage());
        }
    }

    public function checkResponse(PayATMResponse $payATMResponse)
    {
        try {

            //check signature
            $rawHash = Parameter::PARTNER_CODE . "=" . $payATMResponse->getPartnerCode() .
                "&" . Parameter::ACCESS_KEY . "=" . $payATMResponse->getAccessKey() .
                "&" . Parameter::REQUEST_ID . "=" . $payATMResponse->getRequestId() .
                "&" . Parameter::PAY_URL . "=" . $payATMResponse->getPayUrl() .
                "&" . Parameter::ERROR_CODE . "=" . $payATMResponse->getErrorCode() .
                "&" . Parameter::ORDER_ID . "=" . $payATMResponse->getOrderId() .
                "&" . Parameter::MESSAGE . "=" . $payATMResponse->getMessage() .
                "&" . Parameter::LOCAL_MESSAGE . "=" . $payATMResponse->getLocalMessage() .
                "&" . Parameter::REQUEST_TYPE . "=" . $payATMResponse->getRequestType();

            $signature = hash_hmac("sha256", $rawHash, $this->getPartnerInfo()->getSecretKey());

            $this->logger->info("[PayATMResponse] rawData: " . $rawHash
                . ", [Signature] -> " . $signature
                . ", [MoMoSignature] -> " . $payATMResponse->getSignature());

            if ($signature == $payATMResponse->getSignature())
                return $payATMResponse;
            else
                throw new MoMoException("Wrong signature from MoMo side - please contact with us");
        } catch (MoMoException $exception) {
            $this->logger->error('[PayATMResponse][' . $payATMResponse->getOrderId() . '] -> ' . $exception->getErrorMessage());
        }
        return $payATMResponse;
    }
}