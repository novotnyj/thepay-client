<?php

namespace NovotnyJ\ThepayClient\Utils;

use Nette\InvalidArgumentException;
use Nette\Utils\Strings;
use Nette\Utils\Validators;

class Parameters
{

	/**
	 * @var array
	 */
	private $data;

	public function __construct(array $data) {
		$this->data = $data;
	}

	/**
	 * @param string $name
	 * @param bool $required
	 * @return int
	 */
	public function getInt(string $name, bool $required = true) : int {
		if ($required && !$this->has($name)) {
			throw new InvalidArgumentException($name . ' not found.');
		}
		if (Validators::isNumericInt($this->data[$name])) {
			return (int) $this->data[$name];
		} else {
			throw new \InvalidArgumentException($name . ' is not valid int.');
		}
	}

	/**
	 * @param $name
	 * @param bool $required
	 * @return float
	 */
	public function getFloat(string $name, bool $required = true) : float {
		if ($required && !$this->has($name)) {
			throw new InvalidArgumentException($name . ' not found.');
		}
		if (Validators::isNumeric($this->data[$name])) {
			return (float) $this->data[$name];
		} else {
			throw new \InvalidArgumentException($name . ' is not valid float.');
		}
	}

	/**
	 * @param string $name
	 * @param bool $required
	 * @return string
	 */
	public function getString(string $name, bool $required = true) : string {
		if ($required && !$this->has($name)) {
			throw new InvalidArgumentException($name . ' not found');
		}
		if (Validators::is($this->data[$name], 'string')) {
			return $this->data[$name];
		} else {
			throw new \InvalidArgumentException($name . ' is not valid string.');
		}
	}

	/**
	 * @param $name
	 * @param bool $required
	 * @return \DateTime|null
	 */
	public function getDateTimeOrNull(string $name, bool $required = false)
	{
		if ($required && !$this->has($name)) {
			throw new InvalidArgumentException($name . ' not found');
		}
		if (!$required && !$this->has($name)) {
			return null;
		}
		if (!$required && $this->data[$name] === null) {
			return null;
		}
		if (Strings::match($this->data[$name], '/[\d]{4}-[\d]{2}-[\d]{2}\s[\d]{2}:[\d]{2}:[\d]{2}/')) {
			return \DateTime::createFromFormat('Y-m-d H:i:s', $this->data[$name]);
		} elseif (Strings::match($this->data[$name], '/[\d]{4}-[\d]{2}-[\d]{2}T[\d]{2}:[\d]{2}:[\d]{2}\+[\d]{2}:[\d]{2}/')) {
			return \DateTime::createFromFormat(\DateTime::ISO8601, $this->data[$name]);
		} else {
			throw new \InvalidArgumentException($name . ' is not valid datetime.');
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function has(string $name) : bool {
		return array_key_exists($name, $this->data);
	}

}