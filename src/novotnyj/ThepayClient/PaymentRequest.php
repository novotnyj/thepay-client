<?php

namespace NovotnyJ\ThepayClient;

class PaymentRequest
{

	/**
	 * @var int
	 */
	private $methodId;

	/**
	 * @var float
	 */
	private $value;

	/**
	 * @var string
	 */
	private $returnUrl;

	/**
	 * @var string
	 */
	private $merchantData;

	/**
	 * @var null|string
	 */
	private $description;

	/**
	 * @var null|string
	 */
	private $customerData;

	public function __construct(
		$methodId,
		$value,
		$returnUrl,
		$merchantData,
		$description = null,
		$customerData = null)
	{
		$this->methodId = $methodId;
		$this->value = $value;
		$this->returnUrl = $returnUrl;
		$this->merchantData = $merchantData;
		$this->description = $description;
		$this->customerData = $customerData;
	}

	public function toArray()
	{
		return [
			'value' => $this->value,
			'description' => $this->description,
			'merchantData' => $this->merchantData,
			'returnUrl' => $this->returnUrl,
			'methodId' => $this->methodId,
			'customerData' => $this->customerData,
		];
	}

}