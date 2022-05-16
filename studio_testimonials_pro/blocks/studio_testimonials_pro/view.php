<?php    defined('C5_EXECUTE') or die(_("Access Denied."));
$text = Loader::helper('text');
$ih = Loader::helper('image');
?>

<?php    if($display_type == 'single_rotate' && count($testimonials) > 1){?>


<span>CLIENT TESTIMONIALS</span>
	<?php    if(strlen($title) > 0): ?><h2><?php    echo $title?></h2><?php    endif; ?>

	<div class="testimonial-slider">
		<ul class="st-pro-ticker">

			<?php    foreach($testimonials as $testimonial){ ?>
				<li>
					<p><?php    echo nl2br(Loader::helper('text')->shorten($testimonial->content,325)); ?></p>
					<h6> <strong><?php    echo '&#8211; ',$testimonial->author; ?></strong></h6>
					<?php    echo nl2br($text->autolink($testimonial->extra)); ?>

				</li>
			<?php    } ?>
		</ul>
</div>
	<?php    if($enable_submit){ ?>
		<div class="st-pro-submit-link"><a href="#st-form-<?php   echo $bID ?>" class="submit-link"><?php    echo $submit_link_text; ?></a></div>
	<?php    } ?>


<?php    } else { ?>

<span>CLIENT TESTIMONIALS</span>
	<?php    if(strlen($title) > 0): ?><h2><?php    echo $title?></h2><?php    endif; ?>

	<div class="testimonial-slider">
<?php    foreach($testimonials as $testimonial){ ?>
	<div class="testimonial-item">
   <p><?php  echo nl2br(Loader::helper('text')->shorten($testimonial->content,325)); ?></p>
    <h6> <strong><?php    echo $testimonial->author; ?></h6> </strong>
    <h6><?php  echo implode(', ', (array)$testimonial->getCategories()); ?></h6>
    <a class="btn btn-primary" href="/testimonials" title="View All Testimonials">View All Testimonials</a>
    </div>
<?php } ?>
</div>
<?php    if($pagination && $list){
	 $list->displayPagingV2();
} ?>

<?php    if($enable_submit){ ?>
    <div class="st-pro-submit-link"><a href="#st-form-<?php   echo $bID ?>"><?php    echo $submit_link_text; ?></a></div>
<?php    } ?>

<style>
.page-template-full .inside-mainData{background-image:url();}
</style>
<?php    } // end if single_rotate ?>


<?php    if($enable_submit): ?>
<div id="st-form-<?php   echo $bID; ?>" class="st-form white-popup-block mfp-hide">
<h3><?php   echo t('Submit Testimonial') ?></h3>
<hr>
<form method="post" action="<?php    echo REL_DIR_FILES_TOOLS_BLOCKS.'/studio_testimonials_pro/submit_testimonial'; ?>" id="submit-testimonial-form">
    <?php   echo $form->hidden('bID', $bID ); ?>

    <div class="form-group">
        <?php    echo $form->label('author', t('Name'))?>
        <div class="controls"><?php    echo $form->text('author', $author, array('maxlength'=>200))?></div>
    </div>
    <div class="form-group">
        <?php    echo $form->label('content', t('Content'))?>
        <div class="controls"><textarea class="form-control" name="content" id="testimonial-content"><?php    echo $testimonial_content ?></textarea></div>
    </div>
    <div class="form-group">
        <?php    echo $form->label('extra', t('Extra'))?>
        <div class="controls"><?php    echo $form->textarea('extra', $extra)?></div>
        <div class="small text-muted"><?php    echo t('Anything entered here will appear below the author (eg. Title, Company, etc.). Anything beginning with http:// will automatically be linked..')?></div>
    </div>

    <div class="form-group">
    <button type="submit" class="btn btn-primary"><?php   echo t('Submit Testimonial'); ?></button>
    </div>
</form>
</div>

<script>
$(function(){
   $('#submit-testimonial-form').submit(function(e){
        e.preventDefault();
        var error = '';
        if($('#submit-testimonial-form #author').val() == ''){
            error = error + '<?php    echo t('Please enter an author.')?>\n';
        }
        if($('#submit-testimonial-form #testimonial-content').val() == ''){
            error = error + '<?php    echo t('Please enter a testimonial.')?>\n';
        }
        if(error != ''){
            alert(error);
        } else {
            $.post($('#submit-testimonial-form').attr('action'), $('#submit-testimonial-form').serialize(), function(res){
                alert('<?php    echo t('Testimonial has been submitted for approval.'); ?>')
                $.magnificPopup.close();
                $('#submit-testimonial-form').trigger('reset');
            })
        }
   });
});
</script>
<?php   endif; ?>
