<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));


$fm = Loader::helper('form');

?>
<style type="text/css">
    #event_categories {
        margin-left: 0;
        margin-top: 22px;
    }

    #event_categories, #event_categories li {
        list-style: none;
    }

    #event_categories li {
        padding: 6px 0;
    }

    #category_remove {
        display: none;
    }

    .colorpicker {
        border-right-width: 30px !important;
    }
</style>
<link rel="stylesheet" type="text/css" href="/concrete/package/proevents/css/colpick.css">
<script type="text/javascript" href="/concrete/package/proevents/js/colpick.js"></script>
<a href="javascript:;" id="add_event_category" class="btn btn-success"><i class="fa fa-plus"></i> <?php  echo  t(
        'Add Category'
    ) ?></a>
<ul id="event_categories" class="sortable">
    <?php   if ($categories) { ?>
        <?php   foreach ($categories as $cat) { ?>
            <li><i class="fa fa-arrows"></i> <input type="hidden" class="category_id" name="category_id[]"
                                                    value="<?php  echo  $cat['ecID'] ?>"/><input type="text"
                                                                                        name="category_name[]"
                                                                                        placeholder="Category Name"
                                                                                        value="<?php  echo  $cat['value'] ?>"/> #<span
                    class="picker-wrap"><input type="text" name="category_color[]" class="colorpicker"
                                               placeholder="234567" value="<?php  echo  $cat['color'] ?>"/></span> <i
                    class="fa fa-trash-o remove_event_category"></i></li>
        <?php   } ?>
    <?php   } ?>
</ul>
<div id="category_remove">

</div>
<script type="text/javascript">
    $('document').ready(function () {
        $('#add_event_category').click(function () {
            var newItem = '<li><i class="fa fa-arrows"></i> <input type="text" name="category_name[]" placeholder="Category Name" />  #<span class="picker-wrap"><input type="text" name="category_color[]" class="colorpicker" placeholder="234567" /></span> <i class="fa fa-trash-o remove_event_category"></i></li>';
            $('#event_categories').append(newItem);
            setUpColorPicker();
        });
        $('#event_categories').sortable();

        $('.remove_event_category').click(function () {
            if ($(this).parent().find('.category_id')) {
                var removeID = $(this).parent().find('.category_id').val();
                var remove = '<input type="hidden" name="category_remove[]" value="' + removeID + '"/>';
                $('#category_remove').append(remove);
            }
            $(this).parent().remove();
        });

        setUpColorPicker();
    });

    function setUpColorPicker() {
        $('.colorpicker').each(function () {
            var color = $(this).val();
            if (color) {
                $(this).css('border-color', '#' + color);
            }
            $(this).colpick({
                layout: 'hex',
                submit: 0,
                colorScheme: 'dark',
                onChange: function (hsb, hex, rgb, el, bySetColor) {
                    //$(el).css('border-color','#'+hex);
                    $(el).css('border-right', '30px solid #' + hex);
                    // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
                    if (!bySetColor) $(el).val(hex);
                }
            }).keyup(function () {
                $(this).colpickSetColor(this.value);
            });
        });
    }
</script>