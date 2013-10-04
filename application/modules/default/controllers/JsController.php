<?php

class JsController extends Kwgl_Controller_Action{

	public function indexAction(){

		$this->view->headLink()->appendStylesheet('/min/g=cssMain');
		
		$this->view->headScript()->appendFile('/min/g=jsCore');
		$this->view->headScript()->appendFile('/min/g=jsExamples');

		$oForm = new Form_Test();

		$this->view->form = $oForm;


    }

}
