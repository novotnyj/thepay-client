<?php

namespace NovotnyJ\ThepayClient\Payment;

class PaymentStatus
{

	const PAID = 2;

	const UNDERPAID = 6;

	const CANCELLED = 3;

	const WAITING = 7;

	const ERROR = 4;

	const CARD_DEPOSIT = 9;

}