<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>

<select name="<?php  echo $name?>">
    <option value="0">--Choose Fileset--</option>
    <?php  foreach ($fileSets as $fs) : ?>
    <option value='<?php  echo $fs->fsID ?>' <?php  echo $selected == $fs->fsID ? 'selected' : '' ?> >
	<?php  echo htmlspecialchars($fs->fsName, ENT_QUOTES, 'UTF-8') ?>
    </option>
    <?php  endforeach ?>
</select>