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
					let responseMsg = resp.message;

					if (resp.status)
					{
						file.val('');
						IEWebsite.Utils.Swal('Success', responseMsg, 'success');
						$('#productsData').DataTable().ajax.reload();
					}
					else
					{
						if (responseMsg.search('</p>') != -1)
						{
							$(responseMsg).insertAfter("#excelFileErrorMsg");
						}
						else
						{
							$("#excelFileErrorMsg").text(responseMsg);
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
	var vendorId = 0;
	var init = function()
	{
		if ($("#vendorProductsPageContainer").length <= 0)
		{
			return 0;
		};

		$('div.dropdown-menu.open').css({'z-index': 9999});
		
		$('#vendorProductsData').DataTable({
			// "bPaginate": false,
			// "searching": false,   // Search Box will Be Disabled
			"processing": true,
			"serverSide": true,
			"ordering": false,
			"destroy": true,
			"responsive": true,
			ajax: {
				url: FETCH_ASSIGNED_VENDOR_PRODUCTS,
				type: "POST",
				data: function(d) {
					d.vendorId = vendorId
				}
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

				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxGet(`${FETCH_SUB_CATEGORIES}/${categoryId}`, null , function(resp) {
					IEWebsite.Utils.HideLoadingScreen();

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

		$("#vendor").change(function() {
			vendorId = $(this).val();
			$('#vendorProductsData').DataTable().ajax.reload();
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

		if (vendorId > 0)
		{
			let url = FETCH_VENDOR_PRODUCTS + '/' + vendorId + '/' + categoryId; 

			IEWebsite.Utils.ShowLoadingScreen();			
			IEWebsite.Utils.AjaxGet(url, null, function(resp) {
				IEWebsite.Utils.HideLoadingScreen();

				if (resp.status)
				{
					$("#product").html('');
	
					if (!_.isEmpty(resp.response))
					{
						$("#product").html('');
						
						_.each(resp.response, function(row)
						{
							$("#product").append($("<option>").attr('value', row.productId).text(row.productName));
						});
					}
	
					$("#product").selectpicker("refresh");
				}
			});
		}
	};
	
	return {
		Init: init
	}
})();

IEWebsiteAdmin.VendorProductTaxPage = (function() {
	var staticColumnCount = 2;
	var searchBoxEnabled = false;

	var init = function()
	{
		if ($("#vendorProductTaxPageContainer").length <= 0)
		{
			return 0;
		};

		if (searchBoxEnabled)
		{
			$("#searchBar").on("keyup", _.debounce(loadVendorProducts, 500));
		}


		$("#vendor").change(function() {
			$("#manageVendorProductTaxContainer").show();
			loadVendorProducts();
		});

		$("#saveVendorProductTax").click(function() {
			let vendorId = $("#vendor").val();
			let productTaxData = $('#vendorProductTaxForm').serializeArray().reduce(function(obj, item) {
				obj[item.name] = item.value;
				return obj;
			}, {});

			if (!_.isEmpty(productTaxData))
			{
				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(`${SAVE_VENDOR_PRODUCT_TAX_MAPPING}/${vendorId}`, productTaxData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', 'Vendor product tax mapping completed..', 'success');
					}	
				});
			}
		});
	};

	var loadVendorProducts = function() {
		let searchText = $.trim($("#searchBar").val());
		let vendorId = $("#vendor").val();

		$("#vendorProductTableHeading, #vendorProductTableBody").html('');

		if (vendorId > 0)
		{
			let data = {
				search: searchText
			};

			IEWebsite.Utils.ShowLoadingScreen();

			IEWebsite.Utils.AjaxGet(`${FETCH_VENDOR_PRODUCT_WITH_TAXES}/${vendorId}`, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					if (!_.isEmpty(resp.response.tableHeadColumns))
					{
						searchBoxEnabled = true;
						$("#vendorProductTableHeading, #vendorProductTableBody").html('');
						$("#vendorProductWithTaxesData").show();
						
						let tableHeadColumns = '<tr>';
						_.each(resp.response.tableHeadColumns, function(row) {
							tableHeadColumns += '<th>'+ row +'</th>'
						});
						tableHeadColumns += '</tr>';
	
						$("#vendorProductTableHeading").html(tableHeadColumns);
						
						showTableData(resp.response.data);

						if (_.isEmpty(resp.response.data))
						{
							$("#vendorProductTableBody").append('<tr><td align="center" colspan="'+ resp.response.tableHeadColumns.length +'">No Record Found.</td></tr>');
						}
					}
				}
			});
		}

	}

	var showTableData = function(data) {
		if (!_.isEmpty(data))
		{
			_.each(data, function(row) {
				let rowCount = Object.keys(row).length - staticColumnCount;
				let tableRow = '<tr>';

				tableRow += '<td>'+ row.sn +'</td>';
				tableRow += '<td>'+ row.productName +'</td>';
				
				for(i = 0; i < rowCount; i++)
				{
					tableRow += '<td>'+ row[i] +'</td>';
				}

				tableRow += '</tr>';

				$("#vendorProductTableBody").append(tableRow);
			});

			$('[id^=taxHead-]').click(function() {
				let taxId = parseInt($(this).attr('taxid'));
				if (taxId > 0)
				{
					$('[id^=taxRow-'+ taxId + '-Product-]').removeAttr("checked");
					$('[id^=taxRow-'+ taxId + '-Product-]').prop('checked', this.checked);
				}
			});

			$('[id^=taxRow-]').click(function() {
				$(this).removeAttr("checked");

			});
		}
	};
	
	return {
		Init: init
	}
})();

IEWebsiteAdmin.OpeningStockPage = (function() {
	var searchBoxEnabled = false;
	var pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 10,
	}
	var openingStocksData = {};

	var init = function()
	{
		if ($("#openingInventoryPageContainer").length <= 0)
		{
			return 0;
		};

		$("#saveOpeningStock").attr("disabled", "true");
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#category").change(loadProducts);
		$("#productType").change(loadProducts);

		$("#saveOpeningStock").click(function() {
			if (pagination.currentPage !== pagination.currentPage)
			{
				return false;
			}

			if (!_.isEmpty(openingStocksData) && validateData())
			{
				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(SAVE_OPENING_INVETORY_PRODUCTS, openingStocksData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', 'Opening Inventory Created Successfully..', 'success');
						window.setTimeout(function() {
							window.location.reload();
						}, 5000);
					}
				});
			}
			else
			{
				alert('Please add product data.');
			}
		});
	
		$("#tableDataLimit").change(function() {
			pagination.limit = Number($(this).val());
			pagination.currentPage = 1;
			pagination.totalPages  = 0;
			loadProducts();
		});
	};

	var loadProducts = function() {
		let searchText = $.trim($("#searchBar").val());
		let category = $("#category").val();
		let productType = $("#productType").val();

		$("#openingStockTableBody").html('');

		if (!_.isEmpty(category) > 0)
		{
			let data = {
				search: searchText,
				category: category,
				productType: productType,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};
			
			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_OPENING_INVETORY_PRODUCTS, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					$("#manageVendorProductTaxContainer").show();

					pagination.totalPages = resp.response.pagination.totalPages;
					
					$("#saveOpeningStock").attr("disabled", "true");
					
					if (resp.response.pagination.totalPages == resp.response.pagination.current)
					{
						$("#saveOpeningStock").attr("disabled", false);
					}

					searchBoxEnabled = true;
					if (!_.isEmpty(resp.response.data))
					{
						showTableData(resp.response.data);
					}
					else
					{
						$("#openingStockTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
					}

					let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination);
					$("#pagination").html(paginationHtml);

					$("[id^=paginate-]").click(function() {
						let page = Number($(this).attr('page'));

						if (page > 0)
						{
							pagination.currentPage = page;
							loadProducts();
						}
					});
				}
			});
		}

	}

	var showTableData = function(data) 
	{
		if (!_.isEmpty(data))
		{
			_.each(data, function(row) {
				let qty = '',
					unitPrice = '',
					comment = '',
					subTotal = 0,
					unit = 0;

				if (openingStocksData[row.productId])
				{
					qty = openingStocksData[row.productId].qty,
					unit = openingStocksData[row.productId].unit,
					unitPrice = openingStocksData[row.productId].unitPrice,
					comment = openingStocksData[row.productId].comment,
					subTotal = qty * unitPrice;
				}

				subTotal = (Math.round(subTotal * 100) / 100).toFixed(2);

				let qtyInputHtml = '<input type="number" productid="'+ row.productId +'" style="width:60px" min="0" name="product[qty]['+ row.productId +']" value="'+ qty +'"/>';
				let commentInputHtml = '<input type="text" productid="'+ row.productId +'" name="product[comment]['+ row.productId +']" value="'+ comment +'" />';
				let subTotalInputHtml = '<span productid="'+ row.productId +'" id="product[subTotal]['+ row.productId +']" name="product[subTotal]['+ row.productId +']">'+ subTotal +'</span>';
				let unitPriceInputHtml = '<input productid="'+ row.productId +'" type="text" style="width:100px"  name="product[unitPrice]['+ row.productId +']" value="'+ unitPrice +'"/>';
				let siUnitSelectBoxHtml = productSiUnitSelectBox(row.productId, row.productSiUnitsDropdown, unit);

				let tableRow = '<tr>';
					tableRow += '<td><span productid="'+ row.productId +'" id="removeRow-'+ row.productId +'"><i class="material-icons cursor-pointer">clear</i></span></td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ siUnitSelectBoxHtml +'</td>';
					tableRow += '<td>'+ qtyInputHtml +'</td>';
					tableRow += '<td>'+ unitPriceInputHtml +'</td>';
					tableRow += '<td>'+ subTotalInputHtml +'</td>';
					tableRow += '<td>'+ commentInputHtml +'</td>';
					tableRow += '</tr>';
					
				$("#openingStockTableBody").append(tableRow);
			});

			$("span[id^=removeRow-]").click(function(){
				$(this).parent().parent().remove();
				let productId = $(this).attr('productid');

				if (openingStocksData[productId])
				{
					delete openingStocksData[productId];
				}
			});

			$("input[name^='product[qty]']").change(calulateSubtotal);
			$("select[name^='product[unit]']").change(calulateSubtotal);
			$("input[name^='product[unitPrice]']").keyup(calulateSubtotal);
			$("input[name^='product[comment]']").keyup(calulateSubtotal);
		}
	};

	var productSiUnitSelectBox = function(productId, siUnits, selectedUnitId = 0)
	{
		if (!_.isEmpty(siUnits))
		{
			let options = '';

			for(let i in siUnits)
			{
				let selected = selectedUnitId == i ? 'selected' : '';
				options += '<option value="'+ i +'" '+ selected +'>' + siUnits[i] +  '</option>';
			}

			return '<select productid="'+ productId +'" name="product[unit]['+ productId +']">'+ options +'</select>';
		}

		return '';
	}

	var validateData = function()
	{
		if (!_.isEmpty(openingStocksData))
		{
			for(let i in openingStocksData)
			{
				if (openingStocksData[i].qty > 0 && openingStocksData[i].unitPrice > 0)
				{
					return true;
				}
			}
		}

		return false;
	}
	
	var calulateSubtotal = function() 
	{
		let productId = Number($(this).attr('productid'));

		if (isNaN(productId))
		{
			throw 'Missing productId';
		}

		let qty = Number($("input[name='product[qty]["+ productId +"]']").val());
		let unitPrice = Number($("input[name='product[unitPrice]["+ productId +"]']").val());
		let unit = Number($("select[name='product[unit]["+ productId +"]']").val());
		let comment = $("input[name='product[comment]["+ productId +"]']").val();

		openingStocksData[productId] = {
			qty,
			unitPrice,
			unit,
			comment
		};

		if (productId > 0)
		{
			let totalPrice = qty * unitPrice;
				totalPrice = (Math.round(totalPrice * 100) / 100).toFixed(2);

			$("span[id='product[subTotal]["+ productId +"]']").text(totalPrice);
		}
	}

	return {
		Init: init
	}
})();

