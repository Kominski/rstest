jq.ui.ready(function () {
	jq.ui.removeFooterMenu();
	jq("#header").remove();
	jq("#menu").remove();
	jq("#jQui_modal").remove();
	jq("#navbar").remove();
	jq(".scrollBarV").css({"background" : "#2175D9"});
});

$().ready(function () {

	var oUserAnswers = [];

	var iQuestionNumber = 1;

	//	console.log(oQuestions[0]);

	jQuery.noConflict();

	// Questions & Answers - Drag & Drop with Sound
	jQuery(function() {

		jQuery( ".cDivAnswerList div" ).draggable({
			appendTo: "body",
			helper: "clone",
			revert: false
		});

		jQuery( ".question div" ).droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			accept: ":not(.ui-sortable-helper)",
			drop: function( event, ui ) {
				jQuery(this).empty();
				jQuery(this).css({
					//'border' : 'none',
					'background-color' : ui.draggable.css('background-color')
				});
				jQuery( "<div></div>" ).html(ui.draggable.html()).appendTo(this);

				document.getElementById('dropSound').play(); //play drop sound effect
			}
		});
	});

	// Questions & Answers - Selected Answer Submission
	//	jQuery('.cSpanNext').click(function () {
	//		var oAnswers = {'nee': 'no', 'weinig': 'little', 'soms': 'sometimes', 'vaak': 'often', 'ja': 'yes'};
	//		var iPageId = jQuery(this).attr('id').replace('iSpanButtonNext', '');
	//		var iQuestionId = jQuery('#iDivTest' + iPageId + ' .cH1Question').attr('id').replace('iH1Question', '');
	//			iQuestionId = parseInt(iQuestionId);
	//		var iParticipantId = jQuery('#iSpanParticipantId').text();
	//			iParticipantId = parseInt(iParticipantId);
	//		var iQuestionNumber = jQuery('#iDivTest' + iPageId + ' .cSpanQuestionNumber').attr('id').replace('iSpanQuestionNumber', '');
	//			iQuestionNumber = parseInt(iQuestionNumber);
	//		var iQuestionCount = jQuery('#iSpanQuestionCount').text();
	//			iQuestionCount = parseInt(iQuestionCount);
	////
	//		if (jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder span').length) {
	//			var sAnswer = jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder span').text();
	//				sAnswer = oAnswers[sAnswer];
	//
	//			var oData = {'id_participant': iParticipantId, 'id_question': iQuestionId, 'answer': sAnswer};
	//
	//			jQuery.ajax({
	//				type: 'POST',
	//				url: '/xhr_index/answer/',
	//				data: oData,
	//				beforeSend: function() {
	//					jq.ui.showMask('&nbsp;');
	//				},
	//				success: function(oResponse) {
	////					console.log(oResponse);
	//					iPageId = (iPageId == 1)? 2 : 1;
	//					aQuestion = oResponse.response;
	//					iQuestionNumber++;
	//					if (aQuestion.id) {
	//
	//						jQuery('#iDivTest' + iPageId + ' .cH1Question').text(aQuestion.question);
	//						jQuery('#iDivTest' + iPageId + ' .cH1Question').attr('id','iH1Question' + aQuestion.id);
	//						jQuery('#iDivTest' + iPageId + ' .cSpanQuestionNumber').text('vraag ' + aQuestion.id);
	//						jQuery('#iDivTest' + iPageId + ' .cSpanQuestionNumber').attr('id', 'iSpanQuestionNumber' + iQuestionNumber);
	//
	//						jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder').css('background-color', '');
	//						jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder').empty();
	//
	//						var iQuestionPrecentage = (100 / iQuestionCount) * iQuestionNumber;
	//
	//						jQuery('#iDivTest' + iPageId + ' .cDivMarker').css('width', iQuestionPrecentage + '%');
	//
	//						jq.ui.hideMask();
	//						jq.ui.loadContent("iDivTest" + iPageId, false, false, "slide");
	//					} else {
	//						jq.ui.hideMask();
	//						jq.ui.loadContent("iDivFinish", false, false, "slide");
	//					}
	//				},
	//				complete: function() {
	//					jq.ui.hideMask();
	//				},
	//				dataType: 'json'
	//			});
	//		}
	//	});

	jQuery('.cSpanNext').click(function () {
		//		var oAnswers = {'nee': 'no', 'weinig': 'little', 'soms': 'sometimes', 'vaak': 'often', 'ja': 'yes'};
		var iPageId = jQuery(this).attr('id').replace('iSpanButtonNext', '');
		var iQuestionId = jQuery('#iDivTest' + iPageId + ' .cH1Question').attr('id').replace('iH1Question', '');
		iQuestionId = parseInt(iQuestionId);
		var iParticipantId = jQuery('#iSpanParticipantId').text();
		iParticipantId = parseInt(iParticipantId);
		//		var iQuestionNumber = jQuery('#iDivTest' + iPageId + ' .cSpanQuestionNumber').attr('id').replace('iSpanQuestionNumber', '');
		//			iQuestionNumber = parseInt(iQuestionNumber);
		var iQuestionCount = jQuery('#iSpanQuestionCount').text();
		iQuestionCount = parseInt(iQuestionCount);
		var aQuestion = null;

		if (jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder span').length) {
			var sAnswer = jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder span').attr('id').replace('iSpanAnswer' + iPageId, '').toLowerCase();
			//				sAnswer = oAnswers[sAnswer];

			//				console.log(sAnswer);

			oUserAnswers.push({'id_question': iQuestionId, 'answer': sAnswer});

			iPageId = (iPageId == 1)? 2 : 1;

			jQuery.each(oQuestions, function (iIndex, oQuestion){
				if(oQuestion.id > iQuestionId) {
					aQuestion = oQuestion;
					return false;
				} else {
					return true;
				}
			});

			iQuestionNumber ++;
			if (aQuestion) {

				jQuery('#iDivTest' + iPageId + ' .cH1Question').text(aQuestion.question);
				jQuery('#iDivTest' + iPageId + ' .cH1Question').attr('id','iH1Question' + aQuestion.id);
				jQuery('#iDivTest' + iPageId + ' .cSpanQuestionNumber').text('Vraag ' + aQuestion.id);
				//				jQuery('#iDivTest' + iPageId + ' .cSpanQuestionNumber').attr('id', 'iSpanQuestionNumber' + iQuestionNumber);

				jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder').css('background-color', '');
				jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder').empty();

				var iQuestionPrecentage = (100 / iQuestionCount) * iQuestionNumber;

				jQuery('#iDivTest' + iPageId + ' .cDivMarker').css('width', iQuestionPrecentage + '%');

				jq.ui.loadContent("iDivTest" + iPageId, false, false, "slide");
			} else {

				var oData = {'id_participant': iParticipantId, 'answers': oUserAnswers};

				//				console.log(oData);
				jQuery.ajax({
					type: 'POST',
					url: '/xhr_index/saveanswers/',
					data: oData,
					beforeSend: function() {
						jq.ui.showMask('&nbsp;');
					},
					success: function(oResponse) {
						//						console.log(oResponse);
						if (oResponse) {
							jq.ui.hideMask();
							jq.ui.loadContent("iDivFinish", false, false, "slide");
							iPageId = 1;
							iQuestionNumber = 1;
							oUserAnswers = [];

							jQuery('#iDivTest' + iPageId + ' .cH1Question').text(oQuestions[0].question);
							jQuery('#iDivTest' + iPageId + ' .cH1Question').attr('id','iH1Question' + oQuestions[0].id);
							jQuery('#iDivTest' + iPageId + ' .cSpanQuestionNumber').text('Vraag ' + oQuestions[0].id);

							jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder').css('background-color', '');
							jQuery('#iDivTest' + iPageId + ' .cDivAnswreholder').empty();

							var iQuestionPrecentage = (100 / iQuestionCount) * iQuestionNumber;
							jQuery('#iDivTest' + iPageId + ' .cDivMarker').css('width', iQuestionPrecentage + '%');
						}
					},
					complete: function() {
						jq.ui.hideMask();
					},
					dataType: 'json'
				});
			}
		}
	});

	jQuery(':input[name="radioLeading"]').click(function () {
		if (this.value == 'yes') {
			jQuery('#iTrNumberOfEmployees').removeClass('cVisibilityHidden');
		} else {
			jQuery('#iTrNumberOfEmployees').addClass('cVisibilityHidden');
		}
	});

	//Terms and conditions page  - '< terug' to go back to registration form in reverse slide transition (from left to right)
	jQuery('#iSpanBack').click(function(){
		$.ui.loadContent("#iDivRegister",false,true,"slide");
	});

	jQuery('#iATerms').click(function(){
		jQuery('.qtip').remove();
	});

	// Registration
	jQuery('#iFormRegister').submit(function() {
		var oForm = jQuery(this);
		var oData = jQuery(oForm).find(':input[type="text"], :input[type="radio"]:checked, :input[type="checkbox"], select');
		var bValid = true;

		//		jQuery(oForm).find('.cError').remove(); // Remove error message elements
		jQuery(oForm).find('.qtip').remove();

		jQuery.each(oData, function () {
			var sType = jQuery(this).attr('type');
			var sTagName = jQuery(this).prop('tagName').toLowerCase();

			switch(sType) {
				case 'text':
					if (this.value == null || this.value == "" && jQuery(this).attr('id') != 'textNumberOfEmployees') {
						//						jQuery("#" + this.name).after('<span class="cError">required</span>');
						showErrorToolTip(jQuery(this), 'required');
						bValid = false;
					} else if (jQuery(this).attr('id') == 'textEmail') {
						var sEmailRegEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/ ;
						var bEmailValid = sEmailRegEx.test(this.value);

						if (!bEmailValid) {
							//							jQuery("#" + this.name).after('<span class="cError">email invalid</span>');
							showErrorToolTip(jQuery(this), 'invalid');
							bValid = false;
						}
					} else if (jQuery(this).attr('id') == 'textAge') {
						var iAge = parseInt(this.value);
						if (!isFinite(this.value) || iAge < 0 ) {
							//							jQuery("#" + this.name).after('<span class="cError">invalid</span>');
							showErrorToolTip(jQuery(this), 'invalid');
							bValid = false;
						}
					} else if (jQuery(this).attr('id') == 'textYearsOfExperience') {
						var iYearsOfExperience = parseInt(this.value);
						if (!isFinite(this.value) || iYearsOfExperience < 0 ) {
							//							jQuery("#" + this.name).after('<span class="cError">invalid</span>');
							showErrorToolTip(jQuery(this), 'invalid');
							bValid = false;
						}
					} else if (jQuery(this).attr('id') == 'textNumberOfEmployees') {
						var iNumberOfEmployees = parseInt(this.value);
						if ((jQuery('#radioLeading-yes').is(':checked')) && (this.value == null || this.value == "")) {
							//							jQuery("#" + this.name).after('<span class="cError">required</span>');
							showErrorToolTip(jQuery(this), 'required');
							bValid = false;
						}
						else if (jQuery('#radioLeading-yes').is(':checked') && (!isFinite(this.value) || iNumberOfEmployees < 0 )) {
							//							jQuery("#" + this.name).after('<span class="cError">invalid</span>');
							showErrorToolTip(jQuery(this), 'invalid');
							bValid = false;
						}
					}
					break;

				case 'checkbox':
					if (!(jQuery(this).is(':checked'))) {
						//						jQuery("#iLabel" + this.name[0].toUpperCase() + this.name.slice(1)).after('<span class="cError">required</span>')
						showErrorToolTip(jQuery("#iLabel" + this.name[0].toUpperCase() + this.name.slice(1)), 'required');
						bValid = false;
					}
					break;
			}

			switch(sTagName) {
				case 'select':
					if (this.value == 0) {
						bValid = false;
						//						jQuery("#" + this.name).after('<span class="cError">required</span>');
						showErrorToolTip(jQuery(this), 'required');
					}
					break;
			}
		});


		if(!(oForm.find(':input[name="radioGender"]').is(':checked'))) {
			bValid = false;
			//			jQuery('#radioGender-female + label').after('<span class="cError">required</span>');
			//			showErrorToolTip(jQuery('#iDivRadioGender'), 'required');
			showErrorToolTip(jQuery('#iDivRadioGender'), 'required', jQuery('#iDivRadioGender'));
		}

		if(!(oForm.find(':input[name="radioLeading"]').is(':checked'))) {
			bValid = false;
			//			jQuery('#radioLeading-no + label').after('<span class="cError">required</span>');
			//			showErrorToolTip(jQuery('#radioLeading-no + label'), 'required');
			showErrorToolTip(jQuery('#iDivRadioLeading'), 'required', jQuery('#iDivRadioLeading'));
		}

		if (bValid) {
			jQuery.ajax({
				type: 'POST',
				url: '/xhr_index/register/',
				data: oData,
				beforeSend: function() {
					jq.ui.showMask('&nbsp;');
				},
				success: function(oResponse) {
					if(oResponse.response.valid) {
						jQuery('#iSpanParticipantId').text(oResponse.response.id_participant);
						jq.ui.hideMask();
						jq.ui.loadContent("iDivHowTo", false, false, "slide");
						oForm[0].reset();
						jQuery('#iTrNumberOfEmployees').addClass('cVisibilityHidden');
					} else {
						jQuery.each(oResponse.response, function (oElement, sError) {
							//							jQuery("#" + oElement).after('<span class="cError">' + sError + '</span>');
							showErrorToolTip(jQuery("#" + oElement), sError);
						});
					}
				},
				complete: function() {
					jq.ui.hideMask();
				},
				dataType: 'json'
			});
		}
		return false;
	});

	//scroll terms and conditions
	var oScroller = $("#iDivTermsContainer").scroller({
		verticalScroll:true,  //vertical scrolling
		horizontalScroll:false,  //horizontal scrolling
		//scrollBars:true,   //display scrollbars
		vScrollCSS : "scrollBarV" //CSS class for veritcal scrollbar
		//hScrollCSS : "scrollBarH", //CSS class for horizontal scrollbar
		//refresh:true, //Adds 'Pull to refresh' at the top
		//refreshFunction:updateMessage //callback function to execute on pull to refresh
	});

});

