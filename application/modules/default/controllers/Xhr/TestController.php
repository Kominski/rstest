<?php

class Xhr_TestController extends Kwgl_Controller_Action {

	public function answerAction() {

		$aData = array();
		$aData['id_participant'] = $this->_aParameter['id_participant'];
		$aData['id_question'] = $this->_aParameter['id_question'];
		$aData['answer'] = $this->_aParameter['answer'];

		$oDaoParticipantAnswer = Kwgl_Db_Table::factory('Participant_Answer');

		$bAlreadyExist = false;
		$bAlreadyExist = $oDaoParticipantAnswer->fetchRow(array('id_participant = ?' => $aData['id_participant'], 'id_question = ?' => $aData['id_question']));

		if(!$bAlreadyExist) {
			$aResponse = $oDaoParticipantAnswer->insert($aData);
		}

		$oDaoQuestion = Kwgl_Db_Table::factory("Question");
		$oQuestionDetail = $oDaoQuestion->fetchDetail(null, array('id > ?' => $aData['id_question']));

		$aQuestionDetail = false;

		if (isset($oQuestionDetail)) {
			$aQuestionDetail = $oQuestionDetail->toArray();
		} else {
			$this->_generateResult($aData['id_participant']);
		}

		$this->setAjaxResponse($aQuestionDetail);

//		Zend_Debug::dump($aData);
//		$this->setAjaxResponse($bAlreadyExist);

	}

//	public function questionAction() {
//
//		$sId = $this->_aParameter['id'];
//
//		$oDaoQuestion = Kwgl_Db_Table::factory("Question");
//		$oQuestionDetail = $oDaoQuestion->fetchDetail(null, array('id >= ?' => $sId));
//
//		$aQuestionDetail = false;
//
//		if (isset($oQuestionDetail)) {
//			$aQuestionDetail = $oQuestionDetail->toArray();
//		} else {
//			$this->_generateResult();
//		}
//
//		$this->setAjaxResponse($aQuestionDetail);
//
//	}

	/**
	 * method that execute when user submitted the answer to the last question
	 *
	 */
	private function _generateResult($sIdParticipant) {

		$oModelParticipant = new Model_Participant($sIdParticipant);
		$oModelParticipant->saveAnswers();

	}

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
		
		$this->setAjaxResponse('Success');
	}
}
