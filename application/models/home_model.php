<?php
class Home_model extends CI_Model {

  function __construct()
  {
    parent::__construct();
  }

  function get_data_home()
  {
    $data['years'] = array();
    $today = date("Y");
    for ($i=2004; $i<=$today; $i++)
    {
      array_push($data['years'], $i);
    }
    $data['months'] = array(
      '0'                     => 'Jan',
      '1'                     => 'Fev',
      '2'                     => 'Mar',
      '3'                     => 'Abr',
      '4'                     => 'Mai',
      '5'                     => 'Jun',
      '6'                     => 'Jul',
      '7'                     => 'Ago',
      '8'                     => 'Set',
      '9'                     => 'Out',
      '10'                    => 'Nov',
      '11'                    => 'Dez'
    );
    $data['states'] = array(
      '0'                     => 'AC',
      '1'                     => 'AM',
      '2'                     => 'AP',
      '3'                     => 'BA',
      '4'                     => 'MA',
      '5'                     => 'MS',
      '6'                     => 'MT',
      '7'                     => 'PA',
      '8'                     => 'RO',
      '9'                     => 'RR',
      '10'                    => 'TO',
    );
  }
}
