<?php
/* Transaction Test cases generated on: 2011-01-31 15:01:18 : 1296483498*/
App::import('Behavior', 'Transaction');

class TransactionBehaviorTestCase extends CakeTestCase {
	function startTest() {
		$this->Transaction =& new TransactionBehavior();
	}

	function endTest() {
		unset($this->Transaction);
		ClassRegistry::flush();
	}

}
?>