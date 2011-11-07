<?php
include_once "page-data.inc.php";
Class PageDataExtended extends PageData {

	static function create($page) {
		parent::create($page);
		self::create_collections($page);
	  self::create_vars($page);
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
	}
}

?>