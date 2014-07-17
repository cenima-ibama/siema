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
          <div class="checkbox">
        <div class="row-fluid">
          <div class="span12">
            <label class="checkbox pull-right" for="validado">
              <?php echo form_checkbox($validado); ?>
              Validado
            </label>
          </div>
        </div>
      </div>
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
                      <label class="control-label" for="inputLat">Latitude(y)/Longitude(x):*</label>
                      <div class="controls">
                        <?php echo form_input($inputLat); ?>
                        <?php echo form_input($inputLng); ?>
                      </div>
                    </div>

                    <div class="span12">
                      <label class="checkbox" for="oceano">
                        <?php echo form_checkbox($oceano); ?>
                        Oceano
                      </label>
                      <label id="spanBaciaSed" class="checkbox" for="dropdownBaciaSedimentar"
                        <?php
                          if (!isset($oceano['checked']))
                            echo ' style="display:none;"';
                        ?>
                      >
                        &nbsp; Bacia sedimentar
                        <?php
                          if (isset($oceano['checked'])) {
                            echo form_dropdown('dropdownBaciaSedimentar', $dropdownBaciaSedimentar, set_value('dropdownBaciaSedimentar',$id_bacia), 'id="dropdownBaciaSedimentar" class="input"');
                          } else {
                            echo form_dropdown('dropdownBaciaSedimentar', $dropdownBaciaSedimentar, set_value(""), 'id="dropdownBaciaSedimentar" class="input"');

                          }
                          // echo form_input($inputBaciaSed);
                        ?>
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
                    <label class="control-label" for="inputMunicipio">UF/Município:</label>
                    <div class="controls">
                      <?php

                        if (isset($semLocalizacao['checked'])) {
                          echo form_dropdown('dropdownUF', $dropdownUF, set_value('dropdownUF', $id_uf), 'id="dropdownUF" class="input-small" disabled="disabled"');
                          echo form_dropdown('dropdownMunicipio', $dropdownMunicipio, set_value('dropdownMunicipio', $id_municipio), 'id="dropdownMunicipio" class="input-medium" disabled="disabled"');
                        } else {
                          echo form_dropdown('dropdownUF', $dropdownUF, set_value('dropdownUF', $id_uf), 'id="dropdownUF" class="input-small"');
                          echo form_dropdown('dropdownMunicipio', $dropdownMunicipio, set_value('dropdownMunicipio', $id_municipio), 'id="dropdownMunicipio" class="input-medium"');
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
              <!-- <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="checkbox text-left">
                      <?php echo form_checkbox($semLocalizacao,'on'); ?>
                      Sem condições de indicar a localização do acidente no mapa
                    </label>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2">
              2. Data e hora do acidente*
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
                    Data e hora da primeira observação:
                  </h5>
                </div> <br />
                <div class="row-fluid">
                  <div class="span6">
                    <label class="control-label" for="inputDataObs">Data:*</label>
                    <div class="controls">
                      <?php
                        echo form_input($inputDataObs);
                        echo form_dropdown('diaObsSemana', $diaObsSemana, set_value('diaObsSemana', "0"),'id="diaObsSemana" disabled="disabled" style="-moz-appearance:none; text-indent:0.01px; text-overflow:\'\';"');
                      ?>
                    </div>
                  </div>
                  <div class="span6">
                    <label class="control-label" for="inputHoraObs">Hora:</label>
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
              <hr>
              <div id="DataHoraInci" class="control-group">
                <div class="control-label">
                  <h5>
                    Data e hora estimadas do incidente:
                  </h5>
                </div> <br />
                <div class="row-fluid">
                  <div class="span6">
                    <label class="control-label" for="inputDataInci">Data:*</label>
                    <div class="controls">
                      <?php
                        echo form_input($inputDataInci);
                        echo form_dropdown('diaInciSemana', $diaInciSemana, set_value('diaInciSemana', "0"),'id="diaInciSemana" disabled="disabled" style="-moz-appearance:none; text-indent:0.01px; text-overflow:\'\';"');
                      ?>
                    </div>
                  </div>
                  <div class="span6">
                    <label class="control-label" for="inputHoraInci">Hora:</label>
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
              3. Origem do acidente*
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
                    <hr>
                    <div class="row-fluid" data-oleo="true">
                      <h5>
                        Identificação do navio ou instalação que originou o incidente:
                      </h5>
                      <br />
                      <div class="control-group">
                        <label class="radio">';
                  // if (isset($inputNomeNavio) and $inputNomeNavio['value'] != "") {
                  //   echo '  <input type="radio" id="navio" name="typeOfOrigin" value="navio" checked="checked"';
                  // } else {
                  //   echo '  <input type="radio" id="navio" name="typeOfOrigin" value="navio"';
                  // }
                  // if (isset($semNavioInstalacao['checked'])) {
                  //   echo 'disabled="disabled"';
                  // }
                  // echo '/>';
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
                  // if (isset($inputNomeInstalacao) and $inputNomeInstalacao['value'] != "") {
                  //   echo '  <input type="radio" id="instalacao" name="typeOfOrigin" value="instalacao" checked="checked"';
                  // } else {
                  //   echo '  <input type="radio" id="instalacao" name="typeOfOrigin" value="instalacao"';
                  // }
                  // if (isset($semNavioInstalacao['checked'])) {
                  //   echo 'disabled="disabled"';
                  // }
                  // echo '/>';
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
              4. Tipo de evento*
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
              5. Tipo de produto*
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
                  <h5 style="font-style:italic;"> Produtos cadastrados na lista ONU</h5>
                  <div id="productOnuTable" name="productOnuTable"></div>
                  <hr>
                  <h5 style="font-style:italic;"> Produtos NÃO cadastrados na lista ONU</h5>
                  <div id="productOutroTable" name="productOutroTable"></div>
                </div>
                <hr>
                <div class="span4">
                  <div class="control-group">
                    <div class="controls">
                      <label class="checkbox text-left">
                      <?php echo form_checkbox($produtoNaoPerigoso, 'on'); ?> Não classificado
                      </label>
                    </div>
                    <div class="controls">
                      <label class="checkbox text-left">
                      <?php echo form_checkbox($produtoNaoAplica, 'on'); ?> Não se aplica
                      </label>
                    </div>
                    <div class="controls">
                      <label class="checkbox text-left">
                      <?php echo form_checkbox($produtoNaoEspecificado, 'on'); ?> Não especificado
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <br/>
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
                    <hr>
                    <div class="row-fluid" data-oleo="true">
                      <h5>
                        Substância descarregada:
                      </h5>
                      <br />
                      <div class="control-group">
                        <label class="control-label span4" for="inputTipoSubstancia">
                          Tipo de substância:
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
              6. Detalhes do acidente*
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
                    <label class="control-label" for="inputCausaProvavel"> Causa provável do acidente:*</label>
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
              <hr>
              <?php 
              if (isset($hasOleo)){
                echo '
                  <div class="row-fluid" >
                    <label class="control-label"> Situação atual da descarga: </label>
                    <div class="controls row-fluid">
                      <div class="span3">
                        <label class="radio">';
                          echo form_radio($SitParal); 
                          echo ' Paralisada
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">';
                          echo form_radio($SitNaoParal); 
                          echo ' Não foi paralisada
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">';
                          echo form_radio($SitSemCondi); 
                          echo 'Sem condições de informar
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">';
                          echo form_radio($SitNaoSeApl);
                          echo 'Não se aplica
                        </label>
                      </div>
                    </div>
                  </div>
                ';
              }


              ?>

            </div>
          </div>
        </div>

        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse7">
              7. Ocorrências e/ou ambientes atingidos*
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
              <!-- <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputDesDanos">
                      Descrição dos danos:
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputDesDanos); ?>
                  </div>
                </div>
              </div> -->
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <?php echo form_checkbox($semDanos,'on'); ?>
                    Sem informação sobre ocorrências e/ou ambientes atingidos
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse8">
              8. Identificação da empresa/responsável*
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
                        Sem informação sobre a empresa/responsável
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
              9. Instituição/empresa atuando no local*
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
              <div class="control-group">
                <label class="control-label" for="inputInfoInstituicaoNome">Nome do responsável no local/telefone:</label>
                <div class="controls">
                  <?php echo form_input($inputInfoInstituicaoNome); ?>
                  <?php echo form_input($inputInfoInstituicaoTelefone); ?>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <?php echo form_checkbox($semInstituicao,'on'); ?>
                    Sem informação sobre as instituição/empresa atuando no local
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse10">
              10. Ações iniciais tomadas
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
                  <label class="control-label span8"> Existência de Plano de Emergência Individual ou similar: </label>
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
                    Acionado Plano de Emergência Individual
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
                    Sem informação sobre existência/acionamento de Plano de Emergência individual
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse12">
              11. Informações gerais sobre a ocorrência
              <?php
                if (isset($hasOleo))
                  echo "</br><i style=\"font-size: 9px\">(Item XI do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002.)</i>"
              ?>
            </a>
          </div>
          <div id="collapse12" class="accordion-body collapse">
            <div class="accordion-inner">
              <!-- <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputDesOcorrencia">
                      Descrição geral da ocorrência:
                    </label>
                  </div>
                  <div class="controls">
                    <?php echo form_textarea($inputDesOcorrencia); ?>
                  </div>
                </div>
              </div> -->
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputDesObs">
                      Outras informações julgadas úteis:
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
            echo '
            <div class="accordion-group">
              <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse11">
                  12. Identificação do comunicante';
                    if (isset($hasOleo))
                      echo "</br><i style=\"font-size: 9px\">(Item X do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)</i>";
            echo '
                </a>
              </div>
              <div id="collapse11" class="accordion-body collapse">
                <div class="accordion-inner">
                  <div class="row-fluid">
                    <div class="controls span12">
                      <label class="control-label span5" for="inputNomeInformante">Nome completo:
                      </label>
                      <div class="span6">';
                        echo form_input($inputNomeInformante);
            echo '
                      </div>
                    </div>';
                      // if (isset($hasOleo) && isset($isServIBAMA)) {
            echo '
                    <div class="controls span12">
                      <label class="control-label span5" for="inputInstEmp">Instituição / Empresa:
                      </label>
                      <div class="span6">';
                  echo form_input($inputInstEmp);
            echo '
                      </div>
                    </div>
                    ';
            if (isset($hasOleo)) {
              echo '
                      <div class="controls span12">
                        <label class="control-label span5" for="inputCargoFunc">Cargo / Função navio ou instalação:
                        </label>
                        <div class="span6">';
                    echo form_input($inputCargoFunc);
              echo '
                        </div>
                      </div>
                      ';

              echo '
                      <div class="controls span12">
                        <label class="control-label span5" for="inputCargoFunc">Cargo / Função:
                        </label>
                        <div class="span6">';
                    echo form_input($inputCargoFunc);
              echo '
                        </div>
                      </div>
                      ';
            }
                      // }
            echo '
                    <div class="controls span12">
                      <label class="control-label span5" for="inputTelInformante">Telefone de contato:
                      </label>
                      <div class="span6">';
                        echo form_input($inputTelInformante);
            echo '
                      </div>
                    </div>
                    <div class="controls span12">
                      <label class="control-label span5" for="inputEmailInformante">Email de contato:
                      </label>
                      <div class="span6">';
                        echo form_input($inputEmailInformante);
            echo '
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>'
          
        ?>

