<?php echo form_open('','class="my_form" enctype="multipart/form-data"'); ?>
	<div class="form-group">
		<?php 
			echo form_label('Titulo','title');//Primer atributo el nombre visible, segundo atributo el FOR del label
		?>
		<?php 
			$text_input = array(
				'name' => 'title',
				'id' => 'title',
				'value' => $title,
				'class' => 'form-control input-lg'
			);

			echo form_input($text_input);//Imprime el label anterior
		?>

		<?php echo form_error('title','<div class="text-error">','</div>') ?>
	</div>

	<div class="form-group">
		<?php 
			echo form_label('Url Limpia','url_clean');//Primer atributo el nombre visible, segundo atributo el FOR del label
		?>
		<?php 
			$text_input = array(
				'name' => 'url_clean',
				'id' => 'url_clean',
				'value' => $url_clean,
				'class' => 'form-control input-lg'
			);

			echo form_input($text_input);//Imprime el label anterior
		?>

		<?php echo form_error('content','<div class="text-error">','</div>') ?>
	</div>

	<div class="form-group">
		<?php 
			echo form_label('Contenido','content');//Primer atributo el nombre visible, segundo atributo el FOR del label
		?>
		<?php 
			$text_area = array(
				'name' => 'content',
				'id' => 'content',
				'value' => $content,
				'class' => 'form-control input-lg'
			);

			echo form_textarea($text_area);//Imprime el label anterior, imprime el textarea
		?>
	</div>

	<div class="form-group">
		<?php 
			echo form_label('Descripción','description');//Primer atributo el nombre visible, segundo atributo el FOR del label
		?>
		<?php 
			$text_area = array(
				'name' => 'description',
				'id' => 'description',
				'value' => $description,
				'class' => 'form-control input-lg'
			);

			echo form_textarea($text_area);//Imprime el label anterior, imprime el textarea
		?>

		<?php echo form_error('description','<div class="text-error">','</div>') ?>
	</div>

	<div class="form-group">
		<?php 
			echo form_label('Imagen','image');//Primer atributo el nombre visible, segundo atributo el FOR del label
		?>
		<?php 
			$text_input = array(
				'name' => 'upload',
				'id' => 'upload',
				'value' => '',
				'type' => 'file',
				'class' => 'form-control input-lg'
			);

			echo form_input($text_input);//Imprime el label anterior, se agrega el type file para que permita cargar archivos
		?>

		<?php echo $image != "" ? '<img class="img-post img-thumbnail img-presentation-small" src="' . base_url() . 'uploads/post/' . $image . '">':""; ?>
	</div>

	<div class="form-group">
		<?php 
			echo form_label('Publicado','posted');//Primer atributo el nombre visible, segundo atributo el FOR del label

			echo form_dropdown('posted',$data_posted, $posted, 'class="form-control input-lg"');//Hace referencia al arreglo de la funcion post_save del controlador Admin
		?>
	</div>

	<div class="form-group">
		<?php 
			echo form_label('Categorías','category_id');//Primer atributo el nombre visible, segundo atributo el FOR del label

			echo form_dropdown('category_id',$categories, $category_id, 'class="form-control input-lg"');//Hace referencia al arreglo de la funcion post_save del controlador Admin
		?>
	</div>

	<?php echo form_submit('mysubmit', 'Guardar', 'class="btn btn-primary"'); ?>

<?php echo form_close(); ?>

<script>
//Llamo el editor de text CKeditor en el campo de content
	$(function(){
		var editor = CKEDITOR.replace('content', {
			height: 400,
			filebrowserUploadUrl: "<?php echo base_url() ?>admin/upload",//para guardar imagenes
			filebrowserBrowseUrl: "<?php echo base_url() ?>admin/images_server"//para mostrar y buscar imagenes
		});
	});
</script>