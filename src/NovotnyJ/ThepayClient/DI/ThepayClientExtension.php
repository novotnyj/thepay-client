<?php

namespace NovotnyJ\ThepayClient\DI;

use Nette\DI\CompilerExtension;
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
		'demo' => true,
	];

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$parameters = new Parameters($config);

		$gateUrl = 'https://www.thepay.cz/demo-gate';

		if ($config['demo'] === false) {
			$gateUrl = 'https://www.thepay.cz/gate/';
		}

		$container->addDefinition($this->prefix('thepayClient'))
			->setClass(IThepayClient::class)
			->setFactory(ThepayClient::class, [
				'merchantId' => $parameters->getInt('merchantId'),
				'accountId' => $parameters->getInt('accountId'),
				'secret' => $parameters->getString('secret'),
				'gateUrl' => $gateUrl,
				'apiKey' => $parameters->getString('apiKey'),
			]);
	}

}