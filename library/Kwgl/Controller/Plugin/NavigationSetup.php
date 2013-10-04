<?php
/**
 * Sets up Navigation to be used by a Module provided the Module-specific Navigation has been made available as an XML Document
 *
 * @author Jayawi Perera <jayawiperera@gmail.com>
 * @category PHP-Kwgl
 * @package Kwgl_Controller
 * @subpackage Plugin
 */
class Kwgl_Controller_Plugin_NavigationSetup extends Zend_Controller_Plugin_Abstract {

	public function preDispatch (Zend_Controller_Request_Abstract $oRequest) {

		$oNavigationConfiguration = $this->_getNavigationConfiguration($oRequest);

		if (!is_null($oNavigationConfiguration)) {

			$oNavigationContainer = new Zend_Navigation($oNavigationConfiguration);

			$sModule = $oRequest->getModuleName();
			switch ($sModule) {
				case 'kwgldev':

					if (is_null(Zend_Auth::getInstance()->getIdentity())) {
						$oLogoutNavigationEntry = $oNavigationContainer->findOneBy('label', 'my-account'); /* @var $oLogoutNavigationEntry Zend_Navigation_Page_Uri */
						$oNavigationContainer->removePage($oLogoutNavigationEntry);
					}

					break;
			}

			Zend_Registry::set('Navigation', $oNavigationContainer);

			$oViewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
			$oViewRenderer->init();
			$oView = $oViewRenderer->view;
			$oView->navigation($oNavigationContainer);
		}

	}

	/**
	 * Returns a Navigation Configuration for the module if it can be found
	 *
	 * @param Zend_Controller_Request_Abstract $oRequest
	 * @return Zend_Config_Xml
	 */
	protected function _getNavigationConfiguration ($oRequest) {

		$sModule = $sModuleName = $oRequest->getModuleName();

		$sNavigationXmlPath = Kwgl_Config::get(array('paths', 'config')) . 'navigation_' . $sModule . '.xml';

		if (file_exists($sNavigationXmlPath)) {

			$oNavigationConfiguration = new Zend_Config_Xml($sNavigationXmlPath, 'nav');
			return $oNavigationConfiguration;

		}

		return null;
	}

}