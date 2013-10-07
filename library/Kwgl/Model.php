<?php
/**
 * A base for all Model classes that provides some utilitarian members for easy access
 *
 * @author Jayawi Perera <jayawiperera@gmail.com>
 * @category PHP-Kwgl
 * @package Kwgl_Model
 * @uses Zend_Controller_Front
 */
class Kwgl_Model {

	/**
	 * Holds the Zend Request object for easy access
	 *
	 * @var Zend_Controller_Request_Abstract
	 */
	protected $_oRequest = null;

	/**
	 * Holds the current Module name
	 *
	 * @var string
	 */
	protected $_sModule = 'default';

	/**
	 * Holds the current Controller name
	 *
	 * @var string
	 */
	protected $_sController = 'index';

	/**
	 * Holds the current Action name
	 *
	 * @var string
	 */
	protected $_sAction = 'index';

	/**
	 * Holds the Parameters in the current Request
	 *
	 * @var array
	 */
	protected $_aParameter = array();

	/**
	 * Holds the keys (parameter name) of the Parameters in the current Request
	 *
	 * @var array
	 */
	protected $_aParameterKey = array();

	/**
	 * Sets up the member variables for easy access within Model classes
	 */
	public function  __construct() {

		$oFrontController = Zend_Controller_Front::getInstance();
		$this->_oRequest = $oFrontController->getRequest();
		$this->_sModule = $this->_oRequest->getModuleName();
		$this->_sController = $this->_oRequest->getControllerName();
		$this->_sAction = $this->_oRequest->getActionName();
		$this->_aParameter = $this->_oRequest->getParams();
		$this->_aParameterKey = array_keys($this->_aParameter);

	}

}