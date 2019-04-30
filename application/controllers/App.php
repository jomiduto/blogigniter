<?php 

	class App extends CI_Controller //App es el nombre del archivo
	{
		public function __construct()//Esto es lo primero que se ejecuta
		{
			parent::__construct();

			$this->load->database(); //Se establece conexión con la BD

			$this->load->helper("url"); //Helper para usar el BASE_URL
			$this->load->helper("form"); //Helper para trabajar con formularios html
			$this->load->helper("text"); //Libreria para limitar caracteres tabla de list
			
			$this->load->library("parser"); //Libreria para usar Templates en Codeigniter
			$this->load->library("Form_validation");// Libreria para validar los campos del formulario

			$this->load->helper("Post_helper"); //Este helper fue creado por mi OJO esta en la carpeta helper
			$this->load->helper("Date_helper"); //Este helper fue creado por mi OJO esta en la carpeta helper
			
			$this->load->model('Post'); //Post es el nombre del modelo
			$this->load->model('Category'); //Category es el nombre del modelo
        }

        public function login()
		{
			$view["body"] = $this->load->view('app/login', NULL, TRUE); //Se configura para que muestre el template, se pone null porque no carga datos
			$this->parser->parse("admin/template/body1", $view);
		}
	}

?>