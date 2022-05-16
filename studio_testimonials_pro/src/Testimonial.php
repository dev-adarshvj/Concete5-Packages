<?php
namespace Concrete\Package\StudioTestimonialsPro\Src;
use \Concrete\Core\Legacy\Model;
use Loader;

class Testimonial extends Model
{

	public $_table = 'StudioTestimonialsPro';

	public function getID(){
		return $this->id;
	}

	public static function add($data){
		$db = Loader::db();
		$dth = Loader::helper('form/date_time');
		$text = Loader::helper('text');

		$t = new Testimonial();
		// update existing
		if($data['id']){
			$t->load('id=?', array($data['id']));
		}

		$t->author = $text->sanitize($data['author'], 250);
		if($data['date_added']){ $t->date_added = date('Y-m-d', strtotime($data['date_added'])); }
		else { $t->date_added = date('Y-m-d'); }
		$t->content = $text->sanitize($data['content']);
		$t->extra = $text->sanitize($data['extra']);
		$t->rating = intval($data['rating']);
		$t->approved = intval($data['approved']);
		$t->image = intval($data['image']);
		$t->video = intval($data['video']);

		$t->Save();

		// get new testimonial id
		$id = ($t->id) ? $t->id : $db->lastInsertId();

		// update categories
		$db->Execute('delete from StudioTestimonialsCategories where testimonial_id = ?', $id);
		foreach((array)$data['category'] as $category){
			$db->Execute('insert into StudioTestimonialsCategories (testimonial_id, category) values (?,?)', array($id, $category));
		}

		return $t;
	}

	public function getByID($id){
		$t = new Testimonial();

		$t->load('id=?', $id);

		return $t;
	}

    public function delete($id =0){
        $db = Loader::db();
        $db->Execute('delete from StudioTestimonialsPro where id = ?', array($id));
    }

	public function approve($id){
		$t = new Testimonial();
		$t->load('id=?', $id);
		if($t->approved){
			$t->approved = '0';
		} else {
			$t->approved = '1';
		}
		$t->save();
	}

	public function getCategories(){
		$db = Loader::db();
		$res = $db->getCol('select category from StudioTestimonialsCategories where testimonial_id=?', array($this->id));

		return $res;
	}

}
