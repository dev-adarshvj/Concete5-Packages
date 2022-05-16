<?php    defined('C5_EXECUTE') or die(_("Access Denied."));

$form = Loader::helper('form');

?>

<ul class="nav nav-tabs" id="block-tabs">
	<li class="active"><a href="#settings"><?php    echo t('Settings'); ?></a></li>
	<li><a href="#advanced"><?php    echo t('Advanced'); ?></a></li>
</ul>

<div id="settings" style="padding: 10px;">
	
	<?php    echo $form->hidden('testimonial_ids', $testimonial_ids); ?>
	
	<div class="form-group">
		<?php    echo $form->label('title', t('Title')); ?>
		<div class="controls">
		<?php    echo $form->text('title', $title, array('class'=>'span4')); ?>
		</div>	
	</div>
	
	<div class="form-group">
		<?php    echo $form->label('display_type', t('Display Type')); ?>
		<div class="controls">
				<?php    echo $form->select('display_type', $type_options, $display_type ,array('class' => 'span4'));?>
				
				<div class="well" style="margin: 10px 0 0; width: 290px; display: none;" id="multiple-options" >
					<span><?php    echo t('# to display'); ?></span>
					<?php    echo $form->text('number', $number, array('class'=>'span1')); ?>
					<span class="small text-muted"><?php   echo t('Enter 0 to display all') ?></span>
					
					<div id="paging-options" style="margin-top: 10px">
						<div class="checkbox">
						<label>
						<?php    echo $form->checkbox('pagination', '1', $pagination); ?>
						<?php    echo t('Display pagination interface if more items are available than are displayed.'); ?>
						</label>
						</div>
					</div>
					
					<div id="rotate-options" style="display:none">
						<div style="margin-top: 10px;">
							<span><?php    echo t('Speed'); ?></span>
							<?php    echo $form->text('rotate_length', $rotate_length,  array('style' => 'width: 100px'));?>
							<div class="help-inline text-muted"><?php    echo t('In milliseconds.')?></div>
						</div>
						<div style="margin-top: 10px;">
							<span><?php    echo t('Effect'); ?></span>
							<?php    echo $form->select('effect', array('fade'=>t('Fade'), 'slide'=>t('Slide')), $effect, array('style'=>'width: 100px'));?>
						</div>
					</div>
				</div>
		</div>
	</div>
	
	<div class="form-group">
		<?php    echo $form->label('display', t('Filter')); ?>
		<div class="controls">
				<?php    echo $form->select('display', $display_options, $display ,array('class' => 'span4'));?>
				
				<div class="well" style="margin: 10px 0 0; width: 290px; display: none;" id="category-options" >
					<?php    
					if(is_array($categories) && count($categories)){
						foreach($categories as $category){ 
							echo '<div class="checkbox"><label>'.$form->checkbox('category[]', $category, in_array($category, (array)$existing_categories)),' ',$category,'</label></div>'; 
						}
					} else {
						echo '<p>'.t('No categories have been added yet.').'</p>';
					} ?>
				</div>
				
				<div class="well" style="margin: 10px 0 0; width: 290px; display: none;" id="select-options" >
					<button type="button" class="btn btn-default" id="select-testimonials-btn"><?php    echo t('Select Testimonials'); ?></button>
				</div>
				
		</div>
	</div>
	
	<div class="form-group">
		<?php    echo $form->label('sort', t('Sort By')); ?>
		<div class="controls">
				<?php    echo $form->select('sort', $sort_options, $sort ,array('class' => 'span4'));?>
		</div>
	</div>
	
</div>

<div id="advanced" style="padding: 10px; display: none;">
	
	<div class="form-group">
		<div class="checkbox">
			<label> <?php   echo $form->checkbox('display_image', '1', $display_image); ?> <?php    echo t('Display Image'); ?></label>
		</div>
	</div>
	
	<div class="form-group">
		<?php    echo $form->label('image_size', t('Image Size')); ?>
		<div class="controls">
			<div class="row form-inline">
				<div class="col-sm-4">
				<?php    echo $form->text('image_width', $image_width); ?>
				<div class="help-block"><?php    echo t('(width)'); ?></div>
				</div>
				<div class="col-sm-4">
				<?php    echo $form->text('image_height', $image_height); ?>
				<div class="help-block"><?php    echo t('(height)'); ?></div>
				</div>
			</div>
		</div>	
	</div>

	<div class="well">
		<div class="form-group" style="margin-top: 10px;">	
			<div class="checkbox">
				<label>
				<input type="checkbox" value="1" name="enable_submit" <?php    if($enable_submit){ echo 'checked'; } ?>>
				<?php   echo t('Enable User Submissions') ?>
				</label>
				<p class="small text-muted"><?php    echo t('Any submission must be approved before it will be displayed.'); ?></p>
			</div>
		</div>
		
		<div class="form-group">
			<?php    echo $form->label('submit_link_text', t('Submit Link Text')); ?>
			<?php    echo $form->text('submit_link_text', $submit_link_text); ?>
		</div>
		
		<div class="form-group">
			<label class="control-label"><?php    echo t('Notify by email on submission'); ?></label>
			<div class="input-group">
				<span class="input-group-addon">
					<?php    echo $form->checkbox('notify_on_submission', '1', $notify_on_submission, array('onclick' => "$('input[name=recipient_email]').focus()")); ?>
				</span>
				<?php    echo $form->text('recipient_email', $recipient_email); ?>
			</div>	
		</div>
	</div>

</div>


<script type="text/javascript">


$(function(){
	
	/* Tabs */
	$("#block-tabs a").click(function(e){
		e.preventDefault()
		
		$("#settings").hide()
		$("#advanced").hide()
		
		$($(this).attr('href')).show()
		
		$(this).parent().addClass('active').siblings('li').removeClass('active')
	})
	
	$("#display_type").change(function(){
		if($(this).val() == 'multiple' || $(this).val() == 'single_rotate'){
			$("#multiple-options").show()
			if($(this).val() == 'single_rotate'){
				$("#rotate-options").show()
				$("#paging-options").hide()
			} else {
				$("#paging-options").show()
				$("#rotate-options").hide()
			}
		} else {
			$("#multiple-options").hide()
		}
	})
	
	$("#display").change(function(){
        if($(this).val() == 'select'){
            $("#select-options").show();
        } else {
            $("#select-options").hide();
        }
		
        if($(this).val() == 'category'){
            $("#category-options").show();
        } else {
            $("#category-options").hide();
        }
	})
	
	/* defaults */
	$("#display_type").change()
	$("#display").change()

	$("#select-testimonials-btn").click(function(e){
		e.preventDefault();

	   	jQuery.fn.dialog.open({
		  title: '<?php    echo t('Select Testimonials')?>',
		  href: "<?php    echo REL_DIR_FILES_TOOLS_BLOCKS.'/studio_testimonials_pro/select_testimonials'?>",
		  width: 450,
		  modal: false,
		  height: 380
	   	});
	})
	
}) // end ready

</script> 
