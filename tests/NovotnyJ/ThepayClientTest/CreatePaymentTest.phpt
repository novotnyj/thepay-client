<?php

namespace NovotnyJ\ThepayClientTest;

use GuzzleHttp\Client;
use NovotnyJ\ThepayClient\Client\IThepayClient;
use NovotnyJ\ThepayClient\Client\ThepayClient;
use NovotnyJ\ThepayClient\Payment\PaymentMethod;
use NovotnyJ\ThepayClient\Payment\PaymentRequest;
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
			new Client(),
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

		$payment = new PaymentRequest($method->getId(), 10.00, 'http://test.com/');
		$payment->setMerchantData('test data');
		$payment->setDescription('test description');

		$url = $this->thepayClient->getPaymentUrl($payment);

		$httpClient = new Client();
		$response = $httpClient->get($url);

		Assert::same(200, $response->getStatusCode());
	}

}

run(new CreatePaymentTest());
