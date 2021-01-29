<div class="row" id="requestTransferPageContainer">
    <div class="col-md-12">
        <?=showAlertMessage($flashMessage, $flashMessageType)?>
        <div class="card mt-0">
            <form method="post" id="createRequestTransferForm" class="form-horizontal" enctype="multipart/form-data" action="#">
                <div class="card-header card-header-text">
                    <h4 class="card-title"><?=$headTitle?></h4>
                </div>
                <div class="card-content">
                    <div class="row col-md-12">
                        <span><b><?=REQUEST_TRANSFER_SHORT_NAME?>: </b><?=REQUEST_TRANSFER_SHORT_NAME?>-<?=$indentRequestNumber?><span>
                        <span class="pull-right"><b>Date: </b><?=Date('d-m-Y')?></span>
                    </div>
                    <div class="row">
                        <label class="col-md-2 label-on-left">Product Type</label>
                        <div class="col-md-10">
                            <?php
                                $extra = [
                                    'id' => 'productType',
                                    'class' => 'selectpicker',
                                    'data-style' => 'select-with-transition select-box-horizontal', 
                                ];

                                $selectedProudctType = !empty(set_value('productType')) ? set_value('productType') : (isset($productType) ? $productType : '');
                                echo form_dropdown('productType', $productTypes, $selectedProudctType, $extra);
                                echo form_error('productType');
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 label-on-left">Request Type</label>
                        <div class="col-md-10">
                            <?php
                                $extra = [
                                    'id' => 'requestTransferType',
                                    'class' => 'selectpicker',
                                    'data-style' => 'select-with-transition select-box-horizontal', 
                                ];

                                $selectedRequestTransferType = !empty(set_value('requestTransferType')) ? set_value('requestTransferType') : (isset($requestTransferType) ? $requestTransferType : '');
                                echo form_dropdown('requestTransferType', $requestTransferTypes, $selectedRequestTransferType, $extra);
                                echo form_error('requestTransferType');
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 label-on-left">Categories</label>
                        <div class="col-md-10">
                            <?php
                                
                                if (!empty($dropdownSubCategories))
                                {
                                    $ddOptions = '';
                                    $ddCounter = 0;
                                    foreach($dropdownSubCategories as $subCategoryIndex => $subCategoryName)
                                    {
                                        if ($ddCounter == 0)
                                        {
                                            $ddOptions .= sprintf('<option disabled value>%s</option>', $subCategoryName);
                                            $ddOptions .= sprintf('<option value="0">%s</option>', 'All Categories');
                                        }
                                        else
                                        {
                                            $ddOptions .= sprintf('<option value="%s">%s</option>', $subCategoryIndex, $subCategoryName);
                                        }

                                        $ddCounter++;
                                    }

                                    if ($ddOptions)
                                    {
                                        echo sprintf("<select id='category' name='category[]' class='selectpicker' multiple data-style='select-with-transition select-box-horizontal' data-live-search='true'>%s<select>", $ddOptions);
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 label-on-left">Outlets</label>
                        <div class="col-md-10">
                            <?php
                                $extra = [
                                    'id' => 'outlet',
                                    'class' => 'selectpicker',
                                    'data-style' => 'select-with-transition select-box-horizontal', 
                                    'data-live-search' => 'true'
                                ];

                                $selectedOutlet = !empty(set_value('outlet')) ? set_value('outlet') : '';

                                echo form_dropdown('outlet', $restaurantDropdownOptions, $selectedOutlet, $extra);
                                echo form_error('outlet');
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12 displaynone" id="manageRequestTransferContainer">
        <div class="card mt-0">
            <div class="card-content">

                <div class="toolbar mb-10">
                    <div class="row">
                        <div class="col-md-6">
                            <select class='mt-10' id="tableDataLimit">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div> 
                        <div class="col-md-6">
                            <input type="text" name="searchBar" id="searchBar" class="form-control" placeholder="Product Name">
                        </div>
                    </div>
                </div>

                <form id="requestTransferForm" method="POST">
                    <div class="table-responsive">
                        <table class="table table-bordered custom-table" cellspacing="0" width="100%" style="width:100%">
                            <thead class="text-primary">
                                <tr>
                                    <th></th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Unit</th>
                                    <th width="10%">Qty</th>
                                    <?php /*?>
                                    <th>Unit Price</th>
                                    <th  width="10%">Sub Total</th>
                                    <?php*/ ?>
                                    <th>Comment</th>
                                </tr>
                            </thead>
                            <tbody id="requestTransferTableBody"></tbody>
                        </table>
                    </div>
                </form>
                
                <div id="pagination">
                        
                </div>

                <div class="toolbar mb-10">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" name="submit" value="Save" id="saveRequestTransfer" class="btn btn-rose btn-fill">Submit<div class="ripple-container"></div></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>