<?php
include_once "page-data.inc.php";
Class PageDataExtended extends PageData {

	static function create($page) {
		parent::create($page);
		self::create_collections($page);
		self::create_vars($page);
		self::create_semester_projects_ref($page);
	}

	static function create_collections($page) {
		# $all_children
		$page->all_children = Helpers::list_files($page->file_path, '/\S/', true);
		# $all_children_reverse
		$page->all_children_reverse = array_reverse((Array)Helpers::list_files($page->file_path, '/\S/', true), true);
		# $children_reverse
		$page->children_reverse = array_reverse((Array)Helpers::list_files($page->file_path, '/^\d+?\./', true), true);
	}
	
	static function create_vars($page) {
		# @all_children_count
		$page->all_children_count = strval(count($page->data['$all_children']));
		# @parent_slug
		$parent_path = parent::get_parent($page->file_path, $page->url_path);
		$split_url = explode("/", $parent_path[0]);
		$page->parent_slug = $split_url[count($split_url) - 1];
	}
	
	static function create_semester_projects_ref($page) {    
	  if (isset($page->data["@parent_slug"]) && $page->data["@parent_slug"] == "projects") {
	    $page->data['@students'] = random_string('alnum', 10);
	    if (isset($page->data['@keywords'])) $page->data['@shortkeywords'] = character_limiter($page->data['@keywords'], 60);
    } else if (isset($page->data["@parent_slug"]) && $page->data["@parent_slug"] == "semesters") {
  	    $students_path = "";
  	    foreach($page->data['$root'] as $key => $value) {
  	      if (preg_match('/students/', strtolower($key)) ) {
  	        $students_path = $value;
  	        break;
  	      }
  	    }
  	    $students = (Array)Helpers::list_files($students_path, '/\S/', true);
  	    $semester_projects = array();
  	    foreach($students as $key => $val) {
  	      $projects = (Array)Helpers::list_files($val."/projects", '/\S/', true);
  	      foreach($projects as $prj_key => $prj_val) {
  	        if (preg_match('/'.$page->data['@slug'].'./is', strtolower($prj_key))) {
  	          $semester_projects[preg_replace("/^\d+?\./", "", $prj_key)] = $prj_val;
  	        }
  	      }
  	    }
  	    
  	    $page->children = $semester_projects;
	  }
	}
	
}

?>