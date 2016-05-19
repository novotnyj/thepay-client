<?php

namespace NovotnyJ\ThepayClient;

use GuzzleHttp\Client;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use NovotnyJ\ThepayClient\Exceptions\InvalidResponseException;

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

		return $result;
	}

	/**
	 * @param PaymentRequest $payment
	 * @return string
	 */
	public function getPaymentUrl(PaymentRequest $payment)
	{
		$params = $this->buildQuery($payment);
		$params['secret'] = $this->createPaymentSignature($payment);
		return $this->gateUrl . '?' . http_build_query($params);
	}

	/**
	 * @param PaymentRequest $payment
	 * @return string[] [key, value]
	 */
	private function buildQuery(PaymentRequest $payment)
	{
		$valueKeyPairs = [];

		foreach ($payment->toArray() as $key => $value) {
			if ($value !== null) {
				$valueKeyPairs[] = $key . '=' . $value;
			}
		}

		return $valueKeyPairs;
	}

	/**
	 * @param PaymentRequest $payment
	 */
	private function createPaymentSignature(PaymentRequest $payment)
	{
		$query = $this->buildQuery($payment);
		$query['password'] = $this->secret;

		return md5(implode('&', $query));
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