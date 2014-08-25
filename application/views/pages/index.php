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
            <p style="text-align:justify; margin-top: 10px">
            <img class="pull-left" src="../siema/assets/img/linha_verde_logo_small.png" style="margin: 10px 10px 2px 0">
            Para informar sobre desmatamento, incêndio florestal, denúncia sobre maus tratos ou venda ilegal de animais e todos os demais danos ao meio ambiente que não se enquadram como acidente ambiental,  ligue no Linha Verde (<strong>0800-618080</strong>) ou clique no botão abaixo.            
            </p>
            <div class="row-fluid">
              <a class="btn btn-block" href="http://www.ibama.gov.br/cadastro-ocorrencias" style="margin-top: 10px" target="_blank">Denuncie Aqui</a>         
            </div>
          </div>
          <div class="span6" style="text-align:center;">
            <p style="text-align:justify; margin-top: 10px">
            <img class="pull-left" src="../siema/assets/img/acidente_ambiental_logo_small.png" style="margin: 10px 10px 2px 0">
            Para comunicar um acidente envolvendo óleo ou outro produto perigoso (vazamento, derramamento, incêndio/explosão, produtos químicos ou embalagens abandonadas) ou rompimento de barragem, clique no botão abaixo.            
            </p>
            <div class="row-fluid">
              <a id="btnBeginForm" class="btn btn-block" href="#tab2" data-toggle="tab" style="margin-top: 10px">Acidente Ambiental</a>
            </div>
          </div>          
        </div>                 
      </div>
      <div class="tab-pane" id="tab2">

        <div class="row-fluid">
            <?php
              if( $this->session->userdata('logged_in') ) {
                  echo '
                <center>
                <img src="'.  base_url() . 'assets/img/check_sign.png" id="checkedUser" style="display: none;" title=""><br/>
                <div id="containerProgress" class="progress progress-striped active" style="width: 50%;">
                    <div id="authProgress" class="bar"></div>
                </div>
                <span id="textProgress">Checando Usuário...</span>
                <div>
                  <p>
                    <a id="btnLogout" class="btn" href="' . base_url() . 'index.php/auth/logout" style="margin-top: 20px;">Sair</a>
                    &nbsp;
                    <a id="tipoForm" class="btn" href="#tab2" data-toggle="tab" style="margin-top: 20px;">Avançar</a>
                  </p>
                </div>
                </center>
                ';
              } else {
                echo '
                <div id="divLogout">
                  <div class="span4">
                    <h4 style="text-align:center;">Comunicado do IBAMA</h4>
                    <p style="font-size:11px; text-align:justify;">Destinado aos usuários registrados no Sistema Ibama-Net.</p>
                      <p style="font-size:11px; text-align:justify;">Os comunicados já enviados poderão ser atualizados a qualquer momento, para isso esteja com o n° de registro em mãos.</p>
                    <iframe name="login_Form_Ibama" src="' . base_url() . '/index.php/login/login_ibama" frameborder="0" style="width:100%; height: 135px;"></iframe>
                    <div class="block text-center">
                      <button id="btnCadastrar" class="btn btn-success span5" data-toggle="tab" disabled="disabled">Cadastrar</button>
                      <button id="btnLogar" class="btn btn-success span5" onClick="window.top.login_Form_Ibama.document.loginForm.submit();">Logar</button>
                    </div>
                  </div>
                  <div class="span4">
                    <h4 style="text-align:center;">Comunicado das Empresas</h4>
                    <p style="font-size:11px; text-align:justify;">Destinado as Empresas licenciadas pelo Ibama.</p>
                      <p style="font-size:11px; text-align:justify;">Os comunicados já enviados poderão ser atualizados a qualquer momento, para isso esteja com o n° de registro em mãos.</p>
                    <iframe name="login_Form_Empresa" src="' . base_url() . '/index.php/login/login_empresa" frameborder="0" style="width:100%; height: 135px;"></iframe>
                    <div class="block text-center">
                      <button id="btnCadastrar" class="btn btn-success span5" data-toggle="tab" disabled="disabled">Cadastrar</button>
                      <button id="btnLogar" class="btn btn-success span5" onClick="window.top.login_Form_Empresa.document.loginForm.submit();">Logar</button>
                    </div>
                  </div>
                  <div id="divDenuncia" class="span4">
                    <h4 style="text-align:center;">Comunicado do Cidadão</h4>
                    <div style="margin-top: 10px;">
                      <p style="font-size:11px; text-align:justify;">Destinado aos cidadãos que queiram comunicar um acidente ambiental.</p>
                      <p style="font-size:11px; text-align:justify;">O comunicante poderá identificar-se ou não. Favor inserir o máximo de informações.</p>
                      <p style="text-align:center; margin-top:37%;"><a id="denunciaAnonima" class="btn" href="#tab2" data-toggle="tab">Clique aqui</a></p>
                    </div>
                  </div>
                </div>
                <div id="divLogin" style="display:none;">
                  <center>
                    <img src="'.  base_url() . 'assets/img/check_sign.png" id="checkedUser" style="display: none;" title=""><br/>
                    <div id="containerProgress" class="progress progress-striped active" style="width: 50%;">
                        <div id="authProgress" class="bar"></div>
                    </div>
                    <span id="textProgress">Checando Usuário...</span>
                    <div>
                      <p>
                        <a id="btnLogout" class="btn" href="' . base_url() . 'index.php/auth/logout" style="margin-top: 20px;">Sair</a>
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
          <br/>
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
        <div  id="divErrorUpdate" class="alert alert-block alert-error fade in" style="display:none;padding:7px">
          <span id="msgErrorUpdate"></span>
        </div>
        <div class="media">      
          <div class="media-body">
            <label class="radio">
              <input type="radio" name="optionsTipoAcidente" id="optionsAcidenteOleo" value="1">
              <h4 class="media-heading">Acidente envolvendo óleo</h4>
              <div class="media">
                <p style="text-align:justify">
                    Qualquer incidente ocorrido em portos organizados, instalações portuárias, dutos, navios, plataformas e suas instalações de apoio, que possa provocar poluição das águas sob jurisdição nacional.
                    </br><i style="font-size: 9px">(Lei nº. 9.966/2000 e Decreto nº. 4.136/2002)</i>
                </p>
              </div>
            </label>
          </div>
        </div>
        <div class="media">
          <div class="media-body">
            <label class="radio">
              <input type="radio" name="optionsTipoAcidente" id="optionsAcidenteOutros" value="0" checked>
              <h4 class="media-heading">Demais acidentes ambientais</h4>
              <div class="media">
                <p style="text-align:justify">Acidente envolvendo produto(s) perigoso(s) (vazamento, derramamento, incêndio/explosão, produtos químicos ou embalagens abandonadas) ou rompimento de barragem.</p>
              </div>
            </label>
          </div>
        </div>
        <?php
          if($this->session->userdata('logged_in'))
            echo '<div id="inputLoadForm" class="media">';
          else
            echo '<div id="inputLoadForm" class="media" style="display:none;">';
        ?>
        <!-- <div id="inputLoadForm" class="media" style="display:none;"> -->
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
            <input type="checkbox" id="hasOleo" name="hasOleo" value="S">
            <?php
              if($this->session->userdata('logged_in'))
                echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA" checked>';
              else
                echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA">';
            ?>
          </label>
        <?php echo form_close(); ?>
        <?php echo form_open('form/loadformcall', array('id' => 'formLoad', 'target' => 'form_frame')); ?>
          <label class="checkbox" style="display:none;">
            <input type="checkbox" id="hasOleo" name="hasOleo" value="S">
            <input type="hidden" id="nroOcorrenciaLoad" name="nroOcorrencia" value="">
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
          <iframe name="form_frame" style="border: medium none white; height: 394px; width: 100%;"></iframe>
        <!-- </div> -->
      </div>
    </div>
  </div>
  <div class="modal-footer" style="display:none;">
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




















<!--  -->

<div class="loading" id="loading">
  <!-- <img src="<?php echo base_url()?>assets/img/logo.png" id="loading_logo" style="display: inline;" title=""> -->
  <img src="<?php echo base_url()?>assets/img/logo_ibama.png" id="loading_logo" style="display: inline; overflow: hidden;" title="">
</div>

<?php
  if(!$logged_in) {
    echo '<div id="login" class="login"> </div>';
  }
?>

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
        O Ibama registra e analisa informações a respeito de acidentes ambientais que ocorrem em todo o território brasileiro, prioritariamente os que são causados pela liberação acidental de produtos nocivos ou perigosos ao meio ambiente, tais como óleos e demais produtos químicos. Utilize essa ferramenta e faça a sua busca em nosso Sistema.
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
            <option value="12">Todos</option>
          </select>
        </div>
        <div class="item">
          <label>Ano</label>
          <select id="yearsSlct" class="selectpicker" data-width="80px" data-size="6" name="years">
            <option value="2013" selected="selected">2013</option>
            <option value="2012">2012</option>
            <option value="2011">2011</option>
            <option value="2010">2010</option>
            <option value="2009">2009</option>
            <option value="2008">2008</option>
            <option value="2007">2007</option>
            <option value="2006">2006</option>
            <option value="2005">2005</option>
            <option value="2004">2004</option>
          </select>
        </div>
        <div class="item">
          <label>Tipo de Evento</label>
          <select id="typesSlct" class="selectpicker" data-width="140px" data-size="6" name="types">
            <option value="0">Derramamento de líquidos</option>
            <option value="1">Desastre natural</option>
            <option value="2">Explosão/incêndio</option>
            <option value="3">Lançamento de sólidos</option>
            <option value="4">Mortandade de peixes</option>
            <option value="5">Produtos químicos/embalagens abandonadas</option>
            <option value="6">Rompimento de barragem</option>
            <option value="7">Vazamento de gases</option>
            <option value="8">Outros</option>
            <option value="9">Todos</option>
          </select>
        </div>
        <div class="item">
          <label>Origem do Acidente</label>
          <select id="originsSlct" class="selectpicker" data-width="140px" data-size="6" name="origins">
            <option value="0">Rodovia</option>
            <option value="1">Ferrovia</option>
            <option value="2">Terminal/portos/ancoradouros/etc.</option>
            <option value="3">Embarcação</option>
            <option value="4">Refinaria</option>
            <option value="5">Plataforma</option>
            <option value="6">Indústria</option>
            <option value="7">Duto</option>
            <option value="8">Barragem</option>
            <option value="9">Armazenamento/Depósito</option>
            <option value="10">Posto de combustível</option>
            <option value="11">Outros</option>
            <option value="12">Todos</option>
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
      <!-- FOI SOLICITADO A REMOÇÃO DO CHART 1 3 4 E 6 - 21/08/14 -->
      <!--
      <div id="chart1" class="box"> </div>
      -->
      <div id="chart2" class="box"> </div>
      <!--
      <div id="chart3" class="box"> </div>
      
      <div id="chart4" class="box"> </div>
      -->
      <div id="chart5" class="box"> </div>
      <!--
      <div id="chart6" class="box"> </div>
      -->
      <!--<div id="chart9" class="box"> </div>-->
      <div id="chart7" class="box"> </div>
      <div id="chart8" class="box"> </div>
    </div>
    <div id="message" style="text-align: right; font-size: 10px; padding-right: 32px;float:left">
      <span> *Quando não há informação sobre a data exata de ocorrência do acidente, foi considerada a data de registro pelo IBAMA </span>
    </div>
  </div>
</div>

<div id="consultas" class="consultas">
  <div class="consultas-content">

    <div class="row-fluid">
      <div class="quick-slct">
        <div class="item ">
          <label>Tipo de Produto</label>
          <select id="tipoProd" class="selectpicker" data-width="150px" data-size="auto" name="tipoProd">
            <option value="0">Produtos na lista ONU</option>
            <option value="1">Produtos fora da lista ONU</option>
            <option value="2" selected="true">Todos</option>
          </select>
        </div>
        <div class="item">
          <label>Estado(UF)</label>
          <select name="dropDownConsultUF" id="dropConsultUF" data-width="80px" data-size="6" class="selectpicker">
              <option value="AC">AC</option>
              <option value="AL">AL</option>
              <option value="AM">AM</option>
              <option value="AP">AP</option>
              <option value="BA">BA</option>
              <option value="CE">CE</option>
              <option value="DF">DF</option>
              <option value="ES">ES</option>
              <option value="GO">GO</option>
              <option value="MA">MA</option>
              <option value="MG">MG</option>
              <option value="MS">MS</option>
              <option value="MT">MT</option>
              <option value="PA">PA</option>
              <option value="PB">PB</option>
              <option value="PE">PE</option>
              <option value="PI">PI</option>
              <option value="PR">PR</option>
              <option value="RJ">RJ</option>
              <option value="RN">RN</option>
              <option value="RO">RO</option>
              <option value="RR">RR</option>
              <option value="RS">RS</option>
              <option value="SC">SC</option>
              <option value="SE">SE</option>
              <option value="SP">SP</option>
              <option value="TO">TO</option>
              <option value="Todos" selected="true">Todos</option>
          </select>
        </div>

        <div class="item">
          <label>Origem do Acidente</label>
          <select id="originsConsultSlct" class="selectpicker" data-width="140px" data-size="6" name="origins">
            <option value="Rodovia">Rodovia</option>
            <option value="Ferrovia">Ferrovia</option>
            <option value="Terminal/portos/ancoradouros/etc.">Terminal/portos/ancoradouros/etc.</option>
            <option value="Embarcação">Embarcação</option>
            <option value="Refinaria">Refinaria</option>
            <option value="Plataforma">Plataforma</option>
            <option value="Indústria">Indústria</option>
            <option value="Duto">Duto</option>
            <option value="Barragem">Barragem</option>
            <option value="Armazenamento/Depósito">Armazenamento/Depósito</option>
            <option value="Posto de combustível">Posto de combustível</option>
            <option value="Outro(s)">Outro(s)</option>
            <option value="Todos" selected="true">Todos</option>
          </select>
        </div>
        <div class="item">

          <label>Data Inicial</label>
              <div class="input-daterange pull-right" id="dtDataIni">
                  <input class="input-small" name="dateStart" type="text" id="dateStart" placeholder="dd/mm/aaaa" >
              </div>

        </div>
        <div class="item">
          <label>Data Final</label>
          <div class="input-daterange pull-right" id="dtDataIni" style="float:left">
             <input class="input-small" name="dateFinish" type="text" id="dateFinish" placeholder="dd/mm/aaaa" >
          </div>
        </div>
        <div>
           <label><input type="checkbox" name="chkAllDates" value="1" id="chkAllDates" class="pull-left">Todas as datas</label>
        </div>

        <div class="item" id="divBaciaSedimentar">
          <label>Bacia Sedimentar</label>

          <select id="baciaConsultSlct" class="selectpicker" data-width="140px" data-size="6" name="bacia">
            <option value="Foz do Amazonas">Foz do Amazonas</option>
            <option value="Pará-Maranhão">Pará-Maranhão</option>
            <option value="Barreirinhas">Barreirinhas</option>
            <option value="Ceará (Mundão)">Ceará (Mundão)</option>
            <option value="Potiguar">Potiguar</option>
            <option value="Pernambuco-Paraíba">Pernambuco-Paraíba</option>
            <option value="Alagoas">Alagoas</option>
            <option value="Sergipe">Sergipe</option>
            <option value="Jacuípe">Jacuípe</option>
            <option value="Camamu">Camamu</option>
            <option value="Almada">Almada</option>
            <option value="Jequitinhonha">Jequitinhonha</option>
            <option value="Cumuruxatiba">Cumuruxatiba</option>
            <option value="Espírito-Santo">Espírito-Santo</option>
            <option value="Campos">Campos</option>
            <option value="Santos">Santos</option>
            <option value="Pelotas">Pelotas</option>
            <option value="Todos" selected="true">Todos</option>
          </select>

        </div>

        <div class="item">        
           <label><input type="checkbox" name="chkOCeano" value="1" id="chkOCeano" class="pull-left">Oceano</label>
        </div>

        <!-- Button consultarDados on / modify h5home.coffe on assets/coffee-->
        <button id="consultarDados" type="button" class="btn" style="height: 1%; margin: 1.5em -5% 0% 2%">Consultar</button>
        <!-- -->

      </div>

    <div>
      <div id="resultsConsult" >

      </div>
      <div id="optionsExport" style="display:none">
            <a class="btn" style="margin-top: 10px;width: 10%;" id="btnExportXls">Exportar para Planilha</a>
            <a class="btn" style="margin-top: 10px;width: 10%;" id="btnExportPdf">Exportar para PDF</a>
      </div>    


    </div>

    </div>

  </div>

 <div id="modalExport" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 500px">
  <div class="modal-header">
    <button id="btnXClose" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h5 id="myModalLabel">Selecionar Colunas</h5>
  </div>
  <div style="padding: 5%">
    <div style="margin-bottom: 10px">
      <span class="label label-info">Selecione as colunas que serão exportadas para o documento.</span>    
    </div>
    <div id="divOpColunas">
        <label class="checkbox"><input type="checkbox" id="0" value="Número de Registro">Número de Registro</input></label>
        <label class="checkbox"><input type="checkbox" id="1" value="Data do Incidente">Data do Incidente</input></label>
        <label class="checkbox"><input type="checkbox" id="2" value="Município/UF">Município/UF</input></label>
        <label class="checkbox"><input type="checkbox" id="3" value="Origem">Origem</input></label>
        <label class="checkbox"><input type="checkbox" id="4" value="Tipo de Evento">Tipo de Evento</input></label>
        <label class="checkbox"><input type="checkbox" id="5" value="Produtos ONU">Produtos ONU</input></label>
        <label class="checkbox"><input type="checkbox" id="6" value="Produtos Outros">Produtos Outros</input></label>
        <label class="checkbox"><input type="checkbox" id="7" value="Ocorrências/Ambientes Atingidos">Ocorrências/Ambientes Atingidos</input></label>
        <label class="checkbox"><input type="checkbox" id="8" value="Inst. Atuando no Local">Inst. Atuando no Local</input></label>
        <label class="checkbox"><input type="checkbox" id="9" value="Fontes de Informação">Fontes de Informação</input></label>
        <label class="checkbox"><input type="checkbox" id="10" value="Dia da Semana">Dia da Semana</input></label>
        <label class="checkbox"><input type="checkbox" id="11" value="Período">Período</input></label>
        <label class="checkbox"><input type="checkbox" id="12" value="Feriado">Feriado</input></label>
    </div>    
  </div>
  <div class="modal-footer" style="display:none;">
      <a id="btnClose" class="btn" type="button" data-dismiss="modal"><i class="icon-remove"></i> Fechar</a>
      <a id="btnExport" class="btn btn-primary" type="button" data-dismiss="modal"><i class="icon-ok"></i> Exportar</a>
      <input type="hidden" id="tipoExport"></input>
  </div>
</div>

</div>


<div id="manag" class="dash">
  <div class="charts-content">
    <div class="row-fluid">
      <div class="span3 col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
        <?php

          $profilUser = $this->session->userdata('profile_user');

          //Only administrator users acess this option.
          if($profilUser == 1)
            echo '<li class="active"><a href="#" id="btn-manage1">Cadastro de Pessoas</a></li>';

          //Active link if is administrator user.
          if($profilUser == 1 || $profilUser == 2)
          { 
              //Is Administrator User.
              if ($profilUser == 1)
                echo '<li><a href="#" id="btn-manage2">Gerência de Acidentes</a></li>';                            
              else
                echo '<li class="active"><a href="#" id="btn-manage2">Gerência de Acidentes</a></li>';              
          }
          
          //Only administrator users acess this option.
          if($profilUser == 1)
          {
              echo '<li><a href="#" id="btn-manage3">Gerência de Regras</a></li>';
              echo '<li><a href="#" id="btn-manage4">Cadastro de Órgão</a></li>';
          }

        ?>
        </ul>
      </div>
      <div class="span9 col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div class="row-fluid">

        <?php
          //Only administrator users acess this option.
          if ($profilUser == 1)
          {
        ?> 

            <div id="manage1">
            <h2 class="sub-header">Cadastro de Pessoas</h2>
            <form class="form-horizontal" role="form" id="center-cadastro">
              <div class="form-group">
                <div class="item">
                  <label for="inputNome" class="col-sm-2 control-label">Nome</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputNome" placeholder="Nome">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="inputCPF" class="col-sm-2 control-label">CPF</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputCPF" placeholder="CPF">
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                </div>
              </div>
              <div class="form-group">
                <label for="inputOrgao" class="col-sm-2 control-label">Órgão</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputOrgao" placeholder="Orgao">
                </div>
              </div>
              <div class="form-group">
                <label for="inputTelefone" class="col-sm-2 control-label">Telefone</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputTelefone" placeholder="Telefone">
                </div>
              </div>
              <div class="form-group"></div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10" style="margin: 0.5em">
                  <button type="submit" class="btn btn-default">Enviar</button>
                </div>
              </div>
            </form>
            </div>

        <?php
          }
        ?>
        
        <?php
          //Only administrator or validator users acess this option.
          if ($profilUser == 1 || $profilUser == 2 )  
          {
        ?>          
            <div id="manage2">
            <!-- <form id="search" action="/search" method="get">
              <input id="searchInput" type="text" placeholder="Search..." size="40" name="q">
            </form> -->

            <form class="navbar-form form-search">
              <div id="search-group" class="pull-right">
                <i class="icon-search icon-search-filter"></i>
                  <input id="searchInput" type="text" placeholder="Filtrar ocorrência..." class="search-query">
                  <!-- <button class="btn">Search</button> -->
              </div>
            </form>

            <h2 class="sub-header">Ocorrências Não Validadas</h2>
            <!-- <input id="searchInput" type="text" value="Type To Filter"><span style="color:#0088CC;"><i class="icon-search icon-white"></i></span> -->
            <div class="table-responsive table-ocorrencia" id="tableNaoValidado"></div>

            <hr style="margin: 60px 0px;">

            <h2 class="sub-header">Ocorrências Validadas</h2>
            <!-- <input id="searchInput" value="Type To Filter"> -->
            <div class="table-responsive table-ocorrencia" id="tableValidado"></div>
            <!-- <div id="editMeModal" class="login"> </div> -->
            <!-- Modal -->
            <div id="editMeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-header">
                <button id="btnXClose" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5 id="myModalLabel">Acidente Ambiental</h5>
              </div>
              <div class="modal-body" id="modal-body" style="padding: 5%">
                <div style="overflow:hidden;">
            <!-- <div class="modal fade" id="editMeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                  </div>
                  <div class="modal-body" id="modal-body"> -->
                    <?php echo form_open("form/loadformcall", array("id" => "formLoadAdmin", "target" => "form_frame_edit")); ?>
                      <label class="checkbox" style="display:none;">
                        <input type="checkbox" id="hasOleo" name="hasOleo" value="S">
                        <input type="hidden" id="nroOcorrenciaLoadAdmin" name="nroOcorrencia" value="">
                        <?php
                          if($this->session->userdata("logged_in"))
                            echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA" checked>';
                          else
                            echo '<input type="checkbox" id="isServIBAMA" name="isServIBAMA">';
                        ?>
                      </label>
                    <?php echo form_close(); ?>
                    <iframe name="form_frame_edit" id="myModalFrame" style="border: medium none white; height: 394px; width: 100%;"></iframe>
                  </div>
                </div>
                  <div class="modal-footer" style="display:none;">
                    <!-- <a id="modalBtnBack" href="" class="btn" data-toggle="tab">Voltar</a> -->
                    <!-- <a id="modalBtnNext" href="#tab1" class="btn" data-toggle="tab">Avançar</a> -->
                    <a id="modalBtnCancel" href="#tab2" class="btn" data-dismiss="modal"><i class="icon-trash"></i> Cancelar</a>
                    <a id="submit" class="btn btn-primary" type="button" onClick="window.top.form_frame_edit.document.formAcidentes.submit()">
                      <i class="icon-ok icon-white"></i>
                      Enviar
                    </a>
                    <!-- Creating the new buttons Cancelar and Fechar - both hidden -->
                    <a id="btnClose" class="btn btn-primary" type="button" style="display:none;" data-dismiss="modal"><i class="icon-remove"></i>Fechar</a>
                  </div>
                </div>
                  <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div> -->
              </div>
            </div>
            </div>
        <?php
          }       
        ?>
        
        <?php
          //Only administrator users acess this options.
          if ($profilUser == 1) { 
        ?>                       
            <div id="manage3">
              <h2 class="sub-header">Gerência de Regras</h2>
              <div></div>
            </div>

            <div id="manage4">
              <h2 class="sub-header">Cadastro de Órgão</h2>
              <div></div>
            </div>
        <?php        
          }
        ?>
          
        </div>
      </div>
    </div>
  </div>

</div>
