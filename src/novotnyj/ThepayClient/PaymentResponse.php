<?php

namespace NovotnyJ\ThepayClient;

use NovotnyJ\ThepayClient\Utils\Parameters;

class PaymentResponse
{

	/**
	 * @var string
	 */
	private $signature;

	/**
	 * @var int
	 */
	private $accountId;

	/**
	 * @var int
	 */
	private $merchantId;

	/**
	 * @var int
	 */
	private $status;

	/**
	 * @var int
	 */
	private $paymentId;

	/**
	 * @var string|null
	 */
	private $merchantData;

		/**
	 * @var array
	 */
	private $data;

	public function __construct(array $data)
	{
		$parameters = new Parameters($data);

		$this->data = $data;
		$this->accountId = $parameters->getInt('accountId');
		$this->merchantId = $parameters->getInt('merchantId');
		$this->status = $parameters->getInt('status');
		$this->signature = $parameters->getString('signature');
		$this->paymentId = $parameters->getInt('paymentId');
		if (array_key_exists('merchantData', $data)) {
			$this->merchantData = $parameters->getString('merchantData');
		}
	}

	/**
	 * @return bool
	 */
	public function isOk() {
		return $this->status === 2;
	}

	/**
	 * @return bool
	 */
	public function isWaiting() {
		return $this->status === 7;
	}

	/**
	 * @return mixed
	 */
	public function getSignature()
	{
		return $this->signature;
	}

	/**
	 * @return int
	 */
	public function getAccountId()
	{
		return $this->accountId;
	}

	/**
	 * @return int
	 */
	public function getMerchantId()
	{
		return $this->merchantId;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @return int
	 */
	public function getPaymentId()
	{
		return $this->paymentId;
	}

	/**
	 * @return string|null
	 */
	public function getMerchantData()
	{
		return $this->merchantData;
	}

	/**
	 * @return array
	 */
	public function getQueryData()
	{
		return $this->data;
	}

}