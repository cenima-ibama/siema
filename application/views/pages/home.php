<section class="content">
  <section id="loading" class="loading">
    <img id="loading_logo"src="<?= base_url()?>/assets/img/ibama_logo.png">
  </section>
  <section id="map">
    <div id="sidebar-map">
      <div id="sidebar-map-btn"><i class="icon-arrow-right"></i></div>
      <span>Legendas</span>
    </div>
    <div id="map-container" class="map"></div>
  </section>
  <section class="dash">
    <section class="charts-content">
      <div class="row-fluid">
        <div class="quick-slct">
            <div class="item">
            <label>Mês</label>
            <select id="monthsSlct" class="" name="months">
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
            </div>
            <div class="item">
            <label>Ano</label>
            <select id="yearsSlct" class="" name="years">
                <option value="2004">2004</option>
                <option value="2005">2005</option>
                <option value="2006">2006</option>
                <option value="2007">2007</option>
                <option value="2008">2008</option>
                <option value="2009">2009</option>
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013">2013</option>
            </select>
            </div>
        </div>
        <div class="quick-btn">
          <a id="AC" href="#" class="item">
            <i class="icon-ac"></i>
            <span>AC</span>
          </a>
          <a id="AM" href="#" class="item">
            <i class="icon-am"></i>
            <span>AM</span>
          </a>
          <a id="AP" href="#" class="item">
            <i class="icon-ap"></i>
            <span>AP</span>
          </a>
          <a id="MA" href="#" class="item">
            <i class="icon-ma"></i>
            <span>MA</span>
          </a>
          <a id="MT" href="#" class="item">
            <i class="icon-mt"></i>
            <span>MT</span>
          </a>
          <a id="PA" href="#" class="item">
            <i class="icon-pa"></i>
            <span>PA</span>
          </a>
          <a id="RO" href="#" class="item">
            <i class="icon-ro"></i>
            <span>RO</span>
          </a>
          <a id="RR" href="#" class="item">
            <i class="icon-rr"></i>
            <span>RR</span>
          </a>
          <a id="TO" href="#" class="item">
            <i class="icon-to"></i>
            <span>TO</span>
          </a>
          <a id="Todos" href="#" class="item active">
            <i class="icon-br"></i>
            <span>Todos</span>
          </a>
        </div>
      </div>
      <hr>
      <div class="row-fluid">
        <div class="boxes">
          <div id="knob1" class="box"> </div>
          <div id="knob2" class="box"> </div>
          <div id="knob3" class="box"> </div>
          <div id="spark1" class="box"> </div>
          <div id="spark2" class="box"> </div>
        </div>
      </div>
      <hr>
      <div id="charts" class="row-fluid">
        <?php
            if(!$logged_in) {
                echo '<div class="alert alert-info alert-block fade in" style="margin: 0 4% 20px">';
                echo '<button class="close" data-dismiss="alert">&times;</button>
                    <h4 style="text-align: left">Informação importante:</h4></br>
                    <p style="text-align: left">
                    As estatísticas do DETER devem ser usados com cuidado, pois este sistema não foi concebido para medição de áreas desmatadas assim como o PRODES.
                    Maiores informações: '. anchor('http://www.obt.inpe.br/deter/metodologia_v2.pdf', 'DETER') . '
                    </p>';
                echo '</div>';
            }
        ?>
        <div id="chart1" class="chart"> </div>
        <div id="chart2" class="chart"> </div>
        <div id="chart3" class="chart"> </div>
        <div id="chart4" class="chart"> </div>
        <div id="chart5" class="chart"> </div>
        <div id="chart6" class="chart"> </div>
        <div id="chart9" class="chart"> </div>
        <div id="chart7" class="chart-small"> </div>
        <div id="chart8" class="chart-small"> </div>
      </div>
    </section>
    <footer>
      <p>
        <span style="text-align:left;float:left"> &copy; <a href="">Ibama - MMA</a> 2013</span>
        <span style="text-align:right;float:right" class="hidden-phone">Powered by: <a target="_blank" href="http://www.hexgis.com">Hexgis HASH5</a></span>
      </p>
    </footer>
  </section>
</section>
