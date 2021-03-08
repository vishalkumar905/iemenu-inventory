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
				type: "POST",
				complete: function()
				{
					$("a[id^=deleteBtn-]").click(deleteProduct);
				}
			},
			columns: [
				{data: 'sn', width: "5%"},
				{data: 'productCode'},
				{data: 'productName'},
				{data: 'productType'},
				{data: 'action', width: "11%"},
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

	var deleteProduct = function()
	{
		let splittedId = $(this).attr('id').split('-');
		let productId = splittedId[1];
		let confirmation = confirm("Press a Sure to delete!");

		if(confirmation) 
		{
			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(DELETE_PRODUCT + productId, productId, function(resp) {

				IEWebsite.Utils.HideLoadingScreen();

				if(resp.status)
				{
					IEWebsite.Utils.message(resp.message);
					$('#productsData').DataTable().ajax.reload();
				}
				else
				{
					IEWebsite.Utils.message(resp.message, "danger");
				}
			});
			
		}
		else 
		{
			
		}
	}
	
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
	var searchBarText = '';

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
			let resetPage = false;
			if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
			{
				resetPage = true;
			}
			else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
			{
				resetPage = true;
			}

			if (resetPage)
			{
				pagination = {
					currentPage: 1,
					totalPages: 0,
					limit: 10,
				}
			}

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
	var searchBarText = '';

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
			let resetPage = false;
			if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
			{
				resetPage = true;
			}
			else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
			{
				resetPage = true;
			}

			if (resetPage)
			{
				pagination = {
					currentPage: 1,
					totalPages: 0,
					limit: 10,
				}
			}

			let data = {
				search: searchText,
				category: category,
				productType: productType,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};

			searchBarText = searchText;

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
	var searchBarText = '';

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
			let resetPage = false;
			if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
			{
				resetPage = true;
			}
			else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
			{
				resetPage = true;
			}

			if (resetPage)
			{
				pagination = {
					currentPage: 1,
					totalPages: 0,
					limit: 10,
				}
			}

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
	var searchBarText = '';
	
	var init = function()
	{
		if ($("#directOrderPageContainer").length <= 0)
		{
			return;
		};

		$("#saveDirectOrder").attr("disabled", "true");
		$("#searchBar").keyup(_.debounce(loadProducts, 500));

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
			
			if (_.isEmpty(billDate))
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
		let vendorId = parseInt($("#vendor").val());
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
			let resetPage = false;
			if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
			{
				resetPage = true;
			}
			else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
			{
				resetPage = true;
			}

			if (resetPage)
			{
				pagination = {
					currentPage: 1,
					totalPages: 0,
					limit: 10,
				}
			}
			
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
					unit = '',
					comment = '',
					subTotal = 0;

				if (directOrdersData[row.productId])
				{
					qty = directOrdersData[row.productId].qty,
					unit = directOrdersData[row.productId].unit,
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

				if (unit)
				{
					$("select[name='product[unit]["+ row.productId +"]']").val(unit);
				}
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
		
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#search").click(loadProducts);

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_REPORTS) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_REPORTS + '/' + exportType;
			}
		});
	};
	
	var loadProducts = function() {
		let search = $("#searchBar").val();
		let startDate = $("#startDate").val();
		let endDate = $("#endDate").val();
		let category = $("#category").val();

		if (startDate && endDate && category)
		{
			let postData = {
				startDate,
				endDate,
				category,
				search
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
	}

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
			'<td>' + row.transferQtyIn + '</td>' +
			'<td>' + row.transferQtyOut + '</td>' +
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

IEWebsiteAdmin.OpeningInventoryReport = (function() {
    var pageTableBodySelector = $("#openingInventoryReportTableBody");

	var init = function()
	{
		if ($("#openingInventoryReportPageContainer").length == 0)
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
		
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#search").click(loadProducts);

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_OPENING_STOCK_REPORT) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_OPENING_STOCK_REPORT + '/' + exportType;
			}
		});
	};
	
	var loadProducts = function() {
		let search = $("#searchBar").val();
		let startDate = $("#startDate").val();
		let endDate = $("#endDate").val();
		let openingStockNumber = $("#openingStockNumber").val();
		let category = $("#category").val();

		if (true)
		{
			let postData = {
				startDate,
				endDate,
				category,
				search,
				openingStockNumber
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_OPENING_INVENTORY_REPORT, postData, function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
				pageTableBodySelector.html('');

				if (resp.status)
				{	
					if (!_.isEmpty(resp.response))
					{
						showTableData(resp.response);
					}
				}
				else
				{
					pageTableBodySelector.html('<tr align="center"><td colspan="10">No Records Found</td></tr>');
					IEWebsite.Utils.Notification(resp.message);
				}
			});
		}
	}

	var showTableData = function(response)
	{
		if (!_.isEmpty(response.data))
		{
			_.each(response.data, function(row) {
				showTableRow(row, response.startDate, response.endDate);
			});
		}
	}

	var showTableRow = function(row, startDate, endDate)
	{
		if (_.isEmpty(row))
		{
			return ;
		}

		let tableRow = '<tr>'+
			'<td>' + row.sn + '</td>' +
			'<td>' + row.openingStockNumber + '</td>' +
			'<td>' + row.productCode + '</td>' +
			'<td>' + row.productName + '</td>' +
			'<td>' + row.unitName + '</td>' +
			'<td>' + row.productQuantity + '</td>' +
			'<td>' + row.productUnitPrice + '</td>' +
			'<td>' + row.comment + '</td>' +
			'<td>' + row.createdOn + '</td>' +
		'<tr>';

		pageTableBodySelector.append(tableRow);
	}

	return {
		Init: init
	}
})();

