<?php 

	class Post extends MY_Model //Se cambia de Ci_.... A My... con el community Auth
	{
		public $table = "post";//Nombre tabla de base de datos
		public $table_id = "post_id";//LLave primaria de la tabla post
	}

?>