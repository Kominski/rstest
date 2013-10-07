<?php

/**
 * Description of Response
 *
 * @author Jayawi Perera <jayawiperera@gmail.com>
 */
class Kwgldev_View_Helper_Response {

	public function response ($bShowCloseButton = true) {

		$aResponseListing = Model_Kwgldev_Response::getResponseList();

		$sContent = '';

		if (!empty($aResponseListing)) {
			foreach ($aResponseListing as $aResponse) {
				$sStatus = $aResponse['status'];
				$sMessage = $aResponse['message'];
				switch ($sStatus) {
					case 'success':
						$sResponseClass = 'alert-success';
						break;
					case 'error':
						$sResponseClass = 'alert-error';
						break;
					case 'warning':
						$sResponseClass = 'alert-warning';
						break;
					case 'information':
					default:
						$sResponseClass = 'alert-info';
						break;
				}

				$sContent .= '<div class="alert ' . $sResponseClass . '">';
				if ($bShowCloseButton) {
					$sContent .= '<button class="close" data-dismiss="alert">×</button>';
				}
				$sContent .= $sMessage . '</div>';
			}
		}

		return $sContent;

	}

}