<?php
include_once EER_PLUGIN_PATH . '/tests/eer-base-test.php';

class EER_Dancing_As_Test extends PHPUnit_Framework_TestCase2 {


	public function test_get_title() {
		$this->assertEquals('Leader', EER()->dancing_as->eer_get_title(EER_Enum_Dancing_As::LEADER));
		$this->assertEquals('Follower', EER()->dancing_as->eer_get_title(EER_Enum_Dancing_As::FOLLOWER));
		$this->assertEquals('Solo', EER()->dancing_as->eer_get_title(EER_Enum_Dancing_As::SOLO));
	}


	public function test_get_item() {
		$this->assertEquals(['title' => 'Leader'], EER()->dancing_as->eer_get_item(EER_Enum_Dancing_As::LEADER));
		$this->assertEquals(['title' => 'Follower'], EER()->dancing_as->eer_get_item(EER_Enum_Dancing_As::FOLLOWER));
		$this->assertEquals(['title' => 'Solo'], EER()->dancing_as->eer_get_item(EER_Enum_Dancing_As::SOLO));
	}


	public function test_is_leader() {
		$this->assertEquals(true, EER()->dancing_as->eer_is_leader(EER_Enum_Dancing_As::LEADER));
		$this->assertEquals(false, EER()->dancing_as->eer_is_leader(EER_Enum_Dancing_As::FOLLOWER));
		$this->assertEquals(false, EER()->dancing_as->eer_is_leader(EER_Enum_Dancing_As::SOLO));
		$this->assertEquals(false, EER()->dancing_as->eer_is_leader(null));
	}


	public function test_is_follower() {
		$this->assertEquals(false, EER()->dancing_as->eer_is_follower(EER_Enum_Dancing_As::LEADER));
		$this->assertEquals(true, EER()->dancing_as->eer_is_follower(EER_Enum_Dancing_As::FOLLOWER));
		$this->assertEquals(false, EER()->dancing_as->eer_is_follower(EER_Enum_Dancing_As::SOLO));
		$this->assertEquals(false, EER()->dancing_as->eer_is_follower(null));
	}


	public function test_is_leader_registration_enabled() {
		$this->assertEquals(false, EER()->dancing_as->eer_is_leader_registration_enabled(1));
		$this->assertEquals(false, EER()->dancing_as->eer_is_leader_registration_enabled(1, null));
		$this->assertEquals(false, EER()->dancing_as->eer_is_leader_registration_enabled(1, 1));

		//REAL REGISTRATIONS
	}


	public function test_is_follower_registration_enabled() {
		$this->assertEquals(false, EER()->dancing_as->eer_is_followers_registration_enabled(1));
		$this->assertEquals(false, EER()->dancing_as->eer_is_followers_registration_enabled(1, null));
		$this->assertEquals(false, EER()->dancing_as->eer_is_followers_registration_enabled(1, 1));

		//REAL REGISTRATIONS
	}


	public function test_is_solo_registration_enabled() {
		$this->assertEquals(false, EER()->dancing_as->eer_is_solo_registration_enabled(1));
		$this->assertEquals(false, EER()->dancing_as->eer_is_solo_registration_enabled(1, null));
		$this->assertEquals(false, EER()->dancing_as->eer_is_solo_registration_enabled(1, 1));

		//REAL REGISTRATIONS
	}
}
