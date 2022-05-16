<style type="text/css">
.form-group {

  padding-bottom: 45px;
}
.help {
	font-style: normal;
	font-weight: normal;
	border-color: #02890d;
	border-width: 1px;
	border-style: solid;
	max-width: 235px;
	padding: 16px;
	MARGIN-left: 85px;
	background-color: #f5f5f5;
	position: absolute;
	-moz-border-radius: 5px;
this works only in camino/firefox  -webkit-border-radius: 5px;
this is just for Safari
}
#dates_wrap div {
	margin-top: 12px;
}
.small {
	width: 52px!important;
}
</style>
<?php
$pkg = Package::getByHandle("service_locations");
  
	$df = Loader::helper('form/date_time');
	Loader::model("attribute/categories/collection");
	if (is_object($page)) { 
		$pageTitle = $page->getCollectionName();
		$pageDescription = $page->getCollectionDescription();
		$pageDate = $page->getCollectionDatePublic();
		$cParentID = $page->getCollectionParentID();
		$ctID = $page->getCollectionTypeID();
		$task = 'update';
		$buttonText = t('Update Page');
		$title = 'Update';
	} else {
		$task = 'add';
		$buttonText = t('Add Page Entry');
		$title = 'Add';
	}
	
	?>
<?php //echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Service Locations Add/Edit').'<span class="label" style="position:relative;top:-3px;left:12px;">'.t('* required field').'</span>', false, false, false);?>
<?php   if ($this->controller->getTask() == 'edit') { ?>
<form method="post" action="<?php  echo $this->action($task,$page->getCollectionID())?>" id="page-form">
<?php  echo $form->hidden('pageID', $page->getCollectionID())?>
<?php   }else{ ?>
<form method="post" action="<?php  echo $this->action($task)?>" id="page-form">
  <?php  } ?>
  <fieldset>
    <legend><?php echo t('Basic Attributes')?></legend>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('pageTitle', t('Page Title'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
            <?php  echo $form->text('pageTitle', $pageTitle)?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span> </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('pageDescription', t('Page Description'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
            <?php   echo $form->textarea('pageDescription', $pageDescription)?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('cParentID', t('Parent Page'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
            <?php   if (count($sections) == 0) { ?>
            <?php  echo t('No sections defined. Please create a page with the attribute "pixel_module_section" set to true.')?>
            <?php   } else { ?>
            <?php  echo $form->select('cParentID', $sections, $cParentID)?>
            <?php   } ?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span> </div>
        </div>
      </div>
    </div>
    <?php
   Loader::model('config');
  echo  $form->hidden('ctID',$pkg->getConfig()->get('service.SERVICE_LOCATIONS_PAGE_TEMPLATE_ID')) ?>
  

    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('pageTitle', t('Date/Time'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
        	<?php   echo $df->datetime('page_date_time', $pageDate)?>
        </div>
      </div>
    </div>
  </fieldset>
  <?php 
     Loader::model('config');
	$attributeset_id=$pkg->getConfig()->get('service.SERVICE_LOCATIONS_ATTRIBUTE_SET_ID');
	//print_r($attributeset_id);
   if(!$attributeset_id>0){
		echo "No Attribute Set Defined";
	   }
    $set = AttributeSet::getByID($attributeset_id);
	if(is_object($set)){
		$setAttribs = $set->getAttributeKeys();
		//print_r($setAttribs);
			if($setAttribs){
				foreach ($setAttribs as $ak) {
					
					//echo $ak->akHandle;
					//echo $page->getCollectionAttributeValue($ak->akHandle);
					if(is_object($page)) {
						$aValue = $page->getAttributeValueObject($ak);
					}
					echo '<div class="clearfix">
		';
					echo $ak->render('label');
					echo ' <div class="input">';
					
					echo $ak->render('form', $aValue);
					echo '</div>
	</div>
     ';
				}
			}
	}else{
		echo "No Attribute Set Defined/Created";
	}
	
    
    	?>
  <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions"> <a href="<?php echo View::url('/dashboard/service_locations/service_list')?>" class="btn btn-default pull-left"><?php echo t('Cancel')?></a> <?php echo Loader::helper("form")->submit('add', t($title.' Page'), array('class' => 'btn btn-primary pull-right'))?> </div>
  </div>
</form>