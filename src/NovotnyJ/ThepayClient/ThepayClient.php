<?php

namespace NovotnyJ\ThepayClient;

use GuzzleHttp\Client;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use NovotnyJ\ThepayClient\Exceptions\InvalidResponseException;
use Tracy\Debugger;

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

	public function __construct($merchantId, $accountId, $secret, $apiKey, $gateUrl)
	{
		$this->merchantId = $merchantId;
		$this->accountId = $accountId;
		$this->secret = $secret;
		$this->gateUrl = $gateUrl;
		$this->apiKey = $apiKey;
		$this->client = new Client();
	}

	/**
	 * @return PaymentMethod[]
	 * @throws InvalidResponseException
	 */
	public function getPaymentMethods()
	{
		if (!empty($this->methods)) {
			return $this->methods;
		}

		$data = [
			'merchantId' => $this->merchantId,
			'accountId' => $this->accountId,
		];

		$data += ['signature' => $this->createApiSignature($data)];

		$response = $this->client->get(
			$this->gateUrl . '/api/data/getPaymentMethods/',
			[
				'query' => $data,
			]
		);

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
	public function getPaymentUrl(PaymentRequest $payment)
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
	public function getMethodLogoUrl(PaymentMethod $method, $size = '86x86')
	{
		return 'https://www.thepay.cz/gate/images/logos/public/' . $size . '/' . $method->getId() . '.png';
	}

	/**
	 * @param PaymentResponse $paymentResponse
	 * @return bool
	 */
	public function verifyPayment(PaymentResponse $paymentResponse)
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
	 * @param PaymentRequest $payment
	 * @return string[] [key, value]
	 */
	private function buildQuery(PaymentRequest $payment)
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
	private function createPaymentSignature(PaymentRequest $payment)
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
	private function createApiSignature(array $data)
	{
		$valueKeyPairs = [];

		foreach ($data as $key => $value) {
			$valueKeyPairs[] = $key . '=' . $value;
		}

		$string = implode('&', $valueKeyPairs);
		$string .= '&password=' . $this->apiKey;

		return hash('sha256', $string);
	}

}