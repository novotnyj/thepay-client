<?php

namespace NovotnyJ\ThepayClient\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;
use NovotnyJ\ThepayClient\Client\IThepayClient;
use NovotnyJ\ThepayClient\Client\ThepayClient;
use NovotnyJ\ThepayClient\Utils\Parameters;

class ThepayClientExtension extends CompilerExtension
{

	/**
	 * @var array
	 */
	public $defaults = [
		'merchantId' => 1,
		'accountId' => 1,
		'secret' => 'my$up3rsecr3tp4$$word',
		'apiKey' => 'my$up3rsecr3tp4$$word',
		'gateUrl' => 'https://www.thepay.cz/demo-gate',
	];

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		Validators::assertField($config, 'merchantId', 'int');
		Validators::assertField($config, 'accountId', 'int');
		Validators::assertField($config, 'secret', 'string');
		Validators::assertField($config, 'gateUrl', 'string');
		Validators::assertField($config, 'apiKey', 'string');

		$parameters = new Parameters($config);

		$container->addDefinition($this->prefix('thepayClient'))
			->setClass(IThepayClient::class)
			->setFactory(ThepayClient::class, [
				'merchantId' => $parameters->getInt('merchantId'),
				'accountId' => $parameters->getInt('accountId'),
				'secret' => $parameters->getString('secret'),
				'gateUrl' => $parameters->getString('gateUrl'),
				'apiKey' => $parameters->getString('gateUrl'),
			]);
	}

}