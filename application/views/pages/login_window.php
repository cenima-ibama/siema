<div class="container">
    <div class="form-login">
    <a id="close-btn-login" name="close-btn-login" class="close" onclick="$('#login').hide(); $('#btn-map').click()">�</a>
        <?php echo form_fieldset('<img src="' . base_url() . '/assets/img/logo_ibama_icone.png"> <span class="label label-inverse">Login</span>'); ?>
        <?php
            if (validation_errors()) {
                echo '<div class="alert alert-block alert-error fade in">';
                echo '<button class="close" data-dismiss="alert">&times;</button><span>' . validation_errors() .'</span>';
                echo '</div>';
            }
            else if (isset($login_fail)) {
                echo '<div class="alert alert-block alert-error fade in">';
                echo '<button class="close" data-dismiss="alert">&times;</button><span> Usu�rio ou Senha inv�lidos.</span>';
                echo '</div>';
            }
         ?>

        <?php echo form_open('auth/login_site', array('id' => 'loginform', 'class' => 'form-horizontal')); ?>

        <div class="control-group">
            <?php echo form_label('Usu�rio:', 'inputUsername', array('class' => 'control-label', 'for' => 'username')); ?>
            <div class="controls">
                <?php echo form_input(array('name' => 'inputUsername', 'id' => 'username', 'class' => 'formfield', 'type' => 'text')); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo form_label('Senha:', 'inputPassword', array('class' => 'control-label', 'for' => 'password')) ?>
            <div class="controls">
                <?php echo form_password(array('name' => 'inputPassword', 'id' => 'password','class' => 'formfield')); ?>
            </div>
        </div>
        <br />
        <div class="control-group">
            <div class="controls">
                <?php
                $checkbox = form_checkbox(array('name' => 'remember', 'id' => 'remember'), 1,  FALSE);
                echo form_label($checkbox . ' Lembrar-me', 'remember', array('class'=> 'checkbox pull-left'));
                echo form_checkbox(array('name' => 'loginSite', 'id' => 'loginSite', 'type' => 'hidden'), 1,  TRUE);
                ?>
                <?php echo form_button( array('name' => 'login', 'class'=> 'btn btn-primary pull-right', 'type' => 'submit'), 'Login'); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
        <?php echo form_fieldset_close(); ?>
        <hr>
        <h4>Acesso Restrito</h4>
        <p>O acesso a essa sess�o � permitida somente a usu�rios registrados no sistema Ibama-Net.</p>
    </div>
</div> <!-- container -->
