<?php

require_once 'SoapClient.php';
require_once 'SoapResponse.php';

class Pi_Netassessor {

	// employee education types
	const EDUCATIONTYPE_WO = 1;			// University education
	const EDUCATIONTYPE_HBO = 2;		// Higher professional education
	const EDUCATIONTYPE_MBO = 3;		// Senior secondary vocational education
	const EDUCATIONTYPE_MAVO_LBO = 4;	// Junior general secondary education / Lower secondary vocational education
	const EDUCATIONTYPE_HAVO_VWO = 5;	// Senior general secondary education / Pre-university education
	const EDUCATIONTYPE_DOCTORATE = 6;	// Doctorate

	// employee gender
	const GENDER_MALE = 'M';
	const GENDER_FEMALE = 'F';
	const GENDER_UNKNOWN = 'U';
	
	/**
	 * static variable to hold all the common settings
	 * @var array
	 */
	private static $aConfig = array(
		'username'	=> null,
		'password'	=> null,
		'baseUrl'	=> 'http://www.netassessor.nl/smx/soap/'
	);

	public $sUsername = "";
	public $sPassword = "";

	public function  __construct($sUsername = null, $sPassword = null) {

		//combine and get configaration values
		$aConf = self::getConfigValues(array(
			'username'	=> $sUsername,
			'password'	=> $sPassword
		));
		
		//set values
		$this->sUsername = $aConf['username'];
		$this->sPassword = $aConf['password'];

	}
	
	/**
	 * set common configurations
	 * @param array $aConfigurations key value pairs of configuration values 
	 */
	public static function setConfig($aConfigurations) {
		
		//check if any of the keys exists in the configurations
		if(count(array_diff_key(self::$aConfig, $aConfigurations)) == 3){
			throw new Exception('Netassessor Error: Passed configuration keys are not correct.');
		}
		//merge both arrays
		self::$aConfig = array_merge(self::$aConfig, $aConfigurations);
		
	}
	
	/**
	 * static method to combine default configuration values and passed in object specific values.
	 * 
	 * @param array $aCustomValues
	 * @return array 
	 */
	public static function getConfigValues($aCustomValues) {
		
		$aConfCombined = array();
		//check if all the configurations are set, by default or from object specific values
		foreach (self::$aConfig as $sConfParam => $sConfValue) {
			//combine default configurations and object specific configurations
			if(isset ($aCustomValues[$sConfParam]) && !is_null($aCustomValues[$sConfParam]))
				$aConfCombined[$sConfParam] = $aCustomValues[$sConfParam];
			else
				$aConfCombined[$sConfParam] = $sConfValue;
			
			//if the value is null, then an exception is thrown
			if(is_null($aConfCombined[$sConfParam])){
				throw new Exception('Netassessor Error: Configuration value for '. $sConfParam .' is not correct.');
			}
		}
		return $aConfCombined;
		
	}


	public function createTest($sIdInstrument, $sIdEmployee, $sBackPage, $aCompetences = null, $sIdNorm = null, $sIdInstrumentProfile = null) {

		// new DOM document
		$oDomDoc = new DOMDocument('1.0', 'utf-8');
		$oDomDoc->xmlStandalone = true;
		$oDomDoc->formatOutput = true;

		// create the root element
		$oRoot = $oDomDoc->appendChild($oDomDoc->createElement('Test'));
		$oRoot->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation', 'createTest.xsd');

		// get the SimpleXml object for the root element
		$oSXmlElementRoot = simplexml_import_dom($oDomDoc);
		// instrument id element
		$oSXmlElementRoot->addChild('InstrumentID', utf8_encode($sIdInstrument));
		// employee id element
		$oSXmlElementRoot->addChild('EmployeeID', utf8_encode($sIdEmployee));

		if($sIdNorm != null && $sIdNorm > 0){

			// norm id element
			$oSXmlElementRoot->addChild('NormID', utf8_encode($sIdNorm));

		}

		

		// back page url element
		$oSXmlElementRoot->addChild('BackPage', utf8_encode($sBackPage));

		if($aCompetences != null){

			$oCompetences = $oSXmlElementRoot->addChild('CompetenceLevels');

			if(count($aCompetences) > 0){
				foreach($aCompetences AS $aCompetence){
					$oCompetences->addChild('CompetenceLevel',utf8_encode($aCompetence['LEVEL_ID']));
				}
			}

		}

		// current date values
		$aCurrentDate = array(
			'year' => date('Y'),
			'month' => date('m'),
			'day' => date('d')
		);
		
		// return date element with date attributes
		/*$oSXmlElementReturnDate = $oSXmlElementRoot->addChild('ReturnDate');
		$oSXmlElementReturnDate->addAttribute('month', $aCurrentDate['month']);
		$oSXmlElementReturnDate->addAttribute('year', $aCurrentDate['year']);
		$oSXmlElementReturnDate->addAttribute('day', $aCurrentDate['day']);*/

		if($sIdInstrumentProfile != null && $sIdInstrumentProfile > 0){

			// instrument profile id element
			$oSXmlElementRoot->addChild('InstrumentProfileID', utf8_encode($sIdInstrumentProfile));

		}

		// get input xml into a string var for embedding into soap request
		$sXmlString = $oDomDoc->saveXML();

		//echo $sXmlString;
		//exit();

		$oSoapClient = new Pi_SoapClient($this->sUsername,$this->sPassword);
		$sResponse = $oSoapClient->send('createTest', $sXmlString);

		$oResponseHandler = new Pi_SoapResponse("createTest",$sResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sXmlString);
		$oSXmlReturn = $oResponseHandler->parse();

		$aReturnVars = array(
			'status'		=> (string)$oSXmlReturn->Status,
			'test_id'		=> (string)$oSXmlReturn->TestID,
			'test_code'		=> (string)$oSXmlReturn->TestCode,
			'assignment_id' => (string)$oSXmlReturn->AssignmentID,
			'url'			=> (string)$oSXmlReturn->URL
		);

		return $aReturnVars;

	}

