<?php    defined('C5_EXECUTE') or die(_("Access Denied."));
//print_r($data);die();
if(count($data)>0){  ?>
 <form>
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
    <th>Email</th>
    <th>Phone</th>
    <th>Country</th>
   
   
  </tr>
<?php foreach($data as $ex){
    
    echo '<tr>';
    echo '<td>'. $ex->getName(). '</td>';   
    echo '<td>'.$ex->getEmail() .'</td>';
    echo '<td>'.$ex->getPhone().'</td>';
    echo '<td>'.$ex->getCountry().'</td>';

    echo '</tr>';
  
}
  

}else{
    
    echo 'No data';
    
} ?>
</table>




