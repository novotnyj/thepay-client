<?php

namespace NovotnyJ\ThepayClient\Client;

use NovotnyJ\ThepayClient\Payment\PaymentInfo;
use NovotnyJ\ThepayClient\Payment\PaymentMethod;
use NovotnyJ\ThepayClient\Payment\PaymentRequest;
use NovotnyJ\ThepayClient\Payment\PaymentResponse;

interface IThepayClient
{

	/**
	 * @return PaymentMethod[]
	 */
	public function getPaymentMethods() : array ;

	/**
	 * @param PaymentRequest $payment
	 * @return string
	 */
	public function getPaymentUrl(PaymentRequest $payment) : string ;

	/**
	 * @param PaymentMethod $method
	 * @param string $size
	 * @return string
	 */
	public function getMethodLogoUrl(PaymentMethod $method, string $size = '86x86') : string ;

	/**
	 * @param PaymentResponse $paymentResponse
	 * @return bool
	 */
	public function verifyPayment(PaymentResponse $paymentResponse) : bool ;

	/**
	 * @param int $paymentId
	 * @return PaymentInfo
	 */
	public function getPaymentInfo(int $paymentId) : PaymentInfo;

}