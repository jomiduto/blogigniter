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

		public function ajax_attempt_login()
		{
			if( $this->input->is_ajax_request() )
			{
				// Allow this page to be an accepted login page
				$this->config->set_item('allowed_pages_for_login', ['examples/ajax_attempt_login'] );

				// Make sure we aren't redirecting after a successful login
				$this->authentication->redirect_after_login = FALSE;

				// Do the login attempt
				$this->auth_data = $this->authentication->user_status( 0 );

				// Set user variables if successful login
				if( $this->auth_data )
					$this->_set_user_variables();

				// Call the post auth hook
				$this->post_auth_hook();

				// Login attempt was successful
				if( $this->auth_data )
				{
					echo json_encode([
						'status'   => 1,
						'user_id'  => $this->auth_user_id,
						'username' => $this->auth_username,
						'level'    => $this->auth_level,
						'role'     => $this->auth_role,
						'email'    => $this->auth_email
					]);
				}

				// Login attempt not successful
				else
				{
					$this->tokens->name = config_item('login_token_name');

					$on_hold = ( 
						$this->authentication->on_hold === TRUE OR 
						$this->authentication->current_hold_status()
					)
					? 1 : 0;

					echo json_encode([
						'status'  => 0,
						'count'   => $this->authentication->login_errors_count,
						'on_hold' => $on_hold, 
						'token'   => $this->tokens->token()
					]);
				}
			}

			// Show 404 if not AJAX
			else
			{
				show_404();
			}
		}
	}

?>