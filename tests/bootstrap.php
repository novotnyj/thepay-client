<?php

// The Nette Tester command-line runner can be
// invoked through the command: ../vendor/bin/tester .

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer install`';
	exit(1);
}

// configure environment
Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

function run(Tester\TestCase $testCase)
{
	$testCase->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);
}
