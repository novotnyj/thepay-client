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

	/**
	 * @var null|string
	 */
	private $backToEshopUrl = null;

	public function __construct(
		$methodId,
		$value,
		$returnUrl
	) {
		$this->methodId = $methodId;
		$this->value = $value;
		$this->returnUrl = $returnUrl;
	}

	/**
	 * @param string $backToEshopUrl
	 */
	public function setBackToEshopUrl($backToEshopUrl)
	{
		$this->backToEshopUrl = $backToEshopUrl;
	}

	/**
	 * @param string $merchantData
	 */
	public function setMerchantData($merchantData)
	{
		$this->merchantData = $merchantData;
	}

	/**
	 * @param null|string $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * @param null|string $customerData
	 */
	public function setCustomerData($customerData)
	{
		$this->customerData = $customerData;
	}

	public function toArray()
	{
		return [
			'value' => $this->value,
			'description' => $this->description,
			'merchantData' => $this->merchantData,
			'returnUrl' => $this->returnUrl,
			'backToEshopUrl' => $this->backToEshopUrl,
			'methodId' => $this->methodId,
			'customerData' => $this->customerData,
		];
	}

}