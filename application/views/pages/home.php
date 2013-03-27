<section class="content">
  <section id="loading" class="loading">
    <img id="loading_logo"src="<?= base_url()?>/assets/img/ibama_logo.png">
    <div id="progress" class="progress">
      <div id="progressbar" class="bar bar-warning"></div>
    </div>
  </section>
  <section id="map">
    <div id="sidebar-map">
      <div id="sidebar-map-btn"><i class="icon-arrow-right"></i></div>
      <span>Legendas</span>
    </div>
    <div id="map-container" class="map"></div>
  </section>
  <section class="row-fluid charts">
    <div id="sidebar-dash">
      <div id="sidebar-dash-btn"><i class="icon-arrow-right"></i></div>
      <span>Legendas</span>
    </div>
    <section class="charts-content">
      <div class="row-fluid" style="text-align:center">
        <div class="quick-box">
          <a id="AC" href="#" class="btn quick-box-btn">
            <i class="icon-ac"></i>
            <span>AC</span>
          </a>
          <a id="AM" href="#" class="btn quick-box-btn">
            <i class="icon-am"></i>
            <span>AM</span>
          </a>
          <a id="AP" href="#" class="btn quick-box-btn">
            <i class="icon-ap"></i>
            <span>AP</span>
          </a>
          <a id="MA" href="#" class="btn quick-box-btn">
            <i class="icon-ma"></i>
            <span>MA</span>
          </a>
          <a id="MT" href="#" class="btn quick-box-btn">
            <i class="icon-mt"></i>
            <span>MT</span>
          </a>
          <a id="PA" href="#" class="btn quick-box-btn">
            <i class="icon-pa"></i>
            <span>PA</span>
          </a>
          <a id="RO" href="#" class="btn quick-box-btn">
            <i class="icon-ro"></i>
            <span>RO</span>
          </a>
          <a id="RR" href="#" class="btn quick-box-btn">
            <i class="icon-rr"></i>
            <span>RR</span>
          </a>
          <a id="TO" href="#" class="btn quick-box-btn">
            <i class="icon-to"></i>
            <span>TO</span>
          </a>
          <a id="Todos" href="#" class="btn quick-box-btn active">
            <i class="icon-br"></i>
            <span>Todos</span>
          </a>
        </div>
      </div>
      <hr>
      <div class="row-fluid" style="text-align:center">
        <div class="gauges">
          <div id="gauge1" class="gauge" title="" rel="tooltip" data-original-title="Taxa de variação em relação ao mesmo mês do ano anterior"> </div>
          <div id="gauge2" class="gauge" title="" rel="tooltip" data-original-title="Taxa de variação em relação ao mês anterior"> </div>
          <div id="gauge3" class="gauge" title="" rel="tooltip" data-original-title="Taxa de variação em relação ao periodo anterior"> </div>
        </div>
        <ul class="stats-box">
          <li class="popover-visits" data-original-title="" title="">
            <div class="left">
              <i class="icon-trash"></i>
            </div>
            <div class="right">
              <strong>36094</strong> Visits
            </div>
          </li>
          <li class="popover-users" data-original-title="" title="">
            <div class="left">
              <i class="icon-trash"></i>
            </div>
            <div class="right">
              <strong>36094</strong> Visits
            </div>
          </li>
          <li class="popover-orders" data-original-title="" title="">
            <div class="left">
              <i class="icon-trash"></i>
            </div>
            <div class="right">
              <strong>36094</strong> Visits
            </div>
          </li>
        </ul>
      </div>
      <hr>
      <div id="charts" class="row-fluid">
        <div id="chart1" class="chart"> </div>
        <div id="chart2" class="chart"> </div>
        <div id="chart3" class="chart"> </div>
        <div id="chart4" class="chart"> </div>
        <div id="chart5" class="chart"> </div>
        <div id="chart6" class="chart"> </div>
        <div id="chart7" class="chart"> </div>
        <div id="chart8" class="chart"> </div>
        <div id="chart9" class="chart"> </div>
      </div>
    </section>
  </section>
</section>
<footer>
  <p>
    <span style="text-align:left;float:left"> &copy; <a href="">Ibama - MMA</a> 2013</span>
    <span style="text-align:right;float:right" class="hidden-phone">Powered by: <a target="_blank" href="http://www.hexgis.com">Hexgis HASH5</a></span>
  </p>
</footer>
