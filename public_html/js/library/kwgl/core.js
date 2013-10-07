/**
 * Core class to handle main operations. Works as a namespace for the Kwgl library.
 *
 * @author Udantha Pathirana <udanthaya@gmail.com>
 * @package Js.Kwgl
 */

var Kwgl = {

	/**
	 * Initialiser for 'Static Classes' that contains an init function.
	 *
	 * @param oChild json Map of methods and properties
	 * @return oChild
	 */
	init: function (oChild) {

		// Check if the Child has an init method
		if (oChild.hasOwnProperty('init')) {
			// Register init method to be called when document is ready
			$(function(){

				oChild.init.call(oChild);

			});
		}

		// Return the child object
		return oChild;

	},

	/**
	 * Outputs to the Console Window if the Console is available
	 *
	 * @author Jayawi Perera <jayawiperera@gmail.com>
	 * @param mObjectToLog
	 */
	log: function (mObjectToLog) {

		if (window.console) {

			var iArgumentCount = arguments.length;

			for (var iCounter = 0; iCounter < iArgumentCount; iCounter++) {
				console.log(arguments[iCounter]);
			}

		}

		//window.console && console.log(mObjectToLog);
	}

};