IEWebsiteAdmin.ClosingInventoryPage = (function() {
	var searchBoxEnabled = false;
	var pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 10,
	}
	var closingStocksData = {};

	var init = function()
	{
		if ($("#closingInventoryPageContainer").length <= 0)
		{
			return 0;
		};

		$("#saveClosingStock").attr("disabled", "true");
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#category").change(loadProducts);
		$("#productType").change(loadProducts);

		$("#saveClosingStock").click(function() {
			if (pagination.currentPage !== pagination.currentPage)
			{
				return false;
			}

			if (!_.isEmpty(closingStocksData) && validateData())
			{
				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(SAVE_CLOSING_INVETORY_PRODUCTS, closingStocksData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', 'Closing Inventory Created Successfully..', 'success');
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					}
				});
			}
			else
			{
				alert('Please add product data.');
			}
		});
	
		$("#tableDataLimit").change(function() {
			pagination.limit = Number($(this).val());
			pagination.currentPage = 1;
			pagination.totalPages  = 0;
			loadProducts();
		});
	};

	var loadProducts = function() {
		let searchText = $.trim($("#searchBar").val());
		let category = $("#category").val();
		let productType = $("#productType").val();

		$("#closingStockTableBody").html('');

		if (!_.isEmpty(category) > 0)
		{
			let data = {
				search: searchText,
				category: category,
				productType: productType,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_CLOSING_INVETORY_PRODUCTS, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					$("#manageClosingStockContainer").show();

					pagination.totalPages = resp.response.pagination.totalPages;
					
					$("#saveClosingStock").attr("disabled", "true");
					
					if (resp.response.pagination.totalPages == resp.response.pagination.current)
					{
						$("#saveClosingStock").attr("disabled", false);
					}

					searchBoxEnabled = true;
					if (!_.isEmpty(resp.response.data))
					{
						showTableData(resp.response.data);
					}
					else
					{
						$("#closingStockTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
					}

					let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination);
					$("#pagination").html(paginationHtml);

					$("[id^=paginate-]").click(function() {
						let page = Number($(this).attr('page'));

						if (page > 0)
						{
							pagination.currentPage = page;
							loadProducts();
						}
					});
				}
			});
		}

	}

	var showTableData = function(data) 
	{
		if (!_.isEmpty(data))
		{
			_.each(data, function(row) {
				let qty = '',
					unitPrice = '',
					comment = '',
					subTotal = 0,
					unit = 0;

				if (closingStocksData[row.productId])
				{
					qty = closingStocksData[row.productId].qty,
					unit = closingStocksData[row.productId].unit,
					unitPrice = closingStocksData[row.productId].unitPrice,
					comment = closingStocksData[row.productId].comment,
					subTotal = qty * unitPrice;
				}

				subTotal = (Math.round(subTotal * 100) / 100).toFixed(2);

				let qtyInputHtml = '<input type="number" productid="'+ row.productId +'" style="width:60px" min="0" name="product[qty]['+ row.productId +']" value="'+ qty +'"/>';
				let commentInputHtml = '<input type="text" productid="'+ row.productId +'" name="product[comment]['+ row.productId +']" value="'+ comment +'" />';
				let subTotalInputHtml = '<span productid="'+ row.productId +'" id="product[subTotal]['+ row.productId +']" name="product[subTotal]['+ row.productId +']">'+ subTotal +'</span>';
				let unitPriceInputHtml = '<input productid="'+ row.productId +'" type="text" style="width:100px"  name="product[unitPrice]['+ row.productId +']" value="'+ unitPrice +'"/>';
				let siUnitSelectBoxHtml = productSiUnitSelectBox(row.productId, row.productSiUnitsDropdown, unit);


				let tableRow = '<tr>';
					tableRow += '<td><span productid="'+ row.productId +'" id="removeRow-'+ row.productId +'"><i class="material-icons cursor-pointer">clear</i></span></td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ siUnitSelectBoxHtml +'</td>';
					tableRow += '<td>'+ qtyInputHtml +'</td>';
					// tableRow += '<td>'+ unitPriceInputHtml +'</td>';
					// tableRow += '<td>'+ subTotalInputHtml +'</td>';
					tableRow += '<td>'+ commentInputHtml +'</td>';
					tableRow += '</tr>';
					
				$("#closingStockTableBody").append(tableRow);
			});

			$("span[id^=removeRow-]").click(function(){
				$(this).parent().parent().remove();
				let productId = $(this).attr('productid');

				if (closingStocksData[productId])
				{
					delete closingStocksData[productId];
				}
			});

			$("input[name^='product[qty]']").change(calulateSubtotal);
			$("select[name^='product[unit]']").change(calulateSubtotal);
			$("input[name^='product[unitPrice]']").keyup(calulateSubtotal);
			$("input[name^='product[comment]']").keyup(calulateSubtotal);
		}
	};

	var productSiUnitSelectBox = function(productId, siUnits, selectedUnitId = 0)
	{
		if (!_.isEmpty(siUnits))
		{
			let options = '';

			for(let i in siUnits)
			{
				let selected = selectedUnitId == i ? 'selected' : '';
				options += '<option value="'+ i +'" '+ selected +'>' + siUnits[i] +  '</option>';
			}

			return '<select productid="'+ productId +'" name="product[unit]['+ productId +']">'+ options +'</select>';
		}

		return '';
	};

	var validateData = function()
	{
		if (!_.isEmpty(closingStocksData))
		{
			for(let i in closingStocksData)
			{
				if (closingStocksData[i].qty > 0)
				{
					return true;
				}
			}
		}

		return false;
	};
	
	var calulateSubtotal = function() 
	{
		let productId = Number($(this).attr('productid'));
		let qty = Number($("input[name='product[qty]["+ productId +"]']").val());
		let unitPrice = Number($("input[name='product[unitPrice]["+ productId +"]']").val());
		let unit = Number($("select[name='product[unit]["+ productId +"]']").val());
		let comment = $("input[name='product[comment]["+ productId +"]']").val();

		closingStocksData[productId] = {
			qty,
			unitPrice,
			unit,
			comment
		};

		if (productId > 0)
		{
			let totalPrice = qty * unitPrice;
				totalPrice = (Math.round(totalPrice * 100) / 100).toFixed(2);

			$("span[id='product[subTotal]["+ productId +"]']").text(totalPrice);
		}
	}

	return {
		Init: init
	}
})();

