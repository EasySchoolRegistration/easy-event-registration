<?php
include_once EER_PLUGIN_PATH . '/tests/eer-base-test.php';

class EER_Order_Status_Test extends PHPUnit_Framework_TestCase2 {


	public function test_get_title() {
		$this->assertEquals('Ordered', EER()->order_status->get_title(EER_Enum_Order_Status::ORDERED));
		$this->assertEquals('Deleted', EER()->order_status->get_title(EER_Enum_Order_Status::DELETED));
		$this->assertEquals(null, EER()->order_status->get_title(null));
	}


}
