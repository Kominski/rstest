<?php
/**
 * Description of Form_Register
 *
 * @author Chathura Sandeepa <chathuras@gmail.com>
 */
class Form_Register extends Kwgl_Form {

	public function init() {

		$this->setAction('')
				->setMethod('post')
				->setAttrib('id', 'iFormRegister');

		$this->removeCsrfProtection();

		$oName = new Zend_Form_Element_Text('textName');
		$oName->setLabel('Naam')
					->setRequired()
					->setFilters(array(new Zend_Filter_StringTrim(),
										new Kwgl_Filter_HtmlPurifier()));

		$oAge = new Zend_Form_Element_Text('textAge');
		$oAge->setLabel('Leeftijd')
					->setRequired()
					->setAttrib('pattern', '[0-9]*')
					->setFilters(array(new Zend_Filter_StringTrim(),
										new Kwgl_Filter_HtmlPurifier()));

		$oEmail = new Zend_Form_Element_Text('textEmail');
		$oEmail->setLabel('Email')
					->setRequired()
					->setFilters(array(new Zend_Filter_StringTrim(),
										new Kwgl_Filter_HtmlPurifier()));

//		$oFirstName = new Zend_Form_Element_Text('textFirstName');
//		$oFirstName->setLabel('voornaam')
//					->setRequired()
//					->setFilters(array(new Zend_Filter_StringTrim(),
//										new Kwgl_Filter_HtmlPurifier()));
//
//		$oLastName = new Zend_Form_Element_Text('textLastName');
//		$oLastName->setLabel('achternaam')
//					->setRequired()
//					->setFilters(array(new Zend_Filter_StringTrim(),
//										new Kwgl_Filter_HtmlPurifier()));

		$oGender = new Zend_Form_Element_Radio('radioGender');
		$oGender->setLabel('Wat is uw geslacht')
				->addMultiOptions(array('male' => 'Man', 'female' => 'Vrouw'))
				->setSeparator('&nbsp;&nbsp;')
				->setAttrib('class', 'jq-ui-forms')
				->setRequired();


//		$oDate = new Zend_Form_Element_Select('selectDate');
//		$oDate->addMultiOption(0, 'dag')
//				->setRequired();
//
//		for($iDay = 1; $iDay <= 31; $iDay++ ) {
//			$oDate->addMultiOption($iDay, $iDay);
//		}
//
//		$oMonth = new Zend_Form_Element_Select('selectMonth');
//		$oMonth->addMultiOption(0, 'maand')
//				->setRequired();
//
//		for($iMonth = 1; $iMonth <= 12; $iMonth++ ) {
//			$oMonth->addMultiOption($iMonth, $iMonth);
//		}
//
//		$oYear = new Zend_Form_Element_Select('selectYear');
//		$oYear->addMultiOption(0, 'jaar')
//				->setRequired();
//
//		for($iYear = 2012; $iYear >= 1900; $iYear-- ) {
//			$oYear->addMultiOption($iYear, $iYear);
//		}

//		$oEducation = new Zend_Form_Element_Text('textEducation');
//		$oEducation->setLabel('Hoogste opleidingsniveau')
//					->setRequired()
//					->setFilters(array(new Zend_Filter_StringTrim(),
//										new Kwgl_Filter_HtmlPurifier()));

//		$oTrainingType = new Zend_Form_Element_Text('textTrainingType');
//		$oTrainingType->setLabel('Type opleiding')
//					->setRequired()
//					->setFilters(array(new Zend_Filter_StringTrim(),
//										new Kwgl_Filter_HtmlPurifier()));
		$aHighestTrainingOptions = array('Doctoraat',
											'WO Master',
											'WO Bachelor',
											'HBO Master',
											'HBO Bachelor',
											'MBO Theoretisch',
											'MBO Praktisch en Theoretisch',
											'MBO Praktisch',
											'VWO',
											'HAVO',
											'VMBO (MAVO/LBO) Theoretisch',
											'VMBO (MAVO/LBO) Praktisch en theoretisch',
											'VMBO (MAVO/LBO) Praktisch',
											'Basisonderwijs');

		$oHighestTraining = new Zend_Form_Element_Select('selectHighestTraining');
		$oHighestTraining->setLabel('Wat is de hoogste opleiding die u met succes heeft afgerond?')
							->setRequired()
							->addMultiOption(0, '--please select an option--')
							->setAttrib('class', 'jq-ui-forms');

		foreach ($aHighestTrainingOptions as $sHighestTrainingOption) {
			$oHighestTraining->addMultiOption($sHighestTrainingOption, $sHighestTrainingOption);
		}

		$aLastTrainingOptions = array('Agrarisch en milieu',
										'Economie, commercieel, management en administratie',
										'Gezondheidszorg, sociale dienstverlening en verzorging',
										'Horeca, toerisme, vrijetijdsbesteding, transport en logistiek',
										'Juridisch, bestuurlijk, openbare orde en veiligheid',
										'Onderwijs',
										'Talen, sociale wetenschappen, communicatie en kunst',
										'Techniek',
										'Wiskunde, natuurwetenschappen en informatica',
										'Overig');

		$oLastTraining = new Zend_Form_Element_Select('selectLastTraining');
		$oLastTraining->setLabel('Wat voor soort opleiding heeft u als laatste afgerond?')
						->addMultiOption(0, '--please select an option--')
						->setRequired();

		foreach ($aLastTrainingOptions as $sLastTrainingOption) {
			$oLastTraining->addMultiOption($sLastTrainingOption, $sLastTrainingOption);
		}

		$aJobCategoryOptions = array('Commercieel, winkel, koop en verkoop',
										'Financiën, bankwezen, verzekeringen',
										'Gezondheidszorg, paramedici, laboratorium',
										'IT, automatisering, telecommunicatie',
										'Juridisch, bestuur, inspectie, beleidsadviseur',
										'Marketing, PR, reclame',
										'Onderwijs, onderzoek, training',
										'Personeelszaken, arbeidsbemiddeling, organisatie',
										'Techniek',
										'Transport, logistiek, haven, luchthaven',
										'Overig');

		$oJobCategory = new Zend_Form_Element_Select('selectJobCategory');
		$oJobCategory->setLabel('In welke categorie valt uw huidige functie?')
						->addMultiOption(0, '--please select an option--')
						->setRequired();

		foreach ($aJobCategoryOptions as $sJobCategoryOption) {
			$oJobCategory->addMultiOption($sJobCategoryOption, $sJobCategoryOption);
		}

//		$oFunctions = new Zend_Form_Element_Text('textFunctions');
//		$oFunctions->setLabel('Functie (hoofdcategorieën)')
//					->setRequired()
//					->setFilters(array(new Zend_Filter_StringTrim(),
//										new Kwgl_Filter_HtmlPurifier()));

//		$oCareerLevel  = new Zend_Form_Element_Text('textCareerLevel');
//		$oCareerLevel->setLabel('Carrière niveau (aantal jaren in huidige functie)')
//					->setRequired()
//					->setFilters(array(new Zend_Filter_StringTrim(),
//										new Kwgl_Filter_HtmlPurifier()));

		$oYearsOfExperience  = new Zend_Form_Element_Text('textYearsOfExperience');
		$oYearsOfExperience->setLabel('Hoeveel jaren werkervaring heeft u in uw huidige functie?')
							->setRequired()
							->setAttrib('pattern', '[0-9]*')
							->setFilters(array(new Zend_Filter_StringTrim(),
												new Kwgl_Filter_HtmlPurifier()));

		$oLeading = new Zend_Form_Element_Radio('radioLeading');
		$oLeading->setLabel('Geeft u leiding?')
				->addMultiOptions(array('yes' => 'Ja', 'no' => 'Nee'))
				->setSeparator('&nbsp;&nbsp;')
				->setAttrib('class', 'jq-ui-forms')
				->setRequired();

		$oNumberOfEmployees = new Zend_Form_Element_Text('textNumberOfEmployees');
		$oNumberOfEmployees->setLabel('Zo ja, aan hoeveel personen geeft u leiding? (Zowel direct als indirect)')
//							->setRequired()
							->setAttrib('pattern', '[0-9]*')
							->setFilters(array(new Zend_Filter_StringTrim(),
												new Kwgl_Filter_HtmlPurifier()));

//		$oFieldOfInterest = new Zend_Form_Element_Text('textFieldOfInterest');
//		$oFieldOfInterest->setLabel('interessegebied')
//					->setRequired()
//					->setFilters(array(new Zend_Filter_StringTrim(),
//										new Kwgl_Filter_HtmlPurifier()));

		$oAgree = new Zend_Form_Element_Checkbox('checkAgree');
		$oAgree->setLabel('Ik ga akkoord met de <a id="iATerms" href="#iDivTerms" >voorwaarden</a>')
				->setAttrib('class', 'jq-ui-forms')
				->setRequired();

		$oSubmit = new Zend_Form_Element_Submit('submitSubmit');
		$oSubmit->setLabel('')
				->setDecorators(array('ViewHelper'));

//		$aElemenrts = array($oFirstName, $oLastName, $oDate, $oMonth, $oYear, $oEmail, $oEducation,
//							$oFieldOfInterest, $oAgree, $oSubmit);

//		$aElemenrts = array($oName, $oEmail, $oGender, $oAge, $oEducation, $oTrainingType, $oCareerLevel,
//							$oLeading, $oNumberOfEmployees, $oAgree, $oSubmit);

		$aElemenrts = array($oName, $oAge, $oEmail, $oGender, $oHighestTraining,
							$oLastTraining, $oJobCategory, $oYearsOfExperience, $oLeading,
							$oNumberOfEmployees, $oAgree, $oSubmit);

		foreach($aElemenrts as $oElement) {
			$oElement->removeDecorator('Errors');
		}

		$this->addElements($aElemenrts);
		$this->_setViewScriptDecorator('/forms/partials/register.phtml', $this->_aParameters);
	}
}

?>
