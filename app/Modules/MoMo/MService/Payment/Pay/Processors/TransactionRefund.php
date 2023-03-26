<?php


namespace App\Modules\MoMo\MService\Payment\Pay\Processors;

use App\Modules\MoMo\MService\Payment\Pay\Models\TransactionRefundRequest;
use App\Modules\MoMo\MService\Payment\Pay\Models\TransactionRefundResponse;
use App\Modules\MoMo\MService\Payment\Shared\Constants\Parameter;
use App\Modules\MoMo\MService\Payment\Shared\Constants\RequestType;
use App\Modules\MoMo\MService\Payment\Shared\SharedModels\Environment;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Converter;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Encoder;
use App\Modules\MoMo\MService\Payment\Shared\Utils\HttpClient;
use App\Modules\MoMo\MService\Payment\Shared\Utils\MoMoException;
use App\Modules\MoMo\MService\Payment\Shared\Utils\Process;

class TransactionRefund extends Process
{
    public function __construct(Environment $environment)
    {
        parent::__construct($environment);
    }

    public static function process(Environment $env, $requestId, int $amount, $publicKey, $partnerRefId, $momoTransId, $storeId = null, $description = null)
    {
        $transactionRefund = new TransactionRefund($env);

        try {
            $transactionRefundRequest = $transactionRefund->createTransactionRefundRequest($requestId, $amount, $publicKey, $partnerRefId, $momoTransId, $storeId, $description);
            $transactionRefundResponse = $transactionRefund->execute($transactionRefundRequest);
            return $transactionRefundResponse;

        } catch (MoMoException $exception) {
            $transactionRefund->logger->error($exception->getErrorMessage());
        }
    }

    public function createTransactionRefundRequest($requestId, int $amount, $publicKey, $partnerRefId, $momoTransId, $storeId = null, $description = null): TransactionRefundRequest
    {

        $jsonArr = array(
            Parameter::PARTNER_CODE => $this->getPartnerInfo()->getPartnerCode(),
            Parameter::PARTNER_REF_ID => $partnerRefId,
            Parameter::AMOUNT => $amount,
            Parameter::STORE_ID => $storeId,
            Parameter::DESCRIPTION => $description,
            Parameter::MOMO_TRANS_ID => $momoTransId
        );

        $hash = Encoder::encryptRSA($jsonArr, $publicKey);
        $this->logger->debug("[TransactionRefundRequest] rawData: " . Converter::arrayToJsonStrNoNull($jsonArr)
            . ', [Signature] -> ' . $hash);

        $arr = array(
            Parameter::PARTNER_CODE => $this->getPartnerInfo()->getPartnerCode(),
            Parameter::REQUEST_ID => $requestId,
            Parameter::HASH => $hash,
            Parameter::VERSION => RequestType::VERSION,
        );

        return new TransactionRefundRequest($arr);
    }

    public function execute($transactionRefundRequest)
    {
        try {
            $data = Converter::objectToJsonStrNoNull($transactionRefundRequest);
            $response = HttpClient::HTTPPost($this->getEnvironment()->getMomoEndpoint(), $data, $this->getLogger());

            if ($response->getStatusCode() != 200) {
                throw new MoMoException('[TransactionRefundRequest][' . $transactionRefundRequest->getPartnerRefId() . '] -> ' . "Error API");
            }

            $transactionRefundResponse = new TransactionRefundResponse(json_decode($response->getBody(), true));

            return $transactionRefundResponse;

        } catch (MoMoException $exception) {
            $this->logger->error($exception->getErrorMessage());
        }
        return null;
    }
}