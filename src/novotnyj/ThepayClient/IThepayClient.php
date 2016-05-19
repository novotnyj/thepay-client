<?php

namespace NovotnyJ\ThepayClient;

interface IThepayClient
{

	/**
	 * @return PaymentMethod[]
	 */
	public function getPaymentMethods();

	/**
	 * @param PaymentRequest $payment
	 * @return string
	 */
	public function getPaymentUrl(PaymentRequest $payment);

}