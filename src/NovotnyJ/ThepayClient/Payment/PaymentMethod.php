<?php

namespace NovotnyJ\ThepayClient\Payment;

use NovotnyJ\ThepayClient\Utils\Parameters;

class PaymentMethod
{

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	public function __construct(array $data)
	{
		$parameters = new Parameters($data);
		$this->id = $parameters->getInt('id');
		$this->name = $parameters->getString('name');
	}

	/**
	 * @return int
	 */
	public function getId() : int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

}