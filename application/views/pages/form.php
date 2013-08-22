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

    <?php echo form_open('home/validate', array('id' => 'formAcidentes', 'name' => 'formAcidentes')); ?>

      <div class="accordion" id="accordion2">
        <div class="alert alert-block alert-error fade in" style="display:none;" id="error_box"></div>
        <div class="accordion-heading">
          <br />
          <h4 class="text-right" style="color:#0088cc;padding: 0 10px 0 0;">
            <strong>Número do Comunicado:</strong>
            <span id="nroComunicado" name="nroComunicado"><?php
              if (isset($comunicado)) {
                echo set_value('comunicado');
              }
            ?></span>

          </h4>
          <input type="hidden" id="comunicado" name="comunicado"
            <?php
              if (isset($comunicado)) {
                echo ' value="' . set_value('comunicado') . '" ';
              }
            ?>
          />
          <br />
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse1">
              1. Localização
            </a>
          </div>
          <div id="collapse1" class="accordion-body collapse in">
            <div class="accordion-inner">
              <div class="row-fluid text-left">
                <div class="span6">
                  <div id="minimap"></div>
                </div>
                <br />
                <div class="span6">
                  <div class="control-group span12">
                    <div class="span8">
                      <label class="control-label" for="inputLat">Latitude(y)/Longitude(x)</label>
                      <div class="controls">
                        <input id="inputLat" class="input-small" type="text" name="inputLat" placeholder="Latitude"
                          <?php
                            if ($typeOfForm == 'load') {
                              if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                                echo 'disabled="disabled"';
                              else
                                if(isset($inputLat))
                                  echo 'value="' . $inputLat . '"';

                            } else {
                              echo 'value="' . set_value('inputLat') . '"';
                              if(set_value('semLocalizacao') == "on")
                                echo 'disabled="disabled"';
                            }
                          ?>
                        >
                        <input id="inputLng" class="input-small" type="text" name="inputLng" placeholder="Longitude"
                          <?php
                            if ($typeOfForm == 'load') {
                              if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                                echo 'disabled="disabled"';
                              else
                                if(isset($inputLng))
                                  echo 'value="' . $inputLng . '"';

                            } else {
                              echo 'value="' . set_value('inputLng') . '"';
                              if(set_value('semLocalizacao') == "on")
                                echo 'disabled="disabled"';
                            }
                          ?>
                        >
                      </div>
                    </div>
                    <div class="span4">
                      <div class="control-group">
                        <label class="control-label" for="inputEPSG">EPSG</label>
                        <div class="controls">
                          <select id="inputEPSG" name="inputEPSG" class="input-small"
                            <?php
                              if ($typeOfForm == 'load') {
                                if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                                  echo 'disabled="disabled"';
                                else
                                  if (!isset($inputEPSG))
                                    echo ' disabled="disabled" ';
                              } else {
                                echo ' disabled="disabled" ';
                              }
                            ?>
                          >
                            <option selected="selected" value="4674"
                              <?php
                                if (isset($inputEPSG)) {
                                  if( $inputEPSG == "4674")
                                    echo ' selected="selected" ';
                                }
                              ?>
                            >SIRGAS 2000 - 4674</option>
                            <option value="900913"
                              <?php
                                if (isset($inputEPSG)) {
                                  if( $inputEPSG == "900913")
                                    echo ' selected="selected" ';
                                }
                              ?>
                            >Google - 900913</option>
                            <option value="4326"
                              <?php
                                if (isset($inputEPSG)) {
                                  if( $inputEPSG == "4326")
                                    echo ' selected="selected" ';
                                }
                              ?>
                            >WGS84 - 4326</option>
                            <option value="4291"
                              <?php
                                if (isset($inputEPSG)) {
                                  if( $inputEPSG == "4291")
                                    echo ' selected="selected" ';
                                }
                              ?>
                            >SAD69 - 4291</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="inputMunicipio">Município/UF:</label>
                    <div class="controls">
                      <input id="inputMunicipio" class="input-small" type="text" name="inputMunicipio" placeholder="Nome"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                              echo 'disabled="disabled"';
                            else
                              if (isset($inputMunicipio))
                                echo 'value="' . $inputMunicipio . '"';
                          } else {
                            echo 'value="' . set_value('inputMunicipio') . '"';
                            if(set_value('semLocalizacao') == "on")
                              echo 'disabled="disabled"';
                          }
                        ?>
                      >
                      <input id="inputUF" class="input-micro" type="text" name="inputUF" placeholder="UF"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                              echo 'disabled="disabled"';
                            else
                              if (isset($inputUF))
                                echo 'value="' . $inputUF . '"';
                          } else {
                            echo 'value="' . set_value('inputUF') . '"';
                            if(set_value('semLocalizacao') == "on")
                              echo 'disabled="disabled"';
                          }
                        ?>
                      >
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="inputEndereco">Endereço:</label>
                    <div class="controls">
                      <input id="inputEndereco" class="input-large" type="text" name="inputEndereco" placeholder=""
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                              echo 'disabled="disabled"';
                            else
                              if (isset($inputEndereco))
                                echo 'value="' . $inputEndereco . '"';
                          } else {
                            echo 'value="' . set_value('inputEndereco') . '"';
                            if(set_value('semLocalizacao') == "on")
                              echo 'disabled="disabled"';
                          }
                        ?>
                      >
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="checkbox text-left">
                      <input id="semLocalizacao" type="checkbox" name="semLocalizacao"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                              echo 'disabled="disabled"';
                          } else {
                            if (set_value('semLocalizacao') == "on")
                              echo 'checked="checked"';
                          }
                        ?>
                      >
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
              2. Data e Hora do Acidente
            </a>
          </div>
          <div id="collapse2" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="control-group">
                <div class="control-label">
                  <h5>
                    > Data e Hora da primeira observação:
                  </h5>
                </div> <br />
                <div class="row-fluid">
                  <div class="span6">
                    <label class="control-label" for="inputDataObs">Data:</label>
                    <div class="controls">
                      <input id="inputDataObs" class="input-medium" type="datetime" name="inputDataObs" placeholder="DD/MM/AAAA"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                              echo 'disabled="disabled"';
                            else
                              if (isset($inputDataObs))
                                echo 'value="' . $inputDataObs . '"';
                          } else {
                            echo 'value="' . set_value('inputDataObs') . '"';
                            if(set_value('semDataObs') == "on")
                              echo 'disabled="disabled"';
                          }
                        ?>
                      >
                    </div>
                  </div>
                  <div class="span6">
                    <label class="control-label" for="inputHoraObs">Hora</label>
                    <div class="controls">
                      <input id="inputHoraObs" class="input-medium" type="text" name="inputHoraObs" placeholder="HH:MM"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semLocalizacao) && ($semLocalizacao == "on"))
                              echo 'disabled="disabled"';
                            else
                              if (isset($inputHoraObs))
                                echo 'value="' . $inputHoraObs . '"';
                          } else {
                            echo 'value="' . set_value('inputHoraObs') . '"';
                            if(set_value('semDataObs') == "on")
                              echo 'disabled="disabled"';
                          }
                        ?>
                      >
                    </div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span12">
                    <label class="control-label"> Período: </label>
                    <div class="controls row-fluid">
                      <div class="span3">
                        <label class="radio">
                          <input id="PerObsMatu" type="radio" name="PeriodoObs" value="obsMatutino" checked
                            <?php
                              if ($typeOfForm == 'load') {
                                if(isset($semDataObs) && ($semDataObs == "on"))
                                  echo 'disabled="disabled"';
                              } else {
                                if(set_value('semDataObs') == "on")
                                   echo 'disabled="disabled"';
                              }
                            ?>
                          > Matutino
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <input id="PerObsVesper" type="radio" name="PeriodoObs" value="obsVespertino"
                            <?php
                              if ($typeOfForm == 'load') {
                                if(isset($semDataObs) && ($semDataObs == "on"))
                                  echo 'disabled="disabled"';
                                else
                                  if ($PeriodoObs == "obsVespertino")
                                    echo 'checked';
                              } else {
                                if (set_value('PeriodoObs') == "obsVespertino")
                                  echo 'checked';
                                if(set_value('semDataObs') == "on")
                                  echo 'disabled="disabled"';
                              }
                            ?>
                          > Vespertino
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <input id="PerObsNotu" type="radio" name="PeriodoObs" value="obsNoturno"
                            <?php
                              if ($typeOfForm == 'load') {
                                if(isset($semDataObs) && ($semDataObs == "on"))
                                  echo 'disabled="disabled"';
                                else
                                  if ($PeriodoObs == "obsNoturno")
                                    echo 'checked';
                              } else {
                                if (set_value('PeriodoObs') == "obsNoturno")
                                  echo 'checked';
                                if(set_value('semDataObs') == "on")
                                  echo 'disabled="disabled"';
                              }
                            ?>
                          > Noturno
                        </label>
                      </div>
                      <div class="span3">
                        <label class="radio">
                          <input id="PerObsMadru" type="radio" name="PeriodoObs" value="obsMadrugada"
                            <?php
                              if ($typeOfForm == 'load') {
                                if(isset($semDataObs) && ($semDataObs == "on"))
                                  echo 'disabled="disabled"';
                                else
                                  if ($PeriodoObs == "obsMadrugada")
                                    echo 'checked';
                              } else {
                                if (set_value('PeriodoObs') == "obsMadrugada")
                                  echo 'checked';
                                if(set_value('semDataObs') == "on")
                                  echo 'disabled="disabled"';
                              }
                            ?>
                          > Madrugada
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
                          <input id="semDataObs" type="checkbox" name="semDataObs"
                            <?php
                              if ($typeOfForm == 'load') {
                                if(isset($semDataObs) && ($semDataObs == "on"))
                                  echo 'disabled="disabled"';
                              } else {
                                if (set_value('semDataObs') == "on")
                                  echo 'checked="checked"';
                              }
                            ?>
                          > Sem informação sobre data e hora da primeira observação
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="control-group">
                <div class="control-label">
                  <h5>
                    > Data e Hora estimadas do Incidente:
                  </h5>
                </div> <br />
                <div class="row-fluid">
                  <div class="span6">
                    <label class="control-label" for="inputDataInic">Data:</label>
                    <div class="controls">
                      <input id="inputDataInic" class="input-medium" type="date" name="inputDataInic" placeholder="DD/MM/AAAA"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semDataInic) && ($semDataInic == "on"))
                              echo 'disabled="disabled"';
                            else
                              if (isset($inputDataInic))
                                echo 'value="' . $inputDataInic . '"';
                          } else {
                            echo 'value="' . set_value('inputDataInic') . '"';
                            if(set_value('semDataInic') == "on")
                              echo 'disabled="disabled"';
                          }
                        ?>
                      >
                    </div>
                  </div>
                  <div class="span6">
                    <label class="control-label" for="inputHoraInic">Hora</label>
                    <div class="controls">
                      <input id="inputHoraInic" class="input-medium" type="text" name="inputHoraInic" placeholder="HH:MM"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semDataInic) && ($semDataInic == "on"))
                              echo 'disabled="disabled"';
                            else
                              if (isset($inputHoraInic))
                                echo 'value="' . $inputHoraInic . '"';
                          } else {
                            echo 'value="' . set_value('inputHoraInic') . '"';
                            if(set_value('semDataInic') == "on")
                              echo 'disabled="disabled"';
                          }
                        ?>
                      >
                    </div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span12">
                    <label class="control-label"> Período: </label>
                    <div class="control-group">
                      <div class="controls row-fluid">
                        <div class="span3">
                          <label class="radio">
                            <input id="PerInicMatu" type="radio" name="PeriodoInic" value="inicMatutino" checked
                              <?php
                                if ($typeOfForm == 'load') {
                                  if(isset($semDataInic) && ($semDataInic == "on"))
                                    echo 'disabled="disabled"';
                                } else {
                                  if(set_value('semDataInic') == "on")
                                     echo 'disabled="disabled"';
                                }
                              ?>
                            > Matutino
                          </label>
                        </div>
                        <div class="span3">
                          <label class="radio">
                            <input id="PerInicVesper" type="radio" name="PeriodoInic" value="inicVespertino"
                              <?php
                                    $this->firephp->log(set_value('PeriodoInic'));
                                if ($typeOfForm == 'load') {
                                  if(isset($semDataInic) && ($semDataInic == "on"))
                                    echo 'disabled="disabled"';
                                  else {
                                    if ($PeriodoInic == "inicVespertino")
                                      echo 'checked';}
                                } else {
                                  if (set_value('PeriodoInic') == "inicVespertino")
                                    echo 'checked';
                                  if(set_value('semDataInic') == "on")
                                    echo 'disabled="disabled"';
                                }
                              ?>
                            > Vespertino
                          </label>
                        </div>
                        <div class="span3">
                          <label class="radio">
                            <input id="PerInicNotu" type="radio" name="PeriodoInic" value="inicNoturno"
                              <?php
                                if ($typeOfForm == 'load') {
                                  if(isset($semDataInic) && ($semDataInic == "on"))
                                    echo 'disabled="disabled"';
                                  else
                                    if ($PeriodoInic == "inicNoturno")
                                      echo 'checked';
                                } else {
                                  if (set_value('PeriodoInic') == "inicNoturno")
                                    echo 'checked';
                                  if(set_value('semDataInic') == "on")
                                    echo 'disabled="disabled"';
                                }
                              ?>
                            > Noturno
                          </label>
                        </div>
                        <div class="span3">
                          <label class="radio">
                            <input id="PerInicMadru" type="radio" name="PeriodoInic" value="inicMadrugada"
                              <?php
                                if ($typeOfForm == 'load') {
                                  if(isset($semDataInic) && ($semDataInic == "on"))
                                    echo 'disabled="disabled"';
                                  else
                                    if ($PeriodoInic == "inicMadrugada")
                                      echo 'checked';
                                } else {
                                  if (set_value('PeriodoInic') == "inicMadrugada")
                                    echo 'checked';
                                  if(set_value('semDataInic') == "on")
                                    echo 'disabled="disabled"';
                                }
                              ?>
                            > Madrugada
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row-fluid">
                  <div class="span12">
                    <label class="checkbox text-left">
                      <input id="semDataInic" type="checkbox" name="semDataInic"
                        <?php
                          if ($typeOfForm == 'load') {
                            if(isset($semDataInic) && ($semDataInic == "on"))
                              echo 'disabled="disabled"';
                          } else {
                          if (set_value('semDataInic') == "on")
                            echo 'checked="checked"';
                          }
                        ?>
                      > Sem informação sobre data e hora estimada
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
              3. Origem do Acidente
            </a>
          </div>
          <div id="collapse3" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div id="tipoLocalizacao" class="span12">
                  <div style="display:none;">
                    <?php
                      echo $tipoLocalizacao;
                      foreach ($tipoLocalizacao as $id) {
                        echo '<span data-id="postTL">' . $id . '</span>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <!-- <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="inputOrigemOutro"> Outro:</label>
                    <div class="controls">
                      <input id="inputOrigemOutro" class="input-large" type="text" name="inputOrigemOutro"
                        <?php
                          echo 'value="' . set_value('inputOrigemOutro') . '"';
                          if(set_value('semOrigem') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      >
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompOrigem">
                      Informação complementar sobre o(a)
                      <span id="labelInputCompOrigem" class="control-label" for="inputCompOrigem">
                      </span>
                    </label>
                  </div>
                  <div class="controls">
                    <textarea id="inputCompOrigem" class="form-control span12" rows="2" name="inputCompOrigem"
                      <?php
                        if(set_value('semOrigem') == "on")
                          echo 'disabled="disabled"';
                      ?>
                      ><?php
                        echo set_value('inputCompOrigem');
                      ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <input id="semOrigem" type="checkbox" name="semOrigem"
                      <?php
                        if (set_value('semOrigem') == "on")
                          echo 'checked="checked"';
                      ?>
                    > Sem informação sobre a origem do acidente
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
                        <label class="control-label span4" for="inputNomeNavio">
                          Nome do navio:
                        </label>
                        <div class="controls">
                          <input id="inputNomeNavio" class="input" type="text" name="inputNomeNavio" placeholder="Nome do Navio" ';

                    echo 'value="' . set_value('inputNomeNavio') . '"';

                    if(set_value('semNavioInstalacao') == "on")
                      echo 'disabled="disabled"';
                    echo  '>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label span4" for="inputNomeNavio">
                          Nome da instalação:
                        </label>
                        <div class="controls">
                          <input id="inputNomeInstalacao" class="input" type="text" name="inputNomeInstalacao" placeholder="Nome da Instalação" ';

                    echo 'value="' . set_value('inputNomeInstalacao') . '"';

                    if(set_value('semNavioInstalacao') == "on")
                      echo 'disabled="disabled"';

                    echo '
                          >
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group">
                        <div class="controls">
                          <label class="checkbox text-left">
                            <input id="semNavioInstalacao" type="checkbox" name="semNavioInstalacao" ';

                    if (set_value('semNavioInstalacao') == "on")
                      echo 'checked="checked"';
                    echo '
                            >
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
              4. Tipo de Evento
            </a>
          </div>
          <div id="collapse4" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div id="tipoEvento" class="span12">
                  <div style="display:none;">
                    <?php
                        foreach ($tipoEvento as $id) {
                          echo '<span data-id="postTE">' . $id . '</span>';
                        }
                    ?>
                  </div>
                </div>
              </div>
              <!-- <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="inputEventoOutro"> Outro:</label>
                    <div class="controls">
                      <input id="inputEventoOutro" class="input-large" type="text" name="inputEventoOutro"
                        <?php
                          echo 'value="' . set_value('inputEventoOutro') . '"';
                          if(set_value('semEvento') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      >
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompEvento">
                      Informação complementar sobre o(a)
                      <span id="labelInputCompEvento" class="control-label" for="labelInputCompEvento">
                      </span>
                    </label>
                  </div>
                  <div class="controls">
                    <textarea id="inputCompEvento" class="form-control span12" rows="2" name="inputCompEvento"
                      <?php
                        if(set_value('semEvento') == "on")
                          echo 'disabled="disabled"';
                      ?>
                    ><?php
                        echo set_value('inputCompEvento');
                      ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="checkbox text-left">
                      <input id="semEvento" type="checkbox" name="semEvento"
                        <?php
                          if (set_value('semEvento') == "on")
                            echo 'checked="checked"';
                        ?>
                      > Sem informação sobre o tipo do evento
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
              5. Tipo de Produto
            </a>
          </div>
          <div id="collapse5" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="span4">
                  <div class="control-group">
                    <label class="control-label">Nome do Produto:</label>
                    <div class="controls">
                      <select class="input-medium">
                        <option>Alcool</option>
                        <option>Chocolate</option>
                        <option>Cerveja</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="span5">
                  <div class="control-group">
                    <label class="control-label" for="inputQtd">Qtd. Aproximada: </label>
                    <div class="controls">
                      <input id="inputQtd" class="input-small" type="text" name="inputQtd" placeholder="1,2,..">
                      <select id="slctQtd" name="slctQtd" class="input-mini">
                        <option>T</option>
                        <option>Kg</option>
                        <option>G</option>
                        <option>L</option>
                        <option>m³</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="span3">
                  <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                      <button class="btn btn-primary" type="button"><i class="icon-plus icon-white"></i> Adicionar</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <table class="table table-condesed table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Nome da substância</th>
                        <th>Nº da ONU</th>
                        <th>Classe de Risco</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Alcool</td>
                        <td>254</td>
                        <td>3</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row-fluid">
                <!-- <div class="span6">
                  <div class="control-group">
                    <label class="control-label" for="inputTipoOutro"> Outro:</label>
                    <div class="controls">
                      <input id="inputTipoOutro" class="input-large" type="text" name="inputTipoOutro">
                    </div>
                  </div>
                </div> -->
                <div class="span4">
                  <div class="control-group">
                    <label class="control-label">&nbsp;</label>
                    <div class="controls">
                      <label class="checkbox text-left">
                        <input id="produtoNaoPerigoso" type="checkbox" name="produtoNaoPerigoso"> Não perigoso
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <input id="produtoDesc" type="checkbox" name="produtoDesc"> Sem informação sobre o tipo do produto
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
                        <div class="controls">
                          <input id="inputTipoSubstancia" class="input" type="text" name="inputTipoSubstancia" placeholder="Tipo da Substância" ';
                  if (isset($semSubstancia))
                    echo 'disabled="disabled"';
                  else
                    echo ' value="' . set_value('inputTipoSubstancia') . '"';
                  echo '
                          >
                        </div>
                      </div>
                      <div class="control-group">
                        <div class="controls">
                          <label class="control-label" for="inputValorEstimado">
                            Volume estimado em
                            <input id="inputValorEstimado" class="input-small" type="text" name="inputValorEstimado" placeholder="Valor" ';
                  if (isset($semSubstancia))
                    echo 'disabled="disabled"';
                  else
                    echo ' value="' . set_value('inputValorEstimado') . '"';
                  echo '
                            >
                            m3
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row-fluid">
                      <div class="control-group">
                        <div class="controls">
                          <label class="checkbox text-left">
                            <input id="semSubstancia" type="checkbox" name="semSubstancia" ';
                  if (isset($semSubstancia))
                    echo ' checked="checked"';
                  echo '
                            >
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
              6. Detalhes do Acidente
            </a>
          </div>
          <div id="collapse6" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="inputCausaProvavel"> Causa Provavel do Acidente:</label>
                    <div class="controls">
                      <textarea id="inputCausaProvavel" class="form-control span12"rows="2" name="inputCausaProvavel"
                        <?php
                          if(set_value('semCausa') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      ><?php
                          echo set_value('inputCausaProvavel');
                      ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span4" style="align:center;">
                  <label class="checkbox text-left">
                    <input id="semCausa" type="checkbox" name="semCausa"
                      <?php
                        if (set_value('semCausa') == "on")
                          echo 'checked="checked"';
                      ?>
                    > Sem condições de informar
                  </label>
                </div>
              </div>
              <br />
              <div class="row-fluid">
                <label class="control-label"> Situação Atual da Descarga: </label>
                <div class="controls row-fluid">
                  <div class="span3">
                    <label class="radio">
                      <input id="SitParal" type="radio" name="SituacaoDescarga" value="1" checked> Paralisada
                    </label>
                  </div>
                  <div class="span3">
                    <label class="radio">
                      <input id="SitNaoParal" type="radio" name="SituacaoDescarga" value="2"
                        <?php
                          if (set_value('SituacaoDescarga') == "2")
                            echo 'checked';
                        ?>
                      > Não foi paralisada
                    </label>
                  </div>
                  <div class="span3">
                    <label class="radio">
                      <input id="SitSemCondi" type="radio" name="SituacaoDescarga" value="3"
                        <?php
                          if (set_value('SituacaoDescarga') == "3")
                            echo 'checked';
                        ?>
                      > Sem condições de informar
                    </label>
                  </div>
                  <div class="span3">
                    <label class="radio">
                      <input id="SitNaoSeApl" type="radio" name="SituacaoDescarga" value="4"
                        <?php
                          if (set_value('SituacaoDescarga') == "4")
                            echo 'checked';
                        ?>
                      > <strong> Não se aplica </strong>
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
              7. Danos Identificados
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
              <!-- <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="inputDanoOutro"> Outro:</label>
                    <div class="controls">
                      <input id="inputDanoOutro" class="input-large" type="text" name="inputDanoOutro"
                        <?php
                          echo 'value="' . set_value('inputDanoOutro') . '"';
                          if(set_value('semDanos') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      >
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompDano">
                      Informação complementar sobre o(a)
                      <span id="labelInputCompDano" class="control-label" for="inputCompDano">
                      </span>
                    </label>
                  </div>
                  <div class="controls">
                    <textarea id="inputCompDano" class="form-control span12" rows="2" name="inputCompDano"
                        <?php
                          if(set_value('semDanos') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      ><?php
                          echo set_value('inputCompDano');
                      ?></textarea>
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
                    <textarea id="inputDesDanos" class="form-control span12" rows="4" name="inputDesDanos"
                      <?php
                        if(set_value('semOrigem') == "on")
                          echo 'disabled="disabled"';
                      ?>
                      ><?php
                        echo set_value('inputDesDanos');
                      ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <input id="semDanos" type="checkbox" name="semDanos"
                      <?php
                        if (set_value('semDanos') == "on")
                          echo 'checked="checked"';
                      ?>
                    > Sem informação sobre os danos
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse8">
              8. Identificação Empresa/Órgão Responsável
            </a>
          </div>
          <div id="collapse8" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="span6">
                  <div class="control-group">
                    <label class="control-label" for="inputResponsavel">
                      Nome:
                    </label>
                    <div class="controls">
                      <input id="inputResponsavel" class="input-medium" type="text" name="inputResponsavel" placeholder="Nome do Responsavel"
                        <?php
                          echo 'value="' . set_value('inputResponsavel') . '"';
                          if(set_value('semResponsavel') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      >
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="inputCPFCNPJ">CPF/CNPJ: </label>
                    <div class="controls">
                      <input id="inputCPFCNPJ" class="input-medium" type="text" name="inputCPFCNPJ" placeholder="CPF/CNPJ do Responsavel"
                        <?php
                          echo 'value="' . set_value('inputCPFCNPJ') . '"';
                          if(set_value('semResponsavel') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      >
                    </div>
                  </div>
                </div>
                <div class="span6">
                  <div class="control-group">
                    <label class="control-label" for="inputLicenca">
                      Licença ambiental:
                    </label>
                    <div class="controls">
                      <select id="slctLicenca" name="slctLicenca" class="input-medium"
                        <?php
                          if (set_value('semResponsavel') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      >
                        <option
                          <?php
                            if(set_value('slctLicenca') == "1")
                              echo 'selected="selected"';
                          ?>
                        >1</option>
                        <option
                          <?php
                            if(set_value('slctLicenca') == "2")
                              echo 'selected="selected"';
                          ?>
                        >2</option>
                        <option
                          <?php
                            if(set_value('slctLicenca') == "3")
                              echo 'selected="selected"';
                          ?>
                        >3</option>
                        <option
                          <?php
                            if(set_value('slctLicenca') == "4")
                              echo 'selected="selected"';
                          ?>
                        >4</option>
                        <option
                          <?php
                            if(set_value('slctLicenca') == "5")
                              echo 'selected="selected"';
                          ?>
                        >5</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <div class="controls">
                      <label class="checkbox text-left">
                        <input id="semResponsavel" type="checkbox" name="semResponsavel"
                          <?php
                            if (set_value('semResponsavel') == "on")
                              echo 'checked="checked"';
                          ?>
                        > Sem informação sobre a empresa
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
              9. Instituição/Empresa Atuando no Local
            </a>
          </div>
          <div id="collapse9" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div id="tipoInstituicaoAtuando" class="span12">
                  <div style="display:none;">
                    <?php
                        foreach ($instituicaoAtuandoLocal as $id) {
                          echo '<span data-id="postIAL">' . $id . '</span>';
                        }
                    ?>
                  </div>
                </div>
              </div>
              <!-- <div class="row-fluid">
                <div class="span12">
                  <div class="control-group">
                    <label class="control-label" for="inputInstituicaoOutro">
                      Outro:
                    </label>
                    <div class="controls">
                      <input id="inputInstituicaoOutro" class="input-large" type="text" name="inputInstituicaoOutro"
                        <?php
                          echo 'value="' . set_value('inputInstituicaoOutro') . '"';
                          if(set_value('semInstituicao') == "on")
                            echo 'disabled="disabled"';
                        ?>
                      >
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputCompInstituicao">
                      Informação complementar sobre o(a)
                      <span id="labelInputCompInstituicao" class="control-label" for="inputCompInstituicao">
                      </span>
                    </label>
                  </div>
                  <div class="controls">
                    <textarea id="inputCompInstituicao" class="form-control span12" rows="2" name="inputCompInstituicao"
                      <?php
                        if(set_value('semInstituicao') == "on")
                          echo 'disabled="disabled"';
                      ?>
                    ><?php
                        echo set_value('inputCompInstituicao');
                    ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <input id="semInstituicao" type="checkbox" name="semInstituicao"
                      <?php
                        if (set_value('semInstituicao') == "on")
                          echo 'checked="checked"';
                      ?>
                    > Sem informação sobre as instituções.
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse10">
              10. Procedimentos de Atendimento Adotados
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
                          <input id="Nao" type="radio" name="planoEmergencia" value="0" checked > Não
                        </label>
                      </div>
                      <div class="span6">
                        <label class="radio">
                          <input id="Sim" type="radio" name="planoEmergencia" value="1"
                            <?php
                              if (set_value('planoEmergencia') == "1")
                                echo 'checked';
                            ?>
                          > Sim
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <label class="checkbox text-left">
                    <input id="planoAcionado" type="checkbox" name="planoAcionado"
                      <?php
                        if (set_value('planoAcionado') == "on")
                          echo 'checked="checked"';
                      ?>
                    > Acionado Plano Individual de Emergência
                  </label>
                </div>
              </div>
              <div class="row-fluid">
                <div class="span12">
                  <div class="controls">
                    <label class="control-label checkbox span6" for="inputMedidasTomadas">
                      <input id="outrasMedidas" type="checkbox" name="outrasMedidas"
                        <?php
                          if (set_value('outrasMedidas') == "on")
                            echo 'checked="checked"';
                        ?>
                      > Foram tomadas outras providências a saber:
                    </label>
                    <div class="span6">
                      <input id="inputMedidasTomadas" class="input-large" type="text" name="inputMedidasTomadas"
                        <?php
                          echo 'value="' . set_value('inputMedidasTomadas') . '" ';
                          if (set_value('inputMedidasTomadas') == "on")
                            echo ' checked="checked"';
                        ?>
                      >
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="row-fluid">
                <div class="control-group">
                  <label class="checkbox text-left">
                    <input id="" type="checkbox" name="instOrgaoEst"> Órgão Estadual ou Municipal
                  </label>
                </div>
                <div class="controls">
                  <label class="control-label checkbox span6" for="inputMedidasTomadas">
                    <input id="outrasMedidas" type="checkbox" name="outrasMedidas"> Outras medidas foram tomadas
                  </label>
                  <div class="span6">
                    <input id="inputMedidasTomadas" class="input-medium-large" type="text" name="inputMedidasTomadas">
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse11">
              11. Informações sobre o Informante
            </a>
          </div>
          <div id="collapse11" class="accordion-body collapse">
            <div class="accordion-inner">
              <div class="row-fluid">
                <div class="controls span12">
                  <label class="control-label span5" for="inputNomeInformante">Nome Completo:
                  </label>
                  <div class="span6">
                    <input id="inputNomeInformante" class="input-medium-large" type="text" name="inputNomeInformante"
                      <?php
                        if($this->session->userdata('logged_in')) {
                          echo 'value="' . $this->session->userdata('name') . '" ';
                        }
                        echo 'value="' . set_value('inputNomeInformante') . '" ';
                      ?>
                    >
                  </div>
                </div>
                <?php
                  if (isset($hasOleo)) {
                    echo '
                      <div class="controls span12">
                        <label class="control-label span5" for="inputFuncaoNavio">Função navio ou instalação:
                        </label>
                        <div class="span6">
                          <input id="inputFuncaoNavio" class="input-medium-large" type="text" name="inputFuncaoNavio" ';
                    echo ' value="' . set_value('inputFuncaoNavio') . '"';
                    echo '
                          >
                        </div>
                      </div>
                    ';
                  }
                ?>
                <div class="controls span12">
                  <label class="control-label span5" for="inputTelInformante">Telefone de Contato:
                  </label>
                  <div class="span6">
                    <input id="inputTelInformante" class="input-medium-large" type="text" name="inputTelInformante"
                      <?php
                        echo 'value="' . set_value('inputTelInformante') . '" ';
                      ?>
                    >
                  </div>
                </div>
                <div class="controls span12">
                  <label class="control-label span5" for="inputEmailInformante">Email de Contato:
                  </label>
                  <div class="span6">
                    <input id="inputEmailInformante" class="input-medium-large" type="text" name="inputEmailInformante"
                      <?php
                        if($this->session->userdata('logged_in')) {
                          echo 'value="' . $this->session->userdata('mail') . '" ';
                        }
                        echo 'value="' . set_value('inputEmailInformante') . '" ';
                      ?>
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion-group">
          <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse12">
              12. Informações gerais sobre a Ocorrência
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
                    <textarea id="inputDesOcorrencia" class="form-control span12" rows="4" name="inputDesOcorrencia"
                      ><?php
                        echo set_value('inputDesOcorrencia');
                      ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row-fluid">
                <div class="control-group">
                  <div class="span12">
                    <label class="control-label" for="inputDesObs">
                      Informações Adicionais sobre a Ocorrência
                    </label>
                  </div>
                  <div class="controls">
                    <textarea id="inputDesObs" class="form-control span12" rows="3" name="inputDesObs"
                      ><?php
                        echo set_value('inputDesObs');
                      ?></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
          if(isset($isServIBAMA)) {
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
                        <div style="display:none;">
                          <?php
                              foreach ($tipoFonteInformacao as $id) {
                                echo "<span data-id="postTFI">"" . $id . "</span>";
                              }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            ';
          }
        ?>
      </div>
      <!-- <a id="submit" class="btn btn-primary" type="button"><i class="icon-map-marker icon-white"></i> Enviar Formulário</a> -->


      <div class="checkbox" style="display:none;">
        <input type="checkbox" id="hasOleo" name="hasOleo" <?php if(isset($hasOleo)) {echo ' checked="checked"';} ?>>
        <input type="checkbox" id="isServIBAMA" name="isServIBAMA" <?php if(isset($isServIBAMA)) {echo ' checked="checked"';} ?>>
      </div>

    <?php echo form_close(); ?>
  </div>
</div>
