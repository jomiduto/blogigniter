<?php 

	class Category extends MY_Model //Se cambia de Ci_.... A My... con el community Auth
	{
		public $table = "categories";//Nombre tabla de base de datos
		public $table_id = "category_id";//LLave primaria de la tabla post
	}

?>