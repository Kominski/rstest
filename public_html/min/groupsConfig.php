<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 *
 *
 *
 */

$aJsLibrary = array(
	'jquery'					=> '//js/library/jquery-1.7.2.min.js',
	'jqueryUi'					=> '//js/library/jquery-ui-1.8.20.custom.min.js',
	'kwglCore'					=> '//js/library/kwgl/core.js',
	'kwglMessage'				=> '//js/library/kwgl/message.js',
	'kwglAjax'					=> '//js/library/kwgl/ajax.js',
	'kwglForm'					=> '//js/library/kwgl/form.js',
	'kwglPagination'			=> '//js/library/kwgl/pagination.js',
	'kwglTranslate'				=> '//js/library/kwgl/translate.js',
	'kwglUtility'				=> '//js/library/kwgl/utilities.js',
    'bootstrap'                 =>  '//js/library/bootstrap.min.js',
    'bx'                        =>  '//js/library/jquery.bxslider.min.js'
);
$aJsPlugins = array(
	'jsViews' => array(
		'jsrender'				=> '//js/library/jsviews/jsrender.js',
		'observable'			=> '//js/library/jsviews/jquery.observable.js',
		'views'					=> '//js/library/jsviews/jquery.views.js'
	),
);
$aCssBase = array(
	'reset'						=> '//css/_base/reset.css',
	'baseGrid-12'				=> '//css/_base/grid-12.css',
	'baseGrid-16'				=> '//css/_base/grid-16.css',
	'baseGrid-24'				=> '//css/_base/grid-24.css',
	'baseGrid-Fraction-Fixed'	=> '//css/_base/grid-fraction-fixed.css',
	'baseGrid-Fraction-Fluid'	=> '//css/_base/grid-fraction-fluid.css',
	'baseStyle'					=> '//css/_base/style.css'
);
$aCssLibrary = array(
	'bootstrap'					=> '//css/library/bootstrap/bootstrap.css',
	'bx'					=> '//css/library/jquery.bxslider.css',
	'style'					=> '//css/default/style.css'
);

return array(
	'jsCore'		=> $aJsLibrary,
	'jsViews'		=> $aJsPlugins['jsViews'],
	'cssBase'		=> $aCssLibrary,
	'jsExamples'	=> array('//js/default/ajax-example.js'),
	'cssMain'		=> array($aCssBase, '//css/default/ui_extender.css', '//css/default/js-examples.css')

);