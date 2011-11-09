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
		# @is_xhr (is this an AJAX request)
    $page->is_xhr = (preg_match('/text\/javascript/',$_SERVER['HTTP_ACCEPT']) ? true : false);
    
    if (!empty($page->data['@page_number'])) {
      $page_nr = $page->data['@page_number'];
      $page->loop_offset = (($page_nr - 1) * 2) . ":" . ($page_nr * 2);
    } else {
      $page->loop_offset = "0:2";
    }
	}
	
	static function create_semester_projects_ref($page) { 
	  //self::dbg($page);
	   
	  if (isset($page->data["@parent_slug"]) && preg_match('/students/is', $page->data['@parent_slug'])) {
	    /* todo */
    } 
    else if (isset($page->data["@parent_slug"]) && $page->data["@parent_slug"] == "projects") {
	    if (isset($page->data['@keywords'])) {
	      $page->data['@shortkeywords'] = character_limiter($page->data['@keywords'], 60);
	    }
	    if (isset($page->data['$parents'][1])) {
	      $students = (Array)Helpers::list_files($page->data['$parents'][0], '/\S/', true);
  	    foreach($students as $key => $val) {
  	      $projects = (Array)Helpers::list_files($val."/projects", '/\S/', true);
  	      foreach($projects as $prj_key => $prj_val) {
  	        if (preg_match('/'.$page->data['@slug'].'/is', strtolower($prj_key))) { 
  	          $parent = array_shift(PageData::get_parent($prj_val, Helpers::file_path_to_url($prj_val)));
  	          $grandparent = array_shift(PageData::get_parent($parent , Helpers::file_path_to_url($parent)));
  	          $page->data['$children'][] = $grandparent;
  	        }
  	      }
  	    }
      }
    } 
    else if (isset($page->data["@parent_slug"]) && $page->data["@parent_slug"] == "semesters") {
        echo $page->data["@index"] . "<br>";
        echo $page->data["@siblings_count"] . "<br>";
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
	
	static function dbg($input) {
	  echo "<br/>------------------------------------------------------------------------";
	  echo "<pre>";
	  print_r($input);
	  echo "</pre>";
	}
	
}

?>