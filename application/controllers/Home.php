<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('AuthLDAP');

        // Enable firebug
        $this->load->library('Firephp');
        $this->firephp->setEnabled(TRUE);
    }

    public function index()
    {
        //$this->firephp->log("Index");
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

        $this->form_validation->set_message('required', 'O Campo %s é obrigatório');

        // $this->firephp->log($form_data);

        // Validating the location
        if (!isset($form_data["semLocalizacao"])) {
            $this->form_validation->set_rules('inputLat', 'Latitude', 'required');
            $this->form_validation->set_rules('inputLng', 'Longitude ', 'required');
            // $this->form_validation->set_rules('inputMunicipio', 'Municipio', 'required');
            // $this->form_validation->set_rules('inputUF', 'UF', 'required');
            if(isset($form_data["inputEPSG"])) {
                $this->form_validation->set_rules('inputEPSG', 'EPSG', 'required');
            }
        } else {
            $this->form_validation->set_rules('semLocalizacao', 'Localizacao', 'required');
        }

        // Validating the Time and Date when the Accident were observed
        if (!isset($form_data["semDataObs"])) {
            $this->form_validation->set_rules('inputDataObs', 'Data da Observação', 'required');
            // $this->form_validation->set_rules('inputHoraObs', 'Hora da Observação', 'required');
            $this->form_validation->set_rules('PeriodoObs', 'Período da Observação', 'required');
        } else {
            $this->form_validation->set_rules('semDataObs', 'Data e Hora da Observação', 'required');
        }

        // Validating the Time and Date when the Accident happened
        if (!isset($form_data["semDataInci"])) {
            $this->form_validation->set_rules('inputDataInci', 'Data do Acidente', 'required');
            // $this->form_validation->set_rules('inputHoraInci', 'Hora do Acidente', 'required');
            $this->form_validation->set_rules('PeriodoInci', 'Período do Acidente', 'required');
        } else {
            $this->form_validation->set_rules('semDataInci', 'Data e Hora do Acidente', 'required');
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
            // $this->form_validation->set_rules('inputCPFCNPJ', 'CPF/CNPJ da Empresa/Orgão Responsavel', 'required');
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
            // $this->form_validation->set_rules('inputNomeNavio', 'Nome do Navio', 'required');
            // $this->form_validation->set_rules('inputNomeInstalacao', 'Nome da Instalação', 'required');
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
            $this->validateForm($form_data);
        } else {
            $this->insertDB($form_data);
            if($this->session->userdata('logged_in'))
                $this->sendMail($form_data);

        }
    }

    public function createForm()
    {
        $data = $this->dataForm('');

        $data['typeOfForm'] = "create";

        $form['data'] = $data;

        $this->load->view('templates/form', $form);

    }

    public function dataForm($formLoad)
    {
        // 1. Localizacao

        $data['inputLat'] = array(
            'id'           => 'inputLat',
            'name'         => 'inputLat',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'Latitude',
            'value'        => set_value('inputLat')
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputLat'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputLng'] = array(
            'id'           => 'inputLng',
            'name'         => 'inputLng',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'Longitude',
            'value'        => set_value('inputLng')
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputLng'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputEPSG'] = array(
            '4674'         => 'SIRGAS 2000 [4674]',
            '900913'       => 'Google [900913]',
            '4326'         => 'WGS84 [4326]',
            '4291'         => 'SAD69 [4291]'
        );

        $data['inputMunicipio'] = array(
            'id'           => 'inputMunicipio',
            'name'         => 'inputMunicipio',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'Nome',
            'value'        => set_value('inputMunicipio')
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputMunicipio'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputUF'] = array(
            'id'           => 'inputUF',
            'name'         => 'inputUF',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'UF',
            'value'        => set_value('inputUF')
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputUF'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputEndereco'] = array(
            'id'           => 'inputEndereco',
            'name'         => 'inputEndereco',
            'type'         => 'text',
            'class'        => 'input-large',
            'placeholder'  => '',
            'value'        => set_value('inputEndereco')
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputEndereco'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['semLocalizacao'] = array(
            'id'           => 'semLocalizacao',
            'name'         => 'semLocalizacao',
            'type'         => 'checkbox',
        );
        if(set_value('semLocalizacao') == "on"){
            $data['semLocalizacao'] = array(
                'checked'  => 'checked'
            );
        }

        // 2. Data e Hora do Acidente

        $data['inputDataObs'] = array(
            'id'           => 'inputDataObs',
            'name'         => 'inputDataObs',
            'type'         => 'text',
            'class'        => 'input-medium',
            'placeholder'  => 'DD/MM/AAAA',
            'value'        => set_value('inputDataObs')
        );
        if(set_value('semDataObs') == "on"){
            $data['inputDataObs'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputHoraObs'] = array(
            'id'           => 'inputHoraObs',
            'name'         => 'inputHoraObs',
            'type'         => 'text',
            'class'        => 'input-medium',
            'placeholder'  => 'HH:MM',
            'value'        => set_value('inputHoraObs')
        );
        if(set_value('semDataObs') == "on"){
            $data['inputHoraObs'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['PerObsMatu'] = array(
            'id'           => 'PerObsMatu',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsMatutino',
        );

        $data['PerObsVesper'] = array(
            'id'           => 'PerObsVesper',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsVespertino',
        );

        $data['PerObsNotu'] = array(
            'id'           => 'PerObsNotu',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsNoturno',
        );

        $data['PerObsMadru'] = array(
            'id'           => 'PerObsMadru',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsMadrugada',
        );

        $data['semDataObs'] = array(
            'id'           => 'semDataObs',
            'name'         => 'semDataObs',
            'type'         => 'checkbox',
        );
        if(set_value('semDataObs') == "on"){
            $data['semDataObs'] = array(
                'checked'  => 'checked'
            );
        }

        $data['inputDataInci'] = array(
            'id'           => 'inputDataInci',
            'name'         => 'inputDataInci',
            'type'         => 'text',
            'class'        => 'input-medium',
            'placeholder'  => 'DD/MM/AAAA',
            'value'        => set_value('inputDataInci')
        );
        if(set_value('semDataObs') == "on"){
            $data['inputDataInci'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputHoraInci'] = array(
            'id'           => 'inputHoraInci',
            'name'         => 'inputHoraInci',
            'type'         => 'datetime',
            'class'        => 'input-medium',
            'placeholder'  => 'HH:MM',
            'value'        => set_value('inputHoraInci')
        );
        if(set_value('semDataInci') == "on"){
            $data['inputHoraInci'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['PerInciMatu'] = array(
            'id'           => 'PerInciMatu',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciMatutino',
        );

        $data['PerInciVesper'] = array(
            'id'           => 'PerInciVesper',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciVespertino',
        );

        $data['PerInciNotu'] = array(
            'id'           => 'PerInciNotu',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciNoturno',
        );

        $data['PerInciMadru'] = array(
            'id'           => 'PerInciMadru',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciMadrugada',
        );

        $data['semDataInci'] = array(
            'id'           => 'semDataInci',
            'name'         => 'semDataInci',
            'type'         => 'checkbox',
        );
        if(set_value('semDataInci') == "on"){
            $data['semDataInci'] = array(
                'checked'  => 'checked'
            );
        }

        // 3. Origem do acidente

        $data['inputCompOrigem'] = array(
            'id'           => 'inputCompOrigem',
            'name'         => 'inputCompOrigem',
            'rows'         => '2',
            'class'        => 'input-large',
            'maxlength'    => '150',
            'value'        => set_value('inputCompOrigem')
        );
        if(set_value('semOrigem') == "on"){
            $data['inputCompOrigem'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['semOrigem'] = array(
            'id'           => 'semOrigem',
            'name'         => 'semOrigem',
            'type'         => 'checkbox',
        );
        if(set_value('semOrigem') == "on"){
            $data['semOrigem'] = array(
                'checked'  => 'checked'
            );
        }

        // 4. Tipo do Evento

        $data['inputNomeNavio'] = array(
            'id'           => 'inputNomeNavio',
            'name'         => 'inputNomeNavio',
            'type'         => 'text',
            'class'        => 'input-medium',
            'value'        => set_value('inputNomeNavio')
        );
        if(set_value('semNavioInstalacao') == "on"){
            $data['inputNomeNavio'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputNomeInstalacao'] = array(
            'id'           => 'inputNomeInstalacao',
            'name'         => 'inputNomeInstalacao',
            'type'         => 'text',
            'class'        => 'input-medium',
            'value'        => set_value('inputNomeInstalacao')
        );
        if(set_value('semNavioInstalacao') == "on"){
            $data['inputNomeInstalacao'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['semNavioInstalacao'] = array(
            'id'           => 'semNavioInstalacao',
            'name'         => 'semNavioInstalacao',
            'type'         => 'checkbox',
        );
        if(set_value('semNavioInstalacao') == "on"){
            $data['semNavioInstalacao'] = array(
                'checked'  => 'checked'
            );
        }

        $data['inputCompEvento'] = array(
            'id'           => 'inputCompEvento',
            'name'         => 'inputCompEvento',
            'rows'         => '2',
            'class'        => 'input-large',
            'maxlength'    => '150',
            'value'        => set_value('inputCompEvento')
        );
        if(set_value('semEvento') == "on"){
            $data['inputCompEvento'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['semEvento'] = array(
            'id'           => 'semEvento',
            'name'         => 'semEvento',
            'type'         => 'checkbox',
        );
        if(set_value('semEvento') == "on"){
            $data['semEvento'] = array(
                'checked'  => 'checked'
            );
        }

        // 6. Detalhes do acidente

        $data['inputCausaProvavel'] = array(
            'id'           => 'inputCausaProvavel',
            'name'         => 'inputCausaProvavel',
            'rows'         => '2',
            'class'        => 'input-large',
            'value'        => set_value('inputCausaProvavel')
        );
        if(set_value('semCausa') == "on"){
            $data['inputCausaProvavel'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['semCausa'] = array(
            'id'           => 'semCausa',
            'name'         => 'semCausa',
            'type'         => 'checkbox',
        );
        if(set_value('semCausa') == "on"){
            $data['semCausa'] = array(
                'checked'  => 'checked'
            );
        }

        $data['SitParal'] = array(
            'id'           => 'SitParal',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitParal', 1)
        );

        $data['SitNaoParal'] = array(
            'id'           => 'SitNaoParal',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitNaoParal', 2)
        );

        $data['SitSemCondi'] = array(
            'id'           => 'SitSemCondi',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitSemCondi', 3)
        );

        $data['SitNaoSeApl'] = array(
            'id'           => 'SitNaoSeApl',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitNaoSeApl', 2)
        );

        // 7. Danos identificados

        $data['inputCompDano'] = array(
            'id'           => 'inputCompDano',
            'name'         => 'inputCompDano',
            'rows'         => '2',
            'maxlength'    => '150',
            'class'        => 'input-large',
            'value'        => set_value('inputCompDano')
        );
        if(set_value('semDanos') == "on"){
            $data['inputCompDano'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputDesDanos'] = array(
            'id'           => 'inputDesDanos',
            'name'         => 'inputDesDanos',
            'rows'         => '2',
            'class'        => 'input-large',
            'value'        => set_value('inputDesDanos')
        );
        if(set_value('semDanos') == "on"){
            $data['inputDesDano'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['semDanos'] = array(
            'id'           => 'semDanos',
            'name'         => 'semDanos',
            'type'         => 'checkbox',
        );
        if(set_value('semDanos') == "on"){
            $data['semDanos'] = array(
                'checked'  => 'checked'
            );
        }

        // 8. Indentificacao dos responsáveis

        $data['inputResponsavel'] = array(
            'id'           => 'inputResponsavel',
            'name'         => 'inputResponsavel',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => '',
            'value'        => set_value('inputResponsavel')
        );
        if(set_value('semResponsavel') == "on"){
            $data['inputResponsavel'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['inputCPFCNPJ'] = array(
            'id'           => 'inputCPFCNPJ',
            'name'         => 'inputCPFCNPJ',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => '',
            'value'        => set_value('inputCPFCNPJ')
        );
        if(set_value('semResponsavel') == "on"){
            $data['inputCPFCNPJ'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['slctLicenca'] = array(
            '1'         => '1',
            '2'         => '2',
            '3'         => '3',
            '4'         => '4',
            '5'         => '5'
        );

        $data['semResponsavel'] = array(
            'id'           => 'semResponsavel',
            'name'         => 'semResponsavel',
            'type'         => 'checkbox',
        );
        if(set_value('semResponsavel') == "on"){
            $data['semResponsavel'] = array(
                'checked'  => 'checked'
            );
        }

        // 9. Instituicao atuando no local

        $data['inputCompInstituicao'] = array(
            'id'           => 'inputCompInstituicao',
            'name'         => 'inputCompInstituicao',
            'rows'         => '2',
            'class'        => 'input-large',
            'maxlength'    => '150',
            'value'        => set_value('inputCompInstituicao')
        );
        if(set_value('semInstituicao') == "on"){
            $data['inputCompInstituicao'] = array(
                'disabled' => 'disabled'
            );
        }

        $data['semInstituicao'] = array(
            'id'           => 'semInstituicao',
            'name'         => 'semInstituicao',
            'type'         => 'checkbox',
        );
        if(set_value('semInstituicao') == "on"){
            $data['semInstituicao'] = array(
                'checked'  => 'checked'
            );
        }

        // 10. Procedimentos adotados

        $data['planoEmergSim'] = array(
            'id'           => 'Sim',
            'name'         => 'planoEmergincia',
            'type'         => 'radio',
            'value'        => set_value('planoEmergSim', 0)
        );

        $data['planoEmergNao'] = array(
            'id'           => 'Nao',
            'name'         => 'planoEmergincia',
            'type'         => 'radio',
            'value'        => set_value('planoEmergNao', 1)
        );

        $data['planoAcionado'] = array(
            'id'           => 'planoAcionado',
            'name'         => 'planoAcionado',
            'type'         => 'checkbox',
        );
        if(set_value('planoAcionado') == "on"){
            $data['planoAcionado'] = array(
                'checked'  => 'checked'
            );
        }

        $data['outrasMedidas'] = array(
            'id'           => 'outrasMedidas',
            'name'         => 'outrasMedidas',
            'type'         => 'checkbox',
        );
        if(set_value('outrasMedidas') == "on"){
            $data['outrasMedidas'] = array(
                'checked'  => 'checked'
            );
        }

        $data['inputMedidasTomadas'] = array(
            'id'           => 'inputMedidasTomadas',
            'name'         => 'inputMedidasTomadas',
            'rows'         => '2',
            'class'        => 'input-large',
            'value'        => set_value('inputMedidasTomadas')
        );

        // 11. Informacoes do informante

        $data['inputNomeInformante'] = array(
            'id'           => 'inputNomeInformante',
            'name'         => 'inputNomeInformante',
            'class'        => 'input-large',
            'value'        => set_value('inputNomeInformante',  $this->session->userdata('name'))
        );

        $data['inputFuncaoNavio'] = array(
            'id'           => 'inputFuncaoNavio',
            'name'         => 'inputFuncaoNavio',
            'class'        => 'input-large',
            'value'        => set_value('inputFuncaoNavio')
        );

        $data['inputEmailInformante'] = array(
            'id'           => 'inputEmailInformante',
            'name'         => 'inputEmailInformante',
            'class'        => 'input-large',
            'value'        => set_value('inputEmailInformante', $this->session->userdata('mail'))
        );

        $data['inputTelInformante'] = array(
            'id'           => 'inputTelInformante',
            'name'         => 'inputTelInformante',
            'class'        => 'input-large',
            'value'        => set_value('inputTelInformante')
        );

        // 12. Informacoes gerais

        $data['inputDesOcorrencia'] = array(
            'id'           => 'inputDesOcorrencia',
            'name'         => 'inputDesOcorrencia',
            'rows'         => '2',
            'class'        => 'input-large',
            'value'        => set_value('inputDesOcorrencia')
        );

        $data['inputDesObs'] = array(
            'id'           => 'inputDesObs',
            'name'         => 'inputDesObs',
            'rows'         => '2',
            'class'        => 'input-large',
            'value'        => set_value('inputDesObs')
        );

        // Values of the Period
        $data['PeriodoObs'] = isset($formLoad['PeriodoObs']) ? $formLoad['PeriodoObs'] : '' ;

        $data['PeriodoInci'] = isset($formLoad['PeriodoInci']) ? $formLoad['PeriodoInci'] : '' ;


        // Value of the "Comunicado"
        $data['comunicado'] = isset($formLoad['comunicado']) ? $formLoad['comunicado'] : '';


        // Checkbox fields
        $data['tipoLocalizacao'] = isset($formLoad['tipoLocalizacao']) ? $formLoad['tipoLocalizacao'] : '';

        $data['tipoEvento'] = isset($formLoad['tipoEvento']) ? $formLoad['tipoEvento'] : '';

        $data['tipoDanoIdentificado'] = isset($formLoad['tipoDanoIdentificado']) ? $formLoad['tipoDanoIdentificado'] : '';

        $data['instituicaoAtuandoLocal'] = isset($formLoad['instituicaoAtuandoLocal']) ? $formLoad['instituicaoAtuandoLocal'] : '';

        $data['tipoFonteInformacao'] = isset($formLoad['tipoFonteInformacao']) ? $formLoad['tipoFonteInformacao'] : '';


        // Variables that control the type or the form.
        $data['hasOleo'] = isset($formLoad['hasOleo']) ? $formLoad['hasOleo'] : '';

        $data['isServIBAMA'] = isset($formLoad['isServIBAMA']) ? $formLoad['isServIBAMA'] : '';

        return $data;
    }

    public function loadForm($nro_ocorrencia)
    {
        $this->load->helper('form');

        $this->load->model('form_model');

        $data = $this->form_model->load($nro_ocorrencia);

        if ($data != "") {
            $this->firephp->log($data);

            $data['typeOfForm'] = "load";

            $form['data'] = $data;

            // $this->firephp->log($form);

            $this->load->view('templates/form',$form);
        } else {
            $this->load->view('templates/form_fail');
        }
    }

    public function validateForm($form_data)
    {
        $this->load->helper('form');

        $data = $this->dataForm($form_data);

        // $this->firephp->log($form);

        $data['typeOfForm'] = "validate";

        $form['data'] = $data;

        $this->load->view('templates/form', $form);
    }

    public function sendMail($form_data)
    {
        $this->firephp->log("sendMail!!");
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
        $this->email->to("carolsro@gmail.com");

        $this->email->subject($form_data['comunicado'] . ' - SIEMA');
        $this->email->message('Formuário número '. $form_data['comunicado'] . ' enviado com sucesso.');

        $this->email->send();
    }
}
