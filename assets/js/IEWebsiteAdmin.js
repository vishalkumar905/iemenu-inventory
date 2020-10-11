var IEWebsiteAdmin = IEWebsiteAdmin || {};

IEWebsiteAdmin.ProductCreatePage = (function() {
	var init = function()
	{
		if ($("#productPageContainer").length <= 0)
		{
			return 0;
		};

		$("#categories").change(function() {
			let categoryId = $(this).val();
			if (categoryId > 0)
			{
				IEWebsite.Utils.AjaxGet(`${FETCH_SUB_CATEGORIES}/${categoryId}`, null , function(resp) {
					if (resp.status)
					{
						$("#subCategory").html('');
						if (!_.isEmpty(resp.response))
						{
							$("#subCategory").html('').append($("<option>").attr('value', '').text('Choose Subcategory'));

							_.each(resp.response, function(row)
							{
								$("#subCategory").append($("<option>").attr('value', row.id).text(row.categoryName));
							});

							$("#subCategoryBox").show();	
						}
						else
						{
							$("#subCategoryBox").hide();	
						}

						$("#subCategory").selectpicker("refresh");
					}
				});
			}
		});

		$("#baseUnit").change(function() {
			let baseUnitId = $(this).val();
			if (baseUnitId > 0)
			{
				IEWebsite.Utils.AjaxGet(`${FETCH_SUB_SI_UNITS}/${baseUnitId}`, null , function(resp) {
					if (resp.status)
					{
						$("#siUnit").html('');
						if (!_.isEmpty(resp.response))
						{
							$("#siUnit").html('').append($("<option>").attr('value', '').text('Choose Unit'));
							_.each(resp.response, function(row)
							{
								$("#siUnit").append($("<option>").attr('value', row.id).text(row.unitName));
							});
	
							$("#siUnitBox").show();
						}
						else
						{
							$("#siUnitBox").hide();
						}
						$("#siUnit").selectpicker("refresh");
					}
				});
			}
		});

		$("#uploadExcel").click(function() {
			let file = $('#excelFile');
			let errorMessage = "";

			if (_.isEmpty(file[0].files) || file[0].files.length === 0)
			{
				errorMessage = "Please select a file to upload";	
			}
			

			if (!_.isEmpty(errorMessage))
			{
				$("#excelFileErrorMsg").text(errorMessage);
				return false;
			}
			else
			{
				IEWebsite.Utils.ShowLoadingScreen();

				let url = BASE_URL + 'backend/products/import'; 
				let formData = new FormData();
				formData.append('file', file[0].files[0]);
				
				$("#importExcelForm .text-danger").text('');	
				IEWebsite.Utils.AjaxFileUpload(url, formData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();

					if (resp.status)
					{
						file.val('');
						IEWebsite.Utils.Swal('Success', 'Your data has been successully imported..', 'success');
						window.setTimeout(function(){
							window.location.reload();
						}, 10000);
					}
					else
					{
						let responseErrorMsg = resp.message;
						if (responseErrorMsg.search('</p>') != -1)
						{
							$(responseErrorMsg).insertAfter("#excelFileErrorMsg");
						}
						else
						{
							$("#excelFileErrorMsg").text(responseErrorMsg);
						}
					}
				});
			}
		});

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_URL) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_URL + exportType;
			}
		});

		$('#productsData').DataTable({
			// "bPaginate": false,
			// "searching": false,   // Search Box will Be Disabled
			"processing": true,
			"serverSide": true,
			"ordering": false,
			responsive: true,
			ajax: {
				url: FETCH_PRODUCTS,
				type: "POST",

				// data: function ( data) {
				// 	delete data.columns; 
				// },
			},
			columns: [
				{data: 'sn', width: "5%"},
				{data: 'productCode'},
				{data: 'productName'},
				{data: 'productType'},
				{data: 'action', width: "5%"},
			],
			// "pagingType": "full_numbers",
			"lengthMenu": [
				[10, 20, 50],
				[10, 20, 50]
			],
			language: {
				search: "_INPUT_",
				searchPlaceholder: "Search records",
			},
		});
		
		setFormValidation('#createProductForm');
	};

	var setFormValidation = function(id) {
		$(id).validate({
			errorPlacement: function(error, element) {
				$(element).parent('div').addClass('has-error');
			}
		});
	}
	
	return {
		Init: init
	}
})();

IEWebsiteAdmin.ProductManagePage = (function() {
	var init = function()
	{
		if ($("#productManagePageContainer").length <= 0)
		{
			return 0;
		};
	};
	
	return {
		Init: init
	}
})();

IEWebsiteAdmin.CommonJs = (function() {
	var init = function()
	{
		$("#alertMessage .close").click(function() {
			$("#alertMessage").fadeOut();
		});
	
		activateScroller()
		activeCurrentPageSideNavigation();
	};

	var activateScroller = function()
	{
		(function(){
			isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;
	
			if (isWindows && !$('body').hasClass('sidebar-mini')){
				// if we are on windows OS we activate the perfectScrollbar function
				$('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
	
				$('html').addClass('perfect-scrollbar-on');
			} else {
				$('html').addClass('perfect-scrollbar-off');
			}
		})();
	}
	
	var activeCurrentPageSideNavigation = function()
	{
		$('ul.main-menu li a').each(function(){
			if($($(this))[0].href==String(window.location)) {
				$(this).parent().addClass('active');	
			}
		});
		
		$('ul.main-menu li ul li a').each(function(){
			if($($(this))[0].href==String(window.location)) {
				
				$(this).parent().parent().parent().parent().addClass('active');
				$(this).parent().parent().parent().addClass('collapse in');				
			}
		});
	};

	return {
		Init: init
	}
})();

$(document).ready(function(){
	IEWebsiteAdmin.CommonJs.Init();
	IEWebsiteAdmin.ProductCreatePage.Init();
	IEWebsiteAdmin.ProductManagePage.Init();
});