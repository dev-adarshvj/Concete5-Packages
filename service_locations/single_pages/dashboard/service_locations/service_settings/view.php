<?php
defined('C5_EXECUTE') or die(_("Access Denied."));
$form = Loader::helper('form');
$al = Loader::helper('concrete/asset_library');
$view->requireAsset('css','taginput');
$view->requireAsset('javascript','taginput');

	   $fileset_details['0']='--Choose Fileset--';
	   if(is_array($fileSets))   {
       foreach($fileSets as $fileSet){
	   		$fileset_details[$fileSet->fsID]=$fileSet->fsName;
        }
	   }
	 $parent_pages['0']='--Choose Parent Page--';
	 if(is_array($parent_page))   {
     foreach($parent_page as $parent){
		  if($parent->cParentID>0){
		 			$ppname=Page::getByID($parent->cParentID)->getCollectionName().' - ';
		  }else{
			  $ppname='';
		  }
	        $parent_pages[$parent->getCollectionID()]=$ppname.$parent->getCollectionName();
  }
	 }
$variablesArray = Concrete\Package\ServiceLocations\Src\LocationSettings::getVariableName();
$variables = array();
$displayVariable = array();
if(is_array($variablesArray))   {
foreach ($variablesArray as $variableA){
	$variables [] = $variableA['variable'];
	$displayVariable[] =  '{{'.$variableA['variable'].'}}';
}
}
echo Loader::helper('concrete/ui')->tabs(array(
    array('csv-variables', t('CSV Variables'), true),
    array('import', t('Import')),
	array('content-settings', t('Content Settings'))
));?>
<!--=========variables section start===============-->
<div class="ccm-tab-content" id="ccm-tab-content-csv-variables">

<div class="row">
	<div class="col-md-12">

    <div class="res-message variable-error">
   		 <div class="alert alert-danger fade in alert-dismissable">
    		<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    		<strong>Error</strong> an error occured while saving variable, Plase try again
		</div>
    </div>

    <div class="res-message variable-success">
   		 <div class="alert alert-success  fade in alert-dismissable">
    		<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
    		<strong>Success</strong> Variable saved successfully.
		</div>
    </div>

  		<div class="form-group variables_cover">
    		<label for="variables">Add CSV Variable (please avoid space):</label>
    		<input type="text" class="form-control full-width-variable-box" id="variables" value="<?php echo implode(',',$variables) ?>" data-role="tagsinput"/>
    	</div>
       <div class="form-group">
       <input type="button" class="btn btn-primary pull-right" id="save_variable" value="Save variable" />
       </div>
  	</div>
</div>




</div>
<!--=========variables section end===============-->
<form method="post" id="settings-form" action="<?php echo $this->action('save_settings');?>">
 <?php  echo $this->controller->token->output('save_settings')?>
