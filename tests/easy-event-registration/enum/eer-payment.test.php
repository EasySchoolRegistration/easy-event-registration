<?php
include_once EER_PLUGIN_PATH . '/tests/eer-base-test.php';

class EER_Payment_Test extends PHPUnit_Framework_TestCase2 {


	public function test_get_title() {
		$this->assertEquals('Not paid', EER()->enum_payment->get_title(EER_Enum_Payment::NOT_PAID));
		$this->assertEquals('Paid', EER()->enum_payment->get_title(EER_Enum_Payment::PAID));
		$this->assertEquals('Over paid', EER()->enum_payment->get_title(EER_Enum_Payment::OVER_PAID));
		$this->assertEquals('Not paying', EER()->enum_payment->get_title(EER_Enum_Payment::NOT_PAYING));
		$this->assertEquals('Voucher', EER()->enum_payment->get_title(EER_Enum_Payment::VOUCHER));
		$this->assertEquals('Not paid all', EER()->enum_payment->get_title(EER_Enum_Payment::NOT_PAID_ALL));
		$this->assertEquals(null, EER()->enum_payment->get_title(null));
	}

	public function test_get_status() {
		$this->assertEquals(EER_Enum_Payment::NOT_PAID, EER()->enum_payment->get_status(null));

		$payment = new stdClass();
		$payment->is_paying = 0;
		$this->assertEquals(EER_Enum_Payment::NOT_PAYING, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = '0';
		$this->assertEquals(EER_Enum_Payment::NOT_PAYING, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = false;
		$this->assertEquals(EER_Enum_Payment::NOT_PAYING, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = true;
		$this->assertEquals(EER_Enum_Payment::VOUCHER, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = 1;
		$this->assertEquals(EER_Enum_Payment::VOUCHER, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = '1';
		$this->assertEquals(EER_Enum_Payment::VOUCHER, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->payment = null;
		$this->assertEquals(EER_Enum_Payment::NOT_PAID, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->to_pay = 10;
		$payment->payment = 10;
		$this->assertEquals(EER_Enum_Payment::PAID, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->to_pay = 10;
		$payment->payment = 9;
		$this->assertEquals(EER_Enum_Payment::NOT_PAID_ALL, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->to_pay = 10;
		$payment->payment = 11;
		$this->assertEquals(EER_Enum_Payment::OVER_PAID, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->to_pay = 10;
		$payment->payment = 10.5;
		$this->assertEquals(EER_Enum_Payment::OVER_PAID, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->to_pay = 10.1;
		$payment->payment = 10;
		$this->assertEquals(EER_Enum_Payment::NOT_PAID_ALL, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->to_pay = 10.1;
		$payment->payment = 10.1;
		$this->assertEquals(EER_Enum_Payment::PAID, EER()->enum_payment->get_status($payment));

		$payment = new stdClass();
		$payment->is_paying = true;
		$payment->is_voucher = false;
		$payment->to_pay = 10.1;
		$payment->payment = 10.2;
		$this->assertEquals(EER_Enum_Payment::OVER_PAID, EER()->enum_payment->get_status($payment));
	}


}
