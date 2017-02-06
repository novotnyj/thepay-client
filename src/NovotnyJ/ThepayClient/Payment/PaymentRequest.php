<?php

namespace NovotnyJ\ThepayClient\Payment;

use Nette\Utils\Validators;
use NovotnyJ\ThepayClient\Exceptions\InvalidArgumentException;

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
	private $backToEshopUrl;

	public function __construct(
		int $methodId,
		float $value,
		string $returnUrl
	) {
		$this->methodId = $methodId;
		$this->value = $value;
		if (!Validators::isUrl($returnUrl)) {
			throw new InvalidArgumentException($returnUrl . ' is not valid URL');
		}
		$this->returnUrl = $returnUrl;
	}

	/**
	 * @param string $backToEshopUrl
	 */
	public function setBackToEshopUrl(string $backToEshopUrl)
	{
		if (!Validators::isUrl($backToEshopUrl)) {
			throw new InvalidArgumentException($backToEshopUrl . ' is not valid URL');
		}
		$this->backToEshopUrl = $backToEshopUrl;
	}

	/**
	 * @param string $merchantData
	 */
	public function setMerchantData(string $merchantData)
	{
		$this->merchantData = $merchantData;
	}

	/**
	 * @param string $description
	 */
	public function setDescription(string $description)
	{
		$this->description = $description;
	}

	/**
	 * @param string $customerData
	 */
	public function setCustomerData(string $customerData)
	{
		$this->customerData = $customerData;
	}

	public function toArray() : array
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