<!--=========import section start===============-->
<div class="ccm-tab-content" id="ccm-tab-content-import">




  <div class="clearfix clear-bottom">
    <?php  echo $form->label('SERVICE_LOCATIONS_ATTRIBUTE_SET_ID', t('Choose Attribute Set '))?>
    <div class="input">
      <?php if(sizeof($attribute_sets)>0){ ?>
      <?php echo $form->select('SERVICE_LOCATIONS_ATTRIBUTE_SET_ID',$attribute_sets,$attribute_set_id)?>
      <?php }else {
       echo 'No Attribute sets found';
        }?>
    </div>
  </div>


  <div class="clearfix clear-bottom">
    <?php  echo $form->label('SERVICE_LOCATIONS_PAGE_TEMPLATE_ID', t('Choose Page Template'),array('class'=>'col-sm-3'))?>
    <div class="input">
      <?php if(sizeof($attribute_sets)>0){
		   ?>
      <?php echo $form->select('SERVICE_LOCATIONS_PAGE_TEMPLATE_ID',$PageTemplates,$page_template_id)?>
      <?php }else {
       echo 'No Page Templates found';
        }?>
    </div>
  </div>


  <div class="clearfix clear-bottom" >
    <?php  echo $form->label('SERVICE_LOCATIONS_COLLECTION_TYPE_ID',t('Choose Page Type'),array('class'=>'col-sm-3'))?>
    <div class="input">
      <?php if(sizeof($attribute_sets)>0){ ?>
      <?php echo $form->select('SERVICE_LOCATIONS_COLLECTION_TYPE_ID',$PageTypes,$page_type_id)?>
      <?php }else {
       echo 'No Page Types found';
        }?>
    </div>
  </div>


  	<div class="clearfix clear-bottom">
  		<div class="input"> <strong> Note:Choose any one option.</strong> </div>
 	</div>

  <div class="clearfi clear-bottomx">
    <label>Choose the CSV file</label>
    <div class="input"> <?php echo $al->file('ccm-b-file', 'fID', t('Choose File'), $bf)?> </div>
  </div>

  <div class="clearfix clear-bottom">
  	<div class="input"> <strong> Or Add data manually</strong></div>
  </div>

  <div class="clearfix clear-bottom">
  	 <div id="pagesToAdd"> </div>
 	 <div class="csv_button" > <?php echo $form->button("add_page", "Add Manually", array('class'=>"btn btn-primary"))?> </div>
  </div>
</div>




</div>
<!--=========import section end===============-->
<!--=========content section start===============-->
<div class="ccm-tab-content" id="ccm-tab-content-content-settings">
  <div class="clearfix clear-bottom">
    <label>Choose the Service Parent Page</label>
    <div class="input"> <?php echo  $form->select("internalLinkCID",$parent_pages);?><img src="<?php echo BASE_URL . DIR_REL . '/' . DIRNAME_PACKAGES.'/service_locations/image/ajax_loader.gif' ?>" class="loader" width="16px" height="18px"/> </div>
  </div>


  <div class="input clear-bottom"> <strong> Note:Available variable's are</strong> <span class="variables-list"><?php echo implode(', ',$displayVariable); ?></span> </div>

  <div class="clearfix clear-bottom">
    <label>Add the default Content</label>
    <div class="input default-content">
      <?php
		 $editor = Core::make('editor');
		 echo $editor->outputStandardEditor('defaultContent');
		  ?>
    </div>
  </div>


  <div class="clearfix clear-bottom">
    <label>Add Page Title</label>
    <div class="input"> <?php echo $form->text("pageTitle",$pageTitle);   ?> </div>
  </div>


  <div class="clearfix clear-bottom">
    <label>Add Meta Title</label>
    <div class="input"> <?php echo $form->text("metaTitle",$metaTitle);   ?> </div>
  </div>


  <div class="clearfix clear-bottom">
    <label>Add Meta Description</label>
    <div class="input"> <?php echo  $form->textarea("metaDescription",$metaDescription);  ?> </div>
  </div>


  <div class="clearfix clear-bottom">
    <label>Add Meta Tags</label>
    <div class="input"> <?php echo  $form->textarea("metaTags",$metaTags);  ?> </div>
  </div>


  <div class="clearfix clear-bottom" style="display:none">
    <div class="input">
      <p><?php echo  $form->checkbox("fileset","fileset");  ?> Choose file set for the Service Locations</p>
    </div>
  </div>


  <div class="clearfix clear-bottom" id="fileset_choose" style="display:none">
    <div class="input" >
      <p><?php echo  $form->select("fileset_choosed",$fileset_details);?> </p>
    </div>
  </div>


  <div class="clearfix clear-bottom">
    <div class="input">
      <p><?php echo  $form->checkbox("update","update");  ?> <?php echo t("Mass update the pages"); ?> (<em><?php echo t("You must choose the service parent page"); ?></em>)</p>
    </div>
  </div>


  <div class="ccm-pane-footer">
    <?php $ih = Loader::helper('concrete/ui'); ?>
    <?php print $ih->submit('Save Settings ', 'settings-form', 'right', 'btn-primary'); ?> <?php print $ih->button(t('Cancel'), $this->url('/dashboard/service_locations/service_list'), 'left'); ?>
  </div>




