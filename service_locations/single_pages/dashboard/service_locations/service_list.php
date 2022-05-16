<style type="text/css">
a:hover {
	text-decoration:none;
} BG color is a must for IE6
a.pagetooltip span {
	display:none;
	padding:2px 3px;
	margin-left:8px;
	margin-top: -20px;
}
a.pagetooltip:hover span {
	display:inline;
	position:absolute;
	background:#ffffff;
	border:1px solid #cccccc;
	color:#6c6c6c;
}
th {
	text-align: left;
}
td.align_top {
  width: 28%;
  padding-bottom: 13px;
}
.align_top {
	vertical-align: top;
}
.ccm-results-list tr td {
	border-bottom-color: #dfdfdf;
	border-bottom-width: 1px;
	border-bottom-style: solid;
}
.icon {
	display: block;
	float: left;
	height:20px;
	width:20px;
	background-image:url('<?php  echo ASSETS_URL_IMAGES?>/icons_sprite.png'); your location of the image may differ
}
.edit {
	background-position: -22px -2225px;
	margin-right: 6px!important;
}
.copy {
	background-position: -22px -439px;
	margin-right: 6px!important;
}
.delete {
	background-position: -22px -635px;
}
</style>
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('View/Search Service Locations'), false, false, false);?>
<div class="ccm-pane-body">
  <?php 
		if($remove_name){
		?>
  <div class="alert-message block-message error"> <a class="close" href="<?php  echo $this->action('clear_warning');?>">×</a>
   
      <?php  echo t('Are you sure you want to delete ').t($remove_name).'?';?>
    </p>
    <p>
      <?php  echo t('This action may not be undone!');?>
    </p>
    <div class="alert-actions"> <a class="btn small" href="<?php  echo BASE_URL.DIR_REL;?>/index.php/dashboard/service_locations/service_list/delete/<?php  echo $remove_cid;?>/<?php  echo $remove_name;?>/">
      <?php  echo t('Yes Remove This');?>
      </a> <a class="btn small" href="<?php  echo $this->action('clear_warning');?>">
      <?php  echo t('Cancel');?>
      </a> </div>
  </div>
  <?php 
		}
		?>
  <form method="get" action="<?php  echo $this->action('view')?>">
    <?php  
		$sections[0] = '** All';
		asort($sections);
		?>
    <table class="ccm-results-list table">
      <tr>
        <th><strong>
          <?php  echo $form->label('cParentID', t('Section'))?>
          </strong></th>
        <th><strong>
          <?php  echo t('by Name')?>
          </strong></th>
        <th></th>
      </tr>
      <tr>
        <td><?php  echo $form->select('cParentID', $sections, $cParentID)?></td>
        <td><?php  echo $form->text('like', $like)?></td>
        <td><?php  echo $form->submit('submit', t('Search'))?></td>
      </tr>
    </table>
  </form>
  <br/>
  <?php  
		$nh = Loader::helper('navigation');
		$fm = Loader::helper('form');
		if (sizeof($pageResults) > 0) { 
			?>
  <table border="0" class="ccm-results-list table" cellspacing="0" cellpadding="0">
    <tr>
      <th>&nbsp;</th>
      <th class="<?php  //echo $pageList->getSearchResultsClass('cvName')?>"><a href="<?php  //echo $pageList->getSortByURL('cvName', 'asc')?>">
        <?php  echo t('Name')?>
        </a></th>
      <th class="<?php  ///echo $pageList->getSearchResultsClass('cvDatePublic')?>"><a href="<?php  //echo $pageList->getSortByURL('cvDatePublic', 'asc')?>">
        <?php  echo t('Dates')?>
        </a></th>
      <th><?php  echo t('Section')?></th>
    </tr>
    <?php  
			foreach($pageResults as $cobj) { 
			
				$i++;
				
				$t++;
				
				if(is_object($cobj)){
					Loader::model('attribute/categories/collection');
					$section_id = $cobj->getCollectionParentID();
					$sec_page= Page::getByID($section_id);
					if($sec_page->cParentID!=1){
					$prefix=Page::getByID($sec_page->cParentID)->getCollectionName().'-';
					}else{
						$prefix='';
					}
					$page_section = $prefix.$sec_page->getCollectionName();
					
					
					
					
				
					$pkt = Loader::helper('concrete/urls');
					
				}
			?>
    <tr>
      <td width="88px" class="align_top"><a href="<?php   echo $this->url('/dashboard/service_locations/add_edit', 'edit', $cobj->getCollectionID())?>" class="pagetooltip icon edit"></a> &nbsp; <a href="<?php  echo $this->url('/dashboard/service_locations/service_list', 'duplicate', $cobj->getCollectionID())?>" class="pagetooltip icon copy"></a> &nbsp; <a href="<?php  echo $this->url('/dashboard/service_locations/service_list', 'delete_check', $cobj->getCollectionID(),$cobj->getCollectionName())?>" class="pagetooltip icon delete"></a></td>
      <td class="align_top"><a href="<?php   echo $nh->getLinkToCollection($cobj)?>">
        <?php   echo $cobj->getCollectionName()?>
        </a></td>
      <td class="align_top"><?php echo $cobj->getCollectionDatePublic(); ?></td>
      <td class="align_top"><?php echo Page::getByID($cobj->cParentID)->getCollectionName(); ?></td>
    </tr>
    <?php    } ?>
  </table>
  <br/>
  <?php   
			$pageList->getPagination();
		} else {
			print t('No page entries found.');
		}
		?>
</div>
<div class="ccm-pane-footer"> </div>
