<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<style type="text/css">
    #color_preview {
        width: 32px;
        height: 32px;
        display: block;
        float: left;
        border: 1px solid #c1c1c1;
        margin: 0 12px;
    }
    #add_category{
        float: right;
        margin-right: 18px;
    }
</style>
<span id="color_preview" style="background-color: '#<?php  echo  $cat['color'] ?>';"></span>
<a href="<?php  echo URL::to('/proevents/tools/color_category')?>?akID=<?php  echo $akID?>" id="add_category" class="btn btn-info"><i class="fa fa-plus-circle"></i></a>
<select name="akID[<?php  echo  $akID ?>][value]" class="form-control" id="event-category-select">
    <?php   if ($categories) { ?>
        <?php   foreach ($categories as $cat) { ?>

            <option value="<?php  echo  $cat['value'] ?>" <?php   if ($selected_category == $cat['value']) {
                echo 'selected';
            } ?>><?php  echo  $cat['value'] ?></option>

        <?php   } ?>
    <?php   } ?>
</select>
<div id="add_value">

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#add_category').click(function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
               url: url,
               success: function(response){
                   $('#add_value').html(response);
               },
            });
            return false;
        });
        $('#event-category-select').change(function () {
            var colors = {
            <?php   if($categories){ ?>
            <?php   foreach($categories as $cat){ ?>
                '<?php  echo $cat['value']?>': '<?php  echo $cat['color']?>',
            <?php   } ?>
            <?php   } ?>
        };
        var id = $('option:selected', this).val();
        var selected_color = colors[id];
        $('#color_preview').css('background-color', '#' + selected_color);
    });

    var colors = {
    <?php   if($categories){ ?>
    <?php   foreach($categories as $cat){ ?>
        '<?php  echo $cat['value']?>': '<?php  echo $cat['color']?>',
    <?php   } ?>
    <?php   } ?>
    };
    var id = $('#event-category-select option:selected').val();
    var selected_color = colors[id];
    $('#color_preview').css('background-color', '#' + selected_color);
    });
</script>