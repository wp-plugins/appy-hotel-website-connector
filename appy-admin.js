jQuery(document).ready(function(){

	if(jQuery("#dolphin-download-button").length) {
		jQuery("#dolphin-download-button").on('click', function(event){
			event.preventDefault();
			initCoolDownload('dolphin');
		});
	}
	if(jQuery("#orca-download-button").length) {
		jQuery("#orca-download-button").on('click', function(event){
			event.preventDefault();
			initCoolDownload('orca');
		});
	}


	showLoadingFigure();
    showInstallNotification();
	showSaveNotification();
	showRefreshNotification();

    jQuery("input[type=checkbox]").each(function(){
    	var lbl = jQuery(this).attr("data-label");
    		var switchGlobalOpts = {
				labels_placement: "right",
				on_label: lbl + ' is Enabled',
				off_label: lbl + ' is Disabled',
				width: 80,
				button_width: 40,
				height: 24
			} ;
		    jQuery(this).switchButton(switchGlobalOpts);
    });
    
    
/*    baguetteBox.run('.lb', {
    	captions: false
    });*/

    jQuery("#themeSelectionModal").modal();
    jQuery("[data-toggle=tooltip]").tooltip();
    fancyCopyPaste();
});

function initCoolDownload(userTheme) {
	var theme = userTheme;
	var labelID = theme + '-download-message' ;
	var themeFile = '';
	if (theme=="dolphin") {themeFile="http://www.appyhotel.com/wp-content/uploads/2014/10/wpappydolphin.zip";}
	if (theme=="orca") {themeFile="http://www.appyhotel.com/wp-content/uploads/2014/10/wpappyorca.zip";}
	var secondsBeforeDownloading = 5 ;
	var timerInterval = setInterval(
		function () {
			console.log(labelID + ' / '+themeFile);
			
            var label = document.getElementById(labelID);

            if (secondsBeforeDownloading === 0){
                label.innerHTML = '<a href="theme-install.php?upload" target="_blank">Install your theme (guided installation)!</a>';
                downloadFile(themeFile);
                clearInterval(timerInterval);
            } else {
                label.innerHTML = 'Download will start in ' + secondsBeforeDownloading + ' seconds...';
                secondsBeforeDownloading--;
            }
        }

		, 1000);
}


function showLoadingFigure() {
	var iH = jQuery("#loader-figure").height();
	var sH = jQuery(window).height();
	var newTop = (sH-iH)/2
	jQuery("#loader-figure").css({"padding-top":newTop});
	jQuery("#refresh-button").on('click', function(){
		jQuery("#loader").removeClass("hidden");
	});
}

function showInstallNotification() {
	if(jQuery("#install-panel").length) {
		jQuery("#install-panel").collapse('show');
		jQuery("#install-panel").on('shown.bs.collapse', function () {
			setTimeout(function(){
				jQuery("#install-panel").collapse('hide');
			}, 5000);
		});
	}
}

function showSaveNotification() {
	if(jQuery("#save-panel").length) {
		jQuery("#save-panel").collapse('show');
		jQuery("#save-panel").on('shown.bs.collapse', function () {
			setTimeout(function(){
				jQuery("#save-panel").collapse('hide');
			}, 2000);
		});
	}
}

function showRefreshNotification() {
	if(jQuery("#refresh-panel").length) {
		jQuery("#refresh-panel").collapse('show');
		jQuery("#refresh-panel").on('shown.bs.collapse', function () {
			setTimeout(function(){
				jQuery("#refresh-panel").collapse('hide');
			}, 2000);
		});
	}
}

function fancyCopyPaste() {
	jQuery("#appy-demo-id").click(function(){
		var demoId = jQuery(this).text() ;
		jQuery("#input-appy-id").val(demoId);
	});
	jQuery("#appy-demo-key").click(function(){
		var demoKey = jQuery(this).text() ;
		jQuery("#input-appy-key").val(demoKey);
	});
}

function coolCB(lbl) {

}

function appyValidateForm() {
	var chkID = true;
	if (jQuery("#input-appy-id").val().length < 7) {
		jQuery("#appy-id-form-group").children("label").removeClass("hidden");
		jQuery("#appy-id-form-group").children("label").addClass("show");
		jQuery("#appy-id-form-group").addClass("has-error");
		chkID = false;
	} else {
		jQuery("#appy-id-form-group").children("label").addClass("hidden");
		jQuery("#appy-id-form-group").children("label").removeClass("show");
		jQuery("#appy-id-form-group").removeClass("has-error");
		jQuery("#appy-id-form-group").addClass("has-success");
		chkID = true;
	}

	var chkKEY = true;
	if (jQuery("#input-appy-key").val().length !== 32) {
		jQuery("#appy-key-form-group").children("label").removeClass("hidden");
		jQuery("#appy-key-form-group").children("label").addClass("show");
		jQuery("#appy-key-form-group").addClass("has-error");
		chkKEY = false;
	} else {
		jQuery("#appy-key-form-group").children("label").addClass("hidden");
		jQuery("#appy-key-form-group").children("label").removeClass("show");
		jQuery("#appy-key-form-group").removeClass("has-error");
		jQuery("#appy-key-form-group").addClass("has-success");
		chkKEY = true;
	}
	return (chkID && chkKEY );
}