<div class="row" id="vendorProductsPageContainer">
    <div class="col-md-12">
        <?=showAlertMessage($flashMessage, $flashMessageType)?>
    </div>
    <?php $this->load->view('backend/vendor-products/create');?>
    <?php $this->load->view('backend/vendor-products/manage');?>
</div>