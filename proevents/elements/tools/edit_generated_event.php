<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\Proevents\Src\ProEvents\EventItemDate;

$request = \Request::getInstance();
$id = $request->get('id');
$date = new EventItemDate($id);
$fm = Loader::helper('form');
$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
?>
<style type="text/css">
    td {
        padding: 4px !important
    }

    .lable {
        text-align: left;
        width: 120px !important;
        vertical-align: top;
    }
</style>
<div class="ccm-ui">
    <style type="text/css">

    </style>
    <form action="<?php   echo URL::to('/dashboard/proevents/generated_dates/date_edit/'); ?>" method="post" name="update_event"
          id="generatedEventEdit">
        <table>
            <tr>
                <td class="lable"><?php   echo t('Title') ?></td>
            </tr>
            <tr>
                <td><?php   echo $fm->text('title', $date->title); ?></td>
            </tr>
            <tr>
                <td class="lable"><?php   echo t('Status') ?></td>
            </tr>
            <tr>
                <?php  
                $status = array(
                    '' => t('none'),
                    'available' => t('Available'),
                    'booked' => t('Booked')
                );
                ?>
                <td><?php   echo $fm->select('status', $status, $date->status); ?><br/><br/></td>
            </tr>
            <tr>
                <td class="lable"><?php   echo t('Price') ?></td>
            </tr>
            <tr>
                <td><?php   echo $fm->text('event_price', $date->event_price); ?></td>
            </tr>
            <tr>
                <td class="lable"><?php   echo t('Qty') ?></td>
            </tr>
            <tr>
                <td><?php   echo $fm->text('event_qty', $date->event_qty); ?></td>
            </tr>
            <tr>
                <td class="lable"><?php   echo t('Event Content') ?></td>
            </tr>
            <tr>
                <td>
                    <?php   echo $fm->hidden('eID', $id); ?>
                    <?php   echo $fm->textarea(
                        'description',
                        $date->description,
                        array('style' => 'width: 100%; font-family: sans-serif;', 'class' => 'ccm-advanced-editor')
                    ); ?>
                    <script type="text/javascript">
                        var CCM_EDITOR_SECURITY_TOKEN = "<?php  echo Loader::helper('validation/token')->generate('editor')?>";
                        $(document).ready(function(){
                            $('#description').redactor({
                                'concrete5': {
                                    filemanager: <?php   echo $fp->canAccessFileManager()?>,
                                    sitemap: <?php   echo $tp->canAccessSitemap()?>,
                                    lightbox: true
                                },
                                'plugins': [
                                    'fontcolor', 'concrete5'
                                ]
                            });
                        });
                    </script>
                    <br/>
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td>
                    <a class="btn btn-primary generated_date_submit" style="float: right;"><?php  echo  t(
                            'Update This Date'
                        ) ?></a>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    $(".generated_date_submit").click(function (event) {
        event.preventDefault();
        var form = $('#generatedEventEdit').serialize();
        window.location = '/dashboard/proevents/generated_dates/date_edit/?' + form;
        return false;
    });
</script>