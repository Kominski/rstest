<?php
/**
 *
 */
class IndexController extends Kwgl_Controller_Action {

    public function init() {
        parent::init();
        $this->view->headLink()->appendStylesheet('/min/?g=cssBase');

		$this->view->headScript()->appendFile('/min/?g=jsCore');
		
    }
	public function indexAction () {

	}

}