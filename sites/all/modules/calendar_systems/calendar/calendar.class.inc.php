<?php
/**
 * @todo
 *   Esfahbod multi calendar should be used as of base of this package.
 */

class cmfcCalendar {
	function factory($name,$options) {
		if ($name=='old') {
			//require_once('ca.class.inc.php');
			//return new cmfcEmailSenderOld($options);
		}
		if ($name=='v1') {
			require_once(dirname(__FILE__).'/v1/calendarV1.class.inc.php');
			return cmfcCalendarV1::factory($options);
		}
	}
}