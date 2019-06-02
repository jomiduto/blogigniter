<div class="login-box">
  <div class="login-logo">
    <a href=""><?php echo APP_NAME ?></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Iniciar Sesión</p>

    <?php echo form_open('app/ajax_attempt_login', ['class' => 'std-form']); ?>
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

<script>
    $(document).ready(function () {
        $(document).on('submit', 'form', function (e) {
            $.ajax({
                type: 'post',
                cache: false,
                url: '<?php echo base_url() ?>app/ajax_attempt_login',
                data: {
                    'login_string': $('[name="login_string"]').val(),
                    'login_pass': $('[name="login_pass"]').val(),
                    'loginToken': $('[name="token"]').val()
                },
                dataType: 'json',
                success: function (response) {
                    $('[name="loginToken"]').val(response.token);
                    console.log(response);
                    if (response.status == 1) {
                        window.location.href = '<?php echo base_url() ?>admin';
                    } else if (response.status == 0 && response.on_hold) {
                        $('form').hide();
                        $('#on-hold-message').show();
                        alert('Intentos de inicio de sesión excesivos.');
                    } else if (response.status == 0 && response.count) {
                        alert('Login fallido', 'Login fallido ' + response.count + ' de ' + $('#max_allowed_attempts').val(), 'error');
                    }
                }
            });
            return false;
        });
    });
</script>