IEWebsiteAdmin.ClosingInventoryReport = (function() {
    var pageTableBodySelector = $("#closingInventoryReportTableBody");

	var init = function()
	{
		if ($("#closingInventoryReportPageContainer").length == 0)
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
		
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#search").click(loadProducts);

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_CLOSING_STOCK_REPORT) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_CLOSING_STOCK_REPORT + '/' + exportType;
			}
		});
	};
	
	var loadProducts = function() {
		let search = $("#searchBar").val();
		let startDate = $("#startDate").val();
		let endDate = $("#endDate").val();
		let closingStockNumber = $("#closingStockNumber").val();
		let category = $("#category").val();

		if (true)
		{
			let postData = {
				startDate,
				endDate,
				category,
				search,
				closingStockNumber
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_CLOSING_INVENTORY_REPORT, postData, function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
				pageTableBodySelector.html('');

				if (resp.status)
				{	
					if (!_.isEmpty(resp.response))
					{
						showTableData(resp.response);
					}
				}
				else
				{
					pageTableBodySelector.html('<tr align="center"><td colspan="10">No Records Found</td></tr>');
					IEWebsite.Utils.Notification(resp.message);
				}
			});
		}
	}

	var showTableData = function(response)
	{
		if (!_.isEmpty(response.data))
		{
			_.each(response.data, function(row) {
				showTableRow(row, response.startDate, response.endDate);
			});
		}
	}

	var showTableRow = function(row, startDate, endDate)
	{
		if (_.isEmpty(row))
		{
			return ;
		}

		let tableRow = '<tr>'+
			'<td>' + row.sn + '</td>' +
			'<td>' + row.openingStockNumber + '</td>' +
			'<td>' + row.productCode + '</td>' +
			'<td>' + row.productName + '</td>' +
			'<td>' + row.unitName + '</td>' +
			'<td>' + row.productQuantity + '</td>' +
			'<td>' + row.comment + '</td>' +
			'<td>' + row.createdOn + '</td>' +
		'<tr>';

		pageTableBodySelector.append(tableRow);
	}

	return {
		Init: init
	}
})();

IEWebsiteAdmin.WastageInventoryReport = (function() {
    var pageTableBodySelector = $("#wastageInventoryReportTableBody");

	var init = function()
	{
		if ($("#wastageInventoryReportPageContainer").length == 0)
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
		
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#search").click(loadProducts);

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_WASTAGE_INVENTORY_REPORT) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_WASTAGE_INVENTORY_REPORT + '/' + exportType;
			}
		});
	};
	
	var loadProducts = function() {
		let search = $("#searchBar").val();
		let startDate = $("#startDate").val();
		let endDate = $("#endDate").val();
		let wastageStockNumber = $("#wastageStockNumber").val();
		let category = $("#category").val();

		if (true)
		{
			let postData = {
				startDate,
				endDate,
				category,
				search,
				wastageStockNumber
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_WASTAGE_INVENTORY_REPORT, postData, function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
				pageTableBodySelector.html('');

				if (resp.status)
				{	
					if (!_.isEmpty(resp.response))
					{
						showTableData(resp.response);
					}
				}
				else
				{
					pageTableBodySelector.html('<tr align="center"><td colspan="10">No Records Found</td></tr>');
					IEWebsite.Utils.Notification(resp.message);
				}
			});
		}
	}

	var showTableData = function(response)
	{
		if (!_.isEmpty(response.data))
		{
			_.each(response.data, function(row) {
				showTableRow(row, response.startDate, response.endDate);
			});
		}
	}

	var showTableRow = function(row, startDate, endDate)
	{
		if (_.isEmpty(row))
		{
			return ;
		}

		let tableRow = '<tr>'+
			'<td>' + row.sn + '</td>' +
			'<td>' + row.openingStockNumber + '</td>' +
			'<td>' + row.productCode + '</td>' +
			'<td>' + row.productName + '</td>' +
			'<td>' + row.unitName + '</td>' +
			'<td>' + row.productQuantity + '</td>' +
			'<td>' + row.comment + '</td>' +
			'<td>' + row.createdOn + '</td>' +
		'<tr>';

		pageTableBodySelector.append(tableRow);
	}

	return {
		Init: init
	}
})();

