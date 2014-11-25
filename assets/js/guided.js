jQuery(document).ready(function(){
	guidedProcess();
});

function guidedProcess() {
	var step1 = '' ;
	var step2 = 'Locate your theme zip file in your download folder.<br/>Depending on the theme you\'ve selected the filename is:<br/>&bullet; Dolphin: wpappydolphin.zip<br/>&bullet; Orca: wpappyorca.zip' ;
	var step3 = 'Upload your theme zip file and click \"Install\".<br/>Depending on the theme you\'ve selected the filename is:<br/>&bullet; Dolphin: wpappydolphin.zip<br/>&bullet; Orca: wpappyorca.zip' ;

	var step5 = 'Click "Activate" for your selected Appy Hotel theme.';

	if(window.location.pathname == "/wp-admin/theme-install.php") {
		jQuery(".container").attr({"data-intro":"Proceed to installation", "data-step":"1"});
		jQuery("input[name='themezip']").attr({"data-intro":step2, "data-step":"2"});
		jQuery("#install-theme-submit").attr({"data-intro":step3, "data-step":"3"});

		introJs().start();

		jQuery('.introjs-helperNumberLayer').css("display","none");
		jQuery('.introjs-helperLayer').css("display","none");
		jQuery('.introjs-overlay').css("display","none");
		jQuery('.introjs-tooltipReferenceLayer').css("display","none");
		setTimeout(function(){
			// introJs().start().nextStep();
			jQuery('.introjs-helperLayer').css("display","block");
			jQuery('.introjs-overlay').css("display","block");
			jQuery('.introjs-tooltipReferenceLayer').css("display","block");
		}, 300);
	}

	if(window.location.pathname == "/wp-admin/update.php") {
		jQuery("a.activatelink").attr({"data-intro":step5, "data-step":"1"});

		introJs().start();

		jQuery('.introjs-helperNumberLayer').css("display","none");
		jQuery('.introjs-helperLayer').css("display","none");
		jQuery('.introjs-overlay').css("display","none");
		jQuery('.introjs-tooltipReferenceLayer').css("display","none");
		setTimeout(function(){
			// introJs().start().nextStep();
			jQuery('.introjs-helperLayer').css("display","block");
			jQuery('.introjs-overlay').css("display","block");
			jQuery('.introjs-tooltipReferenceLayer').css("display","block");
		}, 300);
	}
}