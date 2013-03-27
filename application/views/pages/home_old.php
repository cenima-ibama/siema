<section class="content">
  <section id="loading" class="loading">
    <img id="loading_logo"src="<?= base_url()?>/assets/img/ibama_logo.png">
    <div id="progress" class="progress">
      <div id="progressbar" class="bar bar-warning"></div>
    </div>
  </section>
  <section id="map">
    <section id="sidebar-map">
      <div id="sidebar-map-btn"><i class="icon-arrow-right"></i></div>
      <span>Legendas</span>
    </section>
    <section id="map-container" class="map"></section>
  </section>
  <section class="row-fluid charts">
    <section id="sidebar-dash">
      <div id="sidebar-dash-btn"><i class="icon-arrow-left"></i></div>
      <ul id="sidebar-dash-main" class="nav nav-pills nav-stacked">
        <li class="active"><a value="9" href="#">Todos</a></div>
        <li><a value="0" href="#">AC</a></li>
        <li><a value="1" href="#">AM</a></li>
        <li><a value="2" href="#">AP</a></li>
        <li><a value="3" href="#">MA</a></li>
        <li><a value="4" href="#">MT</a></li>
        <li><a value="5" href="#">PA</a></li>
        <li><a value="6" href="#">RO</a></li>
        <li><a value="7" href="#">RR</a></li>
        <li><a value="8" href="#">TO</a></li>
      </ul>
    </section>
    <section class="charts-content">
      <div class="row-fluid">
        <div id="chart7" class="span6 chart">
          <div class="chart-header">
            <div class="chart-icon btn-left">
              <form name="form7" class="form-inline" action="">
                <select id="f7m" class="input-mini" name="month" onchange='updateLineValues();'>
                  <option value="3">Mês</option>
                  <option value="0">Jan</option>
                  <option value="1">Fev</option>
                  <option value="2">Mar</option>
                  <option value="3">Abr</option>
                  <option value="4">Mai</option>
                  <option value="5">Jun</option>
                  <option value="6">Jul</option>
                  <option value="7">Ago</option>
                  <option value="8">Set</option>
                  <option value="9">Out</option>
                  <option value="10">Nov</option>
                  <option value="11">Dez</option>
                </select>
                <select id="f7y" class="input-mini" name="year" onchange='updateLineValues();'>
                  <option value="2013">Ano</option>
                  <option value="2004">2004</option>
                  <option value="2005">2005</option>
                  <option value="2006">2006</option>
                  <option value="2007">2006</option>
                  <option value="2008">2008</option>
                  <option value="2009">2009</option>
                  <option value="2010">2010</option>
                  <option value="2011">2011</option>
                  <option value="2012">2012</option>
                  <option value="2013">2013</option>
                </select>
              </form>
            </div>
            <h2> <span class="break"></span>Indicativo acumulado de desmatamento diário </h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="chart-line" class="chart-content"></div>
        </div>
        <div id="chart8" class="span6 chart">
          <div class="chart-header">
            <h2> Taxas (%) de Variações</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div class="chart-content">
            <div id="chart-gauge-month" class="chart-gauge" rel="tooltip" title="Taxa de variação em relação ao mês anterior"></div>
            <div id="chart-gauge-year" class="chart-gauge" rel="tooltip" title="Taxa de variação em relação ao mesmo mês do ano anterior"></div>
            <div id="chart-gauge-period" class="chart-gauge" rel="tooltip" title="Taxa de variação em relação ao periodo anterior"></div>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div id="chart2" class="span6 chart">
          <div class="chart-header">
            <div class="btn-group chart-icon btn-left">
              <button id="m2" class="btn"> <i class="icon-minus"></i> </button>
              <button id="p2" class="btn"> <i class="icon-plus"></i> </button>
            </div>
            <h2> <span class="break"></span>Indicativo total de desmatamento mensal</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="chart-area" class="chart-content"></div>
        </div>
        <div id="chart1" class="span6 chart">
          <div class="chart-header">
            <div class="btn-group chart-icon btn-left">
              <button id="m1" class="btn"><i class="icon-minus"></i></button>
              <button id="p1" class="btn"><i class="icon-plus"></i></button>
            </div>
            <h2> <span class="break"></span>Indicativo de desmatamento do período atual</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="chart-bars" class="chart-content"></div>
        </div>
      </div>
      <div class="row-fluid">
        <div id="chart11" class="span6 chart">
          <div class="chart-header">
            <h2> Indicativo total de desmatamento por período (2004-2013)</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="chart-area-total" class="chart-content"></div>
        </div>
        <div id="chart5" class="span3 chart">
          <div class="chart-header">
            <h2> </span>2004-2013</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="chart-pie-all" class="chart-content" style="padding-left: 12%;"></div>
        </div>
        <div id="chart6" class="span3 chart">
          <div class="chart-header">
            <div class="btn-group chart-icon btn-left">
              <button id="p6" class="btn"><i class="icon-arrow-left"></i></button>
              <button id="m6" class="btn"><i class="icon-arrow-right"></i></button>
            </div>
            <h2> <span class="break"></span>Percentual por UF</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="chart-pie" class="chart-content" style="padding-left: 12%;"></div>
        </div>
      </div>
      <div class="row-fluid">
        <div id="chart3" class="span6 chart">
          <div class="chart-header">
            <h2> Indicativo total de desmatamento por UF (2004-2013)</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="desmatamento_estado_total" class="chart-content"></div>
        </div>
        <div id="chart4" class="span6 chart">
          <div class="chart-header">
            <div class="btn-group chart-icon btn-left">
              <button id="m4" class="btn"><i class="icon-minus"></i></button>
              <button id="p4" class="btn"><i class="icon-plus"></i></button>
            </div>
            <h2> <span class="break"></span>Indicativo total de desmatamento por UF</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="desmatamento_estado" class="chart-content"></div>
        </div>
      </div>
      <div class="row-fluid">
        <div id="chart9" class="span6 chart">
          <div class="chart-header">
            <h2> Qualificação dos polígonos indicativos de desmatamento</h2>
            <div class="btn-group chart-icon btn-right">
              <button class="btn btn-minimize"><i class="icon-chevron-up"></i></button>
              <button class="btn btn-maximize"><i class="icon-resize-full"></i></button>
              <button class="btn btn-close"><i class="icon-remove"></i></button>
            </div>
          </div>
          <div id="chart-pie-qualify" class="chart-content"></div>
        </div>
        <div id="chart10" class="span6 chart"></div>
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
