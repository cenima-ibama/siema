<div class="row-fluid">

    <?php
      if (validation_errors()) {
        echo '<div class="alert alert-block alert-error fade in" style="display:inherit;">';
        echo validation_errors();
        echo '</div>';
      }
    ?>


  <div class="box-header"> </div>
  <div class="box-content">

    <?php echo form_open('form/validate', array('id' => 'formAcidentes', 'name' => 'formAcidentes')); ?>

      <div class="accordion" id="accordion2">
        <div class="alert alert-block alert-error fade in" style="display:none;" id="error_box"></div>
        <div class="accordion-heading">
          <br />
          <h4 class="text-right" style="color:#0088cc;padding: 0 10px 0 0;">
            <strong>Número de registro:</strong>
            <span id="nroComunicado" name="nroComunicado"><?php
              if (isset($comunicado)) {
                echo $comunicado;
              }
            ?></span>

          </h4>
          <input type="hidden" id="comunicado" name="comunicado"
            <?php
              if (isset($comunicado)) {
                echo ' value="' . $comunicado . '" ';
              }
            ?>
          />
          <!-- <input type="hidden" id="typeOfForm" name="typeOfForm" -->
            <?php
              echo form_input($typeOfForm);

              echo form_input($shapeLoaded);
            ?>
          <!-- /> -->
          <br />
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse1">
              1. Localização*
                <?php
                  if (isset($hasOleo))
                      echo "</br><i style=\"font-size: 9px\">(Item IV do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)</i>"
                ?>
            </a>
          </div>
          <div id="collapse1" class="accordion-body collapse in">
            <div class="accordion-inner">
              <p style="font-size: 11px; text-align:center; color: #068ACD">Indique no mapa o local do acidente ou informe as coordenadas</p>
              <div class="row-fluid text-left">
                <div class="span12">
                  <div id="minimap"></div>
                </div>
                <br />
                <div class="span6">
                  <div class="control-group span12">
                    <div class="span8">
                      <label class="control-label" for="inputLat">Latitude(y)/Longitude(x)*</label>
                      <div class="controls">
                        <?php echo form_input($inputLat); ?>
                        <?php echo form_input($inputLng); ?>
                      </div>
                    </div>

                    <div class="span12">
                      <label class="checkbox" for="oceano">
                        <?php echo form_checkbox($oceano); ?>
                        &nbsp; Oceano
                      </label>
                      <label id="spanBaciaSed" class="checkbox" for="inputBaciaSed"
                        <?php
                          if (!isset($oceano['checked']))
                            echo ' style="display:none;"';
                        ?>
                      >
                        &nbsp; Bacia Sedimentar
                        <?php echo form_input($inputBaciaSed); ?>
                      </label>
                    </div>

                    <!--
                    <div class="span4">
                      <div class="control-group">
                        <label class="control-label" for="inputEPSG">EPSG</label>
                        <div class="controls">
                          <?php
                          // if (isset($semLocalizacao['checked']))
                          //   echo form_dropdown('inputEPSG', $inputEPSG, isset($inputEPSG_Selected) ? $inputEPSG_Selected : set_value('inputEPSG'), 'id="inputEPSG" class="input-small" disabled="disabled"');
                          // else
                            // echo form_dropdown('inputEPSG', $inputEPSG, isset($inputEPSG_Selected) ? $inputEPSG_Selected : set_value('inputEPSG'), 'id="inputEPSG" class="input-small"');                           ?>
                        </div>
                      </div>
                    </div>
                    -->
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="inputMunicipio">Município/UF:</label>
                    <div class="controls">
                      <?php

                        if (isset($semLocalizacao['checked'])) {
                          echo form_dropdown('dropdownMunicipio', $dropdownMunicipio, set_value('dropdownMunicipio', $id_municipio), 'id="dropdownMunicipio" class="input-medium" disabled="disabled"');
                          echo form_dropdown('dropdownUF', $dropdownUF, set_value('dropdownUF', $id_uf), 'id="dropdownUF" class="input-mini" disabled="disabled"');
                        } else {
                          echo form_dropdown('dropdownMunicipio', $dropdownMunicipio, set_value('dropdownMunicipio', $id_municipio), 'id="dropdownMunicipio" class="input-medium"');
                          echo form_dropdown('dropdownUF', $dropdownUF, set_value('dropdownUF', $id_uf), 'id="dropdownUF" class="input-mini"');
                        }

                        // echo form_input($inputMunicipio);
                        // echo form_input($inputUF);
                      ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="inputEndereco">Endereço:</label>
                    <div class="controls">
                      <?php echo form_input($inputEndereco); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="checkbox text-left">
                      <?php echo form_checkbox($semLocalizacao,'on'); ?>
                      Sem condições de indicar a localização do acidente no mapa
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2">
              2. Data e Hora do Acidente*
              <?php
                if (isset($hasOleo))
                  echo "</br><i style=\"font-size: 9px\">(Itens II e III do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)</i>"
              ?>
            </a>
          </div>
          <div id="collapse2" class="accordion-body collapse">
            <div class="accordion-inner">
              <div id="DataHoraObs" class="control-group">
                <div class="control-label">
                  <h5>
                    > Data e Hora da primeira observação:
                  </h5>
                </div> <br />
                <div class="row-fluid">
                  <div class="span6">
                    <label class="control-label" for="inputDataObs">Data:*</label>
                    <div class="controls">
                      <?php
                        echo form_input($inputDataObs);
                        echo form_dropdown('diaObsSemana', $diaObsSemana, set_value('diaObsSemana'),'id="diaObsSemana" disabled="disabled"');
                      ?>
                    </div>
                  </div>
                  <div class="span6">
                    <label class="control-label" for="inputHoraObs">Hora</label>
                    <div class="controls">
                      <?php echo form_input($inputHoraObs); ?>
                    </div>
                  </div>
                </div>
                <div id="divPeriodoObs" class="row-fluid">
                  <div class="span12">
                    <label class="control-label"> Período:* </label>
                    <div class="controls row-fluid">
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerObsMatu, 'obsMatutino', $PeriodoObs == 'obsMatutino' ? TRUE : FALSE); ?> Matutino
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerObsVesper, 'obsVespertino', $PeriodoObs == 'obsVespertino' ? TRUE : FALSE); ?> Vespertino
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerObsNotu, 'obsNoturno', $PeriodoObs == 'obsNoturno' ? TRUE : FALSE); ?> Noturno
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerObsMadru, 'obsMadrugada', $PeriodoObs == 'obsMadrugada' ? TRUE: FALSE); ?> Madrugada
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span12">
                    <div class="control-group">
                      <div class="controls">
                        <label class="checkbox text-left">
                          <?php echo form_checkbox($semDataObs,'on'); ?>
                          Sem informação sobre data e hora da primeira observação
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="DataHoraInci" class="control-group">
                <div class="control-label">
                  <h5>
                    > Data e Hora estimadas do Incidente:
                  </h5>
                </div> <br />
                <div class="row-fluid">
                  <div class="span6">
                    <label class="control-label" for="inputDataInci">Data:*</label>
                    <div class="controls">
                      <?php
                        echo form_input($inputDataInci);
                        echo form_dropdown('diaInciSemana', $diaInciSemana, set_value('diaInciSemana'),'id="diaInciSemana" disabled="disabled"');
                      ?>
                    </div>
                  </div>
                  <div class="span6">
                    <label class="control-label" for="inputHoraInci">Hora</label>
                    <div class="controls">
                      <?php echo form_input($inputHoraInci); ?>
                    </div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span12">
                    <label class="checkbox text-left">
                      <?php echo form_checkbox($dtFeriado,'on'); ?>
                      Feriado
                    </label>
                  </div>
                </div>
                <div id="divPeriodoInci" class="row-fluid">
                  <div class="span12">
                    <label class="control-label"> Período:* </label>
                    <div class="control-group">
                      <div class="controls row-fluid">
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerInciMatu, 'inciMatutino', $PeriodoInci == 'inciMatutino' ? TRUE: FALSE); ?> Matutino
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerInciVesper, 'inciVespertino', $PeriodoInci == 'inciVespertino' ? TRUE: FALSE); ?> Vespertino
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerInciNotu, 'inciNoturno', $PeriodoInci == 'inciNoturno' ? TRUE: FALSE); ?> Noturno
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <?php echo form_radio($PerInciMadru, 'inciMadrugada', $PeriodoInci == 'inciMadrugada' ? TRUE: FALSE); ?> Madrugada
                        </label>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span12">
                    <label class="checkbox text-left">
                      <?php echo form_checkbox($semDataInci,'on'); ?>
                      Sem informação sobre data e hora estimada
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse3">
              3. Origem do Acidente*
              <?php
                if (isset($hasOleo))
                  echo "</br><i style=\"font-size: 9px\">(Item I do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)</i>"
              ?>
            </a>
          </div>
          <div id="collapse3" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div id="tipoLocalizacao" class="span12">
                  <div style="display:none;">
                    <?php
                      if(isset($tipoLocalizacao)) {
                        foreach ($tipoLocalizacao as $id) {
                          echo '<span data-id="postTL">' . $id . '</span>';
                        }
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompOrigem">
                      Informações complementares:
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputCompOrigem); ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <?php echo form_checkbox($semOrigem,'on'); ?>
                    Sem informação sobre a origem do acidente
                  </label>
                </div>
              </div>
              <?php
                if  (isset($hasOleo)) {
                  echo '
                    <br />
                    <div class="row-fluid" data-oleo="true">
                      <h5>
                        > Identificação do navio ou instalação que originou o incidente
                      </h5>
                      <br />
                      <div class="control-group">
                        <label class="radio">';
                  if (isset($inputNomeNavio) and $inputNomeNavio['value'] != "") {
                    echo '  <input type="radio" id="navio" name="typeOfOrigin" value="navio" checked="checked"';
                  } else {
                    echo '  <input type="radio" id="navio" name="typeOfOrigin" value="navio"';
                  }
                  if (isset($semNavioInstalacao['checked'])) {
                    echo 'disabled="disabled"';
                  }
                  echo '/>';
                  echo '
                          &nbsp; Nome do navio:
                        </label>
                        <div class="controls" style="padding-left:23px;">';
                  echo form_input($inputNomeNavio);
                  echo  '
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="radio">';
                  if (isset($inputNomeInstalacao) and $inputNomeInstalacao['value'] != "") {
                    echo '  <input type="radio" id="instalacao" name="typeOfOrigin" value="instalacao" checked="checked"';
                  } else {
                    echo '  <input type="radio" id="instalacao" name="typeOfOrigin" value="instalacao"';
                  }
                  if (isset($semNavioInstalacao['checked'])) {
                    echo 'disabled="disabled"';
                  }
                  echo '/>';
                  echo '
                          &nbsp; Nome da instalação:
                        </label>
                        <div class="controls" style="padding-left:23px;">';
                  echo form_input($inputNomeInstalacao);
                  echo '
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group">
                        <div class="controls">
                          <label class="checkbox text-left">';
                  echo form_checkbox($semNavioInstalacao,'on');
                  echo '
                            Sem condições de informar
                          </label>
                        </div>
                      </div>
                    </div>';
                }
              ?>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse4">
              4. Tipo de Evento*
            </a>
          </div>
          <div id="collapse4" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div id="tipoEvento" class="span12">
                  <div style="display:none;">
                    <?php
                        if (isset($tipoEvento)) {
                          foreach ($tipoEvento as $id) {
                            echo '<span data-id="postTE">' . $id . '</span>';
                          }
                        }
                    ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompEvento">
                      Informações complementares:
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputCompEvento); ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="checkbox text-left">
                      <?php echo form_checkbox($semEvento, 'on'); ?>
                      Sem informação sobre o tipo do evento
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse5">
              5. Tipo de Produto*
              <?php
                if (isset($hasOleo))
                  echo "</br><i style=\"font-size: 9px\">(Item V do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002.)</i>"
              ?>
            </a>
          </div>
          <div id="collapse5" class="accordion-body collapse">
            <div class="accordion-inner">
              <div id="productsInfo" class="row-fluid">
                <div class="span12">
                  <div id="myTable"></div>
                  <div id="myTable2"></div>
                </div>
                <div class="span4">
                  <div class="control-group">
                    <span>&nbsp;</span>
                    <div class="controls">
                      <label class="checkbox text-left">
                      <?php echo form_checkbox($produtoNaoPerigoso, 'on'); ?> Não perigoso
                      </label>
                    </div>
                    <div class="controls">
                      <label class="checkbox text-left">
                      <?php echo form_checkbox($produtoNaoAplica, 'on'); ?> Não se aplica
                      </label>
                    </div>
                    <div class="controls">
                      <label class="checkbox text-left">
                      <?php echo form_checkbox($produtoNaoEspecificado, 'on'); ?> Não especificado na lista ONU
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <input id="semProduto" type="checkbox" name="semProduto"> Sem informação sobre o tipo do produto
                  </label>
                </div>
              </div>

              <?php
                if (isset($hasOleo)) {
                  echo '
                    <br />
                    <div class="row-fluid" data-oleo="true">
                      <h5>
                        > Substância Descarregada
                      </h5>
                      <br />
                      <div class="control-group">
                        <label class="control-label span4" for="inputTipoSubstancia">
                          Tipo de Substância:
                        </label>
                        <div class="controls">';
                  if (isset($inputTipoSubstancia)) {
                    echo form_input($inputTipoSubstancia);
                  }
                  echo '
                        </div>
                      </div>
                      <div class="control-group">
                        <div class="controls">
                          <label class="control-label" for="inputValorEstimado">
                            Volume estimado em &nbsp;';
                  if (isset($inputVolumeEstimado)) {
                    echo form_input($inputVolumeEstimado);
                  }
                  echo     '     m3
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group">
                        <div class="controls">
                          <label class="checkbox text-left">';
                  echo form_checkbox($semSubstancia);
                  echo '
                            Sem condições de informar
                          </label>
                        </div>
                      </div>
                    </div>';
                }
              ?>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse6">
              6. Detalhes do Acidente*
              <?php
                if (isset($hasOleo))
                  echo "</br><i style=\"font-size: 9px\">(Itens VI e VII do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)</i>"
              ?>
            </a>
          </div>
          <div id="collapse6" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="inputCausaProvavel"> Causa Provável do Acidente:*</label>
                    <div class="controls">
                    <?php echo form_textarea($inputCausaProvavel); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span4" style="align:center;">
                  <label class="checkbox text-left">
                    <?php echo form_checkbox($semCausa,'on'); ?>
                    Sem condições de informar
                  </label>
                </div>
              </div>
              <br />
              <div class="row-fluid">
                <label class="control-label"> Situação Atual da Descarga: </label>
                <div class="controls row-fluid">
                  <div class="span3">
                    <label class="radio">
                      <?php echo form_radio($SitParal); ?> Paralisada
                    </label>
                  </div>
                  <div class="span3">
                    <label class="radio">
                      <?php echo form_radio($SitNaoParal); ?> Não foi paralisada
                    </label>
                  </div>
                  <div class="span3">
                    <label class="radio">
                      <?php echo form_radio($SitSemCondi); ?> Sem condições de informar
                    </label>
                  </div>
                  <div class="span3">
                    <label class="radio">
                      <?php echo form_radio($SitNaoSeApl); ?> <strong> Não se aplica </strong>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse7">
              7. Danos Identificados*
            </a>
          </div>
          <div id="collapse7" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div id="tipoDanoIdentificado" class="span12">
                  <div style="display:none;">
                    <?php
                        if(isset($tipoDanoIdentificado)) {
                          foreach ($tipoDanoIdentificado as $id) {
                            echo '<span data-id="postTDI">' . $id . '</span>';
                          }
                        }
                    ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompDano">
                      Informações complementares:
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputCompDano); ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputDesDanos">
                      Descrição dos Danos
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputDesDanos); ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <?php echo form_checkbox($semDanos,'on'); ?>
                    Sem informação sobre os danos
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse8">
              8. Identificação Empresa/Órgão Responsável*
            </a>
          </div>
          <div id="collapse8" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="span6">
                  <div class="control-group">
                    <label class="control-label" for="inputResponsavel">
                      Nome:*
                    </label>
                    <div class="controls">
                      <?php echo form_input($inputResponsavel); ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="inputCPFCNPJ">CPF/CNPJ: </label>
                    <div class="controls">
                      <?php echo form_input($inputCPFCNPJ); ?>
                    </div>
                  </div>
                </div>
                <div class="span6">
                  <div class="control-group">
                    <label class="control-label" for="inputLicenca">
                      Licença ambiental:
                    </label>
                    <div class="controls">
                      <?php
                        if (isset($semResponsavel['checked']))
                          echo form_dropdown('slctLicenca', $slctLicenca, set_value('slctLicenca', $id_licenca), 'id="slctLicenca" class="input" disabled="disabled"');
                        else
                          echo form_dropdown('slctLicenca', $slctLicenca, set_value('slctLicenca', $id_licenca), 'id="slctLicenca" class="input"');
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <div class="controls">
                      <label class="checkbox text-left">
                        <?php echo form_checkbox($semResponsavel,'on'); ?>
                        Sem informação sobre a empresa
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse9">
              9. Instituição/Empresa Atuando no Local*
            </a>
          </div>
          <div id="collapse9" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div id="tipoInstituicaoAtuando" class="span12">
                  <div style="display:none;">
                    <?php
                        if (isset($instituicaoAtuandoLocal)) {
                          foreach ($instituicaoAtuandoLocal as $id) {
                            echo '<span data-id="postIAL">' . $id . '</span>';
                          }
                        }
                    ?>
                  </div>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="inputInfoInstituicaoNome">Nome do Responsável no Local/Telefone:</label>
                <div class="controls">
                  <?php echo form_input($inputInfoInstituicaoNome); ?>
                  <?php echo form_input($inputInfoInstituicaoTelefone); ?>
                </div>
              </div>
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompInstituicao">
                      Informações complementares:
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputCompInstituicao); ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <?php echo form_checkbox($semInstituicao,'on'); ?>
                    Sem informação sobre as instituções.
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse10">
              10. Ações Iniciais Tomadas
              <?php
                if (isset($hasOleo))
                  echo "</br><i style=\"font-size: 9px\">(Item VIII do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)</i>"
              ?>
            </a>
          </div>
          <div id="collapse10" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="span12">
                  <label class="control-label span8"> Existência de Plano de Emergência ou Similar: </label>
                  <div class="control-group span4">
                    <div class="controls">
                      <div class="span6">
                        <label class="radio">
                          <?php echo form_radio($planoEmergNao); ?> Não
                        </label>
                      </div>
                      <div class="span6">
                        <label class="radio">
                          <?php echo form_radio($planoEmergSim); ?> Sim
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <?php echo form_input($planoAcionado); ?>
                    Acionado Plano Individual de Emergência
                  </label>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="controls">
                    <label class="control-label checkbox span6" for="inputMedidasTomadas">
                      <?php echo form_input($outrasMedidas); ?>
                      Foram tomadas outras providências a saber:
                    </label>
                    <div class="span6">
                      <?php echo form_textarea($inputMedidasTomadas); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <?php echo form_checkbox($semProcedimentos, 'on'); ?>
                    Sem evidência de ação ou providência até o momento
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse12">
              11. Informações Gerais Sobre a Ocorrência
              <?php
                if (isset($hasOleo))
                  echo "</br><i style=\"font-size: 9px\">(Item XI do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002.)</i>"
              ?>
            </a>
          </div>
          <div id="collapse12" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputDesOcorrencia">
                      Descrição geral da Ocorrência
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputDesOcorrencia); ?>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputDesObs">
                      Outras Informações Julgadas Úteis
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputDesObs); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if($this->authldap->is_authenticated()) {
            echo '
            <div class="accordion-group">
              <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse11">
                  12. Identificação do Comunicante';
                    if (isset($hasOleo))
                      echo "</br><i style=\"font-size: 9px\">(Item X do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)</i>";
            echo '
                </a>
              </div>
              <div id="collapse11" class="accordion-body collapse">
                <div class="accordion-inner">
                  <div class="row-fluid">
                    <div class="controls span12">
                      <label class="control-label span5" for="inputNomeInformante">Nome Completo:
                      </label>
                      <div class="span6">';
                        echo form_input($inputNomeInformante);
            echo '
                      </div>
                    </div>';
                      if (isset($hasOleo) && isset($isServIBAMA)) {
            echo '
                          <div class="controls span12">
                            <label class="control-label span5" for="inputFuncaoNavio">Função navio ou instalação:
                            </label>
                            <div class="span6">';
                        echo form_input($inputFuncaoNavio);
              echo '
                            </div>
                          </div>
                        ';
                      }
              echo '
                    <div class="controls span12">
                      <label class="control-label span5" for="inputTelInformante">Telefone de Contato:
                      </label>
                      <div class="span6">';
                        echo form_input($inputTelInformante);
              echo '
                      </div>
                    </div>
                    <div class="controls span12">
                      <label class="control-label span5" for="inputEmailInformante">Email de Contato:
                      </label>
                      <div class="span6">';
                        echo form_input($inputEmailInformante);
              echo '
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>';
          }
        ?>
        <?php
          if($this->authldap->is_authenticated()) {
            echo '
              <div id="servIBAMA" class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse13">
                    13. Fonte da Informação
                  </a>
                </div>
                <div id="collapse13" class="accordion-body collapse">
                  <div class="accordion-inner">
                    <div class="row-fluid">
                      <div id="tipoFonteInformacao" class="span12">
                        <div style="display:none;">';
            foreach ($tipoFonteInformacao as $id) {
              echo
                          '<span data-id="postTFI">' . $id . '</span>';
            }
            echo '
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            ';
          }
        ?>
        <span style="font-size:12px; color:grey;">Campos marcados com ' <b>*</b> ' são de preenchimento obrigatório.</span>
      </div>

      <div class="checkbox" style="display:none;">

        <?php
          if(isset($hasOleo)) {
            echo form_input($hasOleo);
          }

          if(isset($isServIBAMA)) {
            echo form_input($isServIBAMA);
          }
        ?>

      </div>

    <?php echo form_close(); ?>
  </div>
</div>
