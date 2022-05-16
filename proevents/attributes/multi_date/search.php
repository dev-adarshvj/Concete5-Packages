<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<style type="text/css">

</style>
<?php  
$v = View::getInstance();
$v->addFooterItem(
    '
    <script type="text/javascript">
    $(function(){
        ccm_checkSelectedAdvancedSearchField = function(b,a){
            //alert(b)
            $("#ccm-"+b+"-search-field-set"+a+" .ccm-search-option-type-date_time input").each(function(){
                if($(this).attr("id")=="date_from"){
                    $(this).attr("id","date_from"+a)
                }else{
                    if($(this).attr("id")=="date_to"){
                        $(this).attr("id","date_to"+a)
                    }
                }
            });

            $("#ccm-"+b+"-search-field-set"+a+" .ccm-search-option-type-date_time input").each(function(){
                $(this).attr("id",$(this).attr("id")+a)
            });

            $("#ccm-"+b+"-search-field-set"+a+" .ccm-search-option-type-multi_date input").each(function(){
                $(this).attr("id",$(this).attr("id")+a)
            });

            $("#ccm-"+b+"-search-field-set"+a+" .ccm-search-option-type-date_time input").datepicker({showAnim:"fadeIn"});
            $("#ccm-"+b+"-search-field-set"+a+" .ccm-search-option-type-multi_date input").datepicker({showAnim:"fadeIn"});

            $("#ccm-"+b+"-search-field-set"+a+" .ccm-search-option-type-rating input").rating()
        };
    });
    </script>
    ',
    'SCRIPT'
);