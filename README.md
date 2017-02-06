# Thepay Client

Unofficial client for ThePay payment gate.

## Instalation

Add following to your config:

```neon
extensions:
	thepayClient: NovotnyJ\ThepayClient\DI\ThepayClientExtension

thepayClient:
	merchantId: 1
	accountId: 1
	secret: 'xxx'
	apiKey: 'xxx'
	gateUrl: 'https://www.thepay.cz/demo-gate'
```

## Create payment

Create new payment:

```php
$payment = new PaymentRequest($method->getId(), 10.00, 'http://my-super-eshop.com/thepay');
$payment->setMerchantData('test data');
$payment->setDescription('test description');
$payment->setBackToEshopUrl('http://my-super-shop.com/');

$url = $this->thepayClient->getPaymentUrl($payment);
```

Now redirect your customer to the payment gate on `$url`.

## Process payment response

```php
$get = $this->getParameters();
$response = new PaymentResponse($get);

if ($response->isPaid()) {
	...	
} 

if ($response->isUnderPaid()) {
	$paymentInfo = $this->thepayClient->getPaymentInfo($response->getPaymentId());
	$paid = $paymentInfo->getReceivedValue();
}

if ($response->isCancelled()) {
	...
}

if ($response->isError()) {
	...
}
```

## Available payment methods

To get available payment methods:

```php
$methods = $this->thepayClient->getPaymentMethods();
```