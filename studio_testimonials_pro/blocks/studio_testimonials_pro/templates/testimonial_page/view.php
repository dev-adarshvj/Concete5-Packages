<?php    defined('C5_EXECUTE') or die(_("Access Denied."));
$text = Loader::helper('text');
$ih = Loader::helper('image');
?>
<!--
<section class="testimonials-list">
<div class="container">
<div class="row">
<div class="col-sm-12">
-->
<section class="testimonials-list">
<div class="container">
<div class="row">
<div class="col-sm-12">
<?php    if($display_type == 'single_rotate' && count($testimonials) > 1){?>

<span>CLIENT TESTIMONIALS</span>
	<?php    if(strlen($title) > 0): ?><h2><?php    echo $title?></h2><?php    endif; ?>
		<ul class="st-pro-ticker">
			<?php    foreach($testimonials as $testimonial){ ?>
				<li>
				<div class="st-pro-testimonial">
					<?php    if($display_image && $testimonial->image > 0):
						$f = File::getByID($testimonial->image);
						$image_path = $ih->getThumbnail($f, $image_width, $image_height);
					?>
					<div class="st-pro-image"><img src="<?php    echo $image_path->src; ?>" alt=""></div>
					<?php    endif; ?>
					<div class="st-pro-content" ><p><?php    echo nl2br($testimonial->content); ?></p>
<!--                        <a href="/testimonials">Read More+</a>-->
                        </div>
					<div class="st-pro-author"><?php    echo '&#8211; ',$testimonial->author; ?></div>
					<div class="st-pro-extra"><?php    echo nl2br($text->autolink($testimonial->extra)); ?></div>
				</div>
				</li>
			<?php    } ?>
		</ul>

	<?php    if($enable_submit){ ?>
		<div class="st-pro-submit-link"><a href="#st-form-<?php   echo $bID ?>" class="submit-link"><?php    echo $submit_link_text; ?></a></div>
	<?php    } ?>

<!--</div>-->
   

<?php    } else { ?>

<div class="st-pro-wrapper" id="studio-testimonials-<?php    echo $bID; ?>">
	<?php    if(strlen($title) > 0): ?><h2><?php    echo $title?></h2><?php    endif; ?>
<!--    <h2>What our clients have to say...</h2>-->
<?php    foreach($testimonials as $testimonial){ ?>
<div class="st-pro-testimonial">
	<?php    if($display_image && $testimonial->image > 0):
		$f = File::getByID($testimonial->image);
		$image_path = $ih->getThumbnail($f, $image_width, $image_height);
	?>
	<div class="st-pro-image"><img src="<?php    echo $image_path->src; ?>" alt=""></div>
	<?php    endif; ?>
    <div class="st-pro-content" style="background: none;"><p><?php    echo nl2br($testimonial->content); ?></p>
<!--        <a href="/testimonials">Read More+</a>-->
        </div>
    <div class="st-pro-author"><?php    echo '&#8211; ',$testimonial->author; ?></div>
    <div class="st-pro-extra"><?php    echo nl2br($text->autolink($testimonial->extra)); ?></div>
</div>

<?php    } ?>

<?php    if($pagination && $list){
	$list->displayPagingV2();
} ?>

<?php    if($enable_submit){ ?>
    <div class="st-pro-submit-link"><a href="#st-form-<?php   echo $bID ?>"><?php    echo $submit_link_text; ?></a></div>
<?php    } ?>

</div>
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
<!--
    </div>
    </div>
    </div>
</section>
-->
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
 </div>
    </div>
    </div>
</section>