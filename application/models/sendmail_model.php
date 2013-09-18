<?php
class Sendmail_model extends CI_Model {

  function __construct()
  {
    parent::__construct();
  }

  public function getEmailBody($profile, $protocol_number, $hour, $data) {
    /* Profiles:
      0: Cidadão que se cadastrou no sistema
      1: Empresa
      2: Servidor do IBAMA
      3: Orgão Público
    */

    // always present, regardless profile
    $firstFrase = "O seu comunicado de acidente ambiental foi registrado com sucesso";
    $strComplement = ".\nO Ibama irá avaliar a sua comunicação e adotará as medidas julgadas pertinentes.\n"
    $body = "Os dados informados poderão ser atualizados na página do Ibama (www.ibama.gov.br) por meio do número de registro: " . $protocol_number . ".\nEste é um e-mail enviado automaticamente, não é possível respondê-lo.\nCaso deseje, poderá entrar em contato com o Ibama por meio do e-mail emergenciasambientais.sede@ibama.gov.br.\n"


    if($profile == 0) {
      $firstFrase = $firstFrase . $strComplement;
      // same as profile 3
      $strClosing = getClosing(3);
    }
    else {
      $strData = " às " . $hour .  "do dia " . $date . "\n";
      $firstFrase = $firstFrase . $strData
      $strClosing = getClosing($profile);
    }

    $finalBody = $firstFrase . $body . $strClosing;
    return $finalBody;

  }

  public function getClosing($profile) {
    var $strClosing;
    switch ($profile) {
      // same as profile 0
      case 3:
      $strClosing = "Agradecemos o envio do comunicado.";
      break;
      case 1:
      $strClosing = "As informações enviadas são de responsabilidade da empresa. O envio de informações falsas ou enganosas sujeita o infrator às penalidades previstas no Art. 82 do Decreto nº 6.514, de 22 de julho de 2008.";
      break;
      case 2:
      $strClosing = "Qualquer informação adicional deverá ser obtida com a Coordenação Geral de Emergências Ambientais – CGEMA/DIPRO, pelo e-mail emergenciasambientais.sede@ibama.gov.br.";
      break;
    }

    return $strClosing;
  }

}
