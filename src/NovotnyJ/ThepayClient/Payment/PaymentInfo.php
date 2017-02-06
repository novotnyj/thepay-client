<?php

namespace NovotnyJ\ThepayClient\Payment;

use NovotnyJ\ThepayClient\Utils\Parameters;

class PaymentInfo
{

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var int
	 */
	private $account;

	/**
	 * @var int
	 */
	private $state;

	/**
	 * @var \DateTime|null
	 */
	private $created;

	/**
	 * @var \DateTime|null
	 */
	private $finished;

	/**
	 * @var \DateTime|null
	 */
	private $canceled;

	/**
	 * @var float
	 */
	private $value;

	/**
	 * @var float
	 */
	private $receivedValue;

	/**
	 * @var int
	 */
	private $currency;

	/**
	 * @var float
	 */
	private $fee;

	public function __construct(array $data)
	{
		$parameters = new Parameters($data);

		$this->id = $parameters->getInt('id');
		$this->account = $parameters->getInt('account');
		$this->state = $parameters->getInt('state');
		$this->created = $parameters->getDateTimeOrNull('createdOn');
		$this->finished = $parameters->getDateTimeOrNull('finishedOn');
		$this->canceled = $parameters->getDateTimeOrNull('canceledOn');
		$this->value = $parameters->getFloat('value');
		$this->receivedValue = $parameters->getFloat('receivedValue');
		$this->currency = $parameters->getInt('currency');
		$this->fee = $parameters->getFloat('fee');
	}

	/**
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getAccount() : int
	{
		return $this->account;
	}

	/**
	 * @return int
	 */
	public function getState() : int
	{
		return $this->state;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getFinished()
	{
		return $this->finished;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getCanceled()
	{
		return $this->canceled;
	}

	/**
	 * @return float
	 */
	public function getValue() : float
	{
		return $this->value;
	}

	/**
	 * @return float
	 */
	public function getReceivedValue() : float
	{
		return $this->receivedValue;
	}

	/**
	 * @return int
	 */
	public function getCurrency()
	{
		return $this->currency;
	}

	/**
	 * @return float
	 */
	public function getFee() : float
	{
		return $this->fee;
	}

}