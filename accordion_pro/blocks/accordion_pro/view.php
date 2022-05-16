<?php defined('C5_EXECUTE') or die('Access denied');
use \Concrete\Core\Editor\LinkAbstractor;?>

<div class="faq-list">
      <!-- <div class="faq-title">
          <h2><?php //echo $title;?></h2>
      </div> -->

      <div id="faq-accordion">

        <?php if($items) : ?>

          <?php foreach ($items as $item ): ?>


        <div class="accordion-outer accordion">
          <div class="accordion accordion<?php echo $bID; ?>">
          <span><h5><?php echo $item->getTitle() ?></h5></span>
          </div>
          <div class="panel">
             <?php echo LinkAbstractor::translateFrom(LinkAbstractor::translateTo($item->getParagraph()));?>
             
           
          </div>
        </div>


           <?php endforeach; ?>

<?php endif; ?>

    </div>
  </div>

  <style>
/*
.active, .accordion:hover {
    background-color: #ccc;
}
*/
.panel.active{
  display: block;
}
.panel {
    padding: 0 18px;
    display: none;
    background-color: white;
    overflow: hidden;
}

</style>


<script>

/*$(".accordion-outer").click(function() {
  $("html, body").animate({ scrollTop: 1000 }, "slow");
  return false;
});*/

$(function() {
var Accordion = function(el, multiple) {
  this.el = el || {};
  this.multiple = multiple || false;

  // Variables privadas
  var links = this.el.find('.accordion<?php echo $bID; ?>');
  // Evento
  links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
}

Accordion.prototype.dropdown = function(e) {
  var $el = e.data.el;
    $this = $(this),
    $next = $this.next();

  $next.slideToggle();
  $this.parent().toggleClass('open');

  if (!e.data.multiple) {
    $el.find('.panel').not($next).slideUp().parent().removeClass('open');
  };
}

var accordion = new Accordion($('.accordion'), false);
});

$( document ).ready(function() {
  $('.accordion').first().addClass("open");
  $('.panel').first().show();
});


</script>





