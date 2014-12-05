<h3 style="margin-bottom:5px;"><center> COMUNICADO DE ACIDENTE AMBIENTAL <center></h3>

<!-- <h4 style="color:#0088cc;padding: 0 10px 0 0;"> -->
<h4 style="padding-bottom: 30px;">
  <center>
    <strong><u>NÚMERO DE REGISTRO</u>:</strong>
    <span><?php echo $comunicado;?></span>
  <center>
  <h5>
    <center>
      <span>Data do cadastro: <?php echo date('d/m/Y', strtotime($dt_registro)); ?></span>

      <br />

      <span>Data da atualização: <?php echo date('d/m/Y H:i:s'); ?></span>
    </center>
  <h5 />
</h4>

<div class="span2">
  <?php
    if ($validado == 'S') {
      echo ' <strong>Validado</strong><input id="validado" type="checkbox" value="" name="validado" checked="checked">';
    } else {
      echo ' <strong>Validado</strong><input id="validado" type="checkbox" value="" name="validado">';
    }
  ?>
</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>1. Localização</i></u></strong></a>

  <div>
    &nbsp;&nbsp;
    <div>&nbsp;&nbsp; <strong>Latitude(y):</strong> <?php echo $inputLat;?></div>
    <div>&nbsp;&nbsp; <strong>Longitude(x):</strong> <?php echo $inputLng;?></div>

    <br />

    <?php
      if($oceano == 'on') {
        echo
            '<div>' .
            '<strong>&nbsp;&nbsp;Oceano</strong> ' .
            '&nbsp;&nbsp;<input type="checkbox" checked="checked"/><br />' .
            '&nbsp;&nbsp;<span> <strong>Bacia Sedimentar:</strong>' . $bacia_nome . '</span>' .
            '</div>';
      } else {
        echo '<div>' .
             '<strong>&nbsp;&nbsp;Oceano</strong> &nbsp;&nbsp;<input type="checkbox">' .
             '</div>';
      }
    ?>

    <br />

    <div>&nbsp;&nbsp; <strong>UF:</strong> <?php echo $uf_nome;?></div>
    <div>&nbsp;&nbsp; <strong>Município:</strong> <?php echo $municipio_nome;?></div>

    <br />

    <div>&nbsp;&nbsp; <strong>Endereço:</strong> <?php echo $endereco;?></div>
  </div>
</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>2. Data e Hora do Acidente</i></u></strong></a>

  <h5>&nbsp;&nbsp;&nbsp;&nbsp;* Data e hora da primeira observação: </h5>
  <div>
    <?php
      if($semDataObs == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre data e hora da primeira observação</div>';
      } else {
        echo
            '<div>&nbsp;&nbsp; <strong>Data:</strong> ' . $dataObs . '</div>' .
            '<div>&nbsp;&nbsp; <strong>Hora:</strong> ' . $horaObs . '</div>' .
            '<div>&nbsp;&nbsp; <strong>Período:</strong> ' . $PeriodoObs . '</div>';
      }
    ?>
  </div>

  <h5>&nbsp;&nbsp;&nbsp;&nbsp;* Data e hora estimadas do incidente: </h5>
  <div>
    <?php
      if($semDataInci == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre data e hora da primeira observação</div>';
      } else {
        echo
            '<div>&nbsp;&nbsp; <strong>Data:</strong> ' . $dataInci . '</div>' .
            '<div>&nbsp;&nbsp; <strong>Hora:</strong> ' . $horaInci . '</div>' .
            '<div>&nbsp;&nbsp; <strong>Período:</strong> ' . $PeriodoInci . '</div> <br />';

        if ($feriado == 'checked') {
          echo
            '&nbsp;&nbsp;<strong>Feriado</strong> &nbsp;&nbsp;<input type="checkbox" checked="checked"/><br />';
        } else {
          echo
            '&nbsp;&nbsp;<strong>Feriado</strong> &nbsp;&nbsp;<input type="checkbox"/><br />';
        }
      }
    ?>
  </div>
