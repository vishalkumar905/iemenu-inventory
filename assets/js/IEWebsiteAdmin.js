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
					if (resp.status && !_.isEmpty(resp.response))
					{
						$("#subCategory").html('').append($("<option>").attr('value', '').text('Choose Subcategory'));

						_.each(resp.response, function(row)
						{
							$("#subCategory").append($("<option>").attr('value', row.id).text(row.categoryName));
						});

						$("#subCategoryBox").show();
						$("#subCategory").selectpicker("refresh");
					}
				});
			}
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


		$('#productsData').DataTable({
			"bPaginate": false,
			"searching": false,   // Search Box will Be Disabled
			"processing": true,
			"serverSide": true,
			"ordering": false,
			responsive: true,
			ajax: {
				url: FETCH_PRODUCTS,
			},
			columns: [
				{data: 'sn', width: "5%"},
				{data: 'productCode'},
				{data: 'productName'},
				{data: 'productType'},
				{data: 'createdOn'},
				{data: 'action'},
			],
			"columnDefs":[  
				{  
					"targets":[0, 3, 4],  
					"orderable":false,  
				},  
			],

			"pagingType": "full_numbers",
			"lengthMenu": [
				[10, 25, 50, -1],
				[10, 25, 50, "All"]
			],
			responsive: true,
			language: {
				search: "_INPUT_",
				searchPlaceholder: "Search records",
			}
		});
	};
	
	return {
		Init: init
	}
})();

$(document).ready(function(){
	IEWebsiteAdmin.ProductCreatePage.Init();
	IEWebsiteAdmin.ProductManagePage.Init();
});