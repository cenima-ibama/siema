<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SIEMA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de monitoramento de desmatamento">
    <meta name="author" content="Helmuth Saatkamp <helmuthdu@gmail.com>">

    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
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
      if(isset($login_fail_msg) and ($login_fail_msg) == 'Error with LDAP authentication.') {
        echo '<div class="alert alert-danger alert-block fade in" style="margin: 0 5% 10px">';
        echo '<button class="clos"e data-dismiss="alert">&times;</button>
        <p style="text-align: left;">
        Usuário ou senha incorretos.
        </p>';
        echo '</div>';
      }
    ?>
    <?php echo form_open('auth/login', array('id' => 'loginForm', 'class' => 'form-horizontal', 'name' => 'loginForm')); ?>
      <h4 style="margin:0;">Login</h4>
      <div class="control-group">
          <?php echo form_label('Usuário:', 'inputUsername', array('class' => 'control-label', 'for' => 'username')); ?>
          <div class="controls">
              <?php echo form_input(array('name' => 'inputUsername', 'id' => 'username', 'class' => 'formfield', 'type', 'number')); ?>
          </div>
      </div>
      <div class="control-group">
          <?php echo form_label('Senha:', 'inputPassword', array('class' => 'control-label', 'for' => 'password')) ?>
          <div class="controls">
              <?php echo form_password(array('name' => 'inputPassword', 'id' => 'password','class' => 'formfield')); ?>
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
      $("#username").mask("99999999999");
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