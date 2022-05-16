<?php  
namespace Concrete\Package\StudioTestimonialsPro\Src;
use \Concrete\Core\Legacy\DatabaseItemList;
use Loader;
use \Concrete\Package\StudioTestimonialsPro\Src\Testimonial;

class TestimonialList extends DatabaseItemList{
	protected $autoSortColumns = array('author','order','approved', 'date_added');
	protected $itemsPerPage = 100; 
	
	protected $queryStringPagingVariable = 'pg';
	
	function __construct(){
		$this->setQuery('select distinct id from StudioTestimonialsPro');
	}
	
	public function get($itemsToGet = 0, $offset = 0){
		$records = array();
		$r = parent::get($itemsToGet, $offset);
		
		foreach($r as $row) {
			$item = Testimonial::getByID($row['id']);
			$records[] = $item;
		}
		
		return $records;
	}
	
	public function search($query){
		$db=Loader::db();
		$qk = $db->quote('%'.trim($query).'%');
		
		$this->filter(false, "(author like $qk or content like $qk or extra like $qk)");
	}
	
	public function filterByCategory($category){
		$this->addToQuery(' st left join StudioTestimonialsCategories stc on (st.id=stc.testimonial_id)');
		$this->filter('stc.category', $category);
	}

	public function filterByCategories($categories){
		$db = Loader::db();
		$this->addToQuery(' st left join StudioTestimonialsCategories stc on (st.id=stc.testimonial_id)');
		$sq = '';
		foreach((array)$categories as $key => $category){
			if(!trim($category)){ continue; }
			$category = $db->quote($category);
			if(($key+1) >= count($categories)){
				$sq .= "stc.category = $category";
			} else {
				$sq .= "stc.category = $category or ";
			}
		}
		if($sq){
			$this->filter(false, $sq);
		}
	}
	
	
	// use this so we can still support pagination
	public function filterByIds($ids){
		$sq = '';
		foreach((array)$ids as $key => $id){
			if($id < 1){ continue; }
			
			if(($key+1) >= count($ids)){
				$sq .= "id = $id";
			} else {
				$sq .= "id = $id or ";
			}
		}
		if($sq){
			$this->filter(false, $sq);
		}
	}
	
}