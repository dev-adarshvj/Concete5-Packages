<?php
	$df = Loader::helper('form/date_time');
	Loader::model("attribute/categories/collection");
	if (is_object($page)) {
		$page_title = $page->getCollectionName();
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
   <style type="text/css">
   .ccm-ui .input-group {
	   width:100%;
   }

   </style>
<?php   if ($this->controller->getTask() == 'edit') { ?>

<form method="post" class="form-horizontal" action="<?php  echo $this->action($task,$page->getCollectionID())?>" id="page-form">
<?php  echo $form->hidden('pageID', $page->getCollectionID())?>
<?php   }else{ ?>
<form method="post" class="form-horizontal" action="<?php  echo $this->action($task)?>" id="page-form">
  <?php  } ?>

  <fieldset>
    <legend><?php echo t('Basic Attributes')?></legend>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('pageTitle', t('Title'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
            <?php  echo $form->text('page_title', $page_title)?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span> </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('pageDescription', t('Summary'),array('class'=>'col-sm-3'))?>
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
            <?php  echo t('No sections defined. Please create a page with the attribute "team_management_section" set to true.')?>
            <?php   } else { ?>
            <?php  echo $form->select('cParentID', $sections, $cParentID)?>
            <?php   } ?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span> </div>
        </div>
      </div>
    </div>

        <?php
   Loader::model('config');
  echo  $form->hidden('ctID',Config::get('concrete.team_management_collection_type_id'));
   echo  $form->hidden('ptID',Config::get('concrete.page_template_id')) ?>
    <div class="row">
      <div class="form-group">
        <?php  echo $form->label('page_date_time', t('Date/Time'),array('class'=>'col-sm-3'))?>
        <div class="col-sm-7">
          <div class="input-group">
            <?php   echo $df->datetime('page_date_time', $pageDate)?>
          </div>
        </div>
      </div>
    </div>
  </fieldset>
  <fieldset>
    <legend><?php echo t('Additional Attributes')?></legend>

  <?php
	$attributeset_id=Config::get('concrete.team_management_attribute_set_id');
     $set = AttributeSet::getByID($attributeset_id);
	if(is_object($set)){
		$setAttribs = $set->getAttributeKeys();
			if($setAttribs){

				?>

                <?php
				foreach ($setAttribs as $ak) {
					if(is_object($page)) {
						$aValue = $page->getAttributeValueObject($ak);
					}
					echo '<div class="row">
      <div class="form-group">
		';
		 echo $form->label($ak->getAttributeKeyCategoryHandle(), t($ak->getAttributeKeyName()),array('class'=>'col-sm-3'));
					echo '  <div class="col-sm-7">
          <div class="input-group">';

					echo $ak->render('form', $aValue);
					echo '</div>
        </div>
      </div>
    </div>
     ';
				}
			}
	}else{
		echo "No Attribute Set Defined/Created";
	}


    	?>
         </fieldset>
        <div class="ccm-dashboard-form-actions-wrapper">
    <div class="ccm-dashboard-form-actions"> <a href="<?php echo View::url('/dashboard/team_management_pro/team_list')?>" class="btn btn-default pull-left"><?php echo t('Cancel')?></a> <?php echo Loader::helper("form")->submit('add', t($title.' Page'), array('class' => 'btn btn-primary pull-right'))?> </div>
  </div>

</form>

<style type="text/css">
 .redactor-box textarea{
	     color: #ccc !important;
		 }

</style>
