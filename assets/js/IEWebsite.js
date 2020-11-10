var IEWebsite = IEWebsite || {};

IEWebsite.Utils = (function() {
	var ajaxGet = function (url, data, responseHandler, dataType, error) {
		ajaxRequest(url, data, responseHandler, 'Get', dataType, error);
	};

	var ajaxPost = function (url, data, responseHandler, dataType, error) {
		ajaxRequest(url, data, responseHandler, 'Post', dataType, error);
	};
	var ajaxPut = function (url, data, responseHandler, dataType, error) {
		ajaxRequest(url, data, responseHandler, 'Put', dataType, error);
	};
	var ajaxDelete = function (url, data, responseHandler, dataType, error) {
		ajaxRequest(url, data, responseHandler, 'Delete', dataType, error);
	};

	var sychronousAjaxPost = function(url, data)
	{
		var responseText = $.ajax({
			type : "POST",
			async : false,
			dataType : 'json',
			url : url,
			data : data
		}).responseText;
		return jQuery.parseJSON(responseText);
	}

	var sychronousAjaxGet = function(url, data)
	{
		var responseText = $.ajax({
			type : "GET",
			async : false,
			dataType : 'json',
			url : url,
			data : data,
			beforeSend: function (xhr) {
				
			}
		}).responseText;
		return jQuery.parseJSON(responseText);
	};

    var getFileExtension = function(filename)
    {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

	var scrollToRef = (elementOffset) => {
		$('html, body').animate({
			scrollTop: elementOffset
		}, 1000);
	}

	var ajaxRequest = function (url, data, responseHandler, httpMethod, dataType, error) {
		dataType = dataType || "json";
		if(!error)
		{
			error = function (xhr, ajaxOptions, thrownError){};
		}

		$.ajax({
			type: httpMethod,
			url: url,
			dataType: dataType,
			data: data,
			success: responseHandler,
			error: error,
			beforeSend: function (xhr) {
				
			}
		});
	};

	var ajaxFileUpload = function (url, data, responseHandler, error) {

		if(!error)
		{
			error = function (xhr, ajaxOptions, thrownError){};
		}

		$.ajax({
			url: url,
			data: data,
			type: 'POST',
			contentType: false, 
            processData: false,
			success: responseHandler,
			error: error,
			beforeSend: function (xhr) {
				
			}
		});
	};

	var getURLParam  = function (strParamName) {
		var strReturn = "";
		var strHref = window.location.href;
		if (strHref.indexOf("?") > -1) {
			var strQueryString = strHref.substr(strHref.indexOf("?") + 1);
			var aQueryString = strQueryString.split("&");
			for ( var iParam = 0; iParam < aQueryString.length; iParam++) {
				if (aQueryString[iParam].indexOf(strParamName + "=") > -1) {
					var aParam = aQueryString[iParam].split("=");
					strReturn = aParam[1];
					break;
				}
			}
		}
		return unescape(strReturn);
	}

	var validateEmail = function (email)
	{
		var re = /\S+@\S+\.\S+/;
		return re.test(email);
	}

	var ucFirst = function (string)
	{
		var re = /\S+@\S+\.\S+/;
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	var createLink = function(attributes) {
		if (!_.isEmpty(attributes))
		{
			let text = [];
			let placeholder = attributes.placeholder || '';
			
			delete attributes.placeholder;
			
			for (let attribute in attributes)
			{   
				text.push(String(attribute + '=' + JSON.stringify(attributes[attribute])));
			}

			return '<a '+ text.join(' ') +'>'+ placeholder +'</a>';
		}
	};

	var formatString = function(stringArr, formatter = ' ')
	{
		if (!Array.isArray) {
			Array.isArray = function(arg) {
				return Object.prototype.toString.call(arg) === '[object Array]';
			};
		}

		return !_.isEmpty(stringArr) && Array.isArray(stringArr) ? stringArr.join(formatter) : '';
	}

	var showLoadingScreen = function()
	{
		$("#loadingContainer").show();
	};

	var hideLoadingScreen = function()
	{
		$("#loadingContainer").hide();
	};

	var sweetAlert = function(title, text, type) 
	{
		swal({
			title: title,
			text: text,
			buttonsStyling: false,
			confirmButtonClass: "btn btn-success",
			type: type
		});
	}

	var sweetAlertConfirmation = function()
	{
		swal({
			title: 'Are you sure?',
			text: 'You will not be able to recover this..!',
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'No, keep it',
			confirmButtonClass: "btn btn-success",
			cancelButtonClass: "btn btn-danger",
			buttonsStyling: false
		}).then(function() {
		  	swal({
				title: 'Deleted!',
				text: 'Assigned product removed.',
				type: 'success',
				confirmButtonClass: "btn btn-success",
				buttonsStyling: false
			})
		}, 
		function(dismiss) {
		  	// dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
			if (dismiss === 'cancel') 
			{
				swal({
					title: 'Cancelled',
					text: 'Nothing performed :)',
					type: 'error',
					confirmButtonClass: "btn btn-info",
					buttonsStyling: false
				});
		  	}
		})

	}

	var jqueryFormValidation = function(id) {
		$(id).validate({
			errorPlacement: function(error, element) {
				$(element).parent('div').addClass('has-error');
			}
		});
	}

	var showNotification = function(message, type = 'success', from = 'top', align = 'center') {
		// type = ['','info','success','warning','danger','rose','primary'];
    	$.notify({
        	icon: "notifications",
        	message: message
        },{
            type: type,
			// timer: 1000,
			delay: 100,
            placement: {
                from: from,
                align: align
            }
        });
	}

	return {
		AjaxGet: ajaxGet,
		AjaxPost: ajaxPost,
		AjaxPut: ajaxPut,
		AjaxDelete: ajaxDelete,
		SychronousAjaxPost: sychronousAjaxPost,
		SychronousAjaxGet: sychronousAjaxGet,
		AjaxFileUpload: ajaxFileUpload,
		GetURLParam : getURLParam,
		ValidateEmail : validateEmail,
		UcFirst : ucFirst,
		ScrollToRef :scrollToRef,
		CreateLink: createLink,
		FormatString: formatString,
		GetFileExtension: getFileExtension,
		ShowLoadingScreen: showLoadingScreen,
		HideLoadingScreen: hideLoadingScreen,
		Swal: sweetAlert,
		JqueryFormValidation: jqueryFormValidation,
		Notification: showNotification
	};
})();