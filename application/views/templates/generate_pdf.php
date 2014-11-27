<?php echo '<img src="' . base_url() . 'assets/img/logo_ibama.jpg" style="width: 100px; heigth: 100px; position: absolute; top: 40px' ?>
<?php $this->load->view('includes/header_form'); ?>
<?php $this->load->view('pages/generate_pdf', $data); ?>
<?php $this->load->view('includes/scripts_form'); ?>
<?php $this->load->view('includes/footer'); ?>
