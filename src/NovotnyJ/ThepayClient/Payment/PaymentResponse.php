<?php

namespace NovotnyJ\ThepayClient\Payment;

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
	 * @var float
	 */
	private $value;

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
		$this->value = $parameters->getFloat('value');
		if ($parameters->has('merchantData')) {
			$this->merchantData = $parameters->getString('merchantData');
		}
	}

	/**
	 * @return bool
	 */
	public function isPaid() : bool {
		return $this->status === PaymentStatus::PAID;
	}

	/**
	 * @return bool
	 */
	public function isUnderPaid() : bool {
		return $this->status === PaymentStatus::UNDERPAID;
	}

	/**
	 * @return bool
	 */
	public function isCancelled() : bool
	{
		return $this->status === PaymentStatus::CANCELLED;
	}

	/**
	 * @return bool
	 */
	public function isWaiting() : bool
	{
		return $this->status === PaymentStatus::WAITING;
	}

	/**
	 * @return bool
	 */
	public function isError() : bool
	{
		return $this->status === PaymentStatus::ERROR;
	}

	/**
	 * @return string
	 */
	public function getSignature() : string
	{
		return $this->signature;
	}

	/**
	 * @return int
	 */
	public function getAccountId() : int
	{
		return $this->accountId;
	}

	/**
	 * @return int
	 */
	public function getMerchantId() : int
	{
		return $this->merchantId;
	}

	/**
	 * @return int
	 */
	public function getStatus() : int
	{
		return $this->status;
	}

	/**
	 * @return int
	 */
	public function getPaymentId() : int
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
	public function getQueryData() : array
	{
		return $this->data;
	}

	/**
	 * @return float
	 */
	public function getValue() : float
	{
		return $this->value;
	}

}