<?php
defined('C5_EXECUTE') or die("Access Denied.");
$rssUrl = $showRss ? $controller->getRssUrl($b) : '';
$th = Loader::helper('text');?>
<?php $i= 1;
$total_page = count($pages);
$pages_in_single_div = ceil($total_page / 3); ?>
<section class="service_location clear">
	<div class="container-fluid">
<div class="row">
	<div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12">
		<div class="row">
	<div class="col-md-4 locations">
		<ul>
  <?php foreach ($pages as $key => $page): ?>
  <?php $title = $th->entities($page->getCollectionName());
				$url = $nh->getLinkToCollection($page);
				$target = ($page->getCollectionPointerExternalLink() != "" && $page->openCollectionPointerExternalLinkInNewWindow()) ? "_blank" : $page->getAttribute("nav_target");
				$target = empty($target) ? "_self" : $target;
				$description = $page->getCollectionDescription();
				$description = $controller->truncateSummaries ? $th->wordSafeShortText($description, $controller->truncateChars) : $description;
				$description = $th->entities($description);		?>
   <li><a href="<?php echo $url ?>" title="<?php echo $title ?>"><?php echo $title ?></a> </li>
  <?php if(($i % $pages_in_single_div == 0) && ($i > 0)){	echo '</ul></div><div class="col-md-4 locations"><ul>';		}
		$i++;
		endforeach; ?>
		</ul>
  </div>
</div>
</div>
</div>
</div>
</section>
<!-- end .ccm-page-list -->
<style>.locations{	margin-top:50px;}</style>