IEWebsiteAdmin.WastageInventoryPage = (function() {
	var searchBoxEnabled = false;
	var pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 10,
	}
	var wastageStocksData = {};

	var init = function()
	{
		if ($("#wastageInventoryPageContainer").length <= 0)
		{
			return 0;
		};

		$("#saveWastageStock").attr("disabled", "true");
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#category").change(loadProducts);
		$("#productType").change(loadProducts);

		$("#saveWastageStock").click(function() {
			if (pagination.currentPage !== pagination.currentPage)
			{
				return false;
			}

			if (!_.isEmpty(wastageStocksData) && validateData())
			{
				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(SAVE_WASTAGE_INVETORY_PRODUCTS, wastageStocksData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', 'Wastage Inventory Created Successfully..', 'success');
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					}
				});
			}
			else
			{
				alert('Please add product data.');
			}
		});
	
		$("#tableDataLimit").change(function() {
			pagination.limit = Number($(this).val());
			pagination.currentPage = 1;
			pagination.totalPages  = 0;
			loadProducts();
		});
	};

	var loadProducts = function() {
		let searchText = $.trim($("#searchBar").val());
		let category = $("#category").val();
		let productType = $("#productType").val();

		$("#wastageStockTableBody").html('');

		if (!_.isEmpty(category) > 0)
		{
			let data = {
				search: searchText,
				category: category,
				productType: productType,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_WASTAGE_INVETORY_PRODUCTS, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					$("#manageWastageStockContainer").show();

					pagination.totalPages = resp.response.pagination.totalPages;
					
					$("#saveWastageStock").attr("disabled", "true");
					
					if (resp.response.pagination.totalPages == resp.response.pagination.current)
					{
						$("#saveWastageStock").attr("disabled", false);
					}

					searchBoxEnabled = true;
					if (!_.isEmpty(resp.response.data))
					{
						showTableData(resp.response.data);
					}
					else
					{
						$("#wastageStockTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
					}

					let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination);
					$("#pagination").html(paginationHtml);

					$("[id^=paginate-]").click(function() {
						let page = Number($(this).attr('page'));

						if (page > 0)
						{
							pagination.currentPage = page;
							loadProducts();
						}
					});
				}
			});
		}

	};

	var showTableData = function(data) 
	{
		if (!_.isEmpty(data))
		{
			_.each(data, function(row) {
				let qty = '',
					unitPrice = '',
					comment = '',
					subTotal = 0,
					unit = 0;

				if (wastageStocksData[row.productId])
				{
					qty = wastageStocksData[row.productId].qty,
					unit = wastageStocksData[row.productId].unit,
					unitPrice = wastageStocksData[row.productId].unitPrice,
					comment = wastageStocksData[row.productId].comment,
					subTotal = qty * unitPrice;
				}

				subTotal = (Math.round(subTotal * 100) / 100).toFixed(2);

				let qtyInputHtml = '<input type="number" productid="'+ row.productId +'" style="width:60px" min="0" name="product[qty]['+ row.productId +']" value="'+ qty +'"/>';
				let commentInputHtml = '<input type="text" productid="'+ row.productId +'" name="product[comment]['+ row.productId +']" value="'+ comment +'" />';
				let subTotalInputHtml = '<span productid="'+ row.productId +'" id="product[subTotal]['+ row.productId +']" name="product[subTotal]['+ row.productId +']">'+ subTotal +'</span>';
				let unitPriceInputHtml = '<input productid="'+ row.productId +'" type="text" style="width:100px"  name="product[unitPrice]['+ row.productId +']" value="'+ unitPrice +'"/>';
				let siUnitSelectBoxHtml = productSiUnitSelectBox(row.productId, row.productSiUnitsDropdown, unit);


				let tableRow = '<tr>';
					tableRow += '<td><span productid="'+ row.productId +'" id="removeRow-'+ row.productId +'"><i class="material-icons cursor-pointer">clear</i></span></td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ siUnitSelectBoxHtml +'</td>';
					tableRow += '<td>'+ qtyInputHtml +'</td>';
					// tableRow += '<td>'+ unitPriceInputHtml +'</td>';
					// tableRow += '<td>'+ subTotalInputHtml +'</td>';
					tableRow += '<td>'+ commentInputHtml +'</td>';
					tableRow += '</tr>';
					
				$("#wastageStockTableBody").append(tableRow);
			});

			$("span[id^=removeRow-]").click(function(){
				$(this).parent().parent().remove();
				let productId = $(this).attr('productid');

				if (wastageStocksData[productId])
				{
					delete wastageStocksData[productId];
				}
			});

			$("input[name^='product[qty]']").change(calulateSubtotal);
			$("select[name^='product[unit]']").change(calulateSubtotal);
			$("input[name^='product[unitPrice]']").keyup(calulateSubtotal);
			$("input[name^='product[comment]']").keyup(calulateSubtotal);
		}
	};
	
	var calulateSubtotal = function() 
	{
		let productId = Number($(this).attr('productid'));
		let qty = Number($("input[name='product[qty]["+ productId +"]']").val());
		let unitPrice = Number($("input[name='product[unitPrice]["+ productId +"]']").val()) || 0;
		let unit = Number($("select[name='product[unit]["+ productId +"]']").val());
		let comment = $("input[name='product[comment]["+ productId +"]']").val();

		wastageStocksData[productId] = {
			qty,
			unitPrice,
			unit,
			comment
		};

		if (productId > 0)
		{
			let totalPrice = qty * unitPrice;
				totalPrice = (Math.round(totalPrice * 100) / 100).toFixed(2);

			$("span[id='product[subTotal]["+ productId +"]']").text(totalPrice);
		}
	};

	var productSiUnitSelectBox = function(productId, siUnits, selectedUnitId = 0)
	{
		if (!_.isEmpty(siUnits))
		{
			let options = '';

			for(let i in siUnits)
			{
				let selected = selectedUnitId == i ? 'selected' : '';
				options += '<option value="'+ i +'" '+ selected +'>' + siUnits[i] +  '</option>';
			}

			return '<select productid="'+ productId +'" name="product[unit]['+ productId +']">'+ options +'</select>';
		}

		return '';
	};

	var validateData = function()
	{
		if (!_.isEmpty(wastageStocksData))
		{
			for(let i in wastageStocksData)
			{
				if (wastageStocksData[i].qty > 0)
				{
					return true;
				}
			}
		}

		return false;
	};

	return {
		Init: init
	}
})();

