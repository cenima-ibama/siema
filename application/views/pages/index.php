<div id="addMeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button id="btnXClose" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h5 id="myModalLabel">Acidente Ambiental</h5>
  </div>
  <div class="modal-body" style="padding: 5%">
    <div class="tab-content">
      <div class="tab-pane active" id="tab1">
        <div class="row-fluid">
          <div class="span6" style="text-align:center;">
            <p style="text-align:left; margin-top: 10px">
            <img class="pull-left" src="../siema/assets/img/linha_verde_logo_small.png" style="margin: 10px 10px 2px 0">
            Para informar sobre desmatamento, incêndio florestal, denúncia sobre maus tratos ou venda ilegal de animais e todos os demais danos ao meio ambiente que não se enquadram como acidente ambiental.
            <a class="btn btn-block" href="http://www.ibama.gov.br/servicos/a-linha-verde" style="margin-top: 10px" target="_blank">Linha Verde</a>
            </p>
          </div><!--/span-->
          <div class="span6" style="text-align:center;">
            <p style="text-align:left; margin-top: 10px">
            <img class="pull-left" src="../siema/assets/img/acidente_ambiental_logo_small.png" style="margin: 10px 10px 2px 0">
            Para comunicar um acidente envolvendo óleo ou outro produto perigoso (vazamento, derramamento, incêndio/explosão, produtos químicos ou embalagens abandonadas) ou rompimento de barragem.
            <a id="btnBeginForm" class="btn btn-block" href="#tab2" data-toggle="tab" style="margin-top: 10px">Acidente Ambiental</a>
            </p>
          </div><!--/span-->
        </div><!--/row-->
      </div>
      <div class="tab-pane" id="tab2">
        <div class="row-fluid">
            <?php
              if( $this->session->userdata('logged_in') ) {
                  echo '
                <center>
                <img src="./assets/img/check_sign.png" id="checkedUser" style="display: none;" title=""><br/>
                <div id="containerProgress" class="progress progress-striped active" style="width: 50%;">
                    <div id="authProgress" class="bar"></div>
                </div>
                <span id="textProgress">Checando Usuário...</span>
                <div>
                  <p>
                    <a id="btnLogout" class="btn" href="' . base_url() . 'index.php/auth/logout" style="margin-top: 20px;">Logout</a>
                    &nbsp;
                    <a id="tipoForm" class="btn" href="#tab2" data-toggle="tab" style="margin-top: 20px;">Avançar</a>
                  </p>
                </div>
                </center>
                ';
              } else {
                echo '
                <div id="divLogout">
                  <div class="span6">
                    <iframe name="login_Form" src="' . base_url() . '/index.php/login" frameborder="0" style="width:100%; height: 175px;"></iframe>
                    <div class="block text-center">
                      <button id="btnCadastrar" class="btn btn-success span5" data-toggle="tab" disabled="disabled">Cadastrar</button>
                      <button id="btnLogar" class="btn btn-success span5"  onClick="window.top.login_Form.document.loginForm.submit();">Logar</button>
                    </div>
                  </div>
                  <div id="divDenuncia" class="span6">
                    <h4>Denúncia anônima</h4>
                    <p>Esta opção não permite a revisão ou alteração do comunicado enviado.</p>
                    <p>Ao optar pela denúncia anônima, o IBAMA não conseguirá entrar em contato para solicitar informações precisas sobre o acidente. Favor inserir o máximo e informações possíveis e completas.</p>
                    <p><a id="denunciaAnonima" class="btn" href="#tab2" data-toggle="tab">Clique aqui</a></p>
                  </div>
                </div>
                <div id="divLogin" style="display:none;">
                  <center>
                    <img src="./assets/img/check_sign.png" id="checkedUser" style="display: none;" title=""><br/>
                    <div id="containerProgress" class="progress progress-striped active" style="width: 50%;">
                        <div id="authProgress" class="bar"></div>
                    </div>
                    <span id="textProgress">Checando Usuário...</span>
                    <div>
                      <p>
                        <a id="btnLogout" class="btn" href="' . base_url() . 'index.php/auth/logout" style="margin-top: 20px;">Logout</a>
                        &nbsp;
                        <a id="tipoForm" class="btn" href="#tab2" data-toggle="tab" style="margin-top: 20px;">Avançar</a>
                      </p>
                    </div>
                  </center>
                </div>
                  ';
              }
            ?>
        </div>
      </div>
      <div class="tab-pane" id="tab3">
        <h3>Tipo de conta</h3>
        <div class="media">
          <div class="media-body">
            <label class="radio">
              <input id="radioPubExt" type="radio" name="tipoConta" checked>
              <h5 class="media-heading">Público Externo</h5>
              <span>Se você é público externo.. Adicionar comentário.</span>
            </label>
          </div>
        </div>
        <div class="media">
          <div class="media-body">
            <label class="radio">
              <input id="radioServPub" type="radio" name="tipoConta">
              <h5 class="media-heading">Servidor IBAMA</h5>
              <span>Se você é servidor do IBAMA.. Adicionar comentário.</span>
            </label>
          </div>
        </div>
        <div class="media">
          <div class="media-body">
            <label class="radio">
              <input id="radioEmp" type="radio" name="tipoConta">
              <h5 class="media-heading">Empresa</h5>
              <span>Se você é empresa.. Adicionar comentário.</span>
            </label>
          </div>
        </div>
        <div class="media">
          <div class="media-body">
            <label class="radio">
              <input id="radioOrgao" type="radio" name="tipoConta">
              <h5 class="media-heading">Órgão Público</h5>
              <span>Se você é um Orgão Público . Adicionar comentário.</span>
            </label>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab4">
        <div class="row-fluid">
          <br />
          <div class="form-horizontal">
            <div class="text-center">
              <h5> <strong>Identificação do Orgão/Empresa</strong></h5>
            </div>
            <div class="control-group">
              <label class="control-label" for="usuario">Usuário:</label>
              <div class="controls">
                <input id="usuario" type="text" class="inputUsuario" placeholder="Usuario">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="senha">Senha:</label>
              <div class="controls">
                <input id="senha" type="password" class="inputSenha" placeholder="Senha">
              </div>
            </div>
          </div>
          <br />
        </div>
      </div>
      <div class="tab-pane" id="tab5">
        <div class="row-fluid">
          <div class="span6">
            <div class="control-group">
              <label class="control-label" for="inputNome">Nome:</label>
              <div class="controls">
                <input id="inputNome" class="input-large" type="text" name="inputNome" placeholder="Nome">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="inputCPF">CPF:</label>
              <div class="controls">
                <input id="inputCPF" class="input-large" type="text" name="inputCPF" placeholder="000.000.000-00">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="inputSenha">Senha:</label>
              <div class="controls">
                <input id="inputSenha" class="input-large" type="password" name="inputSenha" placeholder="">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="inputConfirmarSenha">Confirmar senha:</label>
              <div class="controls">
                <input id="inputConfirmarSenha" class="input-large" type="password" name="inputConfirmarSenha" placeholder="">
              </div>
            </div>
          </div>
          <div class="span6">
            <div class="control-group">
              <label class="control-label" for="inputEmail">Email:</label>
              <div class="controls">
                <input id="inputEmail" class="input-large" type="text" name="inputEmail" placeholder="nome@email.com">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="inputTelefone">Telefone:</label>
              <div class="controls">
                <input id="inputTelefone" class="input-large" type="text" name="inputTelefone" placeholder="(00) 0000-0000">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tab6">
        <div class="alert alert-info fade in" style="margin: 100px 120px">
          <strong>Conta criada com sucesso!</strong><br/> Dados de sua conta foram enviados para seu e-mail.
        </div>
      </div>
      <div class="tab-pane" id="tab7">
        <div class="media">
          <div class="media-body">
            <label class="radio">
              <input type="radio" name="optionsTipoAcidente" id="optionsAcidenteOleo" value="1" checked>
              <h4 class="media-heading">Acidente envolvendo óleo</h4>
              <div class="media">
                <p>Qualquer incidente ocorrido em portos organizados, instalações portuárias, dutos, navios, plataformas e suas instalações de apoio, que possa provocar poluição das águas sob jurisdição nacional.</p>
              </div>
            </label>
          </div>
        </div>
        <div class="media">
          <div class="media-body">
            <label class="radio">
              <input type="radio" name="optionsTipoAcidente" id="optionsAcidenteOutros" value="0">
              <h4 class="media-heading">Demais acidentes ambientais</h4>
              <div class="media">
                <p>Acidente envolvendo produto(s) perigoso(s) (vazamento, derramamento, incêndio/explosão, produtos químicos ou embalagens abandonadas) ou rompimento de barragem.</p>
              </div>
            </label>
          </div>
        </div>
        <div id="inputLoadForm" class="media" style="display:none;">
          <div class="media-body">
            <label class="radio">
              <input type="radio" name="optionsTipoAcidente" id="optionsAtualizarAcidente" value="-1">
              <h4 class="media-heading">Atualizar acidentes enviados</h4>
              <div class="media">
                <p> Adicionar mais informações a acidentes já existentes.</p>
              </div>
            </label>
            <label class="radio">
              <label>Número do Registro:</label>
              <div class="controls">
                <input id="inputRegistro" class="input-large" type="text" name="inputRegistro" placeholder="Número do Registro do Acidente">
              </div>
            </label>
          </div>
        </div>
        <?php echo form_open('form/createform', array('id' => 'formCreate', 'target' => 'form_frame')); ?>
          <label class="checkbox" style="display:none;">
            <label id="defaultHtml" name="defaultHtml"></label>
            <input type="checkbox" id="hasOleo" name="hasOleo">
            <?php
              if($this->session->userdata('logged_in'))
                echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA" checked>';
              else
                echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA">';
            ?>
          </label>
        <?php echo form_close(); ?>
        <?php echo form_open('form/loadform', array('id' => 'formLoad', 'target' => 'form_frame')); ?>
          <label class="checkbox" style="display:none;">
            <input type="checkbox" id="hasOleo" name="hasOleo">
            <?php
              if($this->session->userdata('logged_in'))
                echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA" checked>';
              else
                echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA">';
            ?>
          </label>
        <?php echo form_close(); ?>
      </div>
      <div class="tab-pane" id="tab8">
        <!-- <div class="row-fluid"> -->
          <iframe name="form_frame" style="border: medium none white; height: 395px; width: 100%;"></iframe>
        <!-- </div> -->
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <a id="modalBtnBack" href="" class="btn" data-toggle="tab">Voltar</a>
    <a id="modalBtnNext" href="#tab1" class="btn" data-toggle="tab">Avançar</a>
    <a id="modalBtnCancel" href="#tab2" class="btn" data-toggle="tab" style="display:none;"><i class="icon-trash"></i> Cancelar</a>
    <a id="submit" class="btn btn-primary" type="button" style="display:none;" onClick="window.top.form_frame.document.formAcidentes.submit()">
      <i class="icon-ok icon-white"></i>
      Enviar
    </a>
    <!-- Creating the new buttons Cancelar and Fechar - both hidden -->
    <a id="btnClose" class="btn btn-primary" type="button" style="display:none;" data-dismiss="modal"><i class="icon-remove"></i>Fechar</a>
  </div>
