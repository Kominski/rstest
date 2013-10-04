<?php

class Dao_Participant_Answer extends Kwgl_Db_Table {

	public function getQuestionAnswerCodes($sIdParticipant){

		$oDb = Zend_Registry::get(DB);

		$oQuery = $oDb->query("SELECT q.code AS question_code, pa.id_question AS qid, pa.answer AS ans,
							  (SELECT qac.code FROM question_answer_code qac WHERE id_question = qid AND answer = ans) AS answer_code
							  FROM participant_answer pa JOIN question q ON pa.id_question = q.id WHERE pa.id_participant = $sIdParticipant");

		$aQuestionAnswerCodes = $oQuery->fetchAll();

		return $aQuestionAnswerCodes;

	}

}

?>
