//include touch.js on desktop browsers only
if (!((window.DocumentTouch && document instanceof DocumentTouch) || 'ontouchstart' in window)) {
	var script = document.createElement("script");
	script.src = "//cdn.jqmobi.com/touch.js";
	var tag = $("head").append(script);
	$.os.android = true; //let's make it run like an android device
	$.os.desktop = true;
}



var webRoot = "./";
$.ui.autoLaunch = false; //By default, it is set to true and you're app will run right away.  We set it to false to show a splashscreen
/* This function runs when the body is loaded.*/
var init = function () {
	$.ui.backButtonText = "Back";// We override the back button text to always say "Back"
	window.setTimeout(function () {
		$.ui.launch();
	}, 1500);//We wait 1.5 seconds to call $.ui.launch after DOMContentLoaded fires
};
document.addEventListener("DOMContentLoaded", init, false);


/* This code is used for appMobi native apps */
var onDeviceReady = function () {
	AppMobi.device.setRotateOrientation("portrait");
	AppMobi.device.setAutoRotate(false);
	webRoot = AppMobi.webRoot + "/";
	//hide splash screen
	AppMobi.device.hideSplashScreen();

};

document.addEventListener("appMobi.device.ready", onDeviceReady, false);