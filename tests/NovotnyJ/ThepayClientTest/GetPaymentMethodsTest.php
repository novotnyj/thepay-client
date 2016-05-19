<?php

namespace NovotnyJ\ThepayClientTest;

use NovotnyJ\ThepayClient\IThepayClient;
use NovotnyJ\ThepayClient\PaymentMethod;
use NovotnyJ\ThepayClient\ThepayClient;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class GetPaymentMethodsTest extends TestCase
{

	/**
	 * @var IThepayClient
	 */
	private $thepayClient;

	public function setUp()
	{
		parent::setUp();

		$this->thepayClient = new ThepayClient(
			1,
			1,
			'my$up3rsecr3tp4$$word',
			'my$up3rsecr3tp4$$word',
			'https://www.thepay.cz/demo-gate'
		);
	}

	public function testGetPaymentMethods()
	{
		$methods = $this->thepayClient->getPaymentMethods();

		Assert::type('array', $methods);
		Assert::true(count($methods) > 0);
		Assert::type(PaymentMethod::class, $methods[0]);
	}

}

run(new GetPaymentMethodsTest());