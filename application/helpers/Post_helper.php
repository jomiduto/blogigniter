<?php 

	function posted()
	{
		return array("si" => "Si", "no" => "No");
	}

	function categories_to_form($categories)
	{
		$aCategories = array();

		foreach($categories as $key => $c)
		{
			$aCategories[$c->category_id] = $c->name;
		}

		return $aCategories;
	}
	
	function clean_name($name)
	{
		return url_title($name, '-', TRUE);
	}

	function all_images()
	{
		$CI = & get_instance();//Para acceder a la API directamente de codeigniter
		$CI->load->helper('directory');

		$dir = "uploads/post";
		$files = directory_map($dir);

		return $files;
	}
?>