</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>3. Origem do Acidente</i></u></strong></a>

  <div>
    <?php
      echo '&nbsp;&nbsp;';
      if($semOrigem == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre a origem do acidente</div>';
      } else {
        foreach ($tipoLocalizacao as $tipo) {
          echo '<div>&nbsp;&nbsp;- ' . $tipo . '</div>';
        }
      }
      if($origem_comp) {
        echo '<br /><div>&nbsp;&nbsp;<strong>Informações complementares:  </strong>' . $origem_comp . '</div>';
      }
    ?>

    <h5>&nbsp;&nbsp;&nbsp;&nbsp;* Identificação do navio ou instalação que originou o incidente: </h5>

    <?php
      if(isset($ocorrencia_oleo)){
        if (isset($infoOil)){
          echo '<div>&nbsp;&nbsp;' . (isset($instalacao_nome) ? '<strong>Nome da Instalação:</strong> '. $instalacao_nome : '<strong>Nome do Navio:</strong> '. $navio_nome) . '</div>';
        } else {
          echo '<div>&nbsp;&nbsp; Sem condições de informar  </div>';
        }
      }
    ?>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>4. Tipo de Evento</i></u></strong></a>

  <div>
    <?php
      echo '&nbsp;&nbsp;';
      if($semEvento == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre a origem do acidente</div>';
      } else {
        foreach ($tipoEvento as $tipo) {
          echo '<div>&nbsp;&nbsp;- ' . $tipo . '</div>';
        }
      }
      if($evento_comp) {
        echo '<div>&nbsp;&nbsp;<strong>Informações complementares:  </strong>' . $evento_comp . '</div>';
      }
    ?>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>5. Tipo de Produto</i></u></strong></a>

  <div>
    <?php
      echo '&nbsp;&nbsp;';
      if($semProduto == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre o tipo do produto</div>';
      } else {
        echo '<br />&nbsp;&nbsp;<strong>Produtos da lista ONU:</strong> <br />';
        if (!empty($infoProd)) {
          foreach ($infoProd as $tipo) {
            echo '<div>&nbsp;&nbsp;- ' . ucfirst(strtolower($tipo['nome'])) . ' - ' . $tipo['quantidade'] . ' ' . $tipo['unidade_medida'] . '</div>';
          }
        } else {
          echo '<div>&nbsp;&nbsp; ----- </div>';
        }

        echo '<br />&nbsp;&nbsp;<strong>Produto não pertencentes a lista ONU:</strong> <br />';
        if (!empty($infoProdOutros)) {
          foreach ($infoProdOutros as $tipo) {
            echo '<div>&nbsp;&nbsp;- ' . ucfirst(strtolower($tipo['nome'])) . ' - ' . $tipo['quantidade'] . ' ' . $tipo['unidade_medida'] . '</div>';
          }
        } else {
          echo '<div>&nbsp;&nbsp; ----- </div>';
        }

        echo '<br />';

        if ($statusProdSetado) {
          if($produtoNaoPerigoso == 't') {
            echo '<div>&nbsp;&nbsp;- Produto não classificado</div>';
          }
          if($produtoNaoAplica == 't') {
            echo '<div>&nbsp;&nbsp;- Produto não se aplica</div>';
          }
          if($produtoNaoEspecificado == 't') {
            echo '<div>&nbsp;&nbsp;- Produto não especificado</div>';
          }
        }
      }
    ?>

    <?php

      if(!empty($ocorrencia_oleo) && ($ocorrencia_oleo == 'S') ){

        echo '<h5>&nbsp;&nbsp;&nbsp;&nbsp;* Substância descarregada: </h5>';

        if ($semSubstancia == 'checked'){
          echo '<div>&nbsp;&nbsp; Sem condições de informar  </div>';
        } else {
          echo
              '<div>&nbsp;&nbsp;<strong>Tipo de Substância:</strong> '. $tipo_substancia . '</div>' .
              '<div>&nbsp;&nbsp;<strong>Volume Estimado:</strong> '. $volume_estimado . ' m³</div>';
        }
      }

    ?>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>6. Detalhes do Acidente</i></u></strong></a>

  <div>
    <?php
      echo '&nbsp;&nbsp;';
      if($semCausa == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem condições de informar</div>';
      } else {
        echo '<div>&nbsp;&nbsp;<strong>Causa provável do acidente:</strong> '. $causa_provavel . '</div>';
      }
    ?>

    <br />

    <div>
      <?php
      if(isset($situacao_descarga)) {
        echo   '<div>&nbsp;&nbsp; <strong>Situação atual da descarga:</strong>  - '. $situacao_descarga . '</div>';
      }
      ?>
    </div>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>7. Ocorrências e/ou ambientes atingidos</i></u></strong></a>

  <div>
    <?php
      echo '&nbsp;&nbsp;';
      if($semDanos == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre ocorrências e/ou ambientes atingidos</div>';
      } else {
        foreach ($tipoDanoIdentificado as $tipo) {
          echo '<div>&nbsp;&nbsp;- ' . $tipo . '</div>';
        }
      }
      if($dano_comp) {
        echo
            '<br />' .
            '<div>&nbsp;&nbsp;<strong>Informações complementares:  </strong>' . $dano_comp . '</div>';
      }
    ?>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>8. Identificação da empresa/responsável</i></u></strong></a>

  <div>
    <?php
      echo '&nbsp;&nbsp;';
      if($semResponsavel == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre a empresa/responsável</div>';
      } else {
        echo
            '<div>&nbsp;&nbsp; <strong>Nome:</strong> ' . $responsavel . '</div>' .
            '<div>&nbsp;&nbsp; <strong>CPF/CNPJ:</strong> ' . $cpf_cnpj . '</div>' .
            '<div>&nbsp;&nbsp; <strong>Licença Ambiental:</strong> ' . $licenca_ambiental . '</div><br />';
      }
    ?>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>9. Instituição/empresa atuando no local</i></u></strong></a>

  <div>
    <?php
      echo '&nbsp;&nbsp;';
      if($semInstituicao == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem informação sobre instituição/empresa atuando no local</div>';
      } else {
        foreach ($instituicaoAtuandoLocal as $tipo) {
          echo '<div>&nbsp;&nbsp;- ' . $tipo . '</div>';
        }
      }
      if($instituicao_comp) {
        echo '<div>&nbsp;&nbsp;<strong>Informações complementares:  </strong>' . $instituicao_comp . '</div>';
      }
    ?>

    <?php
      echo
          '<br />' .
          '<div>&nbsp;&nbsp; <strong>Nome do responsável no local:</strong> ' . $instituicao_nome . '</div>' .
          '<div>&nbsp;&nbsp; <strong>Telefone:</strong> ' . $instituicao_telefone . '</div>';
    ?>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>10. Ações inicais tomadas</i></u></strong></a>

  <div>
    <?php
      if($semProcedimentos == 'checked'){
        echo '<div>&nbsp;&nbsp; Sem evidência de ação ou ou providência até o momento</div>';
      } else {
        echo '&nbsp;&nbsp;';
        echo '<div>&nbsp;&nbsp; <strong>Existência de Plano de Emergencia Individual ou similar:</strong> ' . $plano_emergencia . '</div>';

        if ($plano_acionado == 'on'){
          echo '<div>&nbsp;&nbsp;<strong>Acionado Plano Indivual de Emergencia</strong>&nbsp;&nbsp;<input id="plano_acionado" type="checkbox" value="" name="plano_acionado" checked="checked"></div>';
        } else {
          echo '<div>&nbsp;&nbsp;<strong>Acionado Plano Indivual de Emergencia</strong>&nbsp;&nbsp;<input id="plano_acionado" type="checkbox" value="" name="plano_acionado"> </div>';
        }

        echo '<br /><div>&nbsp;&nbsp;<strong>Providências tomadas a saber: </strong> ' . (($outras_medidas == 'on') ? $medidas_tomadas : " ----- ") . '</div>';
      }
    ?>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>11. Informações gerais sobre a ocorrência</i></u></strong></a>

  <div>
    &nbsp;&nbsp;
    <div>&nbsp;&nbsp; <strong>Outras informações julgadas úteis:</strong> <?php echo isset($des_obs) ? $des_obs : " ----- ";?></div>
  </div>

