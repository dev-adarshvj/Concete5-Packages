<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$request = \Request::getInstance();
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

    .colpick_dark{z-index: 9000;}
</style>
<div id="category_color">
    <input type="text"
           name="akID[<?php  echo $request->get('akID')?>][new_category][name]"
           placeholder="Category Name"
           value="<?php  echo  $cat['value'] ?>"/> #<span
        class="picker-wrap"> <input type="text" name="akID[<?php  echo $request->get('akID')?>][new_category][color]" class="colorpicker"
                                   placeholder="234567" value="<?php  echo  $cat['color'] ?>"/></span> <i
        class="fa fa-trash-o remove_event_category"></i>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.remove_event_category').click(function () {
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
                    $(el).css('border-color','#'+hex);
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