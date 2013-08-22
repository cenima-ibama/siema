<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('AuthLDAP');

        // Enable firebug
        // $this->load->library('Firephp');
        $this->firephp->setEnabled(TRUE);
    }

    public function index()
    {
        // $this->firephp->log("Index");

        if($this->authldap->is_authenticated()) {
            $data['name'] = $this->session->userdata('name');
            $data['username'] = $this->session->userdata('username');
            $data['logged_in'] = TRUE;
        } else {
            $data['logged_in'] = FALSE;
        }

        $this->load->helper('form');

        $this->load->view('templates/home', $data);

    }

    public function insertDB($form_data)
    {

        $this->load->model('form_model');

        $this->form_model->save($form_data);

        $this->load->view('templates/form_success');
    }

    public function formSetRules ($form_data)
    {

        // Validation message
        // $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        $this->form_validation->set_message('required', 'O Campo %s é obrigatório');

        // $this->firephp->log($form_data);

        // Validating the location
        if (!isset($form_data["semLocalizacao"])) {
            $this->form_validation->set_rules('inputLat', 'Latitude', 'required');
            $this->form_validation->set_rules('inputLng', 'Longitude ', 'required');
            $this->form_validation->set_rules('inputMunicipio', 'Municipio', 'required');
            $this->form_validation->set_rules('inputUF', 'UF', 'required');
            if(isset($form_data["inputEPSG"])) {
                $this->form_validation->set_rules('inputEPSG', 'EPSG', 'required');
            }
        } else {
            $this->form_validation->set_rules('semLocalizacao', 'Localizacao', 'required');
        }

        // Validating the Time and Date when the Accident were observed
        if (!isset($form_data["semDataObs"])) {
            $this->form_validation->set_rules('inputDataObs', 'Data da Observação', 'required');
            $this->form_validation->set_rules('inputHoraObs', 'Hora da Observação', 'required');
        } else {
            $this->form_validation->set_rules('semDataObs', 'Data e Hora da Observação', 'required');
        }

        // Validating the Time and Date when the Accident happened
        if (!isset($form_data["semDataInic"])) {
            $this->form_validation->set_rules('inputDataInic', 'Data do Acidente', 'required');
            $this->form_validation->set_rules('inputHoraInic', 'Hora do Acidente', 'required');
        } else {
            $this->form_validation->set_rules('semDataInic', 'Data e Hora do Acidente', 'required');
        }


        // Validating the Location
        if (!isset($form_data['semOrigem'])) {
            $this->form_validation->set_rules('tipoLocalizacao[]', 'Origem do Acidente', 'required');
        } else {
            $this->form_validation->set_rules('semOrigem', 'Origem do Acidente', 'required');
        }

        // Validating the Event Type
        if (!isset($form_data['semEvento'])) {
            $this->form_validation->set_rules('tipoEvento[]', 'Evento', 'required');
        } else {
            $this->form_validation->set_rules('semEvento', 'Tipo de Evento', 'required');
        }

        // Validating the Probably Cause of Accident
        if (!isset($form_data['semCausa'])) {
            $this->form_validation->set_rules('inputCausaProvavel', 'Causa Provavel do Acidente', 'required');
        } else {
            $this->form_validation->set_rules('semCausa', 'Causa Provavel do Acidente', 'required');
        }

        // Validating the Damage Type
        if (!isset($form_data['semDanos'])) {
            $this->form_validation->set_rules('tipoDanoIdentificado[]', 'Danos Identificados', 'required');
        } else {
            $this->form_validation->set_rules('semDanos', 'Danos Identificados', 'required');
        }

        // Validating the information about the Responsible
        if (!isset($form_data['semResponsavel'])) {
            $this->form_validation->set_rules('inputResponsavel', 'Nome da Empresa/Orgão Responsavel', 'required');
            $this->form_validation->set_rules('slctLicenca', 'Licença Ambiental da Empresa/Orgão Responsavel', 'required');
            $this->form_validation->set_rules('inputCPFCNPJ', 'CPF/CNPJ da Empresa/Orgão Responsavel', 'required');
        } else {
            $this->form_validation->set_rules('semResponsavel', 'Responsavel', 'required');
        }

        // Validating the organization who is actually working at the site
        if (!isset($form_data['semInstituicao'])) {
            $this->form_validation->set_rules('instituicaoAtuandoLocal[]', 'Instituição/Empresa Atuando no Local', 'required');
        } else {
            $this->form_validation->set_rules('semInstituicao', 'Instituição Atuando', 'required');
        }

        // Validating the oil form: ship identification
        if (!isset($form_data['semNavioInstalacao'])) {
            $this->form_validation->set_rules('inputNomeNavio', 'Nome do Navio', 'required');
            $this->form_validation->set_rules('inputNomeInstalacao', 'Nome da Instalação', 'required');
        } else {
            $this->form_validation->set_rules('semInstituicao', 'Informações sobre o navio/instalação', 'required');
        }
    }

    public function validate()
    {
        $this->load->helper('form');

        $form_data =  $this->input->post();

        // Set the rules for validating the form
        $this->formSetRules($form_data);

        // $this->firephp->log($form_data);

        if ($this->form_validation->run() == FALSE) {
            $this->validateForm();
        } else {
            $this->insertDB($form_data);
            if($this->session->userdata('logged_in'))
                $this->sendMail($form_data);
        }
    }

    public function createForm()
    {
        $data = $this->input->post();

        $data['typeOfForm'] = "create";

        $form['data'] = $data;

        $this->load->view('templates/form', $form);

    }

    public function loadForm($nro_ocorrencia)
    {
        $this->load->helper('form');

        $this->load->model('form_model');

        $data = $this->form_model->load($nro_ocorrencia);

        $this->firephp->log($data);

        $data['typeOfForm'] = "load";

        $form['data'] = $data;

        // $this->firephp->log($form);

        $this->load->view('templates/form',$form);
    }

    public function validateForm()
    {
        $this->load->helper('form');

        $data =  $this->input->post();

        // $this->firephp->log($form);

        $data['typeOfForm'] = "validate";

        $form['data'] = $data;

        $this->load->view('templates/form', $form);
    }

    public function sendMail($form_data)
    {

        $config = Array(
            'protocol' => "smtp",
            'smtp_host' => "mailrelay.ibama.gov.br",
            'smtp_port' => 25,
            'smtp_user' => "ibama.siema@gmail.com",
            'smtp_pass' => "ibama@siema",
            'charset'   => "iso-8859-1"
            );

        $this->load->library("email", $config);
        $this->email->set_newline("\r\n");

        $this->email->from("ibama.siema@gmail.com", "SIEMA");
        $this->email->to($this->session->userdata('mail'));

        $this->email->subject($form_data['comunicado'] . ' - SIEMA');
        $this->email->message('Formuário número '. $form_data['comunicado'] . ' enviado com sucesso.');

        $this->email->send();
    }
}
