<?php   defined('C5_EXECUTE') or die("Access Denied.");

use \Concrete\Package\StudioTestimonialsPro\Src\TestimonialList;

$form = Loader::helper('form');
$text = Loader::helper('text');

$list = new TestimonialList();
$list->filter('approved', 1);
$testimonials = $list->get();

?>
<script type="text/javascript">
chooseSelected = function(){
    var choices = new Array();
    $('#choose-testimonials input:checked').each(function(index, ele){
        choices[index] = $(this).val();
    });
    $("#testimonial_ids").val(choices.toString());
    jQuery.fn.dialog.closeTop();
}
$(function(){
    current = $("#testimonial_ids").val();
    if(current){
        current_array = current.split(',');
        for(var i in current_array)
        {
            $('input[value='+current_array[i]+']').attr('checked','checked');
        }
    }
});
</script>
<div id="choose-testimonials" class="ccm-ui">
<table class="table table-striped table-condensed">
    <tr>
        <th scope="col">&nbsp;</th>
        <th scope="col"><?php    echo t('Author'); ?></th>
        <th scope="col"><?php    echo t('Testimonial'); ?></th>
    </tr>
<?php    foreach($testimonials as $testimonial){ ?>
    <tr>
        <td><input type="checkbox" name="selected-testimonials" value="<?php    echo $testimonial->id; ?>"></td>
        <td><?php    echo $testimonial->author; ?></td>
        <td><?php    echo $text->shorten($testimonial->content, 50); ?></td>
    </tr>
<?php    } ?>
</table>
<br/>
<div class="ccm-pane-footer">
<button type="button" class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?php   echo t('Cancel') ?></button>
<button type="button" class="btn btn-primary pull-right" onclick="chooseSelected()"><?php   echo t('Choose Selected') ?></button>
</div>
</div>
