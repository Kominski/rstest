<?php
/**
 * Description of Pi_Soap
 *
 * @author Darshan
 */
class Pi_SoapClient {

	private $sBaseUrl = "";										// base SOAP server url (set in config)
	private $sBasicAuth = "basic";								// authentication type
	private $sWsdl = "plain?wsdl";								// relative wsdl location
	private $aHeaders;
	
	public $sInputXml;											// parameter for SOAP called method
	public $sMethod;											// name of SOAP method being called

	public $sUsername = "";
	public $sPassword = "";

	public function  __construct($sUsername = null, $sPassword = null, $sBaseUrl = null) {

		//combine and get configaration values
		$aConf = Pi_Netassessor::getConfigValues(array(
			'username'	=> $sUsername,
			'password'	=> $sPassword,
			'baseUrl'	=> $sBaseUrl
		));

		//Zend_Debug::dump($aConf);
		//exit();

		//set values
		$this->sUsername = $aConf['username'];
		$this->sPassword = $aConf['password'];
		$this->sBaseUrl = $aConf['baseUrl'];

	}


	/**
	 * Sends a SOAP request wrapped in a SOAP envelope
	 * Checks whether server is alive before trying to send
	 * @param string $sMethod
	 * @param string $sInputXml
	 * @return string - response from SOAP server or boolean false in the case of an error
	 */
	public function send($sMethod, $sInputXml = '') {

		// check whether SOAP server is running
		$sIsAliveResponse = $this->isAlive();
		$oIsAliveResponse = new Pi_SoapResponse("isAlive",$sIsAliveResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sInputXml,true);

		// try to parse the is alive reponse
		try {

			$sIsAlive = $oIsAliveResponse->parse();
			
			if ($sIsAlive == 'true') {

				$this->sMethod = $sMethod;
				$this->sInputXml = $sInputXml;
				$this->setMessage();
				$this->setHeaders();

				return $this->doRequest();

			} else {
				throw new Exception('Server not alive');
			}

		// is alive reponse cannot be parsed
		} catch (Exception $exc) {

			// due to an authorization error
			if(stripos($sIsAliveResponse,"authorization") !== FALSE){

				Pi_SoapError::handleIsAliveError("Authorization required",array("username" => $this->sUsername,"password" => $this->sPassword));
				throw new Exception('Authorization required');
			
			// due to an unknown error
			} else {

				Pi_SoapError::handleIsAliveError("Unknown isAlive error",array("username" => $this->sUsername,"password" => $this->sPassword));
				throw new Exception('Unknown isAlive error');

			}

			
		}


	}

	/**
	 * Configure CURL instance & perform the request
	 * @return string
	 */
	private function doRequest() {

		$oCurl = curl_init();
		curl_setopt($oCurl, CURLOPT_URL, $this->sBaseUrl.$this->sBasicAuth);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $this->sMessage);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($oCurl, CURLOPT_HTTPHEADER, $this->aHeaders);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, 30);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($oCurl, CURLOPT_HEADER, false);

		$sResult = curl_exec($oCurl);

		// handle errors
		if (curl_errno($oCurl)){
			throw new Exception('CURL exception');
		}

		curl_close($oCurl);

		return $sResult;

	}

	/**
	 * @description check if the parameter xml is valid with the online xsd of pi
	 */
	private function isValid(){

		$oDoc = new DomDocument;
		$sXmlFile = '/Users/boudewijnovervliet/Sites/datahoax/createOrUpdatePerson.xml';
		$sXmlSchema = '/Users/boudewijnovervliet/Sites/datahoax/person.xsd';

		// load the xml document in the DOMDocument object
		$oDoc->Load($sXmlFile);

		// validate the xml file against the schema
		if ($oDoc->schemaValidate($sXmlSchema)) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * @description check if the webservice is alive, to do further requests
	 */
	private function isAlive(){

		$this->sMethod = 'isAlive';
		$this->sInputXml = '';

		$this->setMessage();
		$this->setHeaders();

		return $this->doRequest();

	}

	/**
	 * @description place the input xml in the proper SOAP envelope
	 */
	private function setMessage(){

		$this->sMessage =  '<?xml version="1.0" encoding="UTF-8" ?>
							<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:soap="http://soap.picompany.nl/">
								<soapenv:Header/>
								<soapenv:Body>
									<soap:'. $this->sMethod .'>
										<!--Optional:-->
										<inputXML><![CDATA['. $this->sInputXml .']]></inputXML>
									</soap:'. $this->sMethod .'>
								</soapenv:Body>
							</soapenv:Envelope>';

	}

	/**
	 * @description get the headers required to send a proper SOAP request
	 */
	private function setHeaders(){

		$this->aHeaders = array(
			"Content-Type: text/xml; charset=utf-8",
			"Content-Length: " . strlen($this->sMessage),
			"SOAPAction: \"\"",
			"Authorization: Basic " . base64_encode($this->sUsername.":".$this->sPassword),
		);

	}

}

?>
