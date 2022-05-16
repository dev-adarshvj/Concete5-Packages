<?php defined('C5_EXECUTE') or die('Access denied');?>
  	<section class="clear accordion_wrapper content">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="accordion_header text-center">
							<h2><?php echo $faqtitle; ?></h2>
							</div>
					</div>
				</div>
  <div class="faq-inner faq-outer">
    <div id="faq_acc_<?php echo $bID; ?>" class="faq accordion-container style-preset-<?php echo intval($stylePresetId); ?>" data-collapse="<?php echo intval($collapse); ?>"  data-scroll-to-active-item="<?php echo intval($scrollToActiveItem); ?>" data-animation-duration="<?php echo intval($animationDuration); ?>">
      <div class="row accordion_row">
     <?php $count=0;
      foreach ($items as $item) {
     if($count%4==0){ ?><div class="col-lg-6 col-md-6 col-12"><?php } ?>
            <div class="card accordion-item <?php echo $item->getIsOpen() ? "open" : ""; ?>">
                <div class="accordion-header <?php echo is_object($stylePreset) ? $stylePreset->getTitleIconOrientation() : ""; ?>">
                    <div class="accordion-title-overlay"></div>
                    <div class="card-header accordion-header-inner">
                        <div class="accordion-title"><?php echo sprintf("<%s>", $semanticTag); ?><?php echo $item->getTitle() ?><?php echo sprintf("</%s>", $semanticTag); ?></div>
                    </div>
                </div>
                <div class="accordion-outer-content card-body"><div class="accordion-content"><?php echo $item->getParagraph(); ?></div></div>
            </div>
            <?php if($count%4==3){ ?></div><?php } ?>
          <?php $count++; } ?>
            </div>
            </div>
    </div>
</div>
</section>
<script>
 $(document).ready(function() {
     $(".faq-outer #faq_acc_<?php echo $bID; ?> .card.accordion-item").click(function () {
       console.log($(this));
         if(!$(this).hasClass("open")){
             $(".faq-outer #faq_acc_<?php echo $bID; ?> .card.accordion-item").removeClass("open");
           $(this).addClass("open");
          }else{ $(this).removeClass("open"); }
     });
 });
</script>
