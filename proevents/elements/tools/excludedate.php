<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$request = \Request::getInstance();
$dtt = Loader::helper('form/datetimetime');


print '<div class="excludeDate"><a href="javascript:;" onClick="$(this).parent().remove();"><i class="fa fa-trash"></i></a> '.$dtt->date('akID[' . $request->get('akID') . '][' . $request->get('num') . '][date]').'</div>';