  <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
  <!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script src="http://barra.brasil.gov.br/barra.js" type="text/javascript"></script>

  <script src="<?= base_url()?>assets/js/jquery.min.js"></script>
  <script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>
  <!-- <script src="<?= base_url()?>assets/js/jquery.maskedinput.min.js"></script> -->
  <script src="<?= base_url()?>assets/js/jquery.mask.min.js"></script>
  <script src="<?= base_url()?>/assets/js/jquery.dataTables.min.js"></script>
  <!--<script src="<?= base_url()?>/assets/js/dataTables.tableTools.min.js"></script>-->


  <!-- Leaflet -->
  <script src="<?= base_url()?>assets/js/leaflet-0-6-4.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.bing.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.markercluster.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.minimap.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.fullscreen.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.vectorLayer.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.control.switch.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.activeLayers.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.control.locate.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.control.geosearch.js"></script>
  <!-- <script src="<?= base_url()?>assets/js/leaflet.draw.js"></script> -->
  <!-- Bootstrap -->
  <script src="<?= base_url()?>assets/js/bootstrap.min.js"></script>
  <script src="<?= base_url()?>assets/js/bootstrap.select.js"></script>
  <script src="<?= base_url()?>assets/js/bootstrap.switch.js"></script>
  <script src="<?= base_url()?>assets/js/bootstrap.editable.min.js"></script>
  <script src="<?= base_url()?>assets/js/bootstrap-datepicker.js"></script>
  <script src="<?= base_url()?>assets/js/locales/bootstrap-datepicker.pt-BR.js" charset="UTF-8"></script>

  <script src="<?= base_url()?>assets/js/jquery.knob.js"></script>
  <script src="<?= base_url()?>assets/js/sparkline.min.js"></script>
  <script src="<?= base_url()?>assets/js/pusher.color.min.js"></script>

  <!-- <script src="<?= base_url()?>assets/js/less.min.js"></script> -->
  <script src="<?= base_url()?>assets/js/hash5.js" type="text/javascript"></script>

  <script>
      <?php
            // echo "H5.DB.addDB({name:'alert', table:'public_alert'});\n";
            // echo "H5.DB.addDB({name:'cloud', table:'public_cloud'});\n";
            // echo "H5.DB.addDB({name:'diary', table:'public_diary'});\n";
            // echo "H5.DB.addDB({name:'prodes', table:'public_prodes'});\n";


       if($this->session->userdata('logged_in')) {
            echo "H5.logged_in = true;";
            echo "H5.user = '" . $this->session->userdata('name') . "';";
            echo "H5.mail = '" . $this->session->userdata('mail') . "';";
            
            $empresa = $this->session->userdata('empresa') ? "true" : "false";
            echo "H5.empresa = " . $empresa . ";";

            $cnpj_string = str_replace(array('-','/','.'), '', $this->session->userdata('cnpj'));
            $cpf_string = $this->session->userdata('username');

            $id = $this->session->userdata('empresa') ? $cnpj_string : $cpf_string;
            echo "H5.id = '" . $id . "';";
        }
        else {
            echo "H5.logged_in = false;";
        }

        echo "H5.session = " . json_encode($this->session->all_userdata()) . ";";

      ?>
  </script>

  <script src="<?= base_url()?>assets/js/h5home.js" type="text/javascript"></script>
  <script src="<?= base_url()?>assets/js/h5map.js" type="text/javascript"></script>
  <!-- <script src="<?= base_url()?>assets/js/h5charts.js" type="text/javascript"></script> -->
  <script src="<?= base_url()?>assets/js/h5manag.js" type="text/javascript"></script>
  <script src="<?= base_url()?>assets/js/h5wizard.js" type="text/javascript"></script>
  <script src="<?= base_url()?>assets/js/pdfmake.js" type="text/javascript"></script>
  <script src="<?= base_url()?>assets/js/vfs_fonts.js" type="text/javascript"></script>
