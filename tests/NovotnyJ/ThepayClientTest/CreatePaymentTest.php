<?php

namespace NovotnyJ\ThepayClientTest;

use GuzzleHttp\Client;
use NovotnyJ\ThepayClient\IThepayClient;
use NovotnyJ\ThepayClient\PaymentRequest;
use NovotnyJ\ThepayClient\PaymentMethod;
use NovotnyJ\ThepayClient\ThepayClient;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

class CreatePaymentTest extends TestCase
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

	public function testDefault()
	{
		$methods = $this->thepayClient->getPaymentMethods();
		/** @var PaymentMethod $method */
		$method = $methods[0];

		$payment = new PaymentRequest(
			$method->getId(),
			10,
			'http://test.com/',
			'test data',
			'test description'
		);

		$url = $this->thepayClient->getPaymentUrl($payment);

		$httpClient = new Client();
		$response = $httpClient->get($url);

		Assert::equal(200, $response->getStatusCode());
	}

}

run(new CreatePaymentTest());
