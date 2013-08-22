<header id="navbar" class="navbar navbar-fixed-top navbar-inverse">
  <div class="navbar-inner">
    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>
    <a class="brand" href="#"><img src="<?= base_url()?>/assets/img/ibama.png" style="margin:-2px 2px 0 10px;"> <span class="label label-inverse">v0.1</span></a>
    <ul class="nav pull-right">
      <li>
        <form class="navbar-form pull-right">
          <span style="padding-right: 10px;">
            <a class='btn btn-primary btn-small' data-toggle="modal" href="#addMeModal" style="margin-top: 7px"><i class="icon-plus-sign icon-white"></i> Registrar Acidente</a>
          </span>
        </form>
      </li>
    </ul>
    <div class="nav-collapse collapse">
      <ul class="nav">
        <li class=""><a id="btn-map" href="#"><i class="icon-globe icon-white"></i> Mapa</a></li>
        <li class="active"><a id="btn-charts" href="#"><i class="icon-bar-chart icon-white"></i> Estatística</a></li>
      </ul>
    </div>
  </div>
</header>
