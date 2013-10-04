<?php
/**
 * Description of SoapTesting
 *
 * @author Darshan
 */

require_once 'Netassessor.php';

class Pi_SoapTesting {

	private $oNetAssessor;

	public function  __construct() {

		$this->oNetAssessor = new Pi_Netassessor();

	}

	public function createTest() {
	 
		$aReturnVars = $this->oNetAssessor->createTest('111', 'p_md_20080104_3_');
		
		echo 'new test:';
		var_dump($aReturnVars);	 

	}

	public function createOrUpdatePerson() {

		$aProfileInfo = array(
			'email' => 'soaptest@picompany.nl',
			'language' => 'nl',
			'gender' => 'U',
			'education_type' => '1',
			'employee_id' => 'p_md_20080104_3_',
			'birth_date' => '1965-02-02',
			'given_name' => 'Martin234567890123',
			'middle_name' => 'M',
			'family_name' => 'SOAP_Persoontest_20091008'
		);		

		$aReturnVars = $this->oNetAssessor->createOrUpdatePerson($aProfileInfo);

		echo 'create or update person:';
		var_dump($aReturnVars);

	}

	public function getInstrumentProfileIds() {

		$aReturnVars = $this->oNetAssessor->getInstrumentProfileIdsAndLabels('111', 'p_md_20080104_3_');

		echo 'instrument profile ids and labels:';
		var_dump($aReturnVars);

	}

	public function getCompetenceProfileIdsAndLabels() {

		$aReturnVars = $this->oNetAssessor->getCompetenceProfileIdsAndLabels('111', 'p_md_20080104_3_');

		echo 'competence profile ids and labels:';
		var_dump($aReturnVars);

	}

	public function getInstrumentNormIdsAndLabels() {

		$aReturnVars = $this->oNetAssessor->getInstrumentNormIdsAndLabels('111', 'p_md_20080104_3_');

		echo 'instrument norm ids and labels:';
		var_dump($aReturnVars);

	}

	public function getTestStatus() {

		$iTestId = 603145;
		$sTestCode = '85ts7swmnx1aq0eytchgxgvqe2wi1';

		$aReturnVars = $this->oNetAssessor->getTestStatus('111', $iTestId, $sTestCode);

		echo 'test status:';
		var_dump($aReturnVars);

	}

	public function getTestResults() {

		$sTestCode = 'ghts7swmax1as7wttchgxgvlr2w9r';
		
		$aReturnVars = $this->oNetAssessor->getTestResults('111', 'p_md_20080104_3_', $sTestCode);

		echo 'test status:';
		var_dump($aReturnVars);

	}

}

$oTestingClass = new Pi_SoapTesting();

//$oTestingClass->createTest();
//$oTestingClass->createOrUpdatePerson();
//$oTestingClass->getInstrumentProfileIds();
//$oTestingClass->getCompetenceProfileIdsAndLabels();
//$oTestingClass->getInstrumentNormIdsAndLabels();
$oTestingClass->getTestStatus();

?>
