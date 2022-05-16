<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$request = \Request::getInstance();
?>
<div id="event_invite" class="ccm-ui">
    <div id="email_message">

    </div>
    <div id="email_form">
        <?php   echo t('Please list each email on a new line.'); ?>
        <br/>

        <form name="event_invite" class="event_invite">
            <textarea name="invite_emails" class="invite_emails"></textarea>
            <br/>
            <input type="hidden" name="ccID" value="<?php   echo $request->get('ccID'); ?>"/>
            <input type="hidden" name="uID" value="<?php   echo $request->get('uID'); ?>"/>
            <button name="send_invite" class="send_invite btn info"><?php   echo t('Send Invite'); ?></button>
        </form>
    </div>
</div>
<script type="text/javascript">
    /*<![CDATA[*/
    $('.send_invite').click(function () {
        var url = '<?php   echo URL::to('/proevents/tools/invite_validate');?>?';
        var form = $('#event_invite .event_invite').serialize();
        $.get(url + form, function (response) {
            //alert(response);
            if (response != 'success') {
                $('#event_invite #email_message').html('<div class="alert alert-danger" role="alert"><p>' + response + '</p></div>');
            } else {
                $('#event_invite #email_form').hide();
                $('#event_invite #email_message').html('<div class="alert alert-success" role="alert"><p><?php     echo t('Your Invites were sent successfully!');?></p></div>');
            }
        });
        return false;
    });
    /*]]>*/
</script>