<?php
/**
 * Description of SoapResponse
 *
 * @author Darshan
 */
require_once 'SoapError.php';
class Pi_SoapResponse {	
	
	// response statuses
	const STATUS_OK = 'ok';

	private $sResponse;
	private $sMethod;
	private $sStatus;
	private $bIsSimpleTextResponse;
	private $aAccountCredentials;

	public function __construct($sMethod,$sResponse,$aAccountCredentials,$sInputXml,$bIsSimpleTextResponse = false) {

		$this->sMethod = $sMethod;
		$this->sResponse = $sResponse;
		//echo $sResponse;
		$this->bIsSimpleTextResponse = $bIsSimpleTextResponse;
		$this->aAccountCredentials = $aAccountCredentials;
		$this->sInputXml = $sInputXml;

	}	

	public function parse() {

		// all valid soap responses start with this string
		if (substr($this->sResponse, 0, 14) == '<soap:Envelope') {

			// create a simplexml object for the entire soap response
			$oSXmlResponseSoapEnvelope = simplexml_load_string($this->sResponse);

			// get the return element and its string contents
			$aReturnXmlElement = $oSXmlResponseSoapEnvelope->xpath('//return');
			$sReturnXml = (string)$aReturnXmlElement[0];

			// when return is plain text (no xml)
			if ($this->bIsSimpleTextResponse) {
				return $sReturnXml;
			} else {

				// when return string is indeed an escaped xml string,
				// create a simplexml object and return
				$oSXmlReturn = simplexml_load_string($sReturnXml);
				
				// if the status is exception or there is an error node then there is an error
				if ((string)$oSXmlReturn->Status === 'EXCEPTION' || $oSXmlReturn->Error) {

					//Zend_Debug::dump($this->sResponse);
					Pi_SoapError::handleError($this->sMethod,$oSXmlReturn,$this->aAccountCredentials,$this->sInputXml);

					// handle error and throw exception
					throw new Exception($oSXmlReturn->Error->ErrorDescription);
					
				}				

				return $oSXmlReturn;
			
			}
		} else {
			throw new Exception('Invalid response');
		}
				
	}	

}
?>