</div>
<div class="loading" id="loading">
  <img src="./assets/img/logo.png" id="loading_logo" style="display: inline;" title="">
</div>
<div id="map" class="map"></div>
<div id="dash" class="dash">
  <div class="charts-content">
    <div class="row-fluid">
      <?php
      if(!$logged_in) {
        echo '<div class="alert alert-danger alert-block fade in" style="margin: 0 20% 20px">';
        echo '<button class="close" data-dismiss="alert">&times;</button>
        <h4 style="text-align: left">Importante:</h4></br>
        <p style="text-align: left">
        Esse sistema encontra-se em fase de teste, alguns recursos ou funcionalidades não foram totalmente testados ou não foram implementados.
        </p>';
        echo '</div>';
      }
      ?>
      <div class="quick-slct">
        <div class="item ">
          <label>Mês</label>
          <select id="monthsSlct" class="selectpicker" data-width="80px" data-size="auto" name="months">
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
          <select id="yearsSlct" class="selectpicker" data-width="80px" data-size="6" name="years">
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
      <div class="item">
          <label>Tipo de Evento</label>
          <select id="typesSlct" class="selectpicker" data-width="140px" data-size="6" name="types">
            <option value="0">Derramamento de líquidos</option>
            <option value="1">Desastre natural</option>
            <option value="2">Explosão/incêncio</option>
            <option value="3">Lançamento de sólidos</option>
            <option value="4">Mortandade de peixes</option>
            <option value="5">Produtos químicos/embalagens abandonadas</option>
            <option value="6">Rompimento de barragem</option>
            <option value="7">Vazamento de gases</option>
            <option value="8">Outros</option>
            <option value="9">Todos</option>
          </select>
        </div>
      </div>
      <div class="quick-btn">
        <a id="NO" href="#" class="item">
          <i class="icon-no"></i>
          <span>Norte</span>
        </a>
        <a id="NE" href="#" class="item">
          <i class="icon-nd"></i>
          <span>Nordeste</span>
        </a>
        <a id="CO" href="#" class="item">
          <i class="icon-co"></i>
          <span>Centro-Oeste</span>
        </a>
        <a id="SE" href="#" class="item">
          <i class="icon-sd"></i>
          <span>Sudeste</span>
        </a>
        <a id="SU" href="#" class="item">
          <i class="icon-su"></i>
          <span>Sul</span>
        </a>
        <a id="Todos" href="#" class="item active">
          <i class="icon-br"></i>
          <span>Brasil</span>
        </a>
      </div>
    </div>
    <hr>
    <div class="row-fluid">
      <div id="sparks" class="sparks">
        <div id="knob1" class="spark"> </div>
        <div id="knob2" class="spark"> </div>
        <div id="knob3" class="spark"> </div>
        <div id="spark1" class="spark"> </div>
        <div id="spark2" class="spark"> </div>
      </div>
    </div>
    <hr>
    <div id="charts" class="row-fluid">
      <div id="chart1" class="box"> </div>
      <div id="chart2" class="box"> </div>
      <div id="chart3" class="box"> </div>
      <div id="chart4" class="box"> </div>
      <div id="chart5" class="box"> </div>
      <div id="chart6" class="box"> </div>
      <div id="chart9" class="box"> </div>
      <div id="chart7" class="box-small"> </div>
      <div id="chart8" class="box-small"> </div>
    </div>
  </div>
</div>
