<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	protected function _initAutoLoading () {
		Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
	}

	protected function _initApp () {
		define('ROOT_DIR', APPLICATION_PATH . "/..");
		define('CONFIG', 'configuration');
		define('DB_CONFIG', 'database_configuration');
		define('DB', 'database');
		define('SITE_DOMAIN', $_SERVER['HTTP_HOST']);

		$aConfiguration = $this->getOptions();
		Kwgl_Config::setConfig($aConfiguration);
		//Zend_Registry::set(CONFIG, $aConfiguration);
	}

	protected function _initBenchmarking () {
		Kwgl_Benchmark::initialise(true);
	}

	protected function _initDatabaseConfiguration () {
		$oResource = $this->getPluginResource('db');
        $oDb = $oResource->getDbAdapter();
        Zend_Registry::set(DB, $oDb);

		try {
			$oDb->query("SELECT 1");
		} catch (Exception $oException) {
			// Redirect User to a Static Page
			header('Location: /alternate.php');
			exit(0);
		}

		$aPaths = $this->getOption('paths');
		$sDatabaseConfigurationPath = $aPaths['db']['config'];
		$oDatabaseConfiguration = new Zend_Config_Ini($sDatabaseConfigurationPath);
		Zend_Registry::set(DB_CONFIG, $oDatabaseConfiguration);
		Kwgl_Db_Table::setDbConfig($oDatabaseConfiguration);
	}

	protected function _initAuthenticationConfiguration () {
		$aConfiguration = $this->getOption('auth');

		//Zend_Debug::dump($aConfiguration);
		Kwgl_Authenticate::setAuthConfig($aConfiguration);
		Kwgl_Auth_Storage_Db::setAuthConfig($aConfiguration);
	}

	/**
	 * NetAssessor global config settings
	 */
	protected function _initNetAssessorAuth() {

		$aNAConfig = Kwgl_Config::get('netassessor');

		Pi_Netassessor::setConfig(array(
			'username' => $aNAConfig['username'],
			'password' => $aNAConfig['password'],
			'baseUrl' => $aNAConfig['base_url']
		));

	}

}