	public function getTestStatus($sIdEmployee, $iIdTest, $sTestCode) {

		// new DOM document
		$oDomDoc = new DOMDocument('1.0', 'utf-8');
		$oDomDoc->xmlStandalone = true;
		$oDomDoc->formatOutput = true;

		// create the root element
		$oRoot = $oDomDoc->appendChild($oDomDoc->createElement('GetTestStatus'));
		$oRoot->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation', 'GetTestStatus.xsd');

		// get the SimpleXml object for the root element
		$oSXmlElementRoot = simplexml_import_dom($oDomDoc);
		
		// test id element
		$oSXmlElementRoot->addChild('TestID', utf8_encode($iIdTest));

		// get input xml into a string var for embedding into soap request
		$sXmlString = $oDomDoc->saveXML();

		//echo $sXmlString."<br/>";
		//exit();

		$oSoapClient = new Pi_SoapClient($this->sUsername,$this->sPassword);
		$sResponse = $oSoapClient->send('getTestStatus', $sXmlString);

		$oResponseHandler = new Pi_SoapResponse("getTestStatus",$sResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sXmlString);
		$oSXmlReturn = $oResponseHandler->parse();

		$aReturnVars = array(
			'test_id' => (integer)$oSXmlReturn->TestID,
			'test_status_id' => (integer)$oSXmlReturn->TestStatusID,
			'test_status_label' => (string)strtolower($oSXmlReturn->TestStatusLabel)
		);

		return $aReturnVars;

	}

	public function getTestResults($sIdEmployee, $sIdTest, $sTestCode, $bSimpleTextReturn=false) {

		// new DOM document
		$oDomDoc = new DOMDocument('1.0', 'utf-8');
		$oDomDoc->xmlStandalone = true;
		$oDomDoc->formatOutput = true;

		// create the root element
		$oRoot = $oDomDoc->appendChild($oDomDoc->createElement('GetTestResults'));
		$oRoot->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation', 'GetTestResults.xsd');

		// get the SimpleXml object for the root element
		$oSXmlElementRoot = simplexml_import_dom($oDomDoc);

		// employee id element
		$oSXmlElementRoot->addChild('EmployeeID', utf8_encode($sIdEmployee));
		// test id element
		$oSXmlElementRoot->addChild('TestID', utf8_encode($sIdTest));

		$oSXmlElementRoot->addChild('TestCode', utf8_encode($sTestCode));
		
		// get input xml into a string var for embedding into soap request
		$sXmlString = $oDomDoc->saveXML();

		//echo $sXmlString;
		//exit();

		$oSoapClient = new Pi_SoapClient($this->sUsername,$this->sPassword);
		$sResponse = $oSoapClient->send('getTestResults', $sXmlString);

		$oResponseHandler = new Pi_SoapResponse('getTestResults',$sResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sXmlString,$bSimpleTextReturn);

		$oSXmlReturn = $oResponseHandler->parse();

		return $oSXmlReturn;

		// TODO: check response for getTestResults and return an array

	}

