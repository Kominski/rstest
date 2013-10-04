<?php

class Xhr_IndexController extends Kwgl_Controller_Action {

	public function indexAction() {


	}

	public function ajaxexampleAction() {

		$aResponse = array();
		//delay the response
		sleep(1);
		//do something
		$aResponse['data'] = uniqid();

		//send request back to front
		$this->setAjaxResponse($aResponse);

	}

	public function paginationAction() {

		//create select object to pass to pagination function (should be done in module level)
		$oSelect = new Zend_Db_Select(Kwgl_Db_Table::getDefaultAdapter());
		$oSelect->from(Kwgl_Db_Table::name()->System_Account);
		sleep(2);
		//execute pagination
		$this->buildPagination($oSelect);

	}

	public function ajaxformAction() {

		$oForm = new Form_Test();
		//check if post
		if($this->_request->isPost()){
			//if form is valid
			if($oForm->isValid($this->_request->getPost())){

			}
		}

		//pass form object to ajax response method to sent response to front end
		$this->setAjaxResponse($oForm);

	}

	public function registerAction() {

		$oDaoParticipant = Kwgl_Db_Table::factory("Participant");
		$oDaoRegistration = Kwgl_Db_Table::factory("Registration");
		$aParticipantDetail = array();

		if ($this->_oRequest->isPost()) {

			$aFormValues = $this->_oRequest->getPost();
			$aErrors = array();
			$aResponse = array();
			$bValid = true;

//			if ($oDaoParticipant->fetchDetail(null, array('email = ?' => $aFormValues['textEmail']))) {
//				$aErrors['textEmail'] = 'already-exist';
//				$bValid = false;
//			}

			if ($bValid) {

				$aParticipantDetail['name'] = $aFormValues['textName'];
				$aParticipantDetail['age'] = $aFormValues['textAge'];
				$aParticipantDetail['email'] = $aFormValues['textEmail'];
				$aParticipantDetail['gender'] = $aFormValues['radioGender'];
//				$aParticipantDetail['education'] = $aFormValues['textEducation'];
				$aParticipantDetail['highest_training'] = $aFormValues['selectHighestTraining'];
				$aParticipantDetail['last_training'] = $aFormValues['selectLastTraining'];
				$aParticipantDetail['job_category'] = $aFormValues['selectJobCategory'];
				$aParticipantDetail['experience'] = $aFormValues['textYearsOfExperience'];
				$aParticipantDetail['leading'] = $aFormValues['radioLeading'];
				$aParticipantDetail['employees'] = $aFormValues['textNumberOfEmployees'];

				$sIdParticipant = $oDaoParticipant->insert($aParticipantDetail);
				$oNetAssessor = new Pi_Netassessor();
				$sIdNaEmployee = mktime();

				$sLanguage = 'nl';

				list($sFirstName,$sLastName) = explode(" ",$aFormValues["textName"] . " ");

				$aPersonData['email'] = $aFormValues['textEmail'];
				$aPersonData['language'] = $sLanguage;
				$aPersonData['gender'] = ($aFormValues['radioGender'] == 'male')?'M':'F';
				$aPersonData['education_type'] = '1';
				$aPersonData['birth_date'] = "1970-01-01";
				$aPersonData['given_name'] = $sFirstName;
				$aPersonData['middle_name'] = substr($sFirstName, 0, 1);
				$aPersonData['family_name'] = ($sLastName)?$sLastName:$sFirstName;
				$aPersonData['employee_id'] = $sIdNaEmployee;
                $aPersonData['Freefield1'] = $aFormValues['selectHighestTraining'];
                $aPersonData['Freefield2'] = $aFormValues['selectLastTraining'];
                $aPersonData['Freefield3'] = $aFormValues['selectJobCategory'];
                $aPersonData['Freefield4'] = $aFormValues['textYearsOfExperience'];
                $aPersonData['Freefield5'] = $aFormValues['radioLeading'];
                $aPersonData['Freefield6'] = $aFormValues['textNumberOfEmployees'];

				if($oNetAssessor->createOrUpdatePerson($aPersonData)){

					$aTestData = $oNetAssessor->createTest('491',$sIdNaEmployee,'http://randstadtopsporter.kominski.net');

					if(!is_null($aTestData)){

						$aRegistrationData['id_pi_company'] = $sIdNaEmployee;
						$aRegistrationData['id_participant'] = $sIdParticipant;
						$aRegistrationData['id_test'] = $aTestData['test_id'];
						$aRegistrationData['code_test'] = $aTestData['test_code'];
						$oDaoRegistration->insert($aRegistrationData);

						$aResponse['valid'] = $bValid;
						$aResponse['id_participant'] = $sIdParticipant;
						$this->setAjaxResponse($aResponse);

					} else {
						$this->setAjaxResponse(array('createTest' => 'failed'));
					}

				} else {
					$this->setAjaxResponse(array('createOrUpdatePerson' => 'failed'));
				}

			} else{
				$this->setAjaxResponse($aErrors);
			}
		}
	}

//	public function answerAction() {
//
//		$aData = array();
//		$aData['id_participant'] = $this->_aParameter['id_participant'];
//		$aData['id_question'] = $this->_aParameter['id_question'];
//		$aData['answer'] = $this->_aParameter['answer'];
//
//		$oDaoParticipantAnswer = Kwgl_Db_Table::factory('Participant_Answer');
//
//		$bAlreadyExist = false;
//		$bAlreadyExist = $oDaoParticipantAnswer->fetchRow(array('id_participant = ?' => $aData['id_participant'], 'id_question = ?' => $aData['id_question']));
//
//		if(!$bAlreadyExist) {
//			$oDaoParticipantAnswer->insert($aData);
//		}
//
//		$oDaoQuestion = Kwgl_Db_Table::factory("Question");
//		$oQuestionDetail = $oDaoQuestion->fetchDetail(null, array('id > ?' => $aData['id_question']));
//
//		$aQuestionDetail = false;
//
//		if (isset($oQuestionDetail)) {
//			$aQuestionDetail = $oQuestionDetail->toArray();
//		} else {
//			$this->_generateResult($aData['id_participant']);
//		}
//
////		sleep(1);
//
//		$this->setAjaxResponse($aQuestionDetail);
//	}

	public function saveanswersAction() {

		$aData = array();
		$aAnswers = array();

		$aData['id_participant'] = $this->_aParameter['id_participant'];
		$aData['answers'] = $this->_aParameter['answers'];

		$aAnswers = $aData['answers'];

		$oDaoParticipantAnswer = Kwgl_Db_Table::factory('Participant_Answer');

		foreach ($aAnswers as $aAnswer) {
			$aAnswer['id_participant'] = $aData['id_participant'];
			$bAlreadyExist = false;
			$bAlreadyExist = $oDaoParticipantAnswer->fetchRow(array('id_participant = ?' => $aAnswer['id_participant'], 'id_question = ?' => $aAnswer['id_question']));

			if(!$bAlreadyExist) {
				$oDaoParticipantAnswer->insert($aAnswer);
			}
		}

		$this->_generateResult($aData['id_participant']);
		$this->setAjaxResponse('Success');

	}

	/**
	 * method that execute when user submitted the answer to the last question
	 *
	 */
	private function _generateResult($sIdParticipant) {

		$oModelParticipant = new Model_Participant($sIdParticipant);

		try{
			$oModelParticipant->saveAnswers();
		} catch (Zend_Db_Adapter_Exception $oException) {
			throw new Exception('Error updating candidate', null, $oException);
		}

	}
}
