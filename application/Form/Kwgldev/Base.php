<?php
/**
 *
 */
class Form_Kwgldev_Base extends Kwgl_Form {

	protected $_bKwglRemoveErrorDecorator = true;

	public function __construct ($sContext = null, $aAttributes = null, $aParameters = null){
		parent::__construct($sContext, $aAttributes, $aParameters);

		$this->_removeErrorDecorator();

	}

	protected function _removeErrorDecorator() {

		if (!$this->_bKwglRemoveErrorDecorator) {
			return;
		}

		$aFormElements = $this->getElements();

		foreach($aFormElements as $oElement) {
			$oElement->removeDecorator('Errors');
		}
	}

}