IEWebsiteAdmin.DirectOrderPage = (function() {
	var pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 10,
	}
	var directOrdersData = {}, searchBoxEnabled = false;

	var init = function()
	{
		if ($("#directOrderPageContainer").length <= 0)
		{
			return;
		};

		$("#saveDirectOrder").attr("disabled", "true");

		if (searchBoxEnabled)
		{
			$("#searchBar").keyup(_.debounce(loadProducts, 500));
		}

		IEWebsite.Utils.JqueryFormValidation('#createOpeningStockForm');

		$("#vendor").change(loadVendorProductCategories);
		$("#category").change(loadProducts);

		$("#saveDirectOrder").click(function() {
			if (pagination.currentPage !== pagination.currentPage)
			{
				return false;
			}

			let vendorId = $("#vendor").val();
			let billDate = $("input[name='billDate']").val();
			let billNumber = $("input[name='billNumber']").val();

			if (_.isEmpty(billNumber))
			{
				alert('Please enter bill number.');
				return;
			}

			if (_.isEmpty(_.isEmpty(billDate)))
			{
				alert('Please enter bill date.');
				return;
			}
			
			if (!_.isEmpty(directOrdersData) && !_.isEmpty(vendorId) && !_.isEmpty(billDate) && !_.isEmpty(billNumber) && validateData())
			{
				let postData = {
					productData: directOrdersData,
				};

				postData.vendorId = vendorId;
				postData.billDate = billDate;
				postData.billNumber = billNumber;
				
				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(SAVE_DIRECT_ORDER_PRODUCTS, postData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', 'Data Saved Successfully..', 'success');
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					}
				});
			}
			else
			{
				alert('Please add product data.');
			}
		});
	
		$("#tableDataLimit").change(function() {
			pagination.limit = Number($(this).val());
			pagination.currentPage = 1;
			pagination.totalPages  = 0;
			loadProducts();
		});
	};

	var loadVendorProductCategories = function() {
		let vendorId = $("#vendor").val();
		if (vendorId >= 0)
		{
			IEWebsite.Utils.AjaxGet(FETCH_VENDOR_ASSIGNED_PRODUCT_CATEGORIES + '/' + vendorId, directOrdersData, function(resp) {
				if (resp.status)
				{	
					$("#category").html('');

					if (_.isEmpty(resp.response))
					{
						$("#manageDirectOrderContainer").hide();
						alert('Vendor has no product assigned');
					}
					else
					{
						_.each(resp.response, function(row)
						{
							$("#category").append($("<option>").attr('value', row.categoryId).text(row.categoryName));
						});
					}

					$("#category").selectpicker("refresh");
				}
			});
		}
	}

	var loadProducts = function() {
		let searchText = $.trim($("#searchBar").val());
		let category = $("#category").val();
		let vendor = $("#vendor").val();

		$("#directOrderTableBody").html('');

		if (!_.isEmpty(category) > 0)
		{
			let data = {
				search: searchText,
				category: category,
				vendorId: vendor,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_DIRECT_ORDER_PRODUCTS, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					$("#manageDirectOrderContainer").show();

					pagination.totalPages = resp.response.pagination.totalPages;
					
					$("#saveDirectOrder").attr("disabled", "true");
					
					if (resp.response.pagination.totalPages == resp.response.pagination.current)
					{
						$("#saveDirectOrder").attr("disabled", false);
					}

					if (!_.isEmpty(resp.response.data))
					{
						searchBoxEnabled = true;
						showTableData(resp.response.data);
					}
					else
					{
						$("#directOrderTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
					}

					let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination);
					$("#pagination").html(paginationHtml);

					$("[id^=paginate-]").click(function() {
						let page = Number($(this).attr('page'));

						if (page > 0)
						{
							pagination.currentPage = page;
							loadProducts();
						}
					});
				}
			});
		}

	};

	var showTableData = function(data) 
	{
		if (!_.isEmpty(data))
		{
			_.each(data, function(row) {
				let qty = '',
					unitPrice = '',
					comment = '',
					subTotal = 0;

				if (directOrdersData[row.productId])
				{
					qty = directOrdersData[row.productId].qty,
					unitPrice = directOrdersData[row.productId].unitPrice,
					comment = directOrdersData[row.productId].comment,
					subTotal = qty * unitPrice;
				}

				subTotal = (Math.round(subTotal * 100) / 100).toFixed(2);

				let qtyInputHtml = '<input type="number" productid="'+ row.productId +'" style="width:60px" min="0" name="product[qty]['+ row.productId +']" value="'+ qty +'"/>';
				let commentInputHtml = '<input type="text" productid="'+ row.productId +'" name="product[comment]['+ row.productId +']" value="'+ comment +'" />';
				let subTotalInputHtml = '<span productid="'+ row.productId +'" id="product[subTotal]['+ row.productId +']" name="product[subTotal]['+ row.productId +']">'+ subTotal +'</span>';
				let unitPriceInputHtml = '<input productid="'+ row.productId +'" type="text" style="width:100px"  name="product[unitPrice]['+ row.productId +']" value="'+ unitPrice +'"/>';


				let tableRow = '<tr>';
					tableRow += '<td><span productid="'+ row.productId +'" id="removeRow-'+ row.productId +'"><i class="material-icons cursor-pointer">clear</i></span></td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.hsnCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ row.selectSiUnit +'</td>';
					tableRow += '<td>'+ qtyInputHtml +'</td>';
					tableRow += '<td>'+ unitPriceInputHtml +'</td>';
					tableRow += '<td>'+ subTotalInputHtml +'</td>';
					tableRow += '<td>'+ commentInputHtml +'</td>';
					tableRow += '</tr>';
					
				$("#directOrderTableBody").append(tableRow);
			});

			$("span[id^=removeRow-]").click(function(){
				$(this).parent().parent().remove();
				let productId = $(this).attr('productid');

				if (directOrdersData[productId])
				{
					delete directOrdersData[productId];
				}
			});

			$("input[name^='product[qty]']").change(calulateSubtotal);
			$("select[name^='product[unit]']").change(calulateSubtotal);
			$("input[name^='product[unitPrice]']").keyup(calulateSubtotal);
			$("input[name^='product[comment]']").keyup(calulateSubtotal);
		}
	};
	
	var calulateSubtotal = function() 
	{
		let productId = Number($(this).attr('productid'));
		let qty = Number($("input[name='product[qty]["+ productId +"]']").val());
		let unitPrice = Number($("input[name='product[unitPrice]["+ productId +"]']").val());
		let unit = Number($("select[name='product[unit]["+ productId +"]']").val());
		let comment = $("input[name='product[comment]["+ productId +"]']").val();

		directOrdersData[productId] = {
			qty,
			unitPrice,
			unit,
			comment
		};

		if (productId > 0)
		{
			let totalPrice = qty * unitPrice;
				totalPrice = (Math.round(totalPrice * 100) / 100).toFixed(2);

			$("span[id='product[subTotal]["+ productId +"]']").text(totalPrice);
		}
	};

	var productSiUnitSelectBox = function(productId, siUnits, selectedUnitId = 0)
	{
		if (!_.isEmpty(siUnits))
		{
			let options = '';

			for(let i in siUnits)
			{
				let selected = selectedUnitId == i ? 'selected' : '';
				options += '<option value="'+ i +'" '+ selected +'>' + siUnits[i] +  '</option>';
			}

			return '<select productid="'+ productId +'" name="product[unit]['+ productId +']">'+ options +'</select>';
		}

		return '';
	};

	var validateData = function()
	{
		if (!_.isEmpty(directOrdersData))
		{
			for(let i in directOrdersData)
			{
				if (directOrdersData[i].qty > 0 && directOrdersData[i].unitPrice > 0)
				{
					return true;
				}
			}
		}

		return false;
	};

	return {
		Init: init
	}
})();

