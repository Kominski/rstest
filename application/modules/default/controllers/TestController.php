<?php

class TestController extends Kwgl_Controller_Action{

	public function indexAction(){

		$this->view->headScript()->appendFile('/min/g=jsCore');
		$this->view->headScript()->appendFile('/min/f=//js/library/kwgl/validate.js');

		//Zend_Debug::dump(Kwgl_Db_Table::name()->System_Role);

		$oForma = new Form_Test();

		$this->view->form = $oForma;

		$oFormSumting = new Form_Sumting();

		$this->view->formSumting = $oFormSumting;

    }

	public function xhrAction(){

		$oForma = new Form_Test();
		if ($this->_oRequest->isPost() && in_array('submitSubmit', $this->_aParameterKey)) {

//			if($oForma->isValid($this->_oRequest->getPost())){
//
//			}
			//set response
			$this->setAjaxResponse($oForma);

		}

		$oFormSumting = new Form_Sumting();
		if ($this->_oRequest->isPost() && in_array('submitSumtingSubmit', $this->_aParameterKey)) {

//			if($oFormSumting->isValid($this->_oRequest->getPost())){
//
//			}
			//set response
			$this->setAjaxResponse($oFormSumting);

		}
	}

	public function netassessorAction(){

		$oNetAssessor = new Pi_Netassessor();

		$sNaEmployeeId = mktime();

		$sLanguage = 'nl';

		$aPersonData['email'] = 'boudewijn00@gmail.com';
		$aPersonData['language'] = 'nl';
		$aPersonData['gender'] = 'M';
		$aPersonData['education_type'] = '1';
		$aPersonData['birth_date'] = date("Y-m-d", strtotime('02-09-1979'));
		$aPersonData['given_name'] = 'boudewijn';
		$aPersonData['middle_name'] = substr('boudewijn', 0, 1);
		$aPersonData['family_name'] = 'overvliet';
		$aPersonData['employee_id'] = $sNaEmployeeId;

		if($oNetAssessor->createOrUpdatePerson($aPersonData)){

			$oDaoRegistration = Kwgl_Db_Table::factory("Registration");

			$sRegistrationId = $oDaoRegistration->insert(array('id_pi_company' => $sNaEmployeeId, 'id_participant' => '1'));
			echo $sRegistrationId;

		} else {
			echo "error occured";
		}

		die();

	}

	public function saveanswersAction(){

		$sIdParticipant = '1';
		$oModelParticipant = new Model_Participant($sIdParticipant);
		$oModelParticipant->saveAnswers();

		die();

	}

	public function createtestAction(){

		$oNetAssessor = new Pi_Netassessor();

		$sNaEmployeeId = '1339585449';
		$aTestData = $oNetAssessor->createTest('111',$sNaEmployeeId,'http://var/test/index/type/real',null,null,'48790');

		Zend_Debug::dump($aTestData);

		$aRegistrationData['id_test'] = $aTestData['test_id'];
		$aRegistrationData['code_test'] = $aTestData['test_code'];
		$aRegistrationData['id_pi_company'] = $sNaEmployeeId;
		$aRegistrationData['id_participant'] = '1';

		$oDaoRegistration = Kwgl_Db_Table::factory("Registration");
		$oDaoRegistration->insert($aRegistrationData);

		die();

	}

	public function importqacodeAction() {

		$oDaoQuestion = Kwgl_Db_Table::factory("Question");
		$oDaoQuestionAnswerCode = Kwgl_Db_Table::factory("Question_Answer_Code");

		$aAnswerTypes = array('no','little','sometimes','often','yes');

		if (($oHandle = fopen("http://" . $_SERVER['HTTP_HOST'] . "/qa_codes_final.csv", "r")) !== FALSE) {

			$iAnswer = null;
			$iOrder = null;
			$iQuestionCode = null;
			$iAnswerCode = null;
			$iPreviousQuestionCode = null;

			while (($aData = fgetcsv($oHandle)) !== FALSE) {

				foreach ($aData as $iIndex => $aItem) {

					switch ($iIndex % 8) {
						case 0:
							$sQuestionName = trim($aItem);
							break;
						case 2:
							$iQuestionCode = trim($aItem);
							break;
						case 4:
							$iAnswerCode = $aItem;
							break;
						case 6:
							$iAnswer = $aItem - 1;
							break;
						case 7:
							$iOrder = $aItem;

							if(is_numeric($iOrder)) {

								if($iQuestionCode != $iPreviousQuestionCode || !$iPreviousQuestionCode) {
									$oDaoQuestion->insert(array('code' => $iQuestionCode, 'order' => $iOrder, 'question' => $sQuestionName));
//									Zend_Debug::dump(array('code' => $iQuestionCode, 'order' => $iOrder, 'question' => $sQuestionName));
								}

								$oDaoQuestionAnswerCode->insert(array('id_question' => $iOrder, 'answer' => $aAnswerTypes[$iAnswer], 'code' => $iAnswerCode));
//								Zend_Debug::dump(array('id_question' => $iOrder, 'answer' => $aAnswerTypes[$iAnswer], 'code' => $iAnswerCode));

								$iPreviousQuestionCode = $iQuestionCode;

								echo $iOrder." ".$iQuestionCode." ".$iAnswerCode." ".$iAnswer."<br/>";
							}

							break;

						default:
							break;
					}
				}
			}
			fclose($oHandle);
		}
		die();
	}
}