</div>

<br /><br /><hr> <br />

<div>
  <a><strong><u><i>12. Identificação do comunicante</i></u></strong></a>

  <div>
    &nbsp;&nbsp;
    <div>&nbsp;&nbsp; <strong>Nome Completo:</strong> <?php echo $nome_comunicante ?  $nome_comunicante : ' ----- ';?></div>
    <div>&nbsp;&nbsp; <strong>Instituição/Empresa:</strong> <?php echo$instituicao_empresa ?  $instituicao_empresa : ' ----- ';?></div>
    <div>&nbsp;&nbsp; <strong>Cargo/Função navio ou instalação:</strong> <?php echo $cargo_funcao ?  $cargo_funcao : ' ----- ';?></div>
    <div>&nbsp;&nbsp; <strong>Telefone de contato:</strong> <?php echo $tel_informante ?  $tel_informante : ' ----- ';?></div>
    <div>&nbsp;&nbsp; <strong>E-mail de contato:</strong> <?php echo $email_informante ?  $email_informante : ' ----- ';?></div>
  </div>

</div>

<br /><br /><hr> <br />

<?php


  if ($this->authldap->is_authenticated()) {
    echo
        '<div>' .
          '<a><strong><u><i>13. Fonte da informação</i></u></strong></a>' .

          '<div>' .
            '&nbsp;&nbsp;';

    foreach ($tipoFonteInformacao as $tipo) {
      echo  '<div>&nbsp;&nbsp;- ' . $tipo . '</div>';
    }

      if(isset($outras_fontes)) {
        echo   '<br /><div>&nbsp;&nbsp; <strong>Descrição Outra(s) Fonte(s):</strong> '. $outras_fontes . '</div>';
      } else {
        echo   '<br /><div>&nbsp;&nbsp; <strong>Descrição Outra(s) Fonte(s):</strong> ----- </div>';
      }
    // echo   '<br /><div>&nbsp;&nbsp; <strong>Descrição Outra(s) Fonte(s):</strong> '. isset($outras_fontes) ? $outras_fontes : ' ----- ' . '</div>';

    echo    '</div>' .
        '</div>' .

        '<br /><br /><hr> <br />';
  }
?>