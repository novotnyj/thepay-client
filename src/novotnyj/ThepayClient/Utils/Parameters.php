<?php

namespace NovotnyJ\ThepayClient\Utils;

use Nette\InvalidArgumentException;
use Nette\Utils\Validators;

class Parameters
{

	private $data;

	public function __construct(array $data) {
		$this->data = $data;
	}

	/**
	 * @param string $name
	 * @param bool $required
	 * @return int
	 */
	public function getInt($name, $required = true) {
		if ($required && !array_key_exists($name, $this->data)) {
			throw new InvalidArgumentException($name . ' not found.');
		}
		if (Validators::isNumericInt($this->data[$name])) {
			return (int) $this->data[$name];
		} else {
			throw new \InvalidArgumentException($name . ' is not valid int.');
		}
	}

	/**
	 * @param string $name
	 * @param bool $required
	 * @return string
	 */
	public function getString($name, $required = true) {
		if ($required && !array_key_exists($name, $this->data)) {
			throw new InvalidArgumentException($name . ' not found');
		}
		if (Validators::is('string', $this->data[$name])) {
			return $this->data[$name];
		} else {
			throw new \InvalidArgumentException($name . ' is not valid string.');
		}
	}

}