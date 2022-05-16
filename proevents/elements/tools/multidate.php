<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$request = \Request::getInstance();
$dt = Loader::helper('form/datetimetime');


print $dt->datetimetime('akID[' . $request->get('akID') . '][' . $request->get('num') . '][date]',null,false,true,$request->get('instance'));