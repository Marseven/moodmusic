<?php


namespace App\Modules\MoMo\MService\Payment\AllInOne\Processors;

use App\Modules\MoMo\MService\Payment\AllInOne\Models\RefundStatusRequest;
use App\Modules\MoMo\MService\Payment\AllInOne\Models\RefundStatusResponse;
use App\Modules\MoMo\MService\Payment\Shared\Constants\Parameter;
use App\Modules\MoMo\MService\Payment\Shared\Constants\RequestType;
use App\Modules\MoMo\MService\Payment\Shared\SharedModels\Environment;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Converter;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Encoder;
use App\Modules\MoMo\MService\Payment\Shared\Utils\HttpClient;
use App\Modules\MoMo\MService\Payment\Shared\Utils\MoMoException;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Process;

class RefundStatus extends Process
{

    public function __construct(Environment $environment)
    {
        parent::__construct($environment);
    }

    public static function process(Environment $env, $orderId, $requestId)
    {
        $refundStatus = new RefundStatus($env);

        try {
            $refundStatusRequest = $refundStatus->createRefundStatusRequest($orderId, $requestId);
            $refundStatusResponse = $refundStatus->execute($refundStatusRequest);

            return $refundStatusResponse;

        } catch (MoMoException $exception) {
            $refundStatus->logger->error($exception->getErrorMessage());
        }
    }

    public function createRefundStatusRequest($orderId, $requestId): RefundStatusRequest
    {

        $rawData = Parameter::PARTNER_CODE . "=" . $this->getPartnerInfo()->getPartnerCode() .
            "&" . Parameter::ACCESS_KEY . "=" . $this->getPartnerInfo()->getAccessKey() .
            "&" . Parameter::REQUEST_ID . "=" . $requestId .
            "&" . Parameter::ORDER_ID . "=" . $orderId .
            "&" . Parameter::REQUEST_TYPE . "=" . RequestType::QUERY_REFUND;

        $signature = Encoder::hashSha256($rawData, $this->getPartnerInfo()->getSecretKey());
        $this->logger->debug("[RefundStatusRequest] rawData: " . $rawData
            . ", [Signature] -> " . $signature);

        $arr = array(
            Parameter::PARTNER_CODE => $this->getPartnerInfo()->getPartnerCode(),
            Parameter::ACCESS_KEY => $this->getPartnerInfo()->getAccessKey(),
            Parameter::REQUEST_ID => $requestId,
            Parameter::ORDER_ID => $orderId,
            Parameter::SIGNATURE => $signature,
        );

        return new RefundStatusRequest($arr);
    }

    public function execute($refundStatusRequest): array
    {
        try {
            $data = Converter::objectToJsonStrNoNull($refundStatusRequest);
            $response = HttpClient::HTTPPost($this->getEnvironment()->getMomoEndpoint(), $data, $this->getLogger());

            if ($response->getStatusCode() != 200) {
                throw new MoMoException('[RefundStatusRequest][' . $refundStatusRequest->getOrderId() . '] -> Error API');
            }

            $arrObj = json_decode($response->getBody(), true);
            $arrResponse = [];

            foreach ($arrObj as $arr) {
                $refundStatusResponse = new RefundStatusResponse($arr);
                $refundStatusResponse = $this->checkResponse($refundStatusResponse);
                if (get_class($refundStatusResponse) === RefundStatusResponse::class)
                    $arrResponse[] = $refundStatusResponse;
            }

            return $arrResponse;

        } catch (MoMoException $exception) {
            $this->logger->error($exception->getErrorMessage());
        }
        return null;
    }

    public function checkResponse(RefundStatusResponse $refundStatusResponse)
    {
        try {


            //check signature
            $rawHash = Parameter::PARTNER_CODE . "=" . $refundStatusResponse->getPartnerCode() .
                "&" . Parameter::ACCESS_KEY . "=" . $refundStatusResponse->getAccessKey() .
                "&" . Parameter::REQUEST_ID . "=" . $refundStatusResponse->getRequestId() .
                "&" . Parameter::ORDER_ID . "=" . $refundStatusResponse->getOrderId() .
                "&" . Parameter::ERROR_CODE . "=" . $refundStatusResponse->getErrorCode() .
                "&" . Parameter::TRANS_ID . "=" . $refundStatusResponse->getTransId() .
                "&" . Parameter::AMOUNT . "=" . $refundStatusResponse->getAmount() .
                "&" . Parameter::MESSAGE . "=" . $refundStatusResponse->getMessage() .
                "&" . Parameter::LOCAL_MESSAGE . "=" . $refundStatusResponse->getLocalMessage() .
                "&" . Parameter::REQUEST_TYPE . "=" . $refundStatusResponse->getRequestType();

            $signature = hash_hmac("sha256", $rawHash, $this->getPartnerInfo()->getSecretKey());
            $this->logger->info("[RefundTransaction] rawData: " . $rawHash
                . ", [Signature] -> " . $signature
                . ", [MoMoSignature] -> " . $refundStatusResponse->getSignature());

            if ($signature == $refundStatusResponse->getSignature())
                return $refundStatusResponse;
            else
                throw new MoMoException("Wrong signature from MoMo side - please contact with us");
        } catch (MoMoException $exception) {
            $this->logger->error('[RefundStatusResponse][' . $refundStatusResponse->getOrderId() . '] -> ' . $exception->getErrorMessage());
        }
        return null;
    }
}