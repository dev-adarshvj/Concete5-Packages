<?php defined('C5_EXECUTE') or die("Access Denied");
$this->inc('elements/header.php'); ?>
<div class="page_bg">
      <div class="container">
    <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="bread">
               <?php $logo=new GlobalArea('Breadcrumb');
		$logo->display();
		 ?>
            </div>
      </div>
        </div>
  </div>
    </div>
<div class="wrapper inside">
      <div class="container">
    <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="inside_head service_det">
              <h2><?php echo $c->getCollectionName() ?></h2>
               <?php $logo=new Area('Inside Content');
					$logo->display($c);
				 ?>
             
            </div>
      </div>
        </div>
    <div class="row">
 <?php //$logo=new Area('Main');

	   
	   //$h1Title = $c->getCollectionName();
	   
//$pageTitle = $c->getAttribute('page_title');
//if($pageTitle != ''){
	//$h1Title = $pageTitle;
//}

	   ?>
	        
	        
             
              <?php echo $c->getCollectionAttributeValue('service_content'); ?>
              <?php 
      //$a=new Area('Main');
      //$a->display($c);
?>
        </div>
  </div>
    </div>
<?php
$this->inc('elements/footer.php'); ?>