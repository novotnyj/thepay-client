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

	/**
	 * @param PaymentMethod $method
	 * @param string $size
	 * @return string
	 */
	public function getMethodLogoUrl(PaymentMethod $method, $size = '86x86');

	/**
	 * @param PaymentResponse $paymentResponse
	 * @return bool
	 */
	public function verifyPayment(PaymentResponse $paymentResponse);

}