<?php
include_once EER_PLUGIN_PATH . '/tests/eer-base-test.php';

class EER_Pairing_Mode_Test extends PHPUnit_Framework_TestCase2 {


	public function test_get_title() {
		$this->assertEquals('Automatic', EER()->pairing_mode->get_title(EER_Enum_Pairing_Mode::AUTOMATIC));
		$this->assertEquals('Manual', EER()->pairing_mode->get_title(EER_Enum_Pairing_Mode::MANUAL));
		$this->assertEquals('Confirm All', EER()->pairing_mode->get_title(EER_Enum_Pairing_Mode::CONFIRM_ALL));
		$this->assertEquals(null, EER()->pairing_mode->get_title(null));
	}


	public function test_is_pairing_enabled() {
		$this->assertEquals(true, EER()->pairing_mode->is_pairing_enabled(EER_Enum_Pairing_Mode::AUTOMATIC));
		$this->assertEquals(false, EER()->pairing_mode->is_pairing_enabled(EER_Enum_Pairing_Mode::MANUAL));
		$this->assertEquals(false, EER()->pairing_mode->is_pairing_enabled(EER_Enum_Pairing_Mode::CONFIRM_ALL));
		$this->assertEquals(false, EER()->pairing_mode->is_pairing_enabled(null));
	}


	public function test_is_auto_confirmation_enabled() {
		$this->assertEquals(false, EER()->pairing_mode->is_auto_confirmation_enabled(EER_Enum_Pairing_Mode::AUTOMATIC));
		$this->assertEquals(false, EER()->pairing_mode->is_auto_confirmation_enabled(EER_Enum_Pairing_Mode::MANUAL));
		$this->assertEquals(true, EER()->pairing_mode->is_auto_confirmation_enabled(EER_Enum_Pairing_Mode::CONFIRM_ALL));
		$this->assertEquals(false, EER()->pairing_mode->is_auto_confirmation_enabled(null));
	}


	public function test_get_solo_ticket_default_status() {
		$this->assertEquals(EER_Enum_Sold_Ticket_Status::CONFIRMED, EER()->pairing_mode->get_solo_ticket_default_status(EER_Enum_Pairing_Mode::AUTOMATIC));
		$this->assertEquals(EER_Enum_Sold_Ticket_Status::WAITING, EER()->pairing_mode->get_solo_ticket_default_status(EER_Enum_Pairing_Mode::MANUAL));
		$this->assertEquals(EER_Enum_Sold_Ticket_Status::CONFIRMED, EER()->pairing_mode->get_solo_ticket_default_status(EER_Enum_Pairing_Mode::CONFIRM_ALL));
		$this->assertEquals(EER_Enum_Sold_Ticket_Status::CONFIRMED, EER()->pairing_mode->get_solo_ticket_default_status(null));
	}


	public function test_get_items_for_settings() {
		$this->assertEquals([
			EER_Enum_Pairing_Mode::AUTOMATIC   => 'Automatic',
			EER_Enum_Pairing_Mode::MANUAL      => 'Manual',
			EER_Enum_Pairing_Mode::CONFIRM_ALL => 'Confirm All',
		], EER()->pairing_mode->get_items_for_settings());
	}

}
