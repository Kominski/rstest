<?php
/**
 * Facilitates sending Emails
 *
 * @author Jayawi Perera <jayawiperera@gmail.com>
 * @category PHP-Kwgl
 * @package Kwgl_Mail
 * @uses Zend_Mail
 */
class Kwgl_Mail extends Zend_Mail {

	/**
	 * Name of the Template to fetch from the relevant Database Table
	 *
	 * @var string
	 */
	protected $_sTemplateName = null;

	/**
	 * Indicates if the Template specified exists or not
	 *
	 * @var boolean
	 */
	protected $_bTemplateExists = false;

	/**
	 * The DAO that provides access to the relevant Database Table (if configured)
	 *
	 * @var null|Kwgl_Db_Table
	 */
	protected $_oDao = null;

	/**
	 * Subject of the email
	 *
	 * @var string
	 */
	protected $_sTemplateSubject = '';

	/**
	 * Indicates if the subject has been set, either through the template or through manual means
	 *
	 * @var boolean
	 */
	protected $_bSubjectSet = false;

	/**
	 * Body of the email
	 *
	 * @var string
	 */
	protected $_sTemplateBody = '';

	/**
	 * Indicates if the body has been set, either through the template or through manual means
	 *
	 * @var boolean
	 */
	protected $_bBodySet = false;

	/**
	 * ?
	 *
	 * @var array
	 */
	protected $_aTemplateReplace = array();


	/**
	 * Instantiates the Kwgl_Mail Class. If a Template is specified, the template content is retrieved and applied.
	 *
	 * Using Kwgl_Mail with a Template stored in the Database
	 * Eg:
	 * <code>
	 * <?php
	 * $oMail = new Kwgl_Mail('newRegistration');
	 * $aReplace = array(
	 *		'name' => $sName,
	 * );
	 * $oMail->fillTemplate($aReplace);
	 * $oMail->send();
	 * ?>
	 * </code>
	 *
	 * Using Kwgl_Mail without a Template
	 * Eg:
	 * <code>
	 * <?php
	 * $oMail = new Kwgl_Mail();
	 * $oMail->setSubject('Greetings from Kwgl');
	 * $oMail->setBodyHtml('Hello,<br>This is an HTML Mail.<br>Cheers~');
	 * $oMail->fillTemplate(array());
	 * $oMail->send();
	 * ?>
	 * </code>
	 *
	 * @param string $sTemplateName
	 * @param string $sCharacterSet
	 */
	public function __construct ($sTemplateName = null, $sCharacterSet = 'utf-8') {

		// Create a Zend_Mail Object
		parent::__construct($sCharacterSet);

		// Assign Mail Template Name if provided
		$this->_sTemplateName = $sTemplateName;

		// Instantiate Mail Template Storage if provided
		$sDaoClass = Kwgl_Config::get(array('mail', 'template', 'class'));
		if (!is_null($sDaoClass)) {
			$this->_oDao = Kwgl_Db_Table::factory($sDaoClass);
		}

		// Attempt to fetch Template
		$this->setFromTemplate();
	}

	/**
	 * Clears Recipients when the Object has been expressly Cloned
	 */
	public function __clone () {
		// Removes To, Cc & Bcc Receipients
		$this->clearRecipients();
	}

	/**
	 *
	 */
	protected function setFromTemplate () {
		if (!is_null($this->_sTemplateName) && !is_null($this->_oDao)) {
			$aTemplate = $this->_oDao->fetchRow(array('name = ?' => $this->_sTemplateName));
			if (empty($aTemplate)) {
				// Mail Template not found, Mail Content cannot be set
			} else {
				$this->_sTemplateSubject = $aTemplate['subject'];
				$this->_sTemplateBody = $aTemplate['body'];
				$this->_bTemplateExists = true;
			}
		} else {
			// Mail Content cannot be set from Template as either Template Name or Template DAO Class was not provided
		}
	}

	/**
	 *
	 * @param string $sTemplateSubject
	 */
	public function setTemplateSubject ($sTemplateSubject) {
		$this->_sTemplateSubject = $sTemplateSubject;
		/*
		if (is_null($this->_sTemplateSubject) || is_null($this->_sTemplateBody)) {
			$this->_bTemplateExists = true;
		}
		*/
	}

	/**
	 *
	 * @param string $sTemplateBody
	 */
	public function setTemplateBody ($sTemplateBody) {
		$this->_sTemplateBody = $sTemplateBody;
		/*
		if (is_null($this->_sTemplateSubject) || is_null($this->_sTemplateBody)) {
			$this->_bTemplateExists = true;
		}
		*/
	}

	/**
	 *
	 * @return boolean
	 */
	public function doesTemplateExist () {
		return $this->_bTemplateExists;
	}

	/**
	 *
	 * @param array $aFillData
	 * @return boolean
	 */
	public function fillTemplate ($aFillData) {
		if (is_null($this->_sTemplateSubject) || is_null($this->_sTemplateBody)) {
			return false;
		}

		$sTemporarySubject = $this->_sTemplateSubject;
		$sTemporaryBody = $this->_sTemplateBody;

		$aSearch = array();
		$aReplace = array();

		foreach ($aFillData as $sTemplateKey => $sFillValue) {
			$sSearch = '::' . $sTemplateKey . '::';

			$aSearch[] = $sSearch;
			$aReplace[] = $sFillValue;
		}

		if (!empty($aSearch) && !empty($aReplace)) {
			$sTemporarySubject = str_replace($aSearch, $aReplace, $sTemporarySubject);
			$sTemporaryBody = str_replace($aSearch, $aReplace, $sTemporaryBody);
		}

		$this->setSubject($sTemporarySubject);

		$this->setBodyHtml($sTemporaryBody);
		$this->_bBodySet = true;

		return true;
	}

	/**
	 * Wrapper for Zend_Mail's setSubject method
	 *
	 * @param string $sSubject
	 * @return Kwgl_Mail
	 */
	public function setSubject($sSubject) {
		$this->clearSubject();
		parent::setSubject($sSubject);
		$this->_bSubjectSet = true;
		return $this;
	}

	/**
	 * Wrapper for Zend_Mail's setBodyHtml method
	 *
	 * @param string $sHtmlBody
	 * @param string $sCharacterSet
	 * @param string $sEncoding
	 * @return Kwgl_Mail
	 */
	public function setBodyHtml ($sHtmlBody, $sCharacterSet = 'utf-8', $sEncoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE) {
		parent::setBodyHtml($sHtmlBody, $sCharacterSet, $sEncoding);
		$this->_bBodySet = true;
		return $this;
	}

	/**
	 * Wrapper for Zend_Mail's setBodyText method
	 *
	 * @param string $sText
	 * @param string $sCharacterSet
	 * @param string $sEncoding
	 * @return Kwgl_Mail
	 */
	public function setBodyText ($sText, $sCharacterSet = 'utf-8', $sEncoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE) {
		parent::setBodyText($sText, $sCharacterSet, $sEncoding);
		$this->_bBodySet = true;
		return $this;
	}

	/**
	 * Sends the prepared mail.
	 * Note that if you are using the Template setting functions for either the body or the subject, you must call fillTemplate. Otherwise, the mail will not be sent.
	 *
	 * @param Zend_Mail_Transport_Abstract $mTransport
	 * @return Kwgl_Mail
	 */
	public function send ($mTransport = null) {
		if (!$this->_bSubjectSet || !$this->_bBodySet) {
			return false;
		}

		parent::send($mTransport);

		return $this;
	}

}