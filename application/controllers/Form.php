<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('AuthLDAP');

        // Enable firebug
        $this->load->library('Firephp');
        $this->firephp->setEnabled(TRUE);
    }

    public function insertDB($form_data)
    {

        $this->load->model('form_model');

        if ($form_data['typeOfForm'] == 'create') {

            $this->form_model->save($form_data);

        } else {

            $this->form_model->update($form_data);

        }

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
        // Validating the "Situação Atual da Descarga"
        if (!isset($form_data['SituacaoDescarga'])) {
            $this->form_validation->set_rules('SituacaoDescarga', 'Situação Atual da Descarga', 'required');
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


        // Validating the "Existencia de Plano de Emergencia ou Similar"
        if (!isset($form_data['semProcedimentos'])) {
            $this->form_validation->set_rules('planoEmergencia', 'Plano de Emergencia ou Similar', 'required');
        } else {
            $this->form_validation->set_rules('semProcedimentos', 'Plano de Emergencia', 'required');
        }

        // Validating the oil form: ship identification
        if (!isset($form_data['semNavioInstalacao'])) {
            // $this->form_validation->set_rules('inputNomeNavio', 'Nome do Navio', 'required');
            // $this->form_validation->set_rules('inputNomeInstalacao', 'Nome da Instalação', 'required');
        } else {
            $this->form_validation->set_rules('semNavioInstalacao', 'Informações sobre o navio/instalação', 'required');
        }
    }

    public function validate()
    {
        $this->load->helper('form');

        $form_data =  $this->input->post();

        // Set the rules for validating the form
        $this->formSetRules($form_data);

        if ($this->form_validation->run() == FALSE) {
            $this->validateForm($form_data);
        } else {
            $this->insertDB($form_data);
            // $this->firephp->log("DATA FORM");
            // $this->firephp->log($form_data);
            if($this->session->userdata('logged_in'))
                $this->sendMail($form_data);

        }
    }

    public function createForm()
    {
        $formLoad = $this->input->post();

        $formLoad['typeOfForm'] = 'create';

        $data = $this->dataForm($formLoad);

        $form['data'] = $data;

        $this->load->view('templates/form', $form);

    }

    public function dataForm($formLoad)
    {
        // $this->firephp->log($formLoad);


        // Value of the "Comunicado"
        $data['comunicado'] = isset($formLoad['comunicado']) ? $formLoad['comunicado'] : '';


        $data['typeOfForm'] = array (
            'id'            => 'typeOfForm',
            'name'          => 'typeOfForm',
            'type'          => 'hidden',
            'value'         => $formLoad['typeOfForm']
        );

        if (isset($formLoad['hasOleo'])) {
            $data['hasOleo'] = array (
                'id'            => 'hasOleo',
                'name'          => 'hasOleo',
                'type'          => 'hidden',
                'value'         => 'on'
            );
        }

        if (isset($formLoad['isServIBAMA'])) {
            $data['isServIBAMA'] = array (
                'id'            => 'isServIBAMA',
                'name'          => 'isServIBAMA',
                'type'          => 'hidden',
                'value'         => 'on'
            );
        }

        // 1. Localizacao

        $data['inputLat'] = array(
            'id'           => 'inputLat',
            'name'         => 'inputLat',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'Latitude',
            'readonly'     => 'readonly',
            'value'        => set_value('inputLat', isset($formLoad['inputLat']) ? $formLoad['inputLat'] : "")
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputLat'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputLng'] = array(
            'id'           => 'inputLng',
            'name'         => 'inputLng',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'Longitude',
            'readonly'     => 'readonly',
            'value'        => set_value('inputLng', isset($formLoad['inputLng']) ? $formLoad['inputLng'] : "")
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputLng'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputEPSG'] = array(
            '4674'         => 'SIRGAS 2000 [4674]',
            '900913'       => 'Google [900913]',
            '4326'         => 'WGS84 [4326]',
            '4291'         => 'SAD69 [4291]'
        );
        if(isset($formLoad['inputEPSG'])) {
            $data['inputEPSG_Selected'] = $formLoad['inputEPSG'];
        }

        $data['inputMunicipio'] = array(
            'id'           => 'inputMunicipio',
            'name'         => 'inputMunicipio',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'Nome',
            'maxlength'    => '128',
            'value'        => set_value('inputMunicipio', isset($formLoad['inputMunicipio']) ? $formLoad['inputMunicipio'] : "")
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputMunicipio'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputUF'] = array(
            'id'           => 'inputUF',
            'name'         => 'inputUF',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'UF',
            'maxlength'    => '2',
            'value'        => set_value('inputUF', isset($formLoad['inputUF']) ? $formLoad['inputUF'] : "")
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputUF'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputEndereco'] = array(
            'id'           => 'inputEndereco',
            'name'         => 'inputEndereco',
            'type'         => 'text',
            'class'        => 'input-large',
            'placeholder'  => '',
            'maxlength'    => '512',
            'value'        => set_value('inputEndereco', isset($formLoad['inputEndereco']) ? $formLoad['inputEndereco'] : "")
        );
        if(set_value('semLocalizacao') == "on"){
            $data['inputEndereco'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['semLocalizacao'] = array(
            'id'           => 'semLocalizacao',
            'name'         => 'semLocalizacao',
            'type'         => 'checkbox',
        );
        if(set_value('semLocalizacao') == "on"){
            $data['semLocalizacao'] += array(
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
            'value'        => isset($formLoad['inputDataObs']) ? $formLoad['inputDataObs'] : set_value('inputDataObs')
        );
        if(isset($formLoad['semDataObs'])){
            $data['inputDataObs'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputHoraObs'] = array(
            'id'           => 'inputHoraObs',
            'name'         => 'inputHoraObs',
            'type'         => 'text',
            'class'        => 'input-medium',
            'placeholder'  => 'HH:MM',
            'value'        => set_value('inputHoraObs', isset($formLoad['inputHoraObs']) ? $formLoad['inputHoraObs'] : "")
        );
        if(isset($formLoad['semDataObs'])){
            $data['inputHoraObs'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerObsMatu'] = array(
            'id'           => 'PerObsMatu',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsMatutino',
        );
        if(isset($formLoad['semDataObs'])){
            $data['PerObsMatu'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerObsVesper'] = array(
            'id'           => 'PerObsVesper',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsVespertino',
        );
        if(isset($formLoad['semDataObs'])){
            $data['PerObsVesper'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerObsNotu'] = array(
            'id'           => 'PerObsNotu',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsNoturno',
        );
        if(isset($formLoad['semDataObs'])){
            $data['PerObsNotu'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerObsMadru'] = array(
            'id'           => 'PerObsMadru',
            'name'         => 'PeriodoObs',
            'type'         => 'radio',
            'value'        => 'obsMadrugada',
        );
        if(isset($formLoad['semDataObs'])){
            $data['PerObsMadru'] += array(
                'disabled' => 'disabled'
            );
        }

        // Values of the Period
        $data['PeriodoObs'] = isset($formLoad['PeriodoObs']) ? $formLoad['PeriodoObs'] : '' ;

        $data['semDataObs'] = array(
            'id'           => 'semDataObs',
            'name'         => 'semDataObs',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semDataObs'])){
            $data['semDataObs'] += array(
                'checked'  => 'checked'
            );
        }

        $data['inputDataInci'] = array(
            'id'           => 'inputDataInci',
            'name'         => 'inputDataInci',
            'type'         => 'text',
            'class'        => 'input-medium',
            'placeholder'  => 'DD/MM/AAAA',
            'value'        => isset($formLoad['inputDataInci']) ? $formLoad['inputDataInci'] : set_value('inputDataInci')
        );
        if(isset($formLoad['semDataInci'])){
            $data['inputDataInci'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputHoraInci'] = array(
            'id'           => 'inputHoraInci',
            'name'         => 'inputHoraInci',
            'type'         => 'datetime',
            'class'        => 'input-medium',
            'placeholder'  => 'HH:MM',
            'value'        => set_value('inputHoraInci', isset($formLoad['inputHoraInci']) ? $formLoad['inputHoraInci'] : "")
        );
        if(isset($formLoad['semDataInci'])){
            $data['inputHoraInci'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerInciMatu'] = array(
            'id'           => 'PerInciMatu',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciMatutino',
        );
        if(isset($formLoad['semDataInci'])){
            $data['PerInciMatu'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerInciVesper'] = array(
            'id'           => 'PerInciVesper',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciVespertino',
        );
        if(isset($formLoad['semDataInci'])){
            $data['PerInciVesper'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerInciNotu'] = array(
            'id'           => 'PerInciNotu',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciNoturno',
        );
        if(isset($formLoad['semDataInci'])){
            $data['PerInciNotu'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['PerInciMadru'] = array(
            'id'           => 'PerInciMadru',
            'name'         => 'PeriodoInci',
            'type'         => 'radio',
            'value'        => 'inciMadrugada',
        );
        if(isset($formLoad['semDataInci'])){
            $data['PerInciMadru'] += array(
                'disabled' => 'disabled'
            );
        }

        // Values of the Period
        $data['PeriodoInci'] = isset($formLoad['PeriodoInci']) ? $formLoad['PeriodoInci'] : '' ;

        $data['semDataInci'] = array(
            'id'           => 'semDataInci',
            'name'         => 'semDataInci',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semDataInci'])){
            $data['semDataInci'] += array(
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
        if(isset($formLoad['semOrigem'])){
            $data['inputCompOrigem'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['semOrigem'] = array(
            'id'           => 'semOrigem',
            'name'         => 'semOrigem',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semOrigem'])){
            $data['semOrigem'] += array(
                'checked'  => 'checked'
            );
        }

        // Checkbox fields
        $data['tipoLocalizacao'] = isset($formLoad['tipoLocalizacao']) ? $formLoad['tipoLocalizacao'] : '';

        // 4. Tipo do Evento

        $data['inputNomeNavio'] = array(
            'id'           => 'inputNomeNavio',
            'name'         => 'inputNomeNavio',
            'type'         => 'text',
            'class'        => 'input-medium',
            'maxlength'    => '150',
            'value'        => set_value('inputNomeNavio', isset($formLoad['inputNomeNavio']) ? $formLoad['inputNomeNavio'] : '')
        );
        if(isset($formLoad['semNavioInstalacao'])){
            $data['inputNomeNavio'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputNomeInstalacao'] = array(
            'id'           => 'inputNomeInstalacao',
            'name'         => 'inputNomeInstalacao',
            'type'         => 'text',
            'class'        => 'input-medium',
            'maxlength'    => '150',
            'value'        => set_value('inputNomeInstalacao', isset($formLoad['inputNomeInstalacao']) ? $formLoad['inputNomeInstalacao'] : '')
        );
        if(isset($formLoad['semNavioInstalacao'])){
            $data['inputNomeInstalacao'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['semNavioInstalacao'] = array(
            'id'           => 'semNavioInstalacao',
            'name'         => 'semNavioInstalacao',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semNavioInstalacao'])){
            $data['semNavioInstalacao'] += array(
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
        if(isset($formLoad['semEvento'])){
            $data['inputCompEvento'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['semEvento'] = array(
            'id'           => 'semEvento',
            'name'         => 'semEvento',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semEvento'])){
            $data['semEvento'] += array(
                'checked'  => 'checked'
            );
        }

        $data['tipoEvento'] = isset($formLoad['tipoEvento']) ? $formLoad['tipoEvento'] : '';

        // 6. Detalhes do acidente

        $data['inputCausaProvavel'] = array(
            'id'           => 'inputCausaProvavel',
            'name'         => 'inputCausaProvavel',
            'rows'         => '2',
            'class'        => 'input-large',
            'maxlength'    => '2000',
            'value'        => set_value('inputCausaProvavel', isset($formLoad['inputCausaProvavel']) ? $formLoad['inputCausaProvavel'] : '')
        );
        if(isset($formLoad['semCausa'])){
            $data['inputCausaProvavel'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['semCausa'] = array(
            'id'           => 'semCausa',
            'name'         => 'semCausa',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semCausa'])){
            $data['semCausa'] += array(
                'checked'  => 'checked'
            );
        }

        $data['SitParal'] = array(
            'id'           => 'SitParal',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitParal', 1)
        );
        if(isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '1'){
            $data['SitParal'] += array(
                'checked'  => 'checked'
            );
        }

        $data['SitNaoParal'] = array(
            'id'           => 'SitNaoParal',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitNaoParal', 2)
        );
        if(isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '2'){
            $data['SitNaoParal'] += array(
                'checked'  => 'checked'
            );
        }

        $data['SitSemCondi'] = array(
            'id'           => 'SitSemCondi',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitSemCondi', 3)
        );
        if(isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '3'){
            $data['SitSemCondi'] += array(
                'checked'  => 'checked'
            );
        }

        $data['SitNaoSeApl'] = array(
            'id'           => 'SitNaoSeApl',
            'name'         => 'SituacaoDescarga',
            'type'         => 'radio',
            'value'        => set_value('SitNaoSeApl', 4)
        );
        if(isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '4'){
            $data['SitNaoSeApl'] += array(
                'checked'  => 'checked'
            );
        }

        // 7. Danos identificados

        $data['inputCompDano'] = array(
            'id'           => 'inputCompDano',
            'name'         => 'inputCompDano',
            'rows'         => '2',
            'maxlength'    => '1000',
            'class'        => 'input-large',
            'value'        => set_value('inputCompDano')
        );
        if(isset($formLoad['semDanos'])){
            $data['inputCompDano'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputDesDanos'] = array(
            'id'           => 'inputDesDanos',
            'name'         => 'inputDesDanos',
            'rows'         => '2',
            'maxlength'    => '2500',
            'class'        => 'input-large',
            'value'        => set_value('inputDesDanos')
        );
        if(isset($formLoad['semDanos'])){
            $data['inputDesDanos'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['semDanos'] = array(
            'id'           => 'semDanos',
            'name'         => 'semDanos',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semDanos'])){
            $data['semDanos'] += array(
                'checked'  => 'checked'
            );
        }

        $data['tipoDanoIdentificado'] = isset($formLoad['tipoDanoIdentificado']) ? $formLoad['tipoDanoIdentificado'] : '';

        // 8. Indentificacao dos responsáveis

        $data['inputResponsavel'] = array(
            'id'           => 'inputResponsavel',
            'name'         => 'inputResponsavel',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => '',
            'maxlength'    => '150',
            'value'        => set_value('inputResponsavel',isset($formLoad['inputResponsavel']) ? $formLoad['inputResponsavel'] : '')
        );
        if(isset($formLoad['semResponsavel'])){
            $data['inputResponsavel'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputCPFCNPJ'] = array(
            'id'           => 'inputCPFCNPJ',
            'name'         => 'inputCPFCNPJ',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => '',
            'maxlength'    => '20',
            'value'        => set_value('inputCPFCNPJ',isset($formLoad['inputCPFCNPJ']) ? $formLoad['inputCPFCNPJ'] : '')
        );
        if(isset($formLoad['semResponsavel'])){
            $data['inputCPFCNPJ'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['slctLicenca'] = array(
            '1'         => 'Licença ambiental federal',
            '2'         => 'Licença ambiental estadual',
            '3'         => 'Licença ambiental municipal'
        );

        $data['semResponsavel'] = array(
            'id'           => 'semResponsavel',
            'name'         => 'semResponsavel',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semResponsavel'])){
            $data['semResponsavel'] += array(
                'checked'  => 'checked'
            );
        }

        // 9. Instituicao atuando no local
        $data['inputInfoInstituicaoNome'] = array(
            'id'           => 'inputInfoInstituicaoNome',
            'name'         => 'inputInfoInstituicaoNome',
            'type'         => 'text',
            'class'        => 'input',
            'placeholder'  => 'Nome da Empresa',
            'maxlength'    => '128',
            'value'        => set_value('inputInfoInstituicaoNome', isset($formLoad['inputInfoInstituicaoNome']) ? $formLoad['inputInfoInstituicaoNome'] : "")
        );
        if(set_value('semInstituicao') == "on"){
            $data['inputInfoInstituicaoNome'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputInfoInstituicaoTelefone'] = array(
            'id'           => 'inputInfoInstituicaoTelefone',
            'name'         => 'inputInfoInstituicaoTelefone',
            'type'         => 'text',
            'class'        => 'input-small',
            'placeholder'  => 'Telefone da Empresa',
            'maxlength'    => '12',
            'value'        => set_value('inputInfoInstituicaoTelefone', isset($formLoad['inputInfoInstituicaoTelefone']) ? $formLoad['inputInfoInstituicaoTelefone'] : "")
        );
        if(set_value('semInstituicao') == "on"){
            $data['inputInfoInstituicaoTelefone'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['inputCompInstituicao'] = array(
            'id'           => 'inputCompInstituicao',
            'name'         => 'inputCompInstituicao',
            'rows'         => '2',
            'class'        => 'input-large',
            'maxlength'    => '150',
            'value'        => set_value('inputCompInstituicao')
        );
        if(isset($formLoad['semInstituicao'])){
            $data['inputCompInstituicao'] += array(
                'disabled' => 'disabled'
            );
        }

        $data['semInstituicao'] = array(
            'id'           => 'semInstituicao',
            'name'         => 'semInstituicao',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semInstituicao'])){
            $data['semInstituicao'] += array(
                'checked'  => 'checked'
            );
        }

        $data['instituicaoAtuandoLocal'] = isset($formLoad['instituicaoAtuandoLocal']) ? $formLoad['instituicaoAtuandoLocal'] : '';

        // 10. Procedimentos adotados

        $data['planoEmergSim'] = array(
            'id'           => 'planoEmergSim',
            'name'         => 'planoEmergencia',
            'type'         => 'radio',
            'value'        => set_value('planoEmergSim', 0)
        );
        if(isset($formLoad['planoEmergencia']) && ($formLoad['planoEmergencia'] == '1')){
            $data['planoEmergSim'] += array(
                'checked'  => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['planoEmergSim'] += array(
                'disabled'  => 'disabled'
            );
        }

        $data['planoEmergNao'] = array(
            'id'           => 'planoEmergNao',
            'name'         => 'planoEmergencia',
            'type'         => 'radio',
            'value'        => set_value('planoEmergNao', 1)
        );
        if(isset($formLoad['planoEmergencia']) && ($formLoad['planoEmergencia'] == '0')){
            $data['planoEmergNao'] += array(
                'checked'  => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['planoEmergNao'] += array(
                'disabled'  => 'disabled'
            );
        }

        $data['planoAcionado'] = array(
            'id'           => 'planoAcionado',
            'name'         => 'planoAcionado',
            'type'         => 'checkbox',
            'value'        => 'on'
        );
        if(isset($formLoad['planoAcionado'])){
            $data['planoAcionado'] += array(
                'checked'  => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['planoAcionado'] += array(
                'disabled'  => 'disabled'
            );
        }

        $data['outrasMedidas'] = array(
            'id'           => 'outrasMedidas',
            'name'         => 'outrasMedidas',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['outrasMedidas'])){
            $data['outrasMedidas'] += array(
                'checked'  => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['outrasMedidas'] += array(
                'disabled'  => 'disabled'
            );
        }

        $data['inputMedidasTomadas'] = array(
            'id'           => 'inputMedidasTomadas',
            'name'         => 'inputMedidasTomadas',
            'rows'         => '2',
            'class'        => 'input-large',
            'maxlength'    => '1000',
            'value'        => set_value('inputMedidasTomadas')
        );
        if (isset($formLoad['semProcedimentos'])) {
            $data['inputMedidasTomadas'] += array(
                'disabled'  => 'disabled'
            );
        }

        $data['semProcedimentos'] = array(
            'id'           => 'semProcedimentos',
            'name'         => 'semProcedimentos',
            'type'         => 'checkbox',
        );
        if(isset($formLoad['semProcedimentos'])){
            $data['semProcedimentos'] += array(
                'checked'  => 'checked'
            );
        }
        // 11. Identificação do Comunicante

        $data['inputNomeInformante'] = array(
            'id'           => 'inputNomeInformante',
            'name'         => 'inputNomeInformante',
            'class'        => 'input-large',
            'maxlength'    => '150',
            'value'        => set_value('inputNomeInformante', $this->session->userdata('name'))
        );

        $data['inputFuncaoNavio'] = array(
            'id'           => 'inputFuncaoNavio',
            'name'         => 'inputFuncaoNavio',
            'class'        => 'input-large',
            'maxlength'    => '150',
            'value'        => set_value('inputFuncaoNavio', isset($formLoad['inputFuncaoNavio']) ? $formLoad['inputFuncaoNavio'] : '')
        );

        $data['inputEmailInformante'] = array(
            'id'           => 'inputEmailInformante',
            'name'         => 'inputEmailInformante',
            'class'        => 'input-large',
            'maxlength'    => '150',
            'value'        => set_value('inputEmailInformante', $this->session->userdata('mail'))
        );

        $data['inputTelInformante'] = array(
            'id'           => 'inputTelInformante',
            'name'         => 'inputTelInformante',
            'class'        => 'input-large',
            'maxlength'    => '13',
            'value'        => set_value('inputTelInformante', isset($formLoad['inputTelInformante']) ? $formLoad['inputTelInformante'] : '')
        );

        // 12. Informacoes gerais

        $data['inputDesOcorrencia'] = array(
            'id'           => 'inputDesOcorrencia',
            'name'         => 'inputDesOcorrencia',
            'rows'         => '2',
            'class'        => 'input-large',
            'maxlength'    => '2500',
            'value'        => set_value('inputDesOcorrencia', isset($formLoad['inputDesOcorrencia']) ? $formLoad['inputDesOcorrencia'] : '')
        );

        $data['inputDesObs'] = array(
            'id'           => 'inputDesObs',
            'name'         => 'inputDesObs',
            'rows'         => '2',
            'maxlength'    => '2000',
            'class'        => 'input-large',
            'value'        => set_value('inputDesObs', isset($formLoad['inputDesObs']) ? $formLoad['inputDesObs'] : '')
        );

        // 13. Fonte da Informação.
        $data['tipoFonteInformacao'] = isset($formLoad['tipoFonteInformacao']) ? $formLoad['tipoFonteInformacao'] : '';

        // $this->firephp->log($formLoad);
        // $this->firephp->log($formLoad['hasOleo']);
        // $this->firephp->log($formLoad['ocorrencia_oleo']);

        // $data['isServIBAMA'] = isset($formLoad['isServIBAMA']) ? $formLoad['isServIBAMA'] : '';

        return $data;
    }

    public function loadForm($nro_ocorrencia)
    {
        $this->load->helper('form');

        $this->load->model('form_model');

        $formLoad = $this->form_model->load($nro_ocorrencia);

        if ($formLoad != "") {

            // $this->firephp->log($formLoad);

            $formLoad['typeOfForm'] = "load";

            $data = $this->dataForm($formLoad);

            $form['data'] = $data;

            // $this->firephp->log($data);

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

        $form['data'] = $data;

        // $this->firephp->log($form);

        $this->load->view('templates/form', $form);
    }

    public function sendMail($form_data)
    {

        /* Profiles:
          0: Cidadão que se cadastrou no sistema
          1: Empresa
          2: Servidor do IBAMA
          3: Orgão Público
        */
        $this->load->model('sendmail_model');

        // debug
        // $this->firephp->log("sendMail!!");

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
        $this->email->to("ibama.siema@gmail.com");

        $this->email->subject("Ibama – Comunicado de Acidente Ambiental");

        if(array_key_exists("inputHoraObs", $form_data) && array_key_exists("inputDataObs", $form_data))
            $message_body = $this->sendmail_model->getEmailBody(1,$form_data['comunicado'], $form_data["inputHoraObs"], $form_data["inputDataObs"]);
        else
            $message_body = $this->sendmail_model->getEmailBody(1,$form_data['comunicado'], null, null);


        $this->email->message($message_body);

        if ($this->email->send())
            $this->firephp->log("Email enviado com sucesso!");
        else
            $this->firephp->log("Erro ao enviar o email");
            $this->firephp->log($this->email->print_debugger());

    }
}
