<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('PRODUCT_TYPE_SEMIPROCESSED', 1);
define('PRODUCT_TYPE_RAWMATERIAL', 2);

define('PRODUCT_IMAGE_UPLOAD_PATH', 'uploads/products');
define('PRODUCT_THUMBNAIL_PATH', 'assets/img/image_placeholder.jpg');
define('SAMPLE_DATA_TEMPLATE_PATH', 'uploads/templates');
define('PRODUCT_SAMPLE_FORMAT_PATH', SAMPLE_DATA_TEMPLATE_PATH . '/proudct-sample.xlsx');
define('IMPORT_FILE_UPLOAD_PATH', 'uploads/import');

define('IE_PRODUCTS', 'ie_products');
define('IE_PRODUCTS_TAXES', 'ie_products_taxes');
define('IE_TAXES', 'ie_taxes');

define('ACTIVE', 1);
define('DISABLE', -1);
define('DELETED', 0);
define('INACTIVE', -2);

define('STATUS_PENDING', 0);
define('STATUS_ACCEPTED', 1);
define('STATUS_RECEIVED', 1);
define('STATUS_REJECTED', 2);
define('STATUS_DISPATCHED', 3);

define("USER", 1);
define("USER_VENDOR", 2);
define("ADMIN", 3);
define("SUPER_ADMIN", 4);

define('OPENING_STOCK_SHORT_NAME', 'OS');
define('CLOSING_STOCK_SHORT_NAME', 'CS');
define('WASTAGE_STOCK_SHORT_NAME', 'WS');
define('REQUEST_TRANSFER_SHORT_NAME', 'RT');
define('GRN_SHOR_NAME', 'GRN');

define('CIPHERING', 'AES-128-CTR');
define('ENCRYPTION_IV', '1234567891011121');
define('ENCRYPTION_OPTION', '0');
define('ENCRYPTION_KEY', 'IeMenuInventory');

define('DECRYPTION_IV', '1234567891011121');
define('DECRYPTION_KEY', 'IeMenuInventory');
define('DECRYPTION_OPTION', '0');

if (ENVIRONMENT == 'testing')
{
    define('IEMENU_URL', 'https://iemenu.in/MDB');
}
else
{
    define('IEMENU_URL', 'http://localhost/iemenu');
}

define('TOTAL_SECONDS_IN_ONE_DAY', 86400);

define('DIRECT_TRANSER_REQUEST', 1);
define('REPLENISHMENT_REQUEST', 2);

define('SENDER', 1);
define('RECEIVER', 2);

define('OUTGOING', 1);
define('INCOMMING', 2);

define('DISPATCHER_STATUS_ACCEPT', 1);
define('DISPATCHER_STATUS_REJECT', 2);

define('RECEIVER_STATUS_ACCEPT', 1);
define('RECEIVER_STATUS_REJECT', 2);