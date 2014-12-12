<?php echo '<img src="' . base_url() . 'assets/img/logo_ibama.jpg" style="width: 100px; heigth: 100px; position: absolute; left: 43%' ?>
<?php $this->load->view('includes/header_form'); ?>
<?php $this->load->view('pages/generate_pdf', $data); ?>
<?php $this->load->view('includes/scripts_form'); ?>
<?php $this->load->view('includes/footer'); ?>