IEWebsiteAdmin.MasterReport = (function() {
	var init = function()
	{
		if ($("#reportPageContainer").length == 0)
		{
			return;
		}

		let datetimePickeObj = {
			format: 'DD/MM/YYYY',
			icons: {
				time: "fa fa-clock-o",
				date: "fa fa-calendar",
				up: "fa fa-chevron-up",
				down: "fa fa-chevron-down",
				previous: 'fa fa-chevron-left',
				next: 'fa fa-chevron-right',
				today: 'fa fa-screenshot',
				clear: 'fa fa-trash',
				close: 'fa fa-remove',
				inline: true
			}
		}

		$('#startDate').datetimepicker({
			...datetimePickeObj,
			maxDate: new Date(),
			defaultDate: new Date()
		});
		
		$('#endDate').datetimepicker({
			...datetimePickeObj,
			maxDate: new Date(),
			defaultDate: new Date()
		});
		
		$("#search").click(function() {
			let startDate = $("#startDate").val();
			let endDate = $("#endDate").val();
			let category = $("#category").val();

			if (startDate && endDate && category)
			{
				let postData = {
					startDate,
					endDate,
					category
				};

				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(FETCH_MASTER_REPORT, postData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					$("#reportTableBody").html('');

					if (resp.status)
					{	
						if (!_.isEmpty(resp.response))
						{
							showReportTableData(resp.response);
						}
					}
					else
					{
						IEWebsite.Utils.Notification(resp.message);
					}
				});
			}
		});
	};
	
	var showReportTableData = function(response)
	{
		if (!_.isEmpty(response.data))
		{
			_.each(response.data, function(row) {
				reportTableRow(row, response.startDate, response.endDate);
			});
		}
	}

	var reportTableRow = function(row, startDate, endDate)
	{
		if (_.isEmpty(row))
		{
			return ;
		}

		let tableRow = '<tr>'+
			'<td>' + startDate + '</td>' +
			'<td>' + row.productCode + '</td>' +
			'<td>' + row.productName + '</td>' +
			'<td>' + row.averageUnit + '</td>' +
			'<td>' + row.averagePrice + '</td>' +
			'<td>' + row.openingInventoryQty + '</td>' +
			'<td>' + row.openingInventoryAmt + '</td>' +
			'<td>' + row.purchaseInventoryQty + '</td>' +
			'<td>' + row.purchaseInventoryAmt + '</td>' +
			'<td>' + row.wastageInventoryQty + '</td>' +
			'<td>' + row.wastageInventoryAmt + '</td>' +
			'<td>' + row.transferQty + '</td>' +
			'<td>' + row.transferAmt + '</td>' +
			'<td>' + row.currentInventoryQty + '</td>' +
			'<td>' + row.currentInventoryAmt + '</td>' +
			'<td>' + row.closingInventoryQty + '</td>' +
			'<td>' + row.closingInventoryAmt + '</td>' +
			'<td>' + endDate + '</td>' +
			'<td>' + row.consumptionQty + '</td>' +
			'<td>' + row.consumptionAmt + '</td>' +
		'<tr>';

		$("#reportTableBody").append(tableRow);
	}

	return {
		Init: init
	}
})();

