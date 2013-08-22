<?php echo form_open('auth/login', array('id' => 'loginForm', 'class' => 'form-horizontal', 'name' => 'loginForm')); ?>
<h4 class="">Login</h4>
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
<?php echo form_close(); ?>