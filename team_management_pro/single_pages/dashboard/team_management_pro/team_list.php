<?php 
		if($remove_name){
		?>

<div class="alert-message block-message error" style="  background-color: #4674a1;padding: 20px; margin-bottom: 20px; color:#FFF;"> <a class="close" href="<?php  echo $this->action('clear_warning');?>">×</a>
  <p><strong>
    <?php  echo t('This is a warning!');?>
    </strong></p>
  <br/>
  <p><?php  echo t('Are you sure you want to delete ').t($remove_name).'?';?></p>
  <p><?php  echo t('This action may not be undone!');?></p>
  <div class="alert-actions"> <a class="btn btn-danger" href="<?php  echo BASE_URL;?>/index.php/dashboard/team_management_pro/team_list/delete/<?php  echo $remove_cid;?>/<?php  echo $remove_name;?>/">
    <?php  echo t('Yes Remove This');?>
    </a> <a class="btn btn-primary" href="<?php  echo $this->action('clear_warning');?>">
    <?php  echo t('Cancel');?>
    </a> </div>
</div>
<?php 
		}
		?>
		<form method="get" action="<?php  echo $this->action('view')?>"  style="float:left; margin-bottom:20px; width:100%;">
	<?php 

 $form = Loader::helper('form');
	$sections[0] = '** All';
	asort($sections);
	?>
	<table class="ccm-results-list" style="float:left;">
			<tr>	
				<th><strong><?php  echo t('By Name')?></strong></th>
				<th></th>
			</tr>
			<tr>
					
				<td><?php  echo $form->text('like', $like)?></td>
				<td><?php echo $form->select('numResults', array(
			'10' => '10',
			'20' => '20',
			'50' => '50',
			'100' => '100',
			'250' => '250',
			'500' => '500',
			'1000' => '1000',
		), $_REQUEST['numResults'])?></td>
				<td><?php echo $form->submit('submit', t('Search'))?></td>
					
			</tr>
	</table>
	<div class="right" style="float:right; margin-top:40px;"><a class="btn btn-success" href="<?php echo BASE_URL; ?>/index.php/dashboard/team_management_pro/add_edit"><?php echo t('Add Team');?></a></div>		
</form>


<div class="ccm-spacer">&nbsp;</div>
	<?php $nh = Loader::helper('navigation');
		$fm = Loader::helper('form');
		$dh = Core::make('helper/date');
		if ( sizeof($pageResults) > 0) { ?>
	<form action="<?php echo BASE_URL; ?>/index.php/dashboard/team_management_pro/team_list?action=save_order" method="post">
    		
<div class="table-responsive">
                <table class="ccm-search-results-table compact-results">
                    <thead>
	                    <tr>
		
		<th> </th>
				
		<th> 
				<span><?php  echo t('Name')?></span>
				
				
		</th>			
		<th> 
		
				<?php  echo t('Actions')?>
		
		</th>
	    
	    </tr>
		 </thead>
	</tr>
	<tbody class="ccm-file-set-file-list">
	<?php  
		foreach($pageResults as $cobj) { 
			$i++;
			$t++;
			if(is_object($cobj)){
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
		 <td><i class="fa fa-arrows-v"></i><input type="hidden" name="pageid[]" value="<?php echo $cobj->getCollectionID(); ?>"></td>
		
		<td class="align_top" ><a href="<?php   echo $nh->getLinkToCollection($cobj)?>">
			<?php   echo $cobj->getCollectionName()?></a>
		</td>				
		<td  class="align_top" >
			<a href="<?php  echo $this->url('/dashboard/team_management_pro/add_edit', 'edit', $cobj->getCollectionID())?>" class="pagetooltip btn btn-primary">Edit</a>
			<a href="<?php  echo $this->url('/dashboard/team_management_pro/team_list', 'duplicate', $cobj->getCollectionID())?>" class="pagetooltip btn btn-warning">Duplicate</a> 
			<a href="<?php  echo $this->url('/dashboard/team_management_pro/team_list', 'delete_check', $cobj->getCollectionID(),$cobj->getCollectionName())?>" class="pagetooltip btn btn-danger">Delete</a>                
		</td>
	</tr>
	<?php    } ?>
	</tbody>
  
</table>
<br /><br />
<input type="submit" class ="btn btn-success" value="Save Order">
</div>
</form>



	<script>
	$(function() {
     

		$(".ccm-file-set-file-list").sortable();


	});

	</script>


<?php  echo $pagination;
} else {
	print t('No entries found.');
}
?>