	public function getReport($sIdEmployee, $sIdTest, $sTestCode){

		// new DOM document
		$oDomDoc = new DOMDocument('1.0', 'utf-8');
		$oDomDoc->xmlStandalone = true;
		$oDomDoc->formatOutput = true;

		// create the root element
		$oRoot = $oDomDoc->appendChild($oDomDoc->createElement('GetReport'));
		$oRoot->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation', 'GetReport.xsd');

		// get the SimpleXml object for the root element
		$oSXmlElementRoot = simplexml_import_dom($oDomDoc);
	
		// employee id element
		$oSXmlElementRoot->addChild('EmployeeID', utf8_encode($sIdEmployee));
		// test id element
		$oSXmlElementRoot->addChild('TestID', utf8_encode($sIdTest));
		// test code element
		$oSXmlElementRoot->addChild('TestCode', utf8_encode($sTestCode));

		// get input xml into a string var for embedding into soap request
		$sXmlString = $oDomDoc->saveXML();

		echo $sXmlString;
		exit();

		$oSoapClient = new Pi_SoapClient($this->sUsername,$this->sPassword);
		$sResponse = $oSoapClient->send('getReport', $sXmlString);

		//echo $sResponse;
		//exit();

		$oResponseHandler = new Pi_SoapResponse('getReport',$sResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sXmlString);
		$oSXmlReturn = $oResponseHandler->parse();

		$aReturnVars = array(
			'File' => $oSXmlReturn->File,
		);

		return $aReturnVars;

	}

