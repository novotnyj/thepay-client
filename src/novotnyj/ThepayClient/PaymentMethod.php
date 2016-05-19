<?php

namespace NovotnyJ\ThepayClient;

use Nette\Utils\Validators;

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
		Validators::assertField($data, 'id', 'int');
		Validators::assertField($data, 'name', 'string');

		$this->id = (int) $data['id'];
		$this->name = $data['name'];
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

}