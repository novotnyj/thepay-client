<?php

namespace NovotnyJ\ThepayClient\Client;

use GuzzleHttp\Client;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use NovotnyJ\ThepayClient\Exceptions\InvalidResponseException;
use NovotnyJ\ThepayClient\Payment\PaymentInfo;
use NovotnyJ\ThepayClient\Payment\PaymentMethod;
use NovotnyJ\ThepayClient\Payment\PaymentRequest;
use NovotnyJ\ThepayClient\Payment\PaymentResponse;
use Psr\Http\Message\ResponseInterface;

class ThepayClient implements IThepayClient
{

	/**
	 * @var int
	 */
	private $merchantId;

	/**
	 * @var int
	 */
	private $accountId;

	/**
	 * @var string
	 */
	private $secret;

	/**
	 * @var string
	 */
	private $gateUrl;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var string
	 */
	private $apiKey;

	/**
	 * @var PaymentMethod[]
	 */
	private $methods;

	public function __construct(
		Client $client,
		int $merchantId,
		int $accountId,
		string $secret,
		string $apiKey,
		string $gateUrl
	) {
		$this->merchantId = $merchantId;
		$this->accountId = $accountId;
		$this->secret = $secret;
		$this->gateUrl = $gateUrl;
		$this->apiKey = $apiKey;
		$this->client = $client;
	}

	/**
	 * @return PaymentMethod[]
	 * @throws InvalidResponseException
	 */
	public function getPaymentMethods() : array
	{
		if (!empty($this->methods)) {
			return $this->methods;
		}

		$data = [
			'merchantId' => $this->merchantId,
			'accountId' => $this->accountId,
		];

		$response = $this->getApiResponse('getPaymentMethods', $data);

		if ($response->getStatusCode() !== 200) {
			throw new InvalidResponseException('Invalid response code: ' . $response->getStatusCode());
		}

		$stringBody = (string) $response->getBody();

		try {
			$data = Json::decode($stringBody, true);
		} catch (JsonException $e) {
			throw new InvalidResponseException('Cannot decode response JSON');
		}

		$result = [];
		if (array_key_exists('methods', $data)) {
			foreach ($data['methods'] as $method) {
				$result[] = new PaymentMethod($method);
			}
		}

		$this->methods = $result;

		return $result;
	}

	/**
	 * @param PaymentRequest $payment
	 * @return string
	 */
	public function getPaymentUrl(PaymentRequest $payment) : string
	{
		$params = $this->buildQuery($payment);
		$params['signature'] = $this->createPaymentSignature($payment);
		return $this->gateUrl . '?' . http_build_query($params);
	}

	/**
	 * @param PaymentMethod $method
	 * @param string $size
	 * @return string
	 */
	public function getMethodLogoUrl(PaymentMethod $method, string $size = '86x86') : string
	{
		return 'https://www.thepay.cz/gate/images/logos/public/' . $size . '/' . $method->getId() . '.png';
	}

	/**
	 * @param PaymentResponse $paymentResponse
	 * @return bool
	 */
	public function verifyPayment(PaymentResponse $paymentResponse) : bool
	{
		if ($paymentResponse->getMerchantId() !== (int) $this->merchantId ||
			$paymentResponse->getAccountId() !== (int) $this->accountId) {
			return false;
		}

		$out = array();
		$out[] = "merchantId=".$this->merchantId;
		$out[] = "accountId=".$this->accountId;
		$required = [
			"value", "currency", "methodId", "description", "merchantData",
			"status", "paymentId", "ipRating", "isOffline", "needConfirm",
		];
		$optional = [
			"isConfirm", "variableSymbol", "specificSymbol",
			"deposit", "isRecurring", "customerAccountNumber",
			"customerAccountName",
		];
		$query = $paymentResponse->getQueryData();
		foreach (array_merge($required, $optional) as $arg) {
			if (array_key_exists($arg, $query)) {
				$out[] = $arg."=".$query[$arg];
			}
		}
		$out[] = "password=".$this->secret;
		$signature = md5(implode("&", $out));
		return $signature === $paymentResponse->getSignature();
	}

	/**
	 * @param int $paymentId
	 * @return PaymentInfo
	 * @throws InvalidResponseException
	 */
	public function getPaymentInfo(int $paymentId) : PaymentInfo
	{
		$data = [
			'merchantId' => $this->merchantId,
			'paymentId' => $paymentId,
		];

		$response = $this->getApiResponse('getPayment', $data);

		if ($response->getStatusCode() !== 200) {
			throw new InvalidResponseException('Invalid response code: ' . $response->getStatusCode());
		}

		$stringBody = (string) $response->getBody();

		try {
			$data = Json::decode($stringBody, true);
			return new PaymentInfo($data['payment']);
		} catch (JsonException $e) {
			throw new InvalidResponseException('Cannot decode response JSON');
		}
	}

	/**
	 * @param PaymentRequest $payment
	 * @return string[] [key, value]
	 */
	private function buildQuery(PaymentRequest $payment) : array
	{
		$valueKeyPairs = [];
		$valueKeyPairs['merchantId'] = $this->merchantId;
		$valueKeyPairs['accountId'] = $this->accountId;

		foreach ($payment->toArray() as $key => $value) {
			if ($value !== null) {
				$valueKeyPairs[$key] = $value;
			}
		}

		return $valueKeyPairs;
	}

	/**
	 * @param PaymentRequest $payment
	 * @return string
	 */
	private function createPaymentSignature(PaymentRequest $payment) : string
	{
		$query = $this->buildQuery($payment);

		$str = "";
		foreach ($query as $key => $val) {
			$str .= $key . "=" . $val . "&";
		}

		$str .= "password=".$this->secret;

		return md5($str);
	}

	/**
	 * @param array $data
	 * @return string
	 */
	private function createApiSignature(array $data) : string
	{
		$valueKeyPairs = [];

		foreach ($data as $key => $value) {
			$valueKeyPairs[] = $key . '=' . $value;
		}

		$string = implode('&', $valueKeyPairs);
		$string .= '&password=' . $this->apiKey;

		return hash('sha256', $string);
	}

	/**
	 * @param string $method
	 * @param array $data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	private function getApiResponse($method, array $data) : ResponseInterface
	{
		$data += ['signature' => $this->createApiSignature($data)];
		$uri = $this->gateUrl . '/api/data/' . $method . '/';

		return $this->client->get($uri, ['query' => $data]);
	}

}