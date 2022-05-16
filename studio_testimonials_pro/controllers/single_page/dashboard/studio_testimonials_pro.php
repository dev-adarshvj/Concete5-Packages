<?php
namespace Concrete\Package\StudioTestimonialsPro\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Loader;
use Config;
use Package;
use \Concrete\Package\StudioTestimonialsPro\Src\Testimonial;
use \Concrete\Package\StudioTestimonialsPro\Src\TestimonialList;

class StudioTestimonialsPro extends DashboardPageController {

    public function view(){
        $list = new TestimonialList();
		$list->sortBy('date_added', 'desc');
		if($_GET['query']){
			$list->search($_GET['query']);
		}
		if($_GET['category']){
			$list->filterByCategory($_GET['category']);
		}
		$this->set('list', $list);
		$categories = unserialize(Config::get('studio_testimonials_pro.categories'));
		$this->set('categories', $categories);
    }

    public function add(){
		$vs = Loader::helper('validation/strings');

        if($this->isPost()){
        	if (!$vs->notempty($this->post('author'))) {
            	$this->error->add(t('Please enter an author.'));
           	}
        	if (!$vs->notempty($this->post('content'))) {
            	$this->error->add(t('Please enter the testimonial content.'));
            }

			if (!$this->error->has()) {
                $res = Testimonial::add($this->post());

                $this->redirect('/dashboard/studio_testimonials_pro');
            }
        } else {
			// reset form values
			$_POST = array();
        }

		$categories = unserialize(Config::get('studio_testimonials_pro.categories'));
		$this->set('categories', $categories);
    }

    public function delete($id){
        Testimonial::delete($id);
        $this->view();
    }

    public function edit($id = 0){
        if($this->isPost()){
            if(!$this->post('author')){ $error[] = t('Please enter an author.'); }
            if(!$this->post('content')){ $error[] = t('Please enter the testimonial content.'); }

            if(!$error){
                $res = Testimonial::add($this->post());

                $this->redirect('/dashboard/studio_testimonials_pro');
            } else {
                $this->set('error', $error);
                $id = $this->post('id');
            }
        }
        if($id){
            $t = Testimonial::getByID($id);
            $this->set('id', $t->id);
            $this->set('t', $t);

			$categories = unserialize(Config::get('studio_testimonials_pro.categories'));
			$this->set('categories', $categories);
        }else{
            $this->redirect('/dashboard/studio_testimonials_pro');
        }
    }

    public function approve($id){
        Testimonial::approve($id);
        $this->view();
    }

	public function new_category(){
		if($this->post('new_category') != ''){
			$categories = unserialize(Config::get('studio_testimonials_pro.categories'));
			if(!in_array($this->post('new_category'), (array)$categories)){
				$categories[] = $this->post('new_category');
				$this->set('message', t('Category added!'));
			} else {
				$this->set('message', t('The category %s already exists!', $this->post('new_category')));
			}

			Config::save('studio_testimonials_pro.categories', serialize($categories));
			$_POST['new_category'] = '';
		} else {
			$this->set('message', t('Category cannot be blank!'));
		}
		$this->view();
	}

	public function delete_category($category_delete){

		$categories = unserialize(Config::get('studio_testimonials_pro.categories'));

		if(in_array($category_delete, $categories)){
			foreach($categories as $key => $category){
				if($category == $category_delete){
					unset($categories[$key]);
				}
			}
		}

		Config::save('studio_testimonials_pro.categories', serialize($categories));

		$db = Loader::db();
		$db->Execute('delete from StudioTestimonialsCategories where category = ?', array($category_delete));

		$this->view();
	}

}
