<?php
include_once EER_PLUGIN_PATH . '/tests/eer-base-test.php';

class EER_Sold_Ticket_Status_Test extends PHPUnit_Framework_TestCase2 {


	public function test_get_title() {
		$this->assertEquals('Waiting', EER()->sold_ticket_status->get_title(EER_Enum_Sold_Ticket_Status::WAITING));
		$this->assertEquals('Confirmed', EER()->sold_ticket_status->get_title(EER_Enum_Sold_Ticket_Status::CONFIRMED));
		$this->assertEquals('Deleted', EER()->sold_ticket_status->get_title(EER_Enum_Sold_Ticket_Status::DELETED));
		$this->assertEquals(null, EER()->sold_ticket_status->get_title(null));
	}


	public function test_is_waiting() {
		$this->assertEquals(true, EER()->sold_ticket_status->is_waiting(EER_Enum_Sold_Ticket_Status::WAITING));
		$this->assertEquals(false, EER()->sold_ticket_status->is_waiting(EER_Enum_Sold_Ticket_Status::CONFIRMED));
		$this->assertEquals(false, EER()->sold_ticket_status->is_waiting(EER_Enum_Sold_Ticket_Status::DELETED));
		$this->assertEquals(false, EER()->sold_ticket_status->is_waiting(null));
	}


}
