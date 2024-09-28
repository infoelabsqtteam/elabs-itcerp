<?php
/**!*************************************************************
 * Angular Messages
 * Creadted By- : Praveen Singh
 * Package      : ITC ERP
 * Creadted On  : 20-May-17
 * 
 *****************************************************************/

/*
--------------------------------------------------------------------------
 General Setting
--------------------------------------------------------------------------
*/

$env = env('APP_ENV');
switch ($env) {
    case 'production':
        error_reporting(0);
        ini_set('display_errors', 0);
        break;
    case 'development':
    case 'local':
    default:
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        break;
}

//$DOC_ROOT      = @$_SERVER['DOCUMENT_ROOT'].'/itcerp/';
//$protocolArray = explode('/', @$_SERVER['SERVER_PROTOCOL']);
//$SITE_URL      = strtolower(@$protocolArray[0]).'://'.@$_SERVER['HTTP_HOST'].'/itcerp/';
//
//if (!defined('SITE_URL')){
//    define('SITE_URL',$SITE_URL);
//}
//
//if (!defined('DOC_ROOT')){
//    define('DOC_ROOT',$DOC_ROOT);
//}
//
//if (!defined('SITE_NAME')){
//    define('SITE_NAME','ITC-LAB');
//}
?>