IEWebsiteAdmin.DirectOrderReport = (function() {
    var pageTableBodySelector = $("#directOrderReportTableBody");

	var init = function()
	{
		if ($("#directOrderReportPageContainer").length == 0)
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
		
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#search").click(loadProducts);

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_DIRECT_ORDER_REPORT) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_DIRECT_ORDER_REPORT + '/' + exportType;
			}
		});
	};
	
	var loadProducts = function() {
		let search = $("#searchBar").val();
		let startDate = $("#startDate").val();
		let endDate = $("#endDate").val();
		let grnNumber = $("#grnNumber").val();
		let category = $("#category").val();

		if (true)
		{
			let postData = {
				startDate,
				endDate,
				category,
				search,
				grnNumber
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_DIRECT_ORDER_REPORT, postData, function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
				pageTableBodySelector.html('');

				if (resp.status)
				{	
					if (!_.isEmpty(resp.response))
					{
						showTableData(resp.response);
					}
				}
				else
				{
					pageTableBodySelector.html('<tr align="center"><td colspan="10">No Records Found</td></tr>');
					IEWebsite.Utils.Notification(resp.message);
				}
			});
		}
	}

	var showTableData = function(response)
	{
		if (!_.isEmpty(response.data))
		{
			_.each(response.data, function(row) {
				showTableRow(row, response.startDate, response.endDate);
			});
		}
	}

	var showTableRow = function(row, startDate, endDate)
	{
		if (_.isEmpty(row))
		{
			return ;
		}

		let tableRow = '<tr>'+
			'<td>' + row.sn + '</td>' +
			'<td>' + row.vendorNameWithCode + '</td>' +
			'<td>' + row.billNumber + '</td>' +
			'<td>' + row.billDate + '</td>' +
			'<td>' + row.openingStockNumber + '</td>' +
			'<td>' + row.productCode + '</td>' +
			'<td>' + row.productName + '</td>' +
			'<td>' + row.unitName + '</td>' +
			'<td>' + row.productQuantity + '</td>' +
			'<td>' + row.productUnitPrice + '</td>' +
			'<td>' + row.comment + '</td>' +
			'<td>' + row.createdOn + '</td>' +
		'<tr>';

		pageTableBodySelector.append(tableRow);
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
	var searchBarText = '';

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
			let resetPage = false;
			if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
			{
				resetPage = true;
			}
			else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
			{
				resetPage = true;
			}

			if (resetPage)
			{
				pagination = {
					currentPage: 1,
					totalPages: 0,
					limit: 10,
				}
			}

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

IEWebsiteAdmin.RequestTransferPage = (function() {
	var searchBoxEnabled = false;
	var pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 10,
	}
	var requestTransfersData = {};
	var searchBarText = '';

	var init = function()
	{
		if ($("#requestTransferPageContainer").length <= 0)
		{
			return 0;
		};

		$("#saveRequestTransfer").attr("disabled", "true");
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#category").change(loadProducts);
		$("#productType").change(loadProducts);

		$("#saveRequestTransfer").click(function() {
			if (pagination.currentPage !== pagination.currentPage)
			{
				return false;
			}

            let outlet = $("#outlet").val();
            let requestTransferType = $("#requestTransferType").val();
            
			if (_.isEmpty(outlet))
			{
				alert('Please select an outlet.');
				return false;
			}

			if (_.isEmpty(requestTransferType))
			{
				alert('Please select a request type.');
				return false;
			}

			if (!_.isEmpty(requestTransfersData) && validateData())
			{
				let postData = {
					productData: requestTransfersData,
                    outlet,
                    requestTransferType
				};

				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(SAVE_REQUEST_TRANSFER_PRODUCTS, postData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', resp.message, 'success');
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					}
					else
					{
						IEWebsite.Utils.Swal('', resp.message, 'error');
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
		let requestTransferType = $("#requestTransferType").val();

		$("#requestTransferTableBody").html('');

		if (!_.isEmpty(category) > 0)
		{
			let resetPage = false;
			if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
			{
				resetPage = true;
			}
			else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
			{
				resetPage = true;
			}

			if (resetPage)
			{
				pagination = {
					currentPage: 1,
					totalPages: 0,
					limit: 10,
				}
			}

			let data = {
				search: searchText,
				category: category,
				productType: productType,
				requestTransferType: requestTransferType,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_REQUEST_TRANSFER_PRODUCTS, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					$("#manageRequestTransferContainer").show();

					pagination.totalPages = resp.response.pagination.totalPages;
					
					$("#saveRequestTransfer").attr("disabled", "true");
					
					if (resp.response.pagination.totalPages == resp.response.pagination.current)
					{
						$("#saveRequestTransfer").attr("disabled", false);
					}

					searchBoxEnabled = true;
					if (!_.isEmpty(resp.response.data))
					{
						showTableData(resp.response.data);
					}
					else
					{
						$("#requestTransferTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
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

				if (requestTransfersData[row.productId])
				{
					qty = requestTransfersData[row.productId].qty,
					unit = requestTransfersData[row.productId].unit,
					unitPrice = requestTransfersData[row.productId].unitPrice,
					comment = requestTransfersData[row.productId].comment,
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
					
				$("#requestTransferTableBody").append(tableRow);
			});

			$("span[id^=removeRow-]").click(function(){
				$(this).parent().parent().remove();
				let productId = $(this).attr('productid');

				if (requestTransfersData[productId])
				{
					delete requestTransfersData[productId];
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
		if (!_.isEmpty(requestTransfersData))
		{
			for(let i in requestTransfersData)
			{
				if (requestTransfersData[i].qty > 0)
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

		requestTransfersData[productId] = {
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

IEWebsiteAdmin.ManageRequestTransferPage = (function() {
	var currentTab, searchBarText, pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 20,
	}, disputePagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 20,
	}; 

	var init = function()
	{
		if ($("#manageRequestTransferPageContainer").length <= 0)
		{
			return;
		};

		currentTab = $('.nav-tabs li.active > a').text();

		$('.nav-tabs a').on('shown.bs.tab', function(event){
			currentTab = $(event.target).text();         // active tab
			// $(event.relatedTarget).text();  // previous tab
		});

		loadProducts('Incomming');
		loadProducts('Outgoing');
		loadDisputeRequests();
	}

	var loadDisputeRequests = function()
	{
		$("#manageDisputeTableBody").html('');
		
		let params = {
			page: disputePagination.currentPage,
			limit: Number(disputePagination.limit),
		};

		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxPost(FETCH_DISPUTE_REQUESTS, params, function(resp) {
			IEWebsite.Utils.HideLoadingScreen();

			if (resp.status)
			{
				$("#disputePagination").html(IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination));

				disputePagination.totalPages = resp.response.pagination.totalPages;

				if (!_.isEmpty(resp.response.data))
				{
					_.each(resp.response.data, function(row) {
	
						let tableRow = '<tr>';
							tableRow += '<td>'+ row.sn +'</td>';
							tableRow += '<td>'+ row.transferFrom +'</td>';
							tableRow += '<td>'+ row.transferTo +'</td>';
							tableRow += '<td>'+ row.requestType +'</td>';
							tableRow += '<td>'+ row.createdOn +'</td>';
							tableRow += '<td>'+ row.action +'</td>';
							tableRow += '</tr>';
	
							$("#manageDisputeTableBody").append(tableRow);
					});
				}
				else 
				{
					$("#manageDisputeTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
				}


				$("[id^=paginate-]").click(function() {
					let page = Number($(this).attr('page'));

					if (page > 0)
					{
						disputePagination.currentPage = page;
						loadDisputeRequests();
					}
				});
			}
		});
	}

	var loadProducts = function(type = null) {
		let searchText = $.trim($("#searchBar").val());

		$("#manageRequestTransferTableBody").html('');

		let resetPage = false;
		if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
		{
			resetPage = true;
		}
		else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
		{
			resetPage = true;
		}

		if (resetPage)
		{
			pagination = {
				currentPage: 1,
				totalPages: 0,
				limit: 10,
			}
		}

		let data = {
			search: searchText,
			page: pagination.currentPage,
			limit: Number(pagination.limit),
		};


		let apiUrl = FETCH_REQUESTS;

		
		if (!type)
		{
			type = currentTab;
		}

		if (type == 'Incomming')
		{
			apiUrl += '/' + INCOMMING;
		}
		else if (type == 'Outgoing')
		{
			apiUrl += '/' + OUTGOING;
		}
		else 
		{
			throw new Exception('Invalid tab');
		}

		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxPost(apiUrl, data , function(resp) {
			IEWebsite.Utils.HideLoadingScreen();

			if (resp.status)
			{
				let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination);
				if (type == 'Incomming')
				{
					$("#incommingPagination").html(paginationHtml);
					$("#manageIncommingRequestTransferTableBody").html('');
				}
				else if (type == 'Outgoing')
				{
					$("#outgoingPagination").html(paginationHtml);
					$("#manageOutgoingRequestTransferTableBody").html('');
				}

				pagination.totalPages = resp.response.pagination.totalPages;
				showTableData(resp.response.data, type);

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

	var showTableData = function(data, type) 
	{
		if (!_.isEmpty(data))
		{
			let action = null;
			_.each(data, function(row) {

				let tableRow = '<tr>';
					tableRow += '<td>'+ row.sn +'</td>';
					tableRow += '<td>'+ row.transferFrom +'</td>';
					tableRow += '<td>'+ row.transferTo +'</td>';
					tableRow += '<td>'+ row.requestType +'</td>';
					tableRow += '<td>'+ row.status +'</td>';
					tableRow += '<td>'+ row.createdOn +'</td>';
					tableRow += '<td>'+ row.action +'</td>';
					tableRow += '</tr>';
				
				if (type == 'Incomming')
				{
					$("#manageIncommingRequestTransferTableBody").append(tableRow);
				}
				else if (type == 'Outgoing')
				{
					$("#manageOutgoingRequestTransferTableBody").append(tableRow);
				}
			});
		}
		else
		{
			if (type == 'Incomming')
			{
				$("#manageIncommingRequestTransferTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
			}
			else if (type == 'Outgoing')
			{
				$("#manageOutgoingRequestTransferTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
			}
		}
	};

	return {
		Init: init
	}
})();

IEWebsiteAdmin.ReplenishmentRequestPage = (function() {
	var searchBoxEnabled = false;
	var pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 10,
	}
	var replenishmentRequestsData = {};
	var searchBarText = '';

	var init = function()
	{
		if ($("#replenishmentRequestPageContainer").length <= 0)
		{
			return 0;
		};

		$("#saveReplenishmentRequest").attr("disabled", "true");
		$("#searchBar").keyup(_.debounce(loadProducts, 500));
		$("#category").change(loadProducts);
		$("#productType").change(loadProducts);

		$("#saveReplenishmentRequest").click(function() {
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

			if (!_.isEmpty(replenishmentRequestsData) && validateData())
			{
				let postData = {
					productData: replenishmentRequestsData,
					outlet
				};

				IEWebsite.Utils.ShowLoadingScreen();
				IEWebsite.Utils.AjaxPost(SAVE_REPLENISMENT_REQUEST_PRODUCTS, postData, function(resp) {
					IEWebsite.Utils.HideLoadingScreen();
					if (resp.status)
					{
						IEWebsite.Utils.Swal('Success', resp.message, 'success');
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

		$("#replenishmentRequestTableBody").html('');

		if (!_.isEmpty(category) > 0)
		{
			let resetPage = false;
			if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
			{
				resetPage = true;
			}
			else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
			{
				resetPage = true;
			}

			if (resetPage)
			{
				pagination = {
					currentPage: 1,
					totalPages: 0,
					limit: 10,
				}
			}

			let data = {
				search: searchText,
				category: category,
				productType: productType,
				page: pagination.currentPage,
				limit: Number(pagination.limit)
			};

			IEWebsite.Utils.ShowLoadingScreen();
			IEWebsite.Utils.AjaxPost(FETCH_REPLENISMENT_REQUEST_PRODUCTS, data , function(resp) {
				IEWebsite.Utils.HideLoadingScreen();
	
				if (resp.status)
				{
					$("#manageReplenishmentRequestContainer").show();

					pagination.totalPages = resp.response.pagination.totalPages;
					
					$("#saveReplenishmentRequest").attr("disabled", "true");
					
					if (resp.response.pagination.totalPages == resp.response.pagination.current)
					{
						$("#saveReplenishmentRequest").attr("disabled", false);
					}

					searchBoxEnabled = true;
					if (!_.isEmpty(resp.response.data))
					{
						showTableData(resp.response.data);
					}
					else
					{
						$("#replenishmentRequestTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
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

				if (replenishmentRequestsData[row.productId])
				{
					qty = replenishmentRequestsData[row.productId].qty,
					unit = replenishmentRequestsData[row.productId].unit,
					unitPrice = replenishmentRequestsData[row.productId].unitPrice,
					comment = replenishmentRequestsData[row.productId].comment,
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
					
				$("#replenishmentRequestTableBody").append(tableRow);
			});

			$("span[id^=removeRow-]").click(function(){
				$(this).parent().parent().remove();
				let productId = $(this).attr('productid');

				if (replenishmentRequestsData[productId])
				{
					delete replenishmentRequestsData[productId];
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
		if (!_.isEmpty(replenishmentRequestsData))
		{
			for(let i in replenishmentRequestsData)
			{
				if (replenishmentRequestsData[i].qty > 0)
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

		replenishmentRequestsData[productId] = {
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

IEWebsiteAdmin.ViewRequestPage = (function() {
	var searchBarText, requestedProductData = {}, pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 20,
	}, isReceiver, isDispatcher, requestId = IEWebsite.Uri.Segment(4);

	var init = function() {
		if ($("#viewRequestsPageContainer").length <= 0)
		{
			return;
		};

		loadProducts();

		$(".acceptRequest").click(function() {
			
			let isError = highlightInputField();
			if (isError)
			{
				alert('Please add comment for lesser quantity products.');
				return false;
			}

			let modalMessage = '';
			let data = {
				status: $(this).val()
			};
			
			if (data.status == STATUS_ACCEPTED || data.status == STATUS_RECEIVED)
			{
				data.product = requestedProductData;
				modalMessage = 'You have got everything.';
			}
			else if (data.status == STATUS_REJECTED)
			{
				modalMessage = 'You want to reject this request';
			}
			else if (data.status == STATUS_DISPATCHED)
			{
				data.product = requestedProductData;
				modalMessage = 'You have dispatched this request';
			}

			data.isDispatcher = isDispatcher;
			data.isReceiver = isReceiver;

			if (!_.isEmpty(data))
			{
				swal({
					title: 'Are you sure ?',
					text: modalMessage,
					type: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Yes',
					cancelButtonText: 'No',
					confirmButtonClass: "btn btn-success",
					cancelButtonClass: "btn btn-danger",
					buttonsStyling: false
				}).then(function() {
					IEWebsite.Utils.ShowLoadingScreen();

					let apiUrl = SAVE_REQUEST_STATUS + '/' + requestId;
					IEWebsite.Utils.AjaxPost(apiUrl, data, function(resp) {
						IEWebsite.Utils.HideLoadingScreen();

						swal({
							// title: resp.status == 'true' ? 'Success' : 'Danger',
							text: resp.message,
							type: resp.status == 'true' ? 'success' : 'danger',
							confirmButtonClass: "btn btn-success",
							buttonsStyling: false
						}).then(function() {
							window.location.reload();
						});
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
	};

	var loadProducts = function() {
		let apiUrl = FETCH_REQUEST_DETAILS + '/' + requestId;

		let searchText = $.trim($("#searchBar").val());

		$("#manageRequestTransferTableBody").html('');

		let resetPage = false;
		if (_.isEmpty(searchBarText) && !_.isEmpty(searchText))
		{
			resetPage = true;
		}
		else if (!_.isEmpty(searchText) && !_.isEmpty(searchBarText) && searchText != searchBarText)
		{
			resetPage = true;
		}

		if (resetPage)
		{
			pagination = {
				currentPage: 1,
				totalPages: 0,
				limit: 10,
			}
		}

		let data = {
			search: searchText,
			page: pagination.currentPage,
			limit: Number(pagination.limit),
		};

		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxGet(apiUrl, data, function(resp) {
			IEWebsite.Utils.HideLoadingScreen();
			if (resp.status)
			{
				showTableData(resp.response.data);

				$("#pagination").html(IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination));
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
	};

	var showTableData = function(data) 
	{
		$("#viewRequestsTableBody").html('');

		isReceiver = data.isReceiver;
		isDispatcher = data.isDispatcher;

		if (!_.isEmpty(data.products))
		{
			_.each(data.products, function(row) {

				requestedProductData[row.productId] = {
					productId: row.productId,
					productQuantity: row.productQuantity,
					dispatchedQty: row.dispatchedQty,
					requestedQty: row.requestedQty,
					receivedQty: row.dispatchedQty,
					transferStockId: row.transferStockId,
					comment: '',
				}

				let dispatchData = row.dispatchedQty
				if (data.showDispatchQtyField)
				{
					requestedProductData[row.productId].dispatchedQty = row.productQuantity; 
					dispatchData = '<input type="number" data-type="dispatch" data-productid="'+ row.productId +'" style="width:60px" min="0" max="'+ row.productQuantity +'" value="'+ row.productQuantity +'" name="product[dispatch]['+ row.productId +']"/>';
				}
				
				let receiveData = row.receivedQty
				if (data.showReceiveQtyField)
				{
					receiveData = '<input type="number" data-type="receive" data-productid="'+ row.productId +'" style="width:60px" min="0" max="'+ row.dispatchedQty +'" value="'+ row.dispatchedQty +'" name="product[receive]['+ row.productId +']"/>';
				}

				let tableRow = '<tr>';
					tableRow += '<td>'+ row.sn +'</td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ row.unitName +'</td>';
					tableRow += '<td>'+ row.requestedQty +'</td>';
					tableRow += '<td>'+ dispatchData +'</td>';
					tableRow += '<td>'+ receiveData +'</td>';
					tableRow += '<td>'+ row.comment +'</td>';
					tableRow += '</tr>';
				
				$("#viewRequestsTableBody").append(tableRow);
			});

			$("input[name^='product[dispatch]']").change(updateProductQuantity);
			$("input[name^='product[receive]']").change(updateProductQuantity);
			$("input[name^='product[comment]']").keyup(updateProductComment);
		}
		else
		{
			$("#viewRequestsTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
		}
	};

	var updateProductQuantity = function() 
	{
		let productId = Number($(this).attr('data-productid'));
		let inputType = $(this).attr('data-type');
		let maxQty = parseFloat($(this).attr('max'));
		let qty = parseFloat($(this).val());

		if (!isNaN(productId) && productId > 0)
		{
			if (qty > maxQty)
			{
				$("input[name='product[dispatch]["+ productId +"]']").val(maxQty);
				$("input[name='product[receive]["+ productId +"]']").val(maxQty);
				return false;
			}

			if (inputType == 'dispatch')
			{
				requestedProductData[productId].dispatchedQty = qty;
			}
			else if (inputType == 'receive')
			{
				requestedProductData[productId].receivedQty = qty;
			}

			highlightInputField(productId);
		}
	};

	var updateProductComment = function() 
	{
		let productId = Number($(this).attr('data-productid'));
		let comment = $.trim($(this).val());

		if (!isNaN(productId) && productId > 0)
		{
			requestedProductData[productId].comment = comment;
			highlightInputField(productId);
		}
	};

	var checkQtyDifference = function(productId = null)
	{
		if (!_.isEmpty(requestedProductData[productId]))
		{
			if (isReceiver && (requestedProductData[productId].dispatchedQty > requestedProductData[productId].receivedQty) && requestedProductData[productId].comment == '')
			{
				return requestedProductData[productId];
			}

			if (isDispatcher && (requestedProductData[productId].dispatchedQty < requestedProductData[productId].requestedQty) && requestedProductData[productId].comment == '')
			{
				return requestedProductData[productId];
			}
		}
		else if (!_.isEmpty(requestedProductData))
		{
			for (index in requestedProductData)
			{
				if (isReceiver && (requestedProductData[index].dispatchedQty > requestedProductData[index].receivedQty) && requestedProductData[index].comment == '')
				{
					return requestedProductData[index];
				}

				if (isDispatcher && (requestedProductData[index].dispatchedQty < requestedProductData[index].requestedQty) && requestedProductData[index].comment == '')
				{
					return requestedProductData[index];
				}
			}
		}


		return {};
	};

	var highlightInputField = function(productId = null) 
	{
		let hasError = false;
		let getDifference = checkQtyDifference(productId);
		
		if (!_.isEmpty(getDifference))
		{
			hasError = true;
			$("input[name='product[comment]["+ getDifference.productId +"]']").addClass('redBorder');
		}
		else if (productId)
		{
			$("input[name='product[comment]["+ productId +"]']").removeClass('redBorder');
		}

		return hasError;
	};

	return {
		Init: init
	}
})();

IEWebsiteAdmin.ManageDisputeRequestPage = (function() {
	var disputeProductData = {}, pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: 20,
	}, 
	isDispatcher, isReceiver,
	manageDisputeRequestTableBody = $("#manageDisputeRequestTableBody"), 
	requestId = IEWebsite.Uri.Segment(4);

	var init = function() {
		if ($("#manageDisputeRequestPageContainer").length <= 0)
		{
			return;
		};

		loadProducts();
	};

	var loadProducts = function() {
		let apiUrl = FETCH_DISPUTE_REQUEST_PRODUCTS + '/' + requestId;

		manageDisputeRequestTableBody.html('');

		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxGet(apiUrl, null, function(resp) {
			IEWebsite.Utils.HideLoadingScreen();
			if (resp.status)
			{
				showTableData(resp.response.data);
			}
		});
	};

	var showTableData = function(data) 
	{
		isReceiver = data.isReceiver;
		isDispatcher = data.isDispatcher;
		
		if (isDispatcher)
		{
			$("#actionPerformedBy").html(": For Dispatcher");
		}

		if (isReceiver)
		{
			$("#actionPerformedBy").html(": For Receiver");
		}


		if (!_.isEmpty(data.products))
		{
			_.each(data.products, function(row) {
				disputeProductData[row.productId] = {
					productId : row.productId,
					receiverMessage : row.receiverMessage,
					dispatcherMessage : row.dispatcherMessage,
					transferStockId: row.transferStockId,
				};
				
				let tableRow = '<tr>';
					tableRow += '<td>'+ row.sn +'</td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ row.unitName +'</td>';
					tableRow += '<td>'+ row.requestedQty +'</td>';
					tableRow += '<td>'+ row.dispatchedQty +'</td>';
					tableRow += '<td>'+ row.receivedQty +'</td>';
					tableRow += '<td>'+ row.disputeQty +'</td>';
					tableRow += '<td id="dispatcherMessage-'+ row.productId +'">'+ row.dispatcherMessage +'</td>';
					tableRow += '<td id="receiverMessage-'+ row.productId +'">'+ row.receiverMessage +'</td>';
					tableRow += '<td style="white-space: nowrap">'+ row.action +'</td>';
					tableRow += '</tr>';
				
				manageDisputeRequestTableBody.append(tableRow);
			});

			$("input[name^='product[comment]']").keyup(updateComment);
			$("span[id^='acceptDispute-']").click(changeStatus);
			$("span[id^='rejectDispute-']").click(changeStatus);
		}
		else
		{
			manageDisputeRequestTableBody.append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
		}
	};

	var updateComment = function()
	{
		let productId = parseInt($(this).attr('productid'));
		
		if (!isNaN(productId) && productId > 0)
		{
			if (isDispatcher)
			{
				disputeProductData[productId].dispatcherMessage = String($(this).val());
			}

			if (isReceiver)
			{
				disputeProductData[productId].receiverMessage = String($(this).val());
			}

			$("input[name^='product[comment]["+ productId +"]']").removeClass('redBorder');
		}
	};

	var changeStatus = function()
	{
		let statusType = $(this).attr('data-type');
		let statusValue = parseInt($(this).attr('value'));
		let productId = parseInt($(this).attr('productid'));

		if (productId > 0 && (statusType == 'accept' || statusType == 'reject') && statusValue > 0)
		{
			let dispatcherMessage = disputeProductData[productId].dispatcherMessage;
			let receiverMessage = disputeProductData[productId].receiverMessage;

			if ((isDispatcher && dispatcherMessage == '') || (isReceiver && receiverMessage == ''))
			{
				$("input[name^='product[comment]["+ productId +"]']").addClass('redBorder');
				return false;
			}

			let acceptedHtml = '<span class="btn btn-xs mt-0 mb-0 btn-success">Accepted</span>';
			let rejectedHtml = '<span class="btn btn-xs mt-0 mb-0 btn-danger">Rejected</span>';

			postData = {
				productId,
				isReceiver,
				isDispatcher,
				status: statusValue,
				disputeData: disputeProductData[productId],
			};

			if (statusType == 'accept')
			{
				$(this).parent().html(acceptedHtml);
			}
			else if (statusType == 'reject')
			{
				$(this).parent().html(rejectedHtml);
			}
			else
			{
				// Something went wrong
				alert('Something went wrong');
				return false;
			}

			if (isDispatcher)
			{
				$("#dispatcherMessage-" + productId).html(dispatcherMessage);
			}

			if (isReceiver)
			{
				$("#receiverMessage-" + productId).html(receiverMessage);
			}

			saveDisputeMessageAndStatus(postData);
		}
	}

	var saveDisputeMessageAndStatus = function(postData)
	{
		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxPost(SAVE_DISPUTE_STATUS, postData, function(resp) {
			IEWebsite.Utils.HideLoadingScreen();
		});
	}

	return {
		Init: init
	}
})();

IEWebsiteAdmin.RequestTransferReportPaginationBackup = (function() {
	var currentTab, searchBarText, incommingPagination = {
		currentPage: 1,
		totalPages: 0,
		limit: $("#tableDataLimit").val(),
	}, outgoingPagination = {
		currentPage: 1,
		totalPages: 0,
		limit: $("#tableDataLimit").val(),
	}, disputePagination = {
		currentPage: 1,
		totalPages: 0,
		limit: $("#tableDataLimit").val(),
	}; 

	var init = function()
	{
		if ($("#manageRequestTransferReportPageContainer").length <= 0)
		{
			return;
		};

		currentTab = $('.nav-tabs li.active > a').text();

		$('.nav-tabs a').on('shown.bs.tab', function(event){
			currentTab = $(event.target).text();         // active tab
			// $(event.relatedTarget).text();  // previous tab
		});

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

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_REQUEST_TRANSFER_REPORT) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_REQUEST_TRANSFER_REPORT + '/' + currentTab + '/' + exportType;
			}
		});

		$("#search").click(function() {
			loadProducts('Incomming');
			loadProducts('Outgoing');
			loadDisputeRequests();
		});

		$("#tableDataLimit").change(function() {
			
			incommingPagination.limit = Number($(this).val());
			incommingPagination.currentPage = 1;
			incommingPagination.totalPages  = 0;

			outgoingPagination.limit = Number($(this).val());
			outgoingPagination.currentPage = 1;
			outgoingPagination.totalPages  = 0;

			disputePagination.limit = Number($(this).val());
			disputePagination.currentPage = 1;
			disputePagination.totalPages  = 0;

			loadProducts('Incomming');
			loadProducts('Outgoing');
			loadDisputeRequests();
		});

		loadProducts('Incomming');
		loadProducts('Outgoing');
		loadDisputeRequests();
	}

	var loadDisputeRequests = function()
	{
		$("#manageDisputeTableBody").html('');
		
		let params = {
			page: disputePagination.currentPage,
			limit: Number(disputePagination.limit),
			startDate: $("#startDate").val(),
			endDate: $("#endDate").val(),
		};

		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxPost(FETCH_DISPUTE_REQUESTS, params, function(resp) {
			IEWebsite.Utils.HideLoadingScreen();

			if (resp.status)
			{
				$("#disputePagination").html(IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination));

				disputePagination.totalPages = resp.response.pagination.totalPages;

				if (!_.isEmpty(resp.response.data))
				{
					_.each(resp.response.data, function(row) {
	
						let tableRow = '<tr>';
							tableRow += '<td>'+ row.sn +'</td>';
							tableRow += '<td>'+ row.transferFrom +'</td>';
							tableRow += '<td>'+ row.transferTo +'</td>';
							tableRow += '<td>'+ row.requestType +'</td>';
							tableRow += '<td>'+ row.createdOn +'</td>';
							tableRow += '<td>'+ row.action +'</td>';
							tableRow += '</tr>';
	
							$("#manageDisputeTableBody").append(tableRow);
					});
				}
				else 
				{
					$("#manageDisputeTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
				}


				$("[id^=paginate-]").click(function() {
					let page = Number($(this).attr('page'));

					if (page > 0)
					{
						disputePagination.currentPage = page;
						loadDisputeRequests();
					}
				});
			}
		});
	}

	var loadProducts = function(type = null) {
		let searchText = $.trim($("#searchBar").val());

		$("#manageRequestTransferTableBody").html('');

		let data = {
			startDate: $("#startDate").val(),
			endDate: $("#endDate").val(),
		};

		let apiUrl = FETCH_REQUESTS;

		if (!type)
		{
			type = currentTab;
		}

		if (type == 'Incomming')
		{
			apiUrl += '/' + INCOMMING;
			data.page = incommingPagination.currentPage;
			data.limit = incommingPagination.limit;
		}
		else if (type == 'Outgoing')
		{
			apiUrl += '/' + OUTGOING;
			data.page = outgoingPagination.currentPage;
			data.limit = outgoingPagination.limit;
		}
		else 
		{
			throw new Exception('Invalid tab');
		}

		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxPost(apiUrl, data , function(resp) {
			IEWebsite.Utils.HideLoadingScreen();

			if (resp.status)
			{
				let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination, {
					tab: type
				});

				if (type == 'Incomming')
				{
					$("#incommingPagination").html(paginationHtml);
					$("#manageIncommingRequestTransferTableBody").html('');
				}
				else if (type == 'Outgoing')
				{
					$("#outgoingPagination").html(paginationHtml);
					$("#manageOutgoingRequestTransferTableBody").html('');
				}

				showTableData(resp.response.data, type);
				
				$("[id^=paginate-]").click(function() {
					let page = Number($(this).attr('page'));
					let tab = $(this).attr('tab');
					
					if (tab == 'Incomming')
					{
						incommingPagination.totalPages = resp.response.pagination.totalPages;
						incommingPagination.currentPage = page;
						loadProducts(tab);
					}
					else if (tab == 'Outgoing')
					{
						outgoingPagination.totalPages = resp.response.pagination.totalPages;
						outgoingPagination.currentPage = page;
						loadProducts(tab);
					}
				});
			}
		});
	}

	var showTableData = function(data, type) 
	{
		if (!_.isEmpty(data))
		{
			let action = null;
			let limit = (type == 'Incomming' ? incommingPagination.limit : (type == 'Outgoing' ? outgoingPagination.limit : $("#tableDataLimit").val()));
			let currentPage = (type == 'Incomming' ? incommingPagination.currentPage : (type == 'Outgoing' ? outgoingPagination.currentPage : 1));
			let sn = (currentPage - 1) * limit;

			_.each(data, function(row) {

				let tableRow = '<tr>';
					tableRow += '<td>'+ ++sn +'</td>';
					tableRow += '<td>'+ row.transferFrom +'</td>';
					tableRow += '<td>'+ row.transferTo +'</td>';
					tableRow += '<td>'+ row.requestType +'</td>';
					tableRow += '<td>'+ row.status +'</td>';
					tableRow += '<td>'+ row.createdOn +'</td>';
					tableRow += '<td>'+ row.action +'</td>';
					tableRow += '</tr>';
				
				if (type == 'Incomming')
				{
					$("#manageIncommingRequestTransferTableBody").append(tableRow);
				}
				else if (type == 'Outgoing')
				{
					$("#manageOutgoingRequestTransferTableBody").append(tableRow);
				}
			});
		}
		else
		{
			if (type == 'Incomming')
			{
				$("#manageIncommingRequestTransferTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
			}
			else if (type == 'Outgoing')
			{
				$("#manageOutgoingRequestTransferTableBody").append('<tr><td align="center" colspan="11">No Record Found.</td></tr>');
			}
		}
	};

	return {
		Init: init
	}
})();

IEWebsiteAdmin.RequestTransferReport = (function() {
	var currentTab, searchBarText, pagination = {
		currentPage: 1,
		totalPages: 0,
		limit: $("#tableDataLimit").val(),
	}; 

	var init = function()
	{
		if ($("#manageRequestTransferReportPageContainer").length <= 0)
		{
			return;
		};

		currentTab = $('.nav-tabs li.active > a').text();

		$('.nav-tabs a').on('shown.bs.tab', function(event){
			currentTab = $(event.target).text();         // active tab
			// $(event.relatedTarget).text();  // previous tab
		});

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

		$("[name='exportData']").click(function() {
			let exportType = String($(this).val()).toLowerCase();
			if (!_.isEmpty(EXPORT_REQUEST_TRANSFER_REPORT) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_REQUEST_TRANSFER_REPORT + '/' + exportType;
			}
		});

		$("#search").click(loadProducts);

		$("#tableDataLimit").change(function() {
			
			pagination.limit = Number($(this).val());
			pagination.currentPage = 1;
			pagination.totalPages  = 0;

			loadProducts();
		});

		loadProducts();
	}

	var loadProducts = function(type = null) {
		let searchText = $.trim($("#searchBar").val());

		$("#manageRequestTransferTableBody").html('');

		let data = {
			startDate: $("#startDate").val(),
			endDate: $("#endDate").val(),
			page: pagination.currentPage,
			limit: pagination.limit
		};

		IEWebsite.Utils.ShowLoadingScreen();
		IEWebsite.Utils.AjaxPost(FETCH_REQUEST_TRANSFER_REPORT, data , function(resp) {
			IEWebsite.Utils.HideLoadingScreen();

			if (resp.status)
			{
				let paginationHtml = IEWebsiteAdmin.CustomPagination.Init(resp.response.pagination);

				$("#manageRequestTransferTableBody").html('');
				$("#pagination").html(paginationHtml);

				showTableData(resp.response.data, type);
				
				$("[id^=paginate-]").click(function() {
					let page = Number($(this).attr('page'));
					pagination.totalPages = resp.response.pagination.totalPages;
					pagination.currentPage = page;
					loadProducts();
				});
			}
		});
	}

	var showTableData = function(data, type) 
	{
		if (!_.isEmpty(data))
		{
			_.each(data, function(row) {

				let tableRow = '<tr>';
					tableRow += '<td>'+ row.sn +'</td>';
					tableRow += '<td>'+ row.indentRequestNumber +'</td>';
					tableRow += '<td>'+ row.transferFrom +'</td>';
					tableRow += '<td>'+ row.transferTo +'</td>';
					tableRow += '<td>'+ row.requestType +'</td>';
					tableRow += '<td>'+ row.productCode +'</td>';
					tableRow += '<td>'+ row.productName +'</td>';
					tableRow += '<td>'+ row.unitName +'</td>';
					tableRow += '<td>'+ row.productQuantity +'</td>';
					tableRow += '<td>'+ row.dispatchedQty +'</td>';
					tableRow += '<td>'+ row.receivedQty +'</td>';
					tableRow += '<td>'+ row.disputeQty +'</td>';
					tableRow += '<td>'+ row.createdOn +'</td>';
					tableRow += '</tr>';
				
				$("#manageRequestTransferTableBody").append(tableRow);
			});
		}
		else
		{
			$("#manageRequestTransferTableBody").append('<tr><td align="center" colspan="16">No Record Found.</td></tr>');
		}
	};

	return {
		Init: init
	}
})();

IEWebsiteAdmin.CustomPagination = (function() {
	
	var init = function(pagination, attributes = null)
	{
		let html = '';

		if (typeof pagination.totalPages != 'undefined' && pagination.totalPages > 1)
		{
			let { totalPages, current } = pagination;

			for (let index = 1; index <= totalPages; index++) 
			{
				let active = current == index ? 'active' : ''; 
				let customAttributes = '';

				if (!_.isEmpty(attributes))
				{
					let text = [];
					for (let attribute in attributes)
					{   
						text.push(String(attribute + '=' + JSON.stringify(attributes[attribute])));
					}

					customAttributes = text.join(' ');
				}

				html += '<li class="'+ active +'"><a id="paginate-'+ index +'" page="'+ index +'" href="javascript:void(0);" '+ customAttributes +'>' + index + '</li>';
			}
	
			html = '<ul class="pagination pagination-info">' + html + '</ul>';
		}

		return html;
	};

	return {
		Init: init
	}
})();

IEWebsiteAdmin.RecipeManagementPage = (function() {
	var init = function()
	{
		if ($("#recipeManagementPageContainer").length <= 0)
		{
			return 0;
		};

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

				let url = IMPORT_RECIPES; 
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
						$('#recipeManagementData').DataTable().ajax.reload();
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
			if (!_.isEmpty(EXPORT_RECIPES) && exportType == 'csv' || exportType == 'excel')
			{
				window.location.href = EXPORT_RECIPES + exportType;
			}
		});

		$('#recipeManagementData').DataTable({
			// "bPaginate": false,
			// "searching": false,   // Search Box will Be Disabled
			"processing": true,
			"serverSide": true,
			"ordering": false,
			responsive: true,
			ajax: {
				url: FETCH_RECIPES,
				type: "POST"
			},
			columns: [
				{data: 'sn', width: "5%"},
				{data: 'itemName'},
				{data: 'createdOn'},
				{data: 'action', width: "10%"},
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
		
		IEWebsite.Utils.JqueryFormValidation('#createForm');
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
		scrollToActiveSideBarNavigation();
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

	var scrollToActiveSideBarNavigation = function()
	{
		let $container = $(".sidebar-wrapper");
		let $scrollTo = $(".nav > li.active");

		if ($scrollTo.length)
		{
			$container.animate({
				scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop()
			});
		}
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
	IEWebsiteAdmin.ReplenishmentRequestPage.Init();
	IEWebsiteAdmin.RequestTransferPage.Init();
	IEWebsiteAdmin.ManageRequestTransferPage.Init();
	IEWebsiteAdmin.ViewRequestPage.Init();
	IEWebsiteAdmin.ManageDisputeRequestPage.Init();
	IEWebsiteAdmin.OpeningInventoryReport.Init();
	IEWebsiteAdmin.ClosingInventoryReport.Init();
	IEWebsiteAdmin.WastageInventoryReport.Init();
	IEWebsiteAdmin.DirectOrderReport.Init();
	IEWebsiteAdmin.RequestTransferReport.Init();
});