<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$AJAX_url = URL::to('proevents/routes/list_ajax');
$c = Page::getCurrentPage();
$request = \Request::getInstance();
$paging = $request->get('ccm_paging_p');
Loader::packageElement('filters', 'proevents', array('c' => $c, 'AJAX_url' => $AJAX_url, 'bID' => $bID));
?>

<center class="ajax_loader" style="display: none;">
    <img src="<?php   echo Loader::helper('concrete/urls')->getBlockTypeAssetsURL($bt, 'ajax-loader.gif') ?>" alt="loading"/>
</center>

<div class="ccm-page-list" id="event_results">


</div>
<?php   if(!$c->isEditMode()){ ?>
<script type="text/javascript">
    /*<![CDATA[*/
    $(document).ready(function(){
        $('.ajax_loader').show();
        var url = '<?php    echo $AJAX_url?>';
        var args = {ccID: <?php    echo $c->getCollectionID();?>, joinDays: true, bID: <?php  echo $bID?> }
        $.ajax({
            url: url,
            data: args,
            success: function (data) {
                console.log(data)
                $('.ajax_loader').hide();
                $('#event_results').html(data);
            },
            error: function (xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
            }
        });
    });

    function getEventResults(url) {
        //$('#event_results').fadeOut('slow');
        $('#event_results').html('');
        $('.ajax_loader').show();
        $.ajax({
            url: url,
            success: function (data) {
                console.log(data);
                $('.ajax_loader').hide();
                $('#event_results').html(data);
            },
            error: function (xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err.Message);
            }
        });
        return false;
    }
    /*]]>*/
</script>
<?php   } ?>