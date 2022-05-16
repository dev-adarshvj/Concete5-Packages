<?php     
defined('C5_EXECUTE') or die("Access Denied.");

$formDisplayUrl= View::url('dashboard/studio_testimonials_pro');

$body = t("
There has been a testimonial submission on your Concrete5 website.

To view and/or approve this testimonial, visit %s 

", $formDisplayUrl);