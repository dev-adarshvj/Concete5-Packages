<?php    defined('C5_EXECUTE') or die(_("Access Denied."));
//print_r($data);die();
if(count($data)>0){  ?>
 <form id="searchForm" method="get" class="form-inline ccm-search-fields">
 <div class="form-group">
        <label>By Keywords</label>
        <div class="ccm-search-field-content">
          <?php  echo $form->text('keywords', array('placeholder' => t('Keywords')))?>
        </div>
      </div>
       <div class="form-group">
        <?php   echo $form->label('submit', t(''))?>
        <div class="ccm-search-field-content">
          <div class="btn-group">
            <button type="submit" class="btn ccm-input-submit">
            <?php   echo t('Search')?>
            </button>
          </div>
        </div>
      </div>
</form>
  <table class="table">  
  <tr>
    <th>name</th>
    <th>Age</th>
    <th>Email</th>
    <th>Address</th>
    <th>Designation</th>
    <th>Delete</th>
  </tr>
<?php foreach($data as $ah){
    
    echo '<tr>';
    echo '<td>'. $ah->getName(). '</td>';   
    echo '<td>'.$ah->getAge() .'</td>';
    echo '<td>'.$ah->getEmail().'</td>';
    echo '<td>'.$ah->getAddress().'</td>';
    echo '<td>'.$ah->getDesignation().'</td>';
    echo '<td><a href="#" data="'.$this->url('/dashboard/external_form/contact_form','delete',$ah->getEntryID()).'" onclick="confirm_(this)" class="btn btn-danger">Delete</a></td>';
   
    echo '</tr>';
  
}
  

}else{
    
    echo 'No data';
    
} ?>
</table>
<hr/>
    <?php if ($paginator && strlen($paginator->getPages()) > 0) { ?>
        <div class="ccm-pane-footer"> <?php echo $fr->displayPagingV2(); ?> </div>
        <?php
    } ?>

<script>
function confirm_($this){
    
    var link = $($this).attr('data');
   
    if (confirm('Are you sure you want to delete this request info')) {
   
       window.location.href = link; 
  
    }
} 
</script>


