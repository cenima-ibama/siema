<header id="navbar" class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
    <a class="brand" href="#"><img src="<?= base_url()?>/assets/img/ibama.png" style="margin:-2px 2px 0 10px;"> <span class="label label-inverse">0.6</span></a>
    <div class="nav-collapse collapse">
      <ul class="nav">
        <li class=""><a id="btn-map" href="#"><i class="icon-globe icon-white"></i> Mapa</a></li>
        <li class="active"><a id="btn-charts" href="#"><i class="icon-signal icon-white"></i> Estatística</a></li>
      </ul>
      <?php
        if($logged_in) {
          echo '<ul class="nav pull-right">';
            echo '<li class="dropdown pull-right">';
              echo('<a id="btn-login" class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-user icon-white"></i> ' . $name . '<strong class="caret"></strong></a>');
              echo '<ul class="dropdown-menu">';
                echo '<li>' . anchor(base_url() . 'index.php/auth/logout', 'Logout', '') . '</li>';
              echo '</ul>';
            echo '</li>';
          echo '</ul>';
        }
      ?>
    </div>
  </div>
  <!--/
  <div data-toggle="buttons-radio" class="btn-group">
    <button value="1" class="btn active" type="button" name="includeicon"><i class="icon-ok"></i></button>
    <button value="0" class="btn" type="button" name="includeicon"><i class="icon-remove"></i></button>
  </div>
  -->
</header>
