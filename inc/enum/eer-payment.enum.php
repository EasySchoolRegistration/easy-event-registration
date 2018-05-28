<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Enum_Payment
{
	const
		NOT_PAID = 0, PAID = 1, OVER_PAID = 2, NOT_PAYING = 3, VOUCHER = 4, NOT_PAID_ALL = 5;

	private $items = [];


	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$this->items = [
			self::NOT_PAID => [
				'key' => 'not_paid',
				'title' => __('Not paid', 'easy-school-registration'),
			],
			self::PAID => [
				'key' => 'paid',
				'title' => __('Paid', 'easy-school-registration'),
			],
			self::OVER_PAID => [
				'key' => 'over_paid',
				'title' => __('Over paid', 'easy-school-registration'),
			],
			self::NOT_PAYING => [
				'key' => 'not_paying',
				'title' => __('Not paying', 'easy-school-registration'),
			],
			self::VOUCHER => [
				'key' => 'voucher',
				'title' => __('Voucher', 'easy-school-registration'),
			],
			self::NOT_PAID_ALL => [
				'key' => 'not_paid_all',
				'title' => __('Not paid all', 'easy-school-registration'),
			],
		];
	}


	public function getItems()
	{
		return $this->items;
	}


	public function getItem($key)
	{
		return $this->getItems()[$key];
	}


	public function get_title($key)
	{
		return $this->getItem($key)['title'];
	}


	public function get_status($user_payment)
	{
		if ($user_payment !== null) {
			if (!((boolean)$user_payment->is_paying)) {
				return self::NOT_PAYING;
			}
			if ((boolean)$user_payment->is_voucher) {
				return self::VOUCHER;
			}
			if ($user_payment->payment !== null) {
				if (intval($user_payment->to_pay) == $user_payment->payment) {
					return EER_Enum_Payment::PAID;
				} else if (intval($user_payment->to_pay) > $user_payment->payment) {
					return EER_Enum_Payment::NOT_PAID_ALL;
				} else {
					return EER_Enum_Payment::OVER_PAID;
				}
			}
		}

		return self::NOT_PAID;
	}
}