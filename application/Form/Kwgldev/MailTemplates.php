<?php
/**
 *
 */
class Form_Kwgldev_MailTemplates extends Form_Kwgldev_Base {

	public function init () {

//		$this->_bKwglRemoveErrorDecorator = false;

		$oName = new Zend_Form_Element_Text('textName');
		$oName->setLabel('Name')
			->setAttrib('class', 'span4');

		$oSubject = new Zend_Form_Element_Text('textSubject');
		$oSubject->setLabel('Subject')
			->setAttrib('class', 'span4');

		$oBody = new Zend_Form_Element_Textarea('textBody');
		$oBody->setLabel('Body')
			->setAttrib('class', 'span8');

		$oSubmit = new Zend_Form_Element_Submit('submitSubmit');
		$oSubmit->setDecorators(array('ViewHelper'));

		switch ($this->_sContext) {
			case self::CONTEXT_CREATE:
				$oName->setRequired()
					->addValidator('Db_NoRecordExists', true, array('table' => Kwgl_Db_Table::name()->Mail_Template, 'field' => 'name'));
				$oSubject->setRequired();
				$oBody->setRequired();
				$oSubmit->setLabel('Create Mail Template')
					->setAttrib('class', 'btn btn-success');
				$this->addElements(array($oName, $oSubject, $oBody, $oSubmit));
				break;
			case self::CONTEXT_UPDATE:
				$aClause = array('field' => 'id', 'value' => $this->_aParameters['template_id']);
				$oName->setRequired()
					->addValidator('Db_NoRecordExists', true, array('table' => Kwgl_Db_Table::name()->Mail_Template, 'field' => 'name', 'exclude' => $aClause));
				$oSubject->setRequired();
				$oBody->setRequired();
				$oSubmit->setLabel('Update Mail Template')
					->setAttrib('class', 'btn btn-success');
				$this->addElements(array($oName, $oSubject, $oBody, $oSubmit));
				break;
			case self::CONTEXT_DELETE:
				$oName->setAttrib('readonly', true);
				$oSubject->setAttrib('readonly', true);
				$oBody->setAttrib('readonly', true);
				$oSubmit->setLabel('Delete Mail Template')
					->setAttrib('class', 'btn btn-danger');
				$this->addElements(array($oName, $oSubject, $oBody, $oSubmit));
				break;

		}

		$this->setDecorators(array(array('ViewScript', array('viewScript' => $this->_sViewScript))));

	}

}