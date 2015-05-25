<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>SIEMA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de monitoramento de desmatamento">
    <meta name="author" content="Helmuth Saatkamp <helmuthdu@gmail.com>">

    <!-- Bootstrap -->
    <link href="<?= base_url()?>assets/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <!-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet"> -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <style>
        body, html {
          margin: 0;
          padding: 0;
        }
    </style>

  </head>
  <body>
    <?php
      if(isset($login_fail_msg) and ($login_fail_msg) == 'Insert a valid CNPJ.') {
        echo '<div class="alert alert-danger alert-block fade in" style="margin: 0 5% 10px">';
        echo '<button class="clos"e data-dismiss="alert">&times;</button>
        <p style="text-align: left;">
        Insira um CNPJ válido.
        </p>';
        echo '</div>';
        $login_fail_msg = null;
      } else if(isset($login_fail_msg) and ($login_fail_msg) == 'No user matched this CPF number.') {
        echo '<div class="alert alert-danger alert-block fade in" style="margin: 0 5% 10px">';
        echo '<button class="clos"e data-dismiss="alert">&times;</button>
        <p style="text-align: left;">
        Usuário ou senha incorretos.
        </p>';
        echo '</div>';
        $login_fail_msg = null;
      }
    ?>
    <?php echo form_open('auth/login_empresa', array('id' => 'loginForm', 'class' => 'form-horizontal', 'name' => 'loginForm')); ?>
      <!-- <h4 style="margin:0;">Login</h4> -->
      <div class="control-group" style="margin-bottom:5px;">
          <?php echo form_label('Usuário:', 'labelUsername', array('class' => 'control-label', 'for' => 'inputUsername')); ?>
          <div class="controls">
              <?php echo form_input(array('maxlength' => '14', 'name' => 'inputUsername', 'id' => 'inputUsername', 'class' => 'formfield login-input',)); ?>
          </div>
      </div>
      <div class="control-group" style="margin-bottom:5px;">
          <?php echo form_label('Senha:', 'labelPassword', array('class' => 'control-label', 'for' => 'inputPassword')) ?>
          <div class="controls">
              <?php echo form_password(array('name' => 'inputPassword', 'id' => 'inputPassword','class' => 'formfield login-input')); ?>
          </div>
      </div>
      <?php echo form_checkbox(array('name' => 'loginSite', 'id' => 'loginSite', 'type' => 'hidden'), 1,  FALSE);?>
    <?php echo form_close(); ?>

    <!-- jquery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <!-- <script src="<?= base_url()?>assets/js/jquery.maskedinput.min.js"></script> -->
    <script src="<?= base_url()?>assets/js/jquery.mask.min.js"></script>

    <!-- Bootstrap -->
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <!-- mask -->
    <script>
      // $("#username").mask("99999999999");
      $("#inputUsername").mask("99.999.999/9999-99");
    </script>
    <!-- script for logged profile -->
    <?php
      if( $this->session->userdata('logged_in') ) {
        echo '<script src="' . base_url() . 'assets/js/h5login.js" type="text/javascript"></script>';
        echo '<input type="hidden" value="' . $name . '" id="sessionName"/>';
      }
    ?>
  </body>
</html>