<script type="text/javascript">
function showFileName(inputFile) {
    inputFile.offsetParent.getElementsByClassName('fileName')[0].innerHTML = inputFile.value.replace(/\\/g, '/').split('/').pop();
}
</script>



        <?php
          if($this->authldap->is_authenticated()) {
            echo '
              <div id="servIBAMA" class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse13">
                    13. Fonte da informação
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


<!--upload file button without strap-->
        <?php 
            echo '
            <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse14">';
                    if ($this->authldap->is_authenticated())
                      echo '14. Adicionar arquivos';
                    else 
                      echo '13.Adicionar arquivos';
                    echo 
                    '</a>
                  </div>
                  <div id="collapse14" class="accordion-body collapse">
                    <div class="accordion-inner">
                      <div class="row-fluid">
                        <div class="control-group">
                          <div class="input-append">
                            <style type="text/css">                                           
                            .customFileInput input {
                                position: absolute;
                                visibility: hidden;
                                right: 10px;

                            </style>                     

                            <form class="btn" method="post" enctype="multipart/form-data" />
                                <label class="customFileInput">
                                    <div class="btn">Escolher um arquivo</div>
                                    <div class="fileName" style="position:absolute; top: 1em; left: 13em"></div>
                                    <a class="btn" id="uploadButton" href="#" style="position: absolute; right: 2em;" > Upload</a> 
                                    <input type="hidden" id="sendNroComunicado" value="" />
                                    <input type="file" name="userfile" onchange="showFileName(this)" class="boxname" />
                                    <br /><small> *jpg, doc, pdf, xls, até 5MB</small>

                                </label>

                            </form>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>'          
                  ;
      ?>


        
      </div>
      <span style="font-size:12px; color:grey;">Campos marcados com ' <b>*</b> ' são de preenchimento obrigatório.</span>
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
