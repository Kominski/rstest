<?php

class Model_Participant {

	public $sIdParticipant = null;
	public $sIdNaEmployee = null;
	public $sIdTest = null;
	public $sCodeTest = null;

	public function __construct($sIdParticipant){


		$this->sIdParticipant = $sIdParticipant;

		$oDaoRegistration = Kwgl_Db_Table::factory("Registration");
		$aRegistrationData = $oDaoRegistration->fetchDetail(array('id_pi_company','id_test','code_test'),array('id_participant = ?' => $this->sIdParticipant));

		$this->sIdNaEmployee = $aRegistrationData['id_pi_company'];
		$this->sIdTest = $aRegistrationData['id_test'];
		$this->sCodeTest = $aRegistrationData['code_test'];

	}

	public function saveAnswers(){

		$aAnswers = array();

		$oDaoParticipantAnswer = Kwgl_Db_Table::factory("Participant_Answer");

		$aQuestionAnswerCodes = $oDaoParticipantAnswer->getQuestionAnswerCodes($this->sIdParticipant);
		//Zend_Debug::dump($aQuestionAnswerCodes);

		$oNetAssessor = new Pi_Netassessor();

		$sAdministrationId = $this->sIdTest;
		$oNetAssessor->saveAnswers($this->sIdNaEmployee,$sAdministrationId,$this->sCodeTest,$aQuestionAnswerCodes);

	}

	

}

?>
