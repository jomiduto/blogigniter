<div class="login-box">
  <div class="login-logo">
    <a href=""><?php echo APP_NAME ?></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Iniciar Sesión</p>

    <?php
	    echo form_open( '', ['class' => 'std-form'] );
	?>

    <form action="" method="post">
      <div class="form-group has-feedback">
        <input type="text" name="login_string" class="form-control" placeholder="Usuario">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="login_pass" class="form-control" placeholder="Contraseña">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>

        <input type="hidden" id="max_allowed_attempts" value="<?php echo config_item('max_allowed_attempts'); ?>" />
        <input type="hidden" id="mins_on_hold" value="<?php echo ( config_item('seconds_on_hold') / 60 ); ?>" />
            
    </form>

    <a href="#">Olvide mi contraseña</a><br>
    <a href="register.html" class="text-center">Registrarse</a>

  </div>
  <!-- /.login-box-body -->
</div>