</div>
<!--=========content section end===============-->
</form>
<style>
.bootstrap-tagsinput {
	width:100%;
}
.full-width-variable-box, .bootstrap-tagsinput input {
 width:100%;
 min-height:50px;
}
.res-message {
display:none;
}
.clear-bottom {
    margin-bottom: 25px;
}
.manual-close {
    font-size: 16px !important;
    margin: 0 6px 6px 0;
	display:block
}
.close-cover{
width:100%;
float:left;
}
</style>
<script>
<?php
$variablesArray = Concrete\Package\ServiceLocations\Src\LocationSettings::getVariableName();
$variables = array();
foreach ($variablesArray as $variableA){
	$variables [] = $variableA['variable'];
}
?>
var jsonVariables = <?php echo json_encode($variables); ?>;
var manualID = 1;
$(document).ready(function(){
	$('#save_variable').on('click',function($ev){
		$ev.preventDefault();
		var variables = $("#variables").tagsinput('items');

		$.ajax({
			url:'<?php echo $this->action('save_variable'); ?>',
			data: {'variables':variables},
			method:'POST',
			success:function(response){
				var obj = JSON.parse(response);
				if(obj.error){
					$('.variable-error').show();
					$('.variable-success').hide();
				}else{
					$('.variable-success').show();
					$('.variable-error').hide();
					console.log(obj.variables);
					$('.variables-list').html(obj.variables.join(', '))
					jsonVariables = obj.responses;
				}
				console.log(obj);

			},
			error:function(response){


			}
			});

	});

   var itemCover = $('#pagesToAdd');

 	$('#add_page').on('click',function(){
		var html = '<div class="panel panel-default"><div class="close-cover"> <a href="javacript:void(0)" onclick="$(this).parent().parent().remove()" id="close" class="close manual-close"><i class="fa fa-times" aria-hidden="true"></i></a></div><div class="panel-body"><div class="row">';
		$.each(jsonVariables ,function(key,value){
			 html+='<div class="col-md-3 clear-bottom"><input name=\"'+value+'['+manualID+']\" placeholder=\"'+value+'\" type=\"text\" class="form-control" /></div>';
		});
		if(manualID == 1){
			html += '<input type="hidden" name="manual_id" value="1">';
		}
		html += '</div></div></div>';
		manualID = manualID+1;
		itemCover.append(html);
	});

	$('.variables_cover').on('keypress','input',function(ev){
	  	if(ev.which == 32){
			ev.preventDefault();
			alert("Space is not allowed");
	 	}
	});

	$("#fileset").on('change',function(){
		var length=$('#fileset:checked').length;
	   if(length==1){
		   $('#fileset_choose').show();
		}else{
			  $('#fileset_choose').hide();
		}

	});

	$('#internalLinkCID').change(function(){
	var id = $('#internalLinkCID').val();
	$('.loader').show();

	$.ajax({
		type: "POST",
		url:  '<?php echo $this->action('get_defalut_content'); ?>/' + id,

success: function(data) {

	$('.loader').hide();
	console.log("sucess");


var obj=$.parseJSON(data);
//console.log(obj.page_title);


if(obj.meta_title!=false){
$('#metaTitle').val(obj.meta_title);
}
if(obj.meta_description!=false){
$('#metaDescription').val(obj.meta_description);
}
if(obj.meta_keywords!=false){
$('#metaTags').val(obj.meta_keywords);
}
if(obj.page_titles!=false){
$('#pageTitle').val(obj.page_titles);
}
if(obj.service_content!=false){
$('.default-content .cke_wysiwyg_div').html(obj.service_content);
}


if((obj.meta_title!=false)&&(obj.meta_description!=false)&&(obj.meta_keywords!=false)&&(obj.service_content!=false)){}
  },
error: function(e) {
	//called when there is an error
	console.log(e.message);
  }

    });

	});

});
</script>
