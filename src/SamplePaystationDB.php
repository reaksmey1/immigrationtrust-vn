<?php

use \Paystation\PaystationTransaction;
use \Paystation\PaystationDBInterface;

class SamplePaystationDB implements PaystationDBInterface {
	private $filePath = __DIR__ . '/../temp/latest_transaction';

	public function __construct() {
	}

	/**
	 * @param String $transactionId
	 * @return \Paystation\PaystationTransaction|null null if not found.
	 */
	public function get($transactionId) {
		if (!isset($transactionId) || !$transactionId || !file_exists($this->filePath)) {
			return null;
		}

		$file = fopen($this->filePath, "r");
		$txn = unserialize(fread($file, filesize($this->filePath)));
		fclose($file);

		// In this example, only the one most recent transaction details are stored. This is just a sanity check to better mimic a database query.
		return $txn instanceof PaystationTransaction && isset($txn->transactionId) && $txn->transactionId == $transactionId ? $txn : null;
	}

	/**
	 * Update any field that's not null.
	 * @param \Paystation\PaystationTransaction $txn
	 */
	public function save(\Paystation\PaystationTransaction $txn) {
		if (!isset($txn->transactionId)) {
			return;
		}

		// Make sure we don't lose any data that is already saved.
		$storedTxn = $this->get($txn->transactionId);
		if ($storedTxn) {
			foreach ($storedTxn as $key => $value) {
				if (!isset($txn->$key)) {
					$txn->$key = $value;
				}
			}
		}

		$file = fopen($this->filePath, 'w');
		fwrite($file, serialize($txn));
		fclose($file);
	}

	/**
	 * @param \Paystation\PaystationTransaction $transaction The transaction that needs a new ID.
	 * @return string The new ID for the transaction. Max length 64.
	 */
	public function createMerchantSession(\Paystation\PaystationTransaction $transaction) {
		return $this->generateRandomString(8) . time();
	}

	private function generateRandomString($length) {
		$token = "";
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		for ($i = 0; $i < $length; $i++) {
			$token .= $chars[rand(0, strlen($chars) - 1)];
		}
		return $token;
	}
}
