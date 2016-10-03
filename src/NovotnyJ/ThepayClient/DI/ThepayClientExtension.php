<?php

namespace NovotnyJ\ThepayClient\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;
use NovotnyJ\ThepayClient\IThepayClient;
use NovotnyJ\ThepayClient\ThepayClient;

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

		if (!empty($config['merchantId']) && !empty($config['accountId']) && ! empty($config['secret'])) {
			$container->addDefinition($this->prefix('thepayClient'))
				->setClass(IThepayClient::class)
				->setFactory(ThepayClient::class, [
					'merchantId' => $config['merchantId'],
					'accountId' => $config['accountId'],
					'secret' => $config['secret'],
					'gateUrl' => $config['gateUrl'],
					'apiKey' => $config['apiKey'],
				]);
		}
	}

}