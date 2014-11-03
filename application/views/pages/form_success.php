<div class="alert alert-info fade in">
    <strong>Formulário enviado com sucesso!</strong>
</div>


<?php echo form_open('form/generatePDFForm', array('id' => 'formGeneratePdf')); ?>
  <a type="btn" href="#" onclick="formGeneratePdf.submit()">Gerar pdf do comunicado nro <?php echo $comunicado; ?> validado.</a>
  <input id="nro_ocorrencia" name="nro_ocorrencia" type="hidden" value='<?php echo $comunicado; ?>'>
<?php echo form_close(); ?>