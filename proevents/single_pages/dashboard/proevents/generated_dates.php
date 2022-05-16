<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$fm = Loader::helper('form');
$pkg = Package::getByHandle('proevents');
$url = URL::to('/proevents/tools/edit_generated_event');
?>
<style type="text/css">
    th {
        text-align: left;
    }

    .align_top {
        vertical-align: top;
    }

    .ccm-results-list tr td {
        border-bottom-color: #dfdfdf;
        border-bottom-width: 1px;
        border-bottom-style: solid;
    }
    div#ccm-dashboard-content header{padding: 1px 80px 14px 95px;}
</style>
<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(
    t('Generated Dates'),
    false,
    false,
    false
); ?>
<?php  
if ($remove_eid) {
    ?>
    <div class="ccm-ui" id="ccm-dashboard-result-message">
        <div class="alert-message block-message error">
            <a class="close" href="<?php   echo $this->action('clear_warning'); ?>">×</a>

            <p><strong><?php   echo t('Holy guacamole! This is a warning!'); ?></strong></p><br/>

            <p><?php   echo t('Are you sure you want to delete ') . t($remove_name) . '?'; ?></p>

            <p><?php   echo t('This action may not be undone!'); ?></p>

            <div class="alert-actions">
                <a class="btn small"
                   href="<?php   echo BASE_URL . DIR_REL; ?>/index.php/dashboard/proevents/generated_dates/delete/<?php   echo $remove_eid; ?>/<?php   echo $remove_name; ?>/"><?php   echo t(
                        'Yes Remove This'
                    ); ?></a> <a class="btn small" href="<?php   echo $this->action('clear_warning'); ?>"><?php   echo t(
                        'Cancel'
                    ); ?></a>
            </div>
        </div>
    </div>
<?php  
}
?>
<div class="ccm-dashboard-content-full">
    <table class="table">
        <tr>
            <th><strong><?php   echo t('Search') ?></strong></th>
            <th><strong><?php   echo t('By') ?></strong></th>
            <th><strong><?php   echo t('Action') ?></strong></th>
            <th></th>
        </tr>

        <form class="form-search" style="float: left;">
            <tr>
                <td>
                    <input type="text" name="search" value="<?php   echo $searchword ?>"
                           class="input-medium search-query">
                </td>
                <td>
                    <select name="search_type" class="form-control">
                        <option <?php   if ($searchtype == 'date') {
                            echo 'selected';
                        } ?>><?php   echo t('date') ?></option>
                        <option <?php   if ($searchtype == 'title') {
                            echo 'selected';
                        } ?>><?php   echo t('title') ?></option>
                        <option <?php   if ($searchtype == 'description') {
                            echo 'selected';
                        } ?>><?php   echo t('description') ?></option>
                    </select>
                </td>
                <td>
                    <button type="submit" class="btn"><?php   echo t('Search') ?></button>
                </td>
        </form>
        <td>
            <form style="float: left;padding-left: 6px;" action="<?php   echo $this->action('clear_search') ?>">
                <button type="submit" class="btn"><?php   echo t('Clear') ?></button>
            </form>
        </td>
    </table>
    <table border="0" class="ccm-search-results-table" id="dates_list">
        <thead>
        <tr>
            <th>&nbsp;</th>
            <th><a><?php   echo t('Date') ?></a></th>
            <th><a><?php   echo t('Title') ?></a></th>
            <th><a><?php   echo t('Status') ?></a></th>
            <th><a><?php   echo t('Price') ?></a></th>
            <th><a><?php   echo t('Qty') ?></a></th>
        </tr>
        </thead>
        <tbody>
        <?php  
        if ($fullDateList) {
            $count = count($fullDateList);
            foreach ($fullDateList as $key => $date) {
                $i++;
                ?>
                <tr data-set="<?php   echo $date['eID'] ?>" class="<?php   echo $date['status'] ?>">
                    <td width="88px" class="align_top">
                        <a href="<?php   echo $url ?>?id=<?php   echo $date['eID'] ?>" rel="<?php   echo $date['eID'] ?>"
                           class="eventtooltip icon edit dialog-launch" dialog-width="600" dialog-height="460"
                           dialog-modal="true" dialog-title="<?php   echo t('Edit Generated Date'); ?>"
                           dialog-on-close=""><i class="fa fa-edit"></i></a> &nbsp;
                        <a href="<?php   echo $this->action(
                            'delete_check',
                            $date['eID'],
                            $date['title'] . ' - ' . date('M d, Y', strtotime($date['date']))
                        ) ?>" class="eventtooltip icon delete"><i class="fa fa-trash-o"></i></a>
                    </td>
                    <td><?php   echo date('M d, Y', strtotime($date['date'])) . ' ' . date(
                                'g:ia',
                                strtotime($date['sttime'])
                            ) . '-' . date('g:ia', strtotime($date['entime'])) ?></td>
                    <td><?php   echo $date['title'] ?></td>
                    <td><?php   echo $date['status'] ?></td>
                    <td width="80px"><?php   print  $date['event_price'] ?></td>
                    <td width="80px"><?php   print  $date['event_qty'] ?></td>
                </tr>
            <?php  
            }
        }
        ?>
        </tbody>
    </table>
</div>
<div class="ccm-pane-footer">

</div>
<script type="text/javascript">
    $('table.paginated').each(function () {
        var currentPage = 0;
        var numPerPage = 25;
        var $table = $(this);
        $table.bind('repaginate', function () {
            $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
        });
        $table.trigger('repaginate');
        var numRows = $table.find('tbody tr').length;
        var numPages = Math.ceil(numRows / numPerPage);
        var $pager = $('<div class="pagination ccm-pagination"></div>');
        var $list = $('<ul></ul>');
        for (var page = 0; page < numPages; page++) {
            $('<li class="page-number"></li>').html('<a href="javascript:;">' + (page + 1) + '</a>').bind('click', {
                newPage: page
            },function (event) {
                currentPage = event.data['newPage'];
                $table.trigger('repaginate');
                $(this).addClass('active').siblings().removeClass('active');
            }).appendTo($list).addClass('clickable');
        }
        $list.appendTo($pager);
        $pager.appendTo('.ccm-pane-footer').find('li.page-number:first').addClass('active');
    });
</script>
<script type="text/javascript">
    /*<![CDATA[*/
    $(document).ready(function () {
        $('.dialog-launch').dialog();
    });
    /*]]>*/
</script>