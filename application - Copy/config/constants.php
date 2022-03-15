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

define('LEAD_PRICE', '27.50');
define('SMS_PRICE', '2');
define('CONNECTION_PRICE', '20');
define('INTEREST_ID', '1');
define('ADMIN_EMAIL', 'muhammadw873@gmail.com');
define('USER_EMAIL', 'no-reply@debtmonster.co.uk');
define('USER_PASS', 'no-reply@callmonster');

define('STRIPE_LIVE_KEY', 'sk_live_68AFcuDEfJQptxgBvknuMU4R');
define('STRIPE_LIVE_PUB_KEY', 'pk_live_6OHNonjwzKmc1ufKWpeaHSRp');

define('STRIPE_TEST_PUB_KEY', 'pk_test_fslgpcYMnamg1C2myRnStWWv003aKRxeQW');
define('STRIPE_TEST_KEY', 'sk_test_ajCkg2rvqztHQPH56iLYlY4j00erVFZqsl');

define('LIST_ID', 99);
define('STRIPE_CURRENCY_CODE', 'GBP');

define('BUNDLE_SID', 'BUdc2d8db0deed5c62691ebfac7a9674c2');
// {"status": "draft", "date_updated": "2020-06-11T07:42:51Z", "regulation_sid": "RN61b6f858c1a0b46ae2631346138a283c", "friendly_name": "wlc, Local Bundle", "account_sid": "AC10946b57eff7081c60e499d54e945f4b", "url": "https://numbers.twilio.com/v2/RegulatoryCompliance/Bundles/BUdc2d8db0deed5c62691ebfac7a9674c2", "sid": "BUdc2d8db0deed5c62691ebfac7a9674c2", "date_created": "2020-06-11T07:42:51Z", "email": "andy@webleadscompany.com", "status_callback": "http://localhost/dm/listener/callback_url", "links": {"item_assignments": "https://numbers.twilio.com/v2/RegulatoryCompliance/Bundles/BUdc2d8db0deed5c62691ebfac7a9674c2/ItemAssignments", "evaluations": "https://numbers.twilio.com/v2/RegulatoryCompliance/Bundles/BUdc2d8db0deed5c62691ebfac7a9674c2/Evaluations"}}

/*
//test of developer....
define("TWILIO_NUMBER", "+19097570028");
define("TWILIO_SID", "ACdcb321fad468676cf6db19fb4acb10dd");
define("AUTH_TOKEN", "fdd8a0e7baa6164101881764ce501eba");
*/

//test of client....
//define("TWILIO_NUMBER", " ");
// define("TWILIO_SID", "AC7604dc1bd0c7739e452e9c82d1a9eb2f");
// define("AUTH_TOKEN", "9bd51002c13ace02ff0ec805ca52cc40");

//live of client....
//define("TWILIO_NUMBER", " ");
define("TWILIO_SID", "AC10946b57eff7081c60e499d54e945f4b");
define("AUTH_TOKEN", "b9e5d679962a8f5b5a8e6e1433ef03ad");

