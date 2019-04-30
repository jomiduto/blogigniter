<?php echo form_open('','class="my_form" enctype="multipart/form-data"'); ?>
	<div class="form-group">
		<?php 
			echo form_label('Nombre','name');//Primer atributo el nombre visible, segundo atributo el FOR del label
		?>
		<?php 
			$text_input = array(
				'name' => 'name',
				'id' => 'name',
				'value' => $name,
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

	<?php echo form_submit('mysubmit', 'Guardar', 'class="btn btn-primary"'); ?>

<?php echo form_close(); ?>