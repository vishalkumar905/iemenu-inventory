var IEWebsiteAdmin = IEWebsiteAdmin || {};

IEWebsiteAdmin.ProductCreatePage = (function() {
	var init = function()
	{
		if ($("#productPageContainer").length <= 0)
		{
			return 0;
		};

		// $("select option:selected").attr('disabled',"disabled");
		// $("#siUnit").selectpicker("refresh");

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
							// $("#siUnit").html('').append($("<option>").attr('value', '').text('Choose Unit'));
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
				type: "POST"
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
		
		IEWebsite.Utils.JqueryFormValidation('#createProductForm');
	};
	
	return {
		Init: init
	}
})();

IEWebsiteAdmin.MapTaxToProduct = (function() {
	var init = function()
	{
		if ($("#mappedProducts").length <= 0)
		{
			return 0;
		};

		$("#products").selectpicker('refresh');

		$('#mappedProducts').DataTable({
			// "bPaginate": false,
			// "searching": false,   // Search Box will Be Disabled
			"processing": true,
			"serverSide": true,
			"ordering": false,
			responsive: true,
			ajax: {
				url: FETCH_MAPED_TAX_PRODUCTS,
				type: "POST",

				// data: function ( data) {
				// 	delete data.columns; 
				// },
			},
			columns: [
				{data: 'sn', width: "5%"},
				{data: 'productName'},
				{data: 'tax'},
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
	
	};
	
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

IEWebsiteAdmin.VendorPage = (function() {
	var init = function()
	{
		if ($("#vendorPageContainer").length <= 0)
		{
			return 0;
		};

		$('#vendorsData').DataTable({
			// "bPaginate": false,
			// "searching": false,   // Search Box will Be Disabled
			"processing": true,
			"serverSide": true,
			"ordering": false,
			responsive: true,
			ajax: {
				url: FETCH_VENDORS,
				type: "POST"
			},
			columns: [
				{data: 'sn', width: "5%"},
				{data: 'vendorCode'},
				{data: 'vendorName'},
				{data: 'vendorEmail'},
				{data: 'vendorContact'},
				{data: 'gstNumber'},
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

		$('.dataTables_filter input[type="search"]').css(
			{'width':'350px','display':'inline-block'}
		);
		

		IEWebsite.Utils.JqueryFormValidation('#createVendorForm');
	};
	
	return {
		Init: init
	}
})();

IEWebsiteAdmin.VendorProductsPage = (function() {
	var init = function()
	{
		if ($("#vendorProductsPageContainer").length <= 0)
		{
			return 0;
		};

		$('#vendorProductsData').DataTable({
			// "bPaginate": false,
			// "searching": false,   // Search Box will Be Disabled
			"processing": true,
			"serverSide": true,
			"ordering": false,
			responsive: true,
			ajax: {
				url: FETCH_ASSIGNED_VENDOR_PRODUCTS,
				type: "POST"
			},
			columns: [
				{data: 'sn', width: "5%"},
				{data: 'vendorCode'},
				{data: 'vendorName'},
				{data: 'productName'},
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

		$(document).on('click', '.deleteAssignedVendorProduct', function() 
		{
			let vendorProductId = $(this).attr('data-vendorproductid');
			
			if (vendorProductId > 0)
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
					IEWebsite.Utils.AjaxPost(REMOVE_ASSIGNED_VENDOR_PRODUCT, {vendorProductId}, function(resp) {
						if (resp.status)
						{
							$('#vendorProductsData').DataTable().ajax.reload();

							swal({
								title: 'Deleted!',
								text: 'Assigned product removed.',
								type: 'success',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							});
						}
					});
				}, 
				function(dismiss)
				{
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
				});
			}
		});

		$("#category").change(function() {
			let categoryId = $(this).val();
			if (categoryId > 0)
			{
				fetchVendorProducts();

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

		$('#subCategory').change(function() {
			let subCategoryId = $(this).val();
			if (subCategoryId > 0)
			{
				fetchVendorProducts();
			}
		});
	};
	
	var fetchVendorProducts = function()
	{
		let vendorId = parseInt($('#vendor').val());
		let categoryId = parseInt($('#category').val());
		let subCategoryId = parseInt($('#subCategory').val());

		if (subCategoryId > 0)
		{
			categoryId = subCategoryId;
		}

		let url = FETCH_VENDOR_PRODUCTS + '/' + vendorId + '/' + categoryId; 

		IEWebsite.Utils.AjaxGet(url, null, function(resp) {
			if (resp.status)
			{
				$("#product").html('');

				if (!_.isEmpty(resp.response))
				{
					$("#product").html('').append($("<option>").attr('value', '').text('Choose Product'));

					_.each(resp.response, function(row)
					{
						$("#product").append($("<option>").attr('value', row.productId).text(row.productName));
					});
				}

				$("#product").selectpicker("refresh");
			}
		})
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
	IEWebsiteAdmin.MapTaxToProduct.Init();
	IEWebsiteAdmin.VendorPage.Init();
	IEWebsiteAdmin.VendorProductsPage.Init();
});