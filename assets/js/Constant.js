//  API's:

const FETCH_SUB_CATEGORIES = BASE_URL + 'backend/categories/fetch-subcategories';
const FETCH_SUB_SI_UNITS = BASE_URL + 'backend/siunits/fetch-sub-siunits';
const FETCH_PRODUCTS = BASE_URL + 'backend/products/fetchProducts';
const FETCH_MAPED_TAX_PRODUCTS = BASE_URL + 'backend/producttax/getMapedTaxProducts';
const FETCH_VENDORS = BASE_URL + 'backend/vendors/fetchVendors';
const FETCH_VENDOR_PRODUCTS = BASE_URL + 'backend/vendorproducts/fetchVendorProductsForMapping';
const FETCH_ASSIGNED_VENDOR_PRODUCTS = BASE_URL + 'backend/vendorproducts/fetchVendorAssignedProducts';
const REMOVE_ASSIGNED_VENDOR_PRODUCT = BASE_URL + 'backend/vendorproducts/removeVendorAssignedProduct';
const FETCH_VENDOR_PRODUCT_WITH_TAXES = BASE_URL + 'backend/Vendorproducttaxes/fetchVendorAssignedProductWithTaxes';
const SAVE_VENDOR_PRODUCT_TAX_MAPPING = BASE_URL + 'backend/Vendorproducttaxes/saveVendorProductTaxMapping';
const FETCH_OPENING_INVETORY_PRODUCTS = BASE_URL + 'backend/openinginventory/fetchProducts';
const FETCH_CLOSING_INVETORY_PRODUCTS = BASE_URL + 'backend/closinginventory/fetchProducts';
const SAVE_OPENING_INVETORY_PRODUCTS = BASE_URL + 'backend/openinginventory/save';
const SAVE_CLOSING_INVETORY_PRODUCTS = BASE_URL + 'backend/closinginventory/save';
const FETCH_DIRECT_ORDER_PRODUCTS = BASE_URL + 'backend/directorder/fetchProducts';
const SAVE_DIRECT_ORDER_PRODUCTS = BASE_URL + 'backend/directorder/save';
const FETCH_VENDOR_ASSIGNED_PRODUCT_CATEGORIES = BASE_URL + 'backend/categories/fetchVendorAssignedProductCategories';

const FETCH_MASTER_REPORT = BASE_URL + 'backend/report/fetchMaster';
const FETCH_OPENING_INVENTORY_REPORT = BASE_URL + 'backend/reports/openinginventory/fetchReport';

const FETCH_WASTAGE_INVETORY_PRODUCTS = BASE_URL + 'backend/wastageinventory/fetchProducts';
const SAVE_WASTAGE_INVETORY_PRODUCTS = BASE_URL + 'backend/wastageinventory/save';

const FETCH_DIRECT_TRANSFER_PRODUCTS = BASE_URL + 'backend/directtransfer/fetchProducts';
const SAVE_DIRECT_TRANSFER_PRODUCTS = BASE_URL + 'backend/directtransfer/save';

const FETCH_REQUEST_TRANSFER_PRODUCTS = BASE_URL + 'backend/requesttransfer/fetchProducts';
const SAVE_REQUEST_TRANSFER_PRODUCTS = BASE_URL + 'backend/requesttransfer/save';

const FETCH_REPLENISMENT_REQUEST_PRODUCTS = BASE_URL + 'backend/replenishmentrequest/fetchProducts';
const SAVE_REPLENISMENT_REQUEST_PRODUCTS = BASE_URL + 'backend/replenishmentrequest/save';

const FETCH_REQUESTS = BASE_URL + 'backend/requests/fetchRequests';
const FETCH_REQUEST_DETAILS = BASE_URL + 'backend/requests/fetchRequestDetail';
const SAVE_REQUEST_STATUS = BASE_URL + 'backend/requests/processRequest';
const FETCH_DISPUTE_REQUESTS = BASE_URL + 'backend/requests/fetchDisputeRequests';
const FETCH_DISPUTE_REQUEST_PRODUCTS = BASE_URL + 'backend/requests/fetchDisputeRequestProducts';
const SAVE_DISPUTE_STATUS = BASE_URL + 'backend/requests/saveDisputeMessageAndStatus';

const EXPORT_REPORTS = BASE_URL + 'backend/report/export';
const EXPORT_OPENING_STOCK_REPORT = BASE_URL + 'backend/reports/openinginventory/export';

const FETCH_CLOSING_INVENTORY_REPORT = BASE_URL + 'backend/reports/closinginventory/fetchReport';
const EXPORT_CLOSING_STOCK_REPORT = BASE_URL + 'backend/reports/closinginventory/export';

const FETCH_DIRECT_ORDER_REPORT = BASE_URL + 'backend/reports/directorder/fetchReport';
const EXPORT_DIRECT_ORDER_REPORT = BASE_URL + 'backend/reports/directorder/export';

const FETCH_WASTAGE_INVENTORY_REPORT = BASE_URL + 'backend/reports/wastageinventory/fetchReport';
const EXPORT_WASTAGE_INVENTORY_REPORT = BASE_URL + 'backend/reports/wastageinventory/export';




// API- End



// Custom constants


const OUTGOING = 1;
const INCOMMING = 2;

const STATUS_PENDING = 0;
const STATUS_ACCEPTED = 1;
const STATUS_RECEIVED = 1;
const STATUS_REJECTED = 2;
const STATUS_DISPATCHED = 3;

const DISPATCHER_STATUS_ACCEPT = 1;
const DISPATCHER_STATUS_REJECT = 2;

const RECEIVER_STATUS_ACCEPT = 1;
const RECEIVER_STATUS_REJECT = 2;