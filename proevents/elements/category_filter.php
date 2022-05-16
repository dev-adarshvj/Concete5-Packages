<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>

<select name="ctID" id="ctID">
    <?php  
    $getCat = function($ctID)
    {
        $categories = array();
        $db = Loader::db();
        $akID = $db->getOne("SELECT akID FROM AttributeKeys WHERE akHandle = 'event_category'");
        $categories = $db->getAll("SELECT value FROM atEventCategoryOptions WHERE akID = $akID");
        foreach ($categories as $cat) {
            if ($cat['value'] == $ctID && $cat['value'] != 'All Categories') {
                echo '<option  value="' . $cat['value'] . '"  selected>' . $cat['value'] . '</option>';
            } elseif ($cat['value'] != 'All Categories') {
                echo '<option value="' . $cat['value'] . '">' . $cat['value'] . '</option>';
            }
        }
        if ($ctID == 'All Categories' || $ctID == '' || !isset($ctID)) {
            echo '<option value="All Categories" selected>' . t('All Categories') . '</option>';
        } elseif ($ctID != 'All Categories') {
            echo '<option value="All Categories">' . t('All Categories') . '</option>';
        }
    };

    $getCat($ctID);
    ?>
</select>