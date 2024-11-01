/**
 * @package WorldCup
 * @version $Id: wcp-admin.js 1420436 2016-05-19 16:41:40Z landoweb $
 * @author landoweb
 * Copyright Landoweb Programador, 2014
 */
 
jQuery(document).ready(function($) {
	
	/**
	 * Tabs for menu options
	 */
	$('#wcup_tabs').tabs();
	
	$('#selectallprediction').click(function () {
		var state = this.checked;
		$("#listpredictions input[type='checkbox']:not([disabled='disabled'])").attr('checked', state);
	});
	
	$('#selectallmatch').click(function () {
		var state = this.checked;
		$("#scorematches input[type='checkbox']:not([disabled='disabled'])").attr('checked', state);
	});

});