function showErrorToolTip (oElement, sType, oHideElement) {
	var sText;
	var sHideEvent = 'mouseenter';
	var sWidth = 'auto';

	oHideElement = typeof oHideElement !== 'undefined' ? oHideElement : oElement;
	switch (sType){
		case 'required':
			sText = 'verplicht';
			break;
		case 'invalid':
			sText = 'ongeldig';
			break;
		case 'already-exist':
			sText = 'bestaad reeds';
			sWidth = '98px';
			break;
	}

	switch (oHideElement.prop('tagName').toLowerCase()) {
		case 'input':
		case 'select':
			sHideEvent = 'focus';
			break;
		case 'label':
			sHideEvent = 'click';
			break;
		case 'div':
			sHideEvent = 'tap';
			break;
	}

	oElement.qtip({
		content: {
			text: sText
		},
		show: {
			ready: true
		},
		position: {
			my: 'left center',
			at: 'right center'
		},
		hide: {
			event: sHideEvent,
			target: oHideElement,
			effect: function(offset) {
				jQuery(this).fadeOut(1000);
			}
		},
		events: {
			hidden: function(event, api) {
				jQuery(this).remove();
			}
		},
		style: {
			classes: 'ui-tooltip-red ui-tooltip-shadow ui-tooltip-rounded',
			width: sWidth
		}
	});
}