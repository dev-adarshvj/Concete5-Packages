<?php   
namespace Concrete\Package\StudioTestimonialsPro\Block\StudioTestimonialsPro\Tools;

use Loader;
use Block;
use UserInfo;
use \Concrete\Package\StudioTestimonialsPro\Src\Testimonial;

defined('C5_EXECUTE') or die(_("Access Denied."));

$form = Loader::helper('form');

if($_POST){
    $block = Block::getByID($_POST['bID']);
    $cnt = $block->getController();

    $res = Testimonial::add($_POST);
    
    // send email
    if($cnt->notify_on_submission && $cnt->recipient_email){
        if( strlen(EMAIL_DEFAULT_FROM_ADDRESS)>1 && strstr(EMAIL_DEFAULT_FROM_ADDRESS,'@') ){
            $formFormEmailAddress = EMAIL_DEFAULT_FROM_ADDRESS;  
        }else{ 
            $adminUserInfo=UserInfo::getByID(USER_SUPER_ID);
            $formFormEmailAddress = $adminUserInfo->getUserEmail(); 
        }  
        
        try{
            $mh = Loader::helper('mail');
            $mh->to( $cnt->recipient_email ); 
            $mh->from( $formFormEmailAddress ); 
            $mh->load('testimonial_form_submission', 'studio_testimonials_pro');
            $mh->setSubject(t('Testimonial Submission'));
            $mh->sendMail(); 
        } catch(Exception $e){

        }
    }

    // reset form
    $_POST = array();
    
    exit;
}

