<?php
namespace Concrete\Package\StudioTestimonialsPro\Block\StudioTestimonialsPro;
use \Concrete\Core\Block\BlockController;
use Loader;
use Config;
use \Concrete\Package\StudioTestimonialsPro\Src\Testimonial;
use \Concrete\Package\StudioTestimonialsPro\Src\TestimonialList;

class Controller extends BlockController {

		protected $btDescription = "Display user testimonials.";
		protected $btName = "Studio Testimonials Pro";
		protected $btTable = 'btStudioTestimonialsPro';
		protected $btInterfaceWidth = "500";
		protected $btInterfaceHeight = "500";
		protected $btWrapperClass = 'ccm-ui';

		public function view(){
			if($this->enable_submit){
				$html = Loader::helper('html');
				$this->addHeaderItem($html->css('jquery-magnific-popup.css'));
				$this->addFooterItem($html->javascript('jquery-magnific-popup.js'));
				$this->addFooterItem('
				<script>
				$(function(){
					$(".st-pro-submit-link a").magnificPopup({
						type: "inline",
						closeBtnInside: true,
						closeOnBgClick: false,
						showCloseBtn: true
					});
				})
				</script>');
			}

			if($this->display_type == 'single_rotate'){
				$this->addFooterItem('<script type="text/javascript">
				(function($){
  $.fn.list_ticker = function(options){

    var defaults = {
      speed:4000,
      effect:"fade"
    };

    var options = $.extend(defaults, options);

    return this.each(function(){

      var obj = $(this);
      var list = obj.children();
      var stopped = false; //flag if we should stop ticking
      list.not(":first").hide();

        obj.hover( function(){ //adding hover behaviour
            stopped = true;
        }, function(){
            stopped = false;
        });


      setInterval(function(){
          if(stopped) {return;} //Quick check inside interval

        list = obj.children();
        list.not(":first").hide();

        var first_li = list.eq(0)
        var second_li = list.eq(1)

        if(options.effect == "slide"){
            first_li.slideUp();
            second_li.slideDown(function(){
                first_li.remove().appendTo(obj);
            });
        } else if(options.effect == "fade"){
            first_li.fadeOut(function(){
                second_li.fadeIn();
                first_li.remove().appendTo(obj);
            });
        }
      }, options.speed)
    });
  };
})(jQuery);

				$(function(){
					$("#studio-testimonials-'.$this->bID.' .st-pro-ticker").list_ticker({
						speed: "'.$this->rotate_length .'",
						 effect: "'.$this->effect.'",
						 arrows: true,

						})
					});
				</script>');
			}

			if($this->display == 'category'){
				$categories = unserialize($this->category);
			}

			$list = new TestimonialList();
			//$list->debug();
			$list->filter('approved', 1);

			if($this->display == 'category'){
				$list->filterByCategories(unserialize($this->category));
			} elseif($this->display == 'select'){
				$list->filterByIds(explode(',', $this->testimonial_ids));
			}

			if($this->number > 0) {
				$list->setItemsPerPage($this->number);
			}

			$shuffle = false;
			if($this->sort == 'random'){
				// if pagination required show most recent first then shuffle the list
				// normally one would order by date if using pagination
				if($this->pagination && $list->requiresPaging()){
					$list->sortBy('date_added', 'desc');
					$shuffle = true;
				} else {
					$list->filter(false, ' 1=1 order by RAND()');
				}
			} else {
				$list->sortBy('date_added', $this->sort);
			}

			if($this->display_type == 'single'){
				$testimonials = $list->get(1);
			} else {
				$testimonials = $list->getPage();
			}

			if($this->display_type == 'multiple' && $this->pagination){
				$this->set('pagination', true);
				$this->set('list', $list);
			}

			if($shuffle){ shuffle($testimonials); }

			$this->set('testimonials', $testimonials);
		}

		public function action_submit(){
            Loader::model('testimonial', 'studio_testimonials_pro');
			$res = Testimonial::add($this->post());

			// send email
			if($this->notify_on_submission && $this->recipient_email){
				if( strlen(FORM_BLOCK_SENDER_EMAIL)>1 && strstr(FORM_BLOCK_SENDER_EMAIL,'@') ){
					$formFormEmailAddress = FORM_BLOCK_SENDER_EMAIL;
				}else{
					$adminUserInfo=UserInfo::getByID(USER_SUPER_ID);
					$formFormEmailAddress = $adminUserInfo->getUserEmail();
				}

				$mh = Loader::helper('mail');
				$mh->to( $this->recipient_email );
				$mh->from( $formFormEmailAddress );
				$mh->load('testimonial_form_submission', 'studio_testimonials_pro');
				$mh->setSubject(t('Testimonial Submission'));
				@$mh->sendMail();

			}

			exit;
		}

		public function add(){
			$this->add_edit();
			// set default options
			$this->set('rotate_length', 3000);
			$this->set('image_width', 120);
			$this->set('image_height', 90);
		}

		public function edit(){
			$this->add_edit();
		}

		protected function add_edit(){

			$this->set('type_options', array('single' => t('Single'), 'single_rotate' => t('Single Rotating'), 'multiple'=>t('Multiple')));
			$this->set('display_options',  array('all'=> t('All Testimonies'), 'category' => t('By Category'), 'select'=> t('Choose Testimonials')));
			$this->set('sort_options', array('random'=>t('Random'), 'desc'=> t('Most Recent First'), 'asc'=>t('Most Recent Last')));

			$this->set('existing_categories', unserialize($this->category));

			$categories = unserialize(Config::get('studio_testimonials_pro.categories'));
			$this->set('categories', $categories);
		}

		public function save($data){

			$data['number'] = intval($data['number']);
			$data['rotate_length'] = intval($data['rotate_length']);
			$data['display_image'] = intval($data['display_image']);
			$data['image_width'] = ($data['image_width'] > 0) ? intval($data['image_width']) : 120; // defaults
			$data['image_height'] = ($data['image_height'] > 0) ? intval($data['image_height']) : 90; // to avoid image divide by 0 error
			$data['pagination'] = intval($data['pagination']);
			$data['page_size'] = intval($data['page_size']);
			$data['enable_submit'] = intval($data['enable_submit']);
			$data['notify_on_submission'] = intval($data['notify_on_submission']);

			if($data['rotate_length'] < 100){
				$data['rotate_length'] = 3000;
			}

			$data['category'] = serialize($data['category']);

			parent::save($data);
		}

	}

?>
