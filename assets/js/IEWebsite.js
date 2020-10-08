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

	return {
		AjaxGet: ajaxGet,
		AjaxPost: ajaxPost,
		AjaxPut: ajaxPut,
		AjaxDelete: ajaxDelete,
		SychronousAjaxPost: sychronousAjaxPost,
		SychronousAjaxGet: sychronousAjaxGet,
		GetURLParam : getURLParam,
		ValidateEmail : validateEmail,
		UcFirst : ucFirst,
		ScrollToRef :scrollToRef,
		CreateLink: createLink,
		FormatString: formatString,
		GetFileExtension: getFileExtension,
	};
})();