	public function getAcpReport($aCandidateData,$aProjectData,$aTextFieldValues,$aCompetencesByGroup){

		// new DOM document
		$oDomDoc = new DOMDocument('1.0', 'utf-8');
		$oDomDoc->xmlStandalone = true;
		$oDomDoc->formatOutput = true;

		// create the root element
		$oRoot = $oDomDoc->appendChild($oDomDoc->createElement('GetAcpReport'));
		$oRoot->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation', 'GetAcpReport.xsd');

		// get the SimpleXml object for the root element
		$oSXmlElementRoot = simplexml_import_dom($oDomDoc);

		// employee id element
		$oSXmlElementRoot->addChild('EmployeeID',$aCandidateData["NETASSESSOR_EMPLOYEE_ID"]);
		
		// test id element
		$oSXmlElementRoot->addChild('TestIDs', utf8_encode($aCandidateData["TEST_IDS"]));

		$oSXmlElementTestInfo = $oSXmlElementRoot->addChild('Info')->addChild('TestInfo');
		$oSXmlElementTestInfo->addAttribute('type','acp');
		$oSXmlElementFreeFields = $oSXmlElementTestInfo->addChild('Freefields');

		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$this->convertToUtf8($aCandidateData["PROJECT"]));
		$oSXmlElementFreeField->addAttribute('id','1');
		$oSXmlElementFreeField->addAttribute('code','projectName');

		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$this->convertToUtf8($aCandidateData["CLIENT"]));
		$oSXmlElementFreeField->addAttribute('id','2');
		$oSXmlElementFreeField->addAttribute('code','orgCust');

		// main contact part
		$sMainContact = $aProjectData["MAIN_CONTACT_INITIALS"]." ".$aProjectData["MAIN_CONTACT_LASTNAME"];
		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$this->convertToUtf8($sMainContact));
		$oSXmlElementFreeField->addAttribute('id','3');
		$oSXmlElementFreeField->addAttribute('code','mainContact');

		// assessor part
		$sSalutation = ($aCandidateData["ASSESSOR_GENDER"] == "male")? "dhr" : "mevr";
		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$sSalutation);
		$oSXmlElementFreeField->addAttribute('id','4');
		$oSXmlElementFreeField->addAttribute('code','salutationAssessor');

		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$this->convertToUtf8($aCandidateData["ASSESSOR_INITIALS"]));
		$oSXmlElementFreeField->addAttribute('id','5');
		$oSXmlElementFreeField->addAttribute('code','intitialsAssessor');

		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$this->convertToUtf8($aCandidateData["ASSESSOR"]));
		$oSXmlElementFreeField->addAttribute('id','6');
		$oSXmlElementFreeField->addAttribute('code','nameAssessor');

		// assessment
		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',  Kwgl_View_Helper_GetDmyDate::getdmydate($aCandidateData["ASSESSMENT_DAY"]));
		$oSXmlElementFreeField->addAttribute('id','7');
		$oSXmlElementFreeField->addAttribute('code','dateAssessment');

		// instrument id's
		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',  $aProjectData["INSTRUMENT_IDS"]);
		$oSXmlElementFreeField->addAttribute('id','8');
		$oSXmlElementFreeField->addAttribute('code','instrIDs');

		// coordinator part
		$sCoordinator = $aProjectData["COORDINATOR_INITIALS"]." ".$aProjectData["COORDINATOR_LASTNAME"];
		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$this->convertToUtf8($sCoordinator));
		$oSXmlElementFreeField->addAttribute('id','9');
		$oSXmlElementFreeField->addAttribute('code','nameProjectCoord');

		$oSXmlElementFreeField = $oSXmlElementFreeFields->addChild('Freefield',$this->convertToUtf8($aProjectData["COORDINATOR_EMAIL"]));
		$oSXmlElementFreeField->addAttribute('id','10');
		$oSXmlElementFreeField->addAttribute('code','emailProjectCoord');

		// competences and group part
		$oSXmlElementData = $oSXmlElementRoot->addChild('Data');
		$oSXmlElementData->addAttribute('type','acp');
		$oSXmlElementData->addAttribute('typeLabel','select');

		$oSXmlElementCatArea = $oSXmlElementData->addChild('CatAreas')->addChild('CatArea');
		$oSXmlElementScores = $oSXmlElementCatArea->addChild('Scores');

		// default textfield part
		foreach($aTextFieldValues["default"] AS $iKey => $aTextFieldValue){

			$oSXmlElementScore = $oSXmlElementScores->addChild('Score');
			$i = $iKey + 1;
			$oSXmlElementScore->addAttribute('id',$i);

			$oSXmlElementRespondent = $oSXmlElementScore->addChild('RespondentGroup');
			$oSXmlElementRespondent->addAttribute('id','-1');

			$oSXmlElementRespondent->addChild('Value',$this->convertToUtf8($this->stripTags($aTextFieldValue['value'])));

			if($aTextFieldValue["tab_name"] == "conclusion & recommendation"){
				$sConclusion = ($aTextFieldValue["conclusion_type"] == "1")? "yes" : "no";
			}

		}

		if(is_array($aTextFieldValues["custom"])){

			// custom textfield part
			foreach($aTextFieldValues["custom"] AS $iKey => $aTextFieldValue){
				$sValue .= $this->stripTags($aTextFieldValue['value']);
			}

			$oSXmlElementScore = $oSXmlElementScores->addChild('Score');
			$oSXmlElementScore->addAttribute('id','3');

			$oSXmlElementRespondent = $oSXmlElementScore->addChild('RespondentGroup');
			$oSXmlElementRespondent->addAttribute('id','-1');

			$oSXmlElementRespondent->addChild('Value',$this->convertToUtf8($sValue));

		}

		// add conclusion node
		$oSXmlElementScore = $oSXmlElementScores->addChild('Score');
		$oSXmlElementScore->addAttribute('id','4');
		$oSXmlElementRespondent = $oSXmlElementScore->addChild('RespondentGroup');
		$oSXmlElementRespondent->addAttribute('id','-1');
		$oSXmlElementRespondent->addChild('Value',$sConclusion);

		$oSXmlElementCompAreas = $oSXmlElementData->addChild('CompAreas');

		foreach($aCompetencesByGroup AS $sCompetenceGroupId => $aCompetenceGroup){

			$oSXmlElementCompArea = $oSXmlElementCompAreas->addChild('CompArea');
			$oSXmlElementCompArea->addAttribute('id',$sCompetenceGroupId);

			// competence group name
			$oSXmlElementCompArea->addChild('Label',$this->convertToUtf8($aCompetenceGroup["name"]));
			$oSXmlElementCompArea->addChild('Description');

			// add the competences node
			$oSXmlElementCompetences = $oSXmlElementCompArea->addChild('Competences');

			// loop through all the competences
			foreach($aCompetenceGroup["competences"] AS $aCompetence){

				//Zend_Debug::dump($aCompetence);

				// create competence node plus name node
				$oSXmlElementCompetence = $oSXmlElementCompetences->addChild('Competence');
				$oSXmlElementCompetence->addAttribute('id',$aCompetence["id"]);
				$oSXmlElementCompetence->addChild('Label',$this->convertToUtf8($aCompetence["name"]));
				$oSXmlElementCompetence->addChild('Description');

				$oSXmlElementCompLevels = $oSXmlElementCompetence->addChild('CompLevels');
				$oSXmlElementCompLevel = $oSXmlElementCompLevels->addChild('CompLevel');
				$oSXmlElementCompLevel->addChild('Label',$this->convertToUtf8($aCompetence["name"]));
				$oSXmlElementCompLevel->addAttribute('id',$aCompetence['level_id']);
				$oSXmlElementCompLevel->addAttribute('code',$this->convertToUtf8($aCompetence['code']));

				$oSXmlElementScores = $oSXmlElementCompLevel->addChild('Scores');

				// average score part
				$oSXmlElementScore = $oSXmlElementScores->addChild('Score');
				$oSXmlElementScore->addAttribute('id','1');
				$oSXmlElementRespondent = $oSXmlElementScore->addChild('RespondentGroup');
				$oSXmlElementRespondent->addAttribute('id','-1');
				$oSXmlElementValue = $oSXmlElementRespondent->addChild('Value',$aCompetence["score"]);

				// sentences part
				$oSXmlElementScore = $oSXmlElementScores->addChild('Score');
				$oSXmlElementScore->addAttribute('id','2');
				$oSXmlElementRespondent = $oSXmlElementScore->addChild('RespondentGroup');
				$oSXmlElementRespondent->addAttribute('id','-1');

				$oSXmlElementValue = $oSXmlElementRespondent->addChild('Value',$this->convertToUtf8($this->stripTags($aCompetence["sentences"])));

				// characteristics part
				$oSXmlElementScore = $oSXmlElementScores->addChild('Score');
				$oSXmlElementScore->addAttribute('id','3');
				$oSXmlElementRespondent = $oSXmlElementScore->addChild('RespondentGroup');
				$oSXmlElementRespondent->addAttribute('id','-1');
				$oSXmlElementValue = $oSXmlElementRespondent->addChild('Value',$this->convertToUtf8($aCompetence["characteristics"]));

			}
		}

		// get input xml into a string var for embedding into soap request
		$sXmlString = $oDomDoc->saveXML();

		//echo $sXmlString;
		//exit();
		
		$oSoapClient = new Pi_SoapClient($this->sUsername,$this->sPassword);
		$sResponse = $oSoapClient->send('getAcpReport', $sXmlString);

		$oResponseHandler = new Pi_SoapResponse('getAcpReport',$sResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sXmlString);
		$oSXmlReturn = $oResponseHandler->parse();

		//Zend_Debug::dump($sResponse);
		//exit();

		$aReturnVars = array(
			'File' => $oSXmlReturn->File,
		);

		return $aReturnVars;

	}

	/**
	 * Creates or updates an employee on NetAssessor
	 * based on the provided candidate info
	 *
	 * @param array $aPersonData
	 * Possible array keys & values for parameter:
	 *	email
	 *	language		- 'nl' or 'us-en' depending on the instrument more or fewer languages will be available
	 *	gender			- string value as given by GENDER_ class constants
	 *	education_type	- integer value as given by EDUCATIONTYPE_ class constants
	 *  employee_id		- 'some string' if not given, a new user will be created, otherwise update
	 *	birth_date		- 'yyyy-mm-dd' format
	 *	given_name
	 *	middle_name
	 *	family_name
	 *
	 * @return array
	 */
	public function createOrUpdatePerson(array $aPersonData) {
		
		// utf-8 encode all input values
		foreach ($aPersonData as &$sValue) {
			$sValue = utf8_encode($sValue);
		}		

		$oDomDoc = new DOMDocument('1.0', 'utf-8');
		$oDomDoc->xmlStandalone = true;
		$oDomDoc->formatOutput = true;

		// create the root element
		$oRoot = $oDomDoc->appendChild($oDomDoc->createElement('Person'));
		$oRoot->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance','xsi:noNamespaceSchemaLocation', 'Person.xsd');

		// get the SimpleXml object for the root element
		$oSXmlElementRoot = simplexml_import_dom($oDomDoc);

		// email element
		$oSXmlElementRoot->addChild('EmailAddress', $aPersonData['email']);
		// language element
		$oSXmlElementRoot->addChild('Language', $aPersonData['language']);
		// gender element
		$oSXmlElementRoot->addChild('Gender', $aPersonData['gender']);
		// education type element
		$oSXmlElementRoot->addChild('EducationType', $aPersonData['education_type']);
		// when employee id is not set, a new person will be created
		if (array_key_exists('employee_id', $aPersonData)) {
			// employee id element
			$oSXmlElementRoot->addChild('EmployeeID', $aPersonData['employee_id']);
		}
        // extra generic fields
        for($i = 1; $i<7; $i++){
            if (array_key_exists('Freefield'.$i, $aPersonData)) {
                $oSXmlElementRoot->addChild('Freefield'.$i, $aPersonData['Freefield'.$i]);
            }
        }
		// birth date split into pieces
		$aBirthDatePieces = explode('-', $aPersonData['birth_date']);
		// birth date element
		$oSXmlElementBirthDate = $oSXmlElementRoot->addChild('BirthDate');
		// add dmy attribs to birth date element
		$oSXmlElementBirthDate->addAttribute('month', $aBirthDatePieces[1]);
		$oSXmlElementBirthDate->addAttribute('year', $aBirthDatePieces[0]);
		$oSXmlElementBirthDate->addAttribute('day', $aBirthDatePieces[2]);
		// person name child node
		$oSXmlElementNames = $oSXmlElementRoot->addChild('PersonName');
		// add different name nodes to person name element
		$oSXmlElementNames->addChild('GivenName', $aPersonData['given_name']);
		$oSXmlElementNames->addChild('MiddleName', $aPersonData['middle_name']);
		$oSXmlElementNames->addChild('FamilyName', $aPersonData['family_name']);

		// get input xml into a string var, only for debugging purpose
		$sXmlString = $oDomDoc->saveXML();
        
        //echo $sXmlString;
        //exit();

		$oSoapClient = new Pi_SoapClient($this->sUsername,$this->sPassword);
		$sResponse = $oSoapClient->send('createOrUpdatePerson', $sXmlString);

		$oResponseHandler = new Pi_SoapResponse('createOrUpdatePerson',$sResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sXmlString);
		$oSXmlReturn = $oResponseHandler->parse();

		$aReturnVars = array(
			'status'		=> (string)$oSXmlReturn->Status,
			'action_type'	=> (string)$oSXmlReturn->ActionType
		);

		return $aReturnVars;

	}

	public function saveAnswers($sIdEmployee, $iIdTest, $sTestCode, $aAnswers){

		// new DOM document
		$oDomDoc = new DOMDocument('1.0', 'utf-8');
		$oDomDoc->xmlStandalone = true;
		$oDomDoc->formatOutput = true;

		// create the root element
		$oRoot = $oDomDoc->appendChild($oDomDoc->createElement('SaveAnswers'));

		// get the SimpleXml object for the root element
		$oSXmlElementRoot = simplexml_import_dom($oDomDoc);

		// test id element
		$oSXmlElementRoot->addChild('AdministrationID', utf8_encode($iIdTest."00"));
		$oSXmlElementRoot->addChild('TestCode', utf8_encode($sTestCode));
		$oSXmlElementRoot->addChild('EmployeeID', utf8_encode($sIdEmployee));

		$oSXmlAnswersElement = $oSXmlElementRoot->addChild('Answers');

		foreach($aAnswers AS $aAnswer){

			$oSXmlAnswerElement = $oSXmlAnswersElement->addChild('Answer');
			$oSXmlAnswerElement->addChild('ItemID',$aAnswer['question_code']);
			$oSXmlAnswerElement->addChild('AlternativeID',$aAnswer['answer_code']);

		}

		// get input xml into a string var for embedding into soap request
		$sXmlString = $oDomDoc->saveXML();

		$oSoapClient = new Pi_SoapClient($this->sUsername,$this->sPassword);
		$sResponse = $oSoapClient->send('saveAnswers', $sXmlString);

		$oResponseHandler = new Pi_SoapResponse("getTestStatus",$sResponse,array("username" => $this->sUsername,"password" => $this->sPassword),$sXmlString);
		$oSXmlReturn = $oResponseHandler->parse();

		$aReturnVars = array(
			'status' => (integer)$oSXmlReturn->Status
		);

		return $aReturnVars;

	}

	public static function convertToUtf8($sString){

		//iconv_set_encoding("internal_encoding", "UTF-8");
		//iconv_set_encoding("output_encoding", "UTF-8");
		//var_dump(iconv_get_encoding('all'));

		$sString = html_entity_decode($sString);
		$sString = iconv('ISO-8859-1', 'UTF-8', $sString);

		return $sString;

	}

	public static function stripTags($sString){

		$sString = strip_tags(html_entity_decode(stripslashes(nl2br($sString)),ENT_NOQUOTES));
		return $sString;

	}
	
}

?>
