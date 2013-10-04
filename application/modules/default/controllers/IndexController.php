<?php

class IndexController extends Kwgl_Controller_Action {

	public function indexAction() {
		$this->view->headMeta()->appendName('viewport', 'width = device-width, initial-scale=1, maximum-scale=1');
		$this->view->headMeta()->appendName('apple-mobile-web-app-capable', 'yes');


		$this->view->headLink()->appendStylesheet('/min?g=cssMain');

//		$this->view->headLink()->appendStylesheet('/css/library/jqmobi/jq.ui.css');
//		$this->view->headLink()->appendStylesheet('/css/default/jquery.ui.override.css');
//		$this->view->headLink()->appendStylesheet('/css/default/ui_override.css');
//		$this->view->headLink()->appendStylesheet('/css/default/default.css');

		$this->view->headScript()->appendFile('/min?g=jsMain');

//		$this->view->headScript()->appendFile('/js/library/jqmobi/jq.mobi.dist.js');
//        $this->view->headScript()->appendFile('/js/library/jqmobi/jq.mobi.init.js');
//
//		$this->view->headScript()->appendFile('/js/library/jquery-1.7.2.min.js');
//		$this->view->headScript()->appendFile('/js/library/jquery-ui-1.8.20.custom.min.js');
//		$this->view->headScript()->appendFile('/js/library/jquery.ui.touch-punch.min.js');
//
//		$this->view->headScript()->appendFile('/js/default/default.js');


		$oDaoQuestion = Kwgl_Db_Table::factory("Question");
		$aQuestionDetail = $oDaoQuestion->fetchDetail(null, array('id >= ?' => '1'))->toArray();
		$aQuestionListing = $oDaoQuestion->fetchList(array('id', 'question'), null, array('order'))->toArray();


//		Zend_Debug::dump(json_encode($aQuestionListing)); die();
		$iQuestionCount = count($oDaoQuestion->fetchAll()->toArray());

		$oFormRegister = new Form_Register();

		$aContent = array();

		$aContent['question_detail'] = $aQuestionDetail;
		$aContent['question_listing'] = $aQuestionListing;
		$aContent['form_register'] = $oFormRegister;
		$aContent['question_count'] = $iQuestionCount;

		$this->view->aContent = $aContent;
	}


//	public function indexAction() {
//
//
////		xdebug_start_trace('/users/boudewijnovervliet/Sites/zendframework/trace/trace');
////
////		function fac($x){
////			if (0 == $x) return 1;
////			return $x * fac($x - 1);
////		}
////
////		print fac(7);
////
////		xdebug_stop_trace();
////		$conf =  Zend_Registry::get(DATABASE);
//		//Zend_Debug::dump($conf->toArray());
////		Kwgl_Db_Table::setDbConfig($conf);
//		//$o = Kwgl_Db_Table::factory('Candidate');
//
//		$bStatus = Kwgl_Authenticate::login('jayawiperera@gmail.com', 'poofpoof', false);
//		Zend_Debug::dump($bStatus, 'Login Status');
//
//		$aIdentity = Zend_Auth::getInstance()->getIdentity();
//		Zend_Debug::dump($aIdentity, 'Identity');
//
//		Kwgl_Benchmark::setMarker('Starting ACL Check');
//		$bStatus = Kwgl_Acl::allowed('member', 'default');
//		Kwgl_Benchmark::setMarker('Finishing ACL Check');
//
//		//Zend_Debug::dump($bStatus, 'Permission Granted');
//		//Zend_Debug::dump($o->fetchAll());
//		//Zend_Debug::dump(Kwgl_User::get(), 'User Details');
//		//Zend_Debug::dump(Kwgl_User::getRole('name'), 'User Role Details');
//		//$oTest = Kwgl_Db_Table::factory("System_Role");
//		//$aList = $oTest->fetchAll();
//
//		$oFormTest = new Form_Test();
//
//		if (in_array('submitSubmit', $this->_aParameterKey)) {
//			if ($this->_oRequest->isPost()) {
//				$bValid = $oFormTest->isValid($this->_oRequest->getPost());
//				if (!$bValid) {
//					$aErrors = $oFormTest->getErrors();
//					Zend_Debug::dump($aErrors, 'Errors');
//					$aMessages = $oFormTest->getMessages();
//					Zend_Debug::dump($aMessages, 'Messages');
//					$aErrorMessages = $oFormTest->getErrorMessages();
//					Zend_Debug::dump($aErrorMessages, 'Error Messages');
//				}
//			}
//		}
//
//		$oFormSumting = new Form_Sumting();
//
//		if (in_array('submitSumtingSubmit', $this->_aParameterKey)) {
//			if ($this->_oRequest->isPost()) {
//				$bValid = $oFormSumting->isValid($this->_oRequest->getPost());
//				if (!$bValid) {
//					$aErrors = $oFormSumting->getErrors();
//					Zend_Debug::dump($aErrors, 'Errors in Sumting');
//					$aMessages = $oFormTest->getMessages();
//					Zend_Debug::dump($aMessages, 'Messages');
//					$aErrorMessages = $oFormSumting->getErrorMessages();
//					Zend_Debug::dump($aErrorMessages, 'Error Messages in Sumting');
//				}
//			}
//		}
//
//
//		$this->view->form = $oFormTest;
//
//		$this->view->sumForm = $oFormSumting;
//	}

	public function testAction() {
		try {
			//new Candidate('Dao_Candidate');

			$o = Kwgl_Db_Table::factory('System_Account');
			/* @var $o Dao_System_Account  */
			$accountsRowset = $o->find(1);
			$user1234 = $accountsRowset->current();

			$oEmp = Kwgl_Db_Table::factory('System_Role');/* @var $oEmp Dao_System_Role */
			Kwgl_Db_Table::name()->System_Account;
			$oEmpRowset = $oEmp->fetchAll();
			$oCurrentEmp = $oEmpRowset->current();
//
//			Zend_Debug::dump($user1234->findDependentRowset('Candidate_Employer')->toArray(), 'findDependentRowset = ');
//			Zend_Debug::dump($user1234->findCandidate_Employer()->toArray(), 'find* = ');
//
//			Zend_Debug::dump($oCurrentEmp->findParentRow('Candidate')->toArray(), 'findParentRow = ');
//			Zend_Debug::dump($oCurrentEmp->findParentCandidate()->toArray(), 'findParent* = ');

			Zend_Debug::dump($oEmp->fetchList()->toArray());
			Zend_Debug::dump($oEmp->fetchDetail(null, array('id = ?' => 1))->toArray());
			Zend_Debug::dump($oEmp->fetchPairs(array('id', 'name')));
		} catch (Exception $e) {
			echo $e->getMessage() . '<br />';
			echo $e->getTraceAsString() . '<br />';
		}
		exit;
	}

	public function formsAction() {

		$oLoginForm = new Form_AdminLogin();

		if ($this->_request->isPost()) {
			if ($oLoginForm->isValid($this->_request->getPost())) {
				$aFormValues = $oLoginForm->getValues();
				Zend_Debug::Dump($aFormValues);
			}
		}

		$this->view->oLoginForm = $oLoginForm;

		//echo Kwgl_Language::translate('txt_test');
	}

	public function sampleAction() {
		$this->view->headLink()->appendStylesheet('/min/?g=cssBase');
		$this->view->headLink()->appendStylesheet('/css/default/style.css');
	}

	public function translateAction() {

		echo 'keyword txt_test translated from controller: ' . Kwgl_Language::translate('txt_test');
	}

}
