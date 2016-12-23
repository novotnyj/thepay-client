<?php

namespace NovotnyJ\ThepayClient;

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
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getAccount()
	{
		return $this->account;
	}

	/**
	 * @return int
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @return mixed
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @return mixed
	 */
	public function getFinished()
	{
		return $this->finished;
	}

	/**
	 * @return mixed
	 */
	public function getCanceled()
	{
		return $this->canceled;
	}

	/**
	 * @return float
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return float
	 */
	public function getReceivedValue()
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
	public function getFee()
	{
		return $this->fee;
	}

}