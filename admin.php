<?php
/**
 * @copyright   Copyright (c) 2014-2016 xyhcms.com All rights reserved.
 * @license     http://www.xyhcms.com/help/1.html
 * @link        http://www.xyhcms.com
 * @author      gosea <gosea199@gmail.com>
 */

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
	die('require PHP > 5.3.0 !');
}

define('BIND_MODULE', 'Manage');
define('APP_DEBUG',TRUE);
define('APP_PATH', "./App/");
define('THINK_PATH', "./Include/");
require THINK_PATH . 'ThinkPHP.php';
