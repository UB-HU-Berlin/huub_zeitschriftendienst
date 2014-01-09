<?php

class Tx_HuubZeitschriftendienst_ViewHelpers_LinkViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

  /**
   * @param string $zeitschrift 
   * @param string $zdbid
   **/

	public function render($zeitschrift, $zdbid) {
		if ($zdbid == "") {
			$zdbidText = "";
		}
		else {
			$zdbidText = "&zdbid=" . $zdbid;
		}

		return "http://services.d-nb.de/fize-service/gvr/html-service.htm?sid=vifa:evifa&genre=journal&title=" . urlencode($zeitschrift) . "&pid=" . urlencode("client_ip=" . $_SERVER['REMOTE_ADDR'] . $zdbidText);
	}

}

?>