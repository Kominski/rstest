<?php
/**
 * Description of SoapResponse
 *
 * @author Darshan
 */


class Pi_SoapError {

	public function __construct() {

		

	}

	public static function handleError($sMethod,$oResponse,$aAccountCredentials,$sInputXml,$bSendMail = false){

		foreach($oResponse->attributes() as $sName => $sValue) {

			if($sName == "employeeID"){
				$sEmployeeId = (string)$sValue;
			} else if($sName == "instrumentID"){
				$sInstrumentId = (string)$sValue;
			}

		}

		$oNetAssessor = new Zend_Db_Table('netassessor_errors');
		$aInsertData["na_username"] = $aAccountCredentials["username"];
		$aInsertData["na_password"] = $aAccountCredentials["password"];
		$aInsertData["na_employee_id"] = ($sEmployeeId) ? $sEmployeeId : 0 ;
		$aInsertData["na_instrument_id"] = ($sInstrumentId) ? $sInstrumentId : 0 ;
		$aInsertData["na_error_type"] = (string)$oResponse->Error->ErrorType;
		$aInsertData["na_error_label"] = (string)$oResponse->Error->ErrorLabel;
		$aInsertData["na_error_description"] = (string)$oResponse->Error->ErrorDescription;
		$aInsertData["na_method"] = (string)$sMethod;
		$aInsertData["na_input_xml"] = (string)$sInputXml;
		$aInsertData["datetime"] = date("Y-m-d H:i:s");
		$oNetAssessor->insert($aInsertData);

	}

	public static function handleIsAliveError($sIsAliveError,$aAccountCredentials){

		$oNetAssessor = new Zend_Db_Table('netassessor_errors');
		$aInsertData["na_username"] = $aAccountCredentials["username"];
		$aInsertData["na_password"] = $aAccountCredentials["password"];
		$aInsertData["na_error_description"] = $sIsAliveError;
		$aInsertData["na_method"] = "isAlive";
		$aInsertData["datetime"] = date("Y-m-d H:i:s");
		$oNetAssessor->insert($aInsertData);

	}

	

	

}
?>
