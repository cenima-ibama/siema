  <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
  <!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script src="//cdn.leafletjs.com/leaflet-0.5.1/leaflet.js"></script>
  <script src="//www.google.com/jsapi" type="text/javascript"></script>

  <script src="<?= base_url()?>assets/js/bootstrap.min.js"></script>
  <script src="<?= base_url()?>assets/js/bootstrapSwitch.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet-bing.js"></script>
  <script src="<?= base_url()?>assets/js/leaflet.markercluster.js"></script>
  <script src="<?= base_url()?>assets/js/lvector.js"></script>
  <script src="<?= base_url()?>assets/js/masonry.min.js"></script>
  <script src="<?= base_url()?>assets/js/knob.js"></script>
  <script src="<?= base_url()?>assets/js/sparkline.min.js"></script>
  <script src="<?= base_url()?>assets/js/pusher.color.min.js"></script>
  <script src="<?= base_url()?>assets/js/less.min.js"></script>
  <script src="<?= base_url()?>assets/js/hash5.js" type="text/javascript"></script>

  <script>
      <?php
        if($logged_in) {
            echo "H5.DB.addDB({name:'alert', table:'alerta'});\n";
            echo "H5.DB.addDB({name:'cloud', table:'nuvem_deter'});\n";
            echo "H5.DB.addDB({name:'diary', table:'alerta_acumulado_diario'});\n";
            echo "H5.DB.addDB({name:'prodes', table:'taxa_prodes'});\n";
        }
        else {
            echo "H5.DB.addDB({name:'alert', table:'public_alert'});\n";
            echo "H5.DB.addDB({name:'cloud', table:'public_cloud'});\n";
            echo "H5.DB.addDB({name:'diary', table:'public_diary'});\n";
            echo "H5.DB.addDB({name:'prodes', table:'public_prodes'});\n";
        }
      ?>
  </script>

  <script src="<?= base_url()?>assets/js/h5charts.js" type="text/javascript"></script>
  <script src="<?= base_url()?>assets/js/h5map.js" type="text/javascript"></script>
  <script src="<?= base_url()?>assets/js/h5custom.js" type="text/javascript"></script>