IEWebsiteAdmin.DirectTransferPage = (function() {
	var searchBoxEnabled = false;
	var pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 10,
	}
	var directTransfersData = {};

	var init = function()
	{
		if ($("#directTransferPageContainer").length <= 0)
		{
			return 0;
		};

		$("#saveDirectTransfer").attr("disabled", "true");
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#category").change(loadProducts);
		$("#productType").change(loadProducts);

		$("#saveDirectTransfer").click(function() {
			if (pagination.currentPage !== pagination.currentPage)
			{
				return false;
			}

			let outlet = $("#outlet").val();
			if (_.isEmpty(outlet))
			{
				alert('Please select an outlet.');
				return false;
			}

			if (!_.isEmpty(directTransfersData) && validateData())
			{
				let postData = {
					productData: directTransfersData,
					outlet
				};

				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(SAVE_DIRECT_TRANSFER_PRODUCTS, postData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', 'Direct Transfer Created Successfully..', 'success');
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					}
				});
			}
			else
			{
				alert('Please add product data.');
			}
		});
	
		$("#tableDataLimit").change(function() {
			pagination.limit = Number($(this).val());
			pagination.currentPage = 1;
			pagination.totalPages  = 0;
			loadProducts();
		});
	};

	var loadProducts = function() {
		let searchText = $.trim($("#searchBar").val());
		let category = $("#category").val();
		let productType = $("#productType").val();

		$("#directTransferTableBody").html('');

		if (!_.isEmpty(category) > 0)
		{
			let data = {
				search: searchText,
				category: category,
				productType: productType,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_DIRECT_TRANSFER_PRODUCTS, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					$("#manageDirectTransferContainer").show();

					pagination.totalPages = resp.response.pagination.totalPages;
					
					$("#saveDirectTransfer").attr("disabled", "true");
					
					if (resp.response.pagination.totalPages == resp.response.pagination.current)
					{
						$("#saveDirectTransfer").attr("disabled", false);
					}

					searchBoxEnabled = true;
					if (!_.isEmpty(resp.response.data))
					{
						showTableData(resp.response.data);
					}
					else
					{
						$("#directTransferTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
					}

					let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination);
					$("#pagination").html(paginationHtml);

					$("[id^=paginate-]").click(function() {
						let page = Number($(this).attr('page'));

						if (page > 0)
						{
							pagination.currentPage = page;
							loadProducts();
						}
					});
				}
			});
		}

	}

	var showTableData = function(data) 
	{
		if (!_.isEmpty(data))
		{
			_.each(data, function(row) {
				let qty = '',
					unitPrice = '',
					comment = '',
					subTotal = 0,
					unit = 0;

				if (directTransfersData[row.productId])
				{
					qty = directTransfersData[row.productId].qty,
					unit = directTransfersData[row.productId].unit,
					unitPrice = directTransfersData[row.productId].unitPrice,
					comment = directTransfersData[row.productId].comment,
					subTotal = qty * unitPrice;
				}

				subTotal = (Math.round(subTotal * 100) / 100).toFixed(2);

				let qtyInputHtml = '<input type="number" productid="'+ row.productId +'" style="width:60px" min="0" name="product[qty]['+ row.productId +']" value="'+ qty +'"/>';
				let commentInputHtml = '<input type="text" productid="'+ row.productId +'" name="product[comment]['+ row.productId +']" value="'+ comment +'" />';
				let subTotalInputHtml = '<span productid="'+ row.productId +'" id="product[subTotal]['+ row.productId +']" name="product[subTotal]['+ row.productId +']">'+ subTotal +'</span>';
				let unitPriceInputHtml = '<input productid="'+ row.productId +'" type="text" style="width:100px"  name="product[unitPrice]['+ row.productId +']" value="'+ unitPrice +'"/>';
				let siUnitSelectBoxHtml = productSiUnitSelectBox(row.productId, row.productSiUnitsDropdown, unit);


				let tableRow = '<tr>';
					tableRow += '<td><span productid="'+ row.productId +'" id="removeRow-'+ row.productId +'"><i class="material-icons cursor-pointer">clear</i></span></td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ siUnitSelectBoxHtml +'</td>';
					tableRow += '<td>'+ qtyInputHtml +'</td>';
					// tableRow += '<td>'+ unitPriceInputHtml +'</td>';
					// tableRow += '<td>'+ subTotalInputHtml +'</td>';
					tableRow += '<td>'+ commentInputHtml +'</td>';
					tableRow += '</tr>';
					
				$("#directTransferTableBody").append(tableRow);
			});

			$("span[id^=removeRow-]").click(function(){
				$(this).parent().parent().remove();
				let productId = $(this).attr('productid');

				if (directTransfersData[productId])
				{
					delete directTransfersData[productId];
				}
			});

			$("input[name^='product[qty]']").change(calulateSubtotal);
			$("select[name^='product[unit]']").change(calulateSubtotal);
			$("input[name^='product[unitPrice]']").keyup(calulateSubtotal);
			$("input[name^='product[comment]']").keyup(calulateSubtotal);
		}
	};

	var productSiUnitSelectBox = function(productId, siUnits, selectedUnitId = 0)
	{
		if (!_.isEmpty(siUnits))
		{
			let options = '';

			for(let i in siUnits)
			{
				let selected = selectedUnitId == i ? 'selected' : '';
				options += '<option value="'+ i +'" '+ selected +'>' + siUnits[i] +  '</option>';
			}

			return '<select productid="'+ productId +'" name="product[unit]['+ productId +']">'+ options +'</select>';
		}

		return '';
	};

	var validateData = function()
	{
		if (!_.isEmpty(directTransfersData))
		{
			for(let i in directTransfersData)
			{
				if (directTransfersData[i].qty > 0)
				{
					return true;
				}
			}
		}

		return false;
	};
	
	var calulateSubtotal = function() 
	{
		let productId = Number($(this).attr('productid'));
		let qty = Number($("input[name='product[qty]["+ productId +"]']").val());
		let unitPrice = Number($("input[name='product[unitPrice]["+ productId +"]']").val());
		let unit = Number($("select[name='product[unit]["+ productId +"]']").val());
		let comment = $("input[name='product[comment]["+ productId +"]']").val();

		directTransfersData[productId] = {
			qty,
			unitPrice,
			unit,
			comment
		};

		if (productId > 0)
		{
			let totalPrice = qty * unitPrice;
				totalPrice = (Math.round(totalPrice * 100) / 100).toFixed(2);

			$("span[id='product[subTotal]["+ productId +"]']").text(totalPrice);
		}
	}

	return {
		Init: init
	}
})();


IEWebsiteAdmin.CustomPagination = (function() {
	
	var init = function(pagination)
	{
		let html = '';

		if (typeof pagination.totalPages != 'undefined' && pagination.totalPages > 1)
		{
			let { totalPages, current } = pagination;

			for (let index = 1; index <= totalPages; index++) 
			{
				let active = current == index ? 'active' : ''; 
				html += '<li class="'+ active +'"><a id="paginate-'+ index +'" page="'+ index +'" href="javascript:void(0);">' + index + '</li>';
			}
	
			html = '<ul class="pagination pagination-info">' + html + '</ul>';
		}

		return html;
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
	IEWebsiteAdmin.VendorProductTaxPage.Init();
	IEWebsiteAdmin.OpeningStockPage.Init();
	IEWebsiteAdmin.ClosingInventoryPage.Init();
	IEWebsiteAdmin.WastageInventoryPage.Init();
	IEWebsiteAdmin.DirectOrderPage.Init();
	IEWebsiteAdmin.MasterReport.Init();
	IEWebsiteAdmin.DirectTransferPage.Init();
});