<?php 

	class Admin extends MY_Controller //Admin es el nombre del archivo - Se cambia de CI_con... a My_con cuando se incluye la libreria de login
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

			$this->init_seccion_auto(9);//Nivel que se requiere para acceder a este controlador - Community Auth
		}

		public function index()//Carga la vista solicitada
		{
			$this->load->view('admin/test');
		}

		/***** CRUD PARA LOS POST *****/

		public function post_list()
		{
			$data["posts"] = $this->Post->findAll();
			$view["body"] = $this->load->view('admin/post/list', $data, TRUE); //Se configura para que muestre el template, se pone null porque no carga datos
			$view["title"] = "Listar Posts";//Se define el titulo de la página
			$this->parser->parse("admin/template/body", $view);
		}

		public function post_save($post_id = null)
		{
			if($post_id == null)
			{
				//Creación del Post
				$data['category_id'] = $data['title'] = $data['image'] = $data['content'] = $data['description'] = $data['posted'] = $data['url_clean'] = ""; //Se deja todo vacio
				$view["title"] = "Crear Post";//Se define el titulo de la página
			}else
			{
				//Edición del Post
				$post = $this->Post->find($post_id);//Se hace la busqueda del id enviado y se cargan los datos en un array
				$data['title'] = $post->title;
				$data['content'] = $post->content;
				$data['description'] = $post->description;
				$data['posted'] = $post->posted;
				$data['url_clean'] = $post->url_clean;
				$data['image'] = $post->image;
				$data['category_id'] = $post->category_id;
				$view["title"] = "Actualizar Post";//Se define el titulo de la página
			}

			//Cargo las categorias para que se seleccione en el post, las triago del helper que cree
			$data['categories'] = categories_to_form($this->Category->findAll());
			

			if($this->input->server('REQUEST_METHOD') == "POST")
			{
				/*Valida campos del formulario */
				$this->form_validation->set_rules('title','Título','required|min_length[10]|max_length[65]');//Primer campo es el name del campo, y el segundo es el label
				$this->form_validation->set_rules('content','Contenido','required|min_length[10]');
				$this->form_validation->set_rules('description','Descripción','max_length[100]');
				$this->form_validation->set_rules('posted','Descripción','required');
				
				//Se copian los datos del array acá para que cargue en los input cuando se va a editar
				$data['title'] = $this->input->post("title");
				$data['content'] = $this->input->post("content");
				$data['description'] = $this->input->post("description");
				$data['posted'] = $this->input->post("posted");
				$data['url_clean'] = $this->input->post("url_clean");

				/*Valida que todos los datos pasan las validaciones */
				if($this->form_validation->run())
				{
					$url_clean = $this->input->post("url_clean");

					if($url_clean == "")
					{
						$url_clean = clean_name($this->input->post("title")); //clean_name, helper creado para limpiar el dato
					}

					//nuestro form es valido
					$save = array(
						'title' => $this->input->post("title"),
						'content' => $this->input->post("content"),
						'description' => $this->input->post("description"),
						'posted' => $this->input->post("posted"),
						'category_id' => $this->input->post("category_id"),
						'url_clean' => $url_clean
					);
					
					if($post_id == null)
					{
						//Inserta el post
						$post_id = $this->Post->insert($save);//Post es el nombre del modelo
					}else
					{
						//Edita el post
						$this->Post->update($post_id, $save);
					}

					$this->upload($post_id, $this->input->post("title"));
				}
			}

			$data["data_posted"] = posted();//Llama la funcion del helper que cree, el archivo esta en la carpeta helper
			$view["body"] = $this->load->view('admin/post/save', $data, TRUE);//La variable body debe coincidir con la variable usada en el archivo body del templte

			$this->parser->parse("admin/template/body", $view);//Indica la ruta donde se usa el template, view es la variable con datos que se van a pasar
		}

		public function post_delete($post_id = null)
		{
			if($post_id == null)
			{
				echo 0;
			}else
			{
				$this->Post->delete($post_id);
				echo 1;
			}
		}

		//Funcion para listar todas las imagenes guardadas en la carpeta "uploads/post"
		function images_server()
		{
			//Usa la funcion all_images del Post_helper que cree
			$data["images"] = all_images();
			$this->load->view("admin/post/image", $data);
		}

		//Se pone private porque no se puede acceder desde la URL, no es visible, realiza un proveso back
		function upload($post_id = null, $title = null)
		{
			$image = "upload"; //Es el nombre del campo que sube la imagen del ckeditor

			if($title != null)
			{
				$title = clean_name($title);//funcion esta en el archivo helper que cree el post_helper
			}

			//Configuraciones de carga
			$config['upload_path'] = 'uploads/post';
			if($title != null)
			{
				$config['file_name'] = $title;	
			}
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = 5000;
			$config['overwrite'] = TRUE;

			//Carga de la libreria
			$this->load->library('upload', $config);

			if($this->upload->do_upload($image))//Verifica si cargo la imagen
			{
				//Cargo la imagen

				//Datos del upload,sirve para obtener la extensión del archivo
				$data = $this->upload->data();
				
				if($title != null && $post_id != null)
				{
					$save = array(
						'image' => $title. $data["file_ext"]// Inserto la imagen, su nombre con la extensión
					);
	
					$this->Post->update($post_id, $save);//como e registro ya existe mando el update
				}else{
					$title = $data["file_name"];
					echo json_encode(array("fileName" => $title, "uploaded" => 1, "url" => "/" . PROJECT_FOLDER . "/uploads/post/" . $title));
				}
				
				//Redimensionar imagen
				$this->resize_image($data['full_path'], $title . $data['file_ext']);
			}
		}

		function resize_image($path_image)
		{
			$config['image_library'] = 'gd2';
			$config['source_image'] = $path_image;
			//$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] =  TRUE;
			$config['width'] = 500;
			$config['height'] = 500;

			$this->load->library('image_lib', $config);

			$this->image_lib->resize();
		}


		/****** CRUD PARA LAS CATEGORÍAS */


		public function category_list()
		{
			$data["categories"] = $this->Category->findAll();
			$view["body"] = $this->load->view('admin/category/list', $data, TRUE); //Se configura para que muestre el template, se pone null porque no carga datos
			$view["title"] = "Listar Categorías";//Se define el titulo de la página
			$this->parser->parse("admin/template/body", $view);
		}

		public function category_save($category_id = null)
		{
			if($category_id == null)
			{
				//Creación Categoría
				$data['name'] = ""; //Se deja todo vacio
				$data['url_clean'] = "";
				$view["title"] = "Crear Categoría";//Se define el titulo de la página
			}else
			{
				//Edición Categpría
				$category = $this->Category->find($category_id);//Se hace la busqueda del id enviado y se cargan los datos en un array
				$data['name'] = $category->name;
				$data['url_clean'] = $category->url_clean;
				$view["title"] = "Actualizar Categoría";//Se define el titulo de la página
			}

			if($this->input->server('REQUEST_METHOD') == "POST")
			{
				/*Valida campos del formulario */
				$this->form_validation->set_rules('name','Nombre','required|min_length[10]|max_length[100]');//Primer campo es el name del campo, y el segundo es el label
				
				//Se copian los datos del array acá para que cargue en los input cuando se va a editar
				$data['name'] = $this->input->post("name");
				$data['url_clean'] = $this->input->post("url_clean");

				/*Valida que todos los datos pasan las validaciones */
				if($this->form_validation->run())
				{
					$url_clean = $this->input->post("url_clean");

					if($url_clean == "")
					{
						$url_clean = clean_name($this->input->post("name")); //clean_name, helper creado para limpiar el dato
					}

					//nuestro form es valido
					$save = array(
						'name' => $this->input->post("name"),
						'url_clean' => $url_clean
					);
					
					if($category_id == null)
					{
						//Inserta el post
						$category_id = $this->Category->insert($save);//Post es el nombre del modelo
					}else
					{
						//Edita el post
						$this->Category->update($category_id, $save);
					}
				}
			}

			$view["body"] = $this->load->view('admin/category/save', $data, TRUE);//La variable body debe coincidir con la variable usada en el archivo body del templte

			$this->parser->parse("admin/template/body", $view);//Indica la ruta donde se usa el template, view es la variable con datos que se van a pasar
		}

		public function category_delete($category_id = null)
		{
			if($category_id == null)
			{
				echo 0;
			}else
			{
				$this->Category->delete($category_id);
				echo 1;
			}
		}
	}

?>