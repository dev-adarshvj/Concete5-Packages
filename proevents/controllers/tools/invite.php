<?php  
namespace Concrete\Package\Proevents\Controller\Tools;

use User;
use UserInfo;
use Loader;
use Page;
use Block;
use Concrete\Core\Controller\Controller as RouteController;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;

class Invite extends RouteController
{

    public function render()
    {
        Loader::PackageElement('tools/invite', 'proevents');
    }

    public function validate()
    {
        $request = \Request::getInstance();
        function isValidEmail($email)
        {
            //return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        if (strlen($request->get('invite_emails')) < 1) {
            print t('You must enter at least one email address.');
            exit;
        }
        if (strpos($request->get('invite_emails'), ",") > 0) {
            print t('Your email list is in the wrong format');
            exit;
        }

        $emails_array = explode("\r\n", $request->get('invite_emails'));
        foreach ($emails_array as $email) {
            if (isValidEmail($email) != $email) {
                print t(
                    'We\'re sorry.  But one or more emails entered are not valid email addresses. Please try again'
                );
                exit;
            }
        }

        $ui = UserInfo::getByID($request->get('uID'));
        $uName = $ui->getUserFirstName() . ' ' . $ui->getUserLastName();

        $event = Page::getByID($request->get('ccID'));
        $eName = $event->getCollectionName();
        $eDescription = $event->getCollectionDescription();
        $eLink = BASE_URL . Loader::helper('navigation')->getLinkToCollection($event);

        $mh = Loader::helper('mail');
        $mh->addParameter('uName', $uName);
        $mh->addParameter('eName', $eName);
        $mh->addParameter('eDescription', $eDescription);
        $mh->addParameter('eLink', $eLink);
        $mh->from('events@' . substr(BASE_URL, 7));

        foreach ($emails_array as $email) {
            $mh->to($email);
        }
        $mh->load('event_invite', 'proevents');
		$mh->setSubject(t('You have been invited to an Event!'));
        @$mh->sendMail();

        print 'success';
        exit;
    }

}
