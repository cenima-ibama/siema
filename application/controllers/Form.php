<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Form extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('AuthLDAP');

        // Enable firebug
        $this->load->library('Firephp');

        $this->load->model('form_model');

        $this->load->model('form_validations_model');

        $this->firephp->setEnabled(TRUE);
    }

    public function insertDB($form_data) {

        if ($form_data['typeOfForm'] == 'create') {

            $this->form_model->save($form_data);
        } else {

            $this->form_model->update($form_data);
        }
    }

    public function formSetRules($form_data) {

        // Changing the message to a more user friendly (In portuguese)
        $this->form_validation->set_message('required', 'O campo %s é obrigatório');
        //
        // 1. Localização
        //
        // if (!isset($form_data["semLocalizacao"])) {
        $this->form_validation->set_rules('inputLat', 'Latitude', 'required');
        $this->form_validation->set_rules('inputLng', 'Longitude ', 'required');
        // $this->form_validation->set_rules('inputMunicipio', 'Municipio', 'required');
        // $this->form_validation->set_rules('inputUF', 'UF', 'required');
        // if(isset($form_data["inputEPSG"])) {
        //     $this->form_validation->set_rules('inputEPSG', 'EPSG', 'required');
        // }
        // } else {
        //     $this->form_validation->set_rules('semLocalizacao', 'Localização', 'required');
        // }
        //
        // 2. Data e Hora do Acidente
        //
        if (!isset($form_data["semDataObs"])) {
            $this->form_validation->set_rules('inputDataObs', 'Data da observação', 'required');
            $this->form_validation->set_rules('PeriodoObs', 'Período da observação', 'required');
        } else {
            $this->form_validation->set_rules('semDataObs', 'Data e hora da observação', 'required');
        }
        if (!isset($form_data["semDataInci"])) {
            $this->form_validation->set_rules('inputDataInci', 'Data do acidente', 'required');
            $this->form_validation->set_rules('PeriodoInci', 'Período do acidente', 'required');
        } else {
            $this->form_validation->set_rules('semDataInci', 'Data e hora do acidente', 'required');
        }


        //
        // 3. Origem do Acidente
        //
        if (!isset($form_data['semOrigem'])) {
            $this->form_validation->set_rules('tipoLocalizacao[]', 'Origem do acidente', 'required');
        } else {
            $this->form_validation->set_rules('semOrigem', 'Origem do acidente', 'required');
        }

        if (isset($form_data['hasOleo']) and ( $form_data['hasOleo'] == 'S')) {
            if (!isset($form_data['semNavioInstalacao'])) {
                if (isset($form_data['typeOfOrigin']) and $form_data['typeOfOrigin'] == 'navio') {
                    $this->form_validation->set_rules('inputNomeNavio', 'Nome do navio', 'required');
                } else if (isset($form_data['typeOfOrigin']) and $form_data['typeOfOrigin'] == 'instalacao') {
                    $this->form_validation->set_rules('inputNomeInstalacao', 'Nome da instalação', 'required');
                } else {
                    $this->form_validation->set_rules('inputNomeNavio', 'Nome do navio ou nome da instalação', 'required');
                }
            } else {
                $this->form_validation->set_rules('semNavioInstalacao', 'Nome do navio ou nome da instalação', 'required');
            }
        }

        //
        // 4. Tipo de Evento
        //
        if (!isset($form_data['semEvento'])) {
            $this->form_validation->set_rules('tipoEvento[]', 'Evento', 'required');
        } else {
            $this->form_validation->set_rules('semEvento', 'Tipo de evento', 'required');
        }


        // 5. Tipo de Produto
        if (isset($form_data['hasOleo']) and ( $form_data['hasOleo'] == 'S')) {
            if (!isset($form_data['semSubstancia'])) {
                $this->form_validation->set_rules('inputTipoSubstancia', 'Tipo da substância', 'required');
                $this->form_validation->set_rules('inputVolumeEstimado', 'Volume estimado da substância', 'required');
            } else {
                $this->form_validation->set_rules('semSubstancia', 'Tipo de produto', 'required');
            }
        }


        //
        // 6. Detalhes do Acidente
        //
        if (!isset($form_data['semCausa'])) {
            $this->form_validation->set_rules('inputCausaProvavel', 'Causa provável do acidente', 'required');
        } else {
            $this->form_validation->set_rules('semCausa', 'Causa provável do acidente', 'required');
        }

        if (isset($form_data['hasOleo']) and $form_data['hasOleo'] == 'S' and ! isset($form_data['SituacaoDescarga'])) {
            $this->form_validation->set_rules('SituacaoDescarga', 'Situação atual da descarga', 'required');
        }


        //
        // 7. Danos Identificados
        //
        if (!isset($form_data['semDanos'])) {
            $this->form_validation->set_rules('tipoDanoIdentificado[]', 'Ocorrências e/ou ambientes atingidos', 'required');
        } else {
            $this->form_validation->set_rules('semDanos', 'Ocorrências e/ou ambientes atingidos', 'required');
        }


        //
        // 8. Identificação Empresa/Órgão Responsável
        //
        if (!isset($form_data['semResponsavel'])) {
            $this->form_validation->set_rules('inputResponsavel', 'Nome da empresa/órgão responsável', 'required');
            // $this->form_validation->set_rules('slctLicenca', 'Licença ambiental da empresa/órgão responsável', 'required');
            // $this->form_validation->set_rules('inputCPFCNPJ', 'CPF/CNPJ da empresa/órgão responsável', 'required');
        } else {
            $this->form_validation->set_rules('semResponsavel', 'Responsável', 'required');
        }


        //
        // 9. Instituição/Empresa Atuando no Local
        //
        if (!isset($form_data['semInstituicao'])) {
            $this->form_validation->set_rules('instituicaoAtuandoLocal[]', 'Instituição/empresa atuando no local', 'required');
        } else {
            $this->form_validation->set_rules('semInstituicao', 'Instituição atuando', 'required');
        }


        //
        // 10. Ações Inciais Tomadas
        //
        if (!isset($form_data['semProcedimentos'])) {
            $this->form_validation->set_rules('planoEmergencia', 'Plano de emergência ou similar', 'required');
        } else {
            $this->form_validation->set_rules('semProcedimentos', 'Plano de emergência', 'required');
        }


        // //
        // // 11. Informações Gerais sobre a Ocorrência
        // //
        // if (!isset($form_data['semNavioInstalacao'])) {
        //     // $this->form_validation->set_rules('inputNomeNavio', 'Nome do navio', 'required');
        //     // $this->form_validation->set_rules('inputNomeInstalacao', 'Nome da instalação', 'required');
        // } else {
        //     $this->form_validation->set_rules('semNavioInstalacao', 'Informações sobre o navio/instalação', 'required');
        // }


        if ($this->do_upload($form_data)) {
            echo 'ok';
            //print_r($this->upload->display_errors());
            //exit(1);
        } else {
            //$this->form_validation->set_message('userfile', "You must upload an image!");
            //$this->form_validation->set_message('Userfile', $this->upload->display_errors());
            //$this->form_validation->set_message('$this->upload->display_errors()');
            //$this->form_validation->set_rules('userfile', $this->upload->display_errors(), 'required');
//            echo '<div class="alert alert-block alert-error fade in" style="display:inherit;">';
//                echo $this->upload->display_errors();
//                echo '</div>';

            if (!(strcmp('<p>Você não selecionou um arquivo para envio.</p>', $this->upload->display_errors()))) {
                // PARA EXIBIR MENSAGEM ALERTANDO QUE NÃO FOI USADO O UPLOAD DE ARQUIVOS
//                echo '<div class="alert alert-block alert-warning fade in" style="display:inherit;">';
//                echo $this->upload->display_errors();
//                echo '</div>';
            } else if (!(strcmp('<p>O caminho de envio não parece válido.</p>', $this->upload->display_errors()))) {
                // PARA EXIBIR MENSAGEM ALERTANDO QUE NÃO FOI USADO O UPLOAD DE ARQUIVOS
//                echo '<div class="alert alert-block alert-warning fade in" style="display:inherit;">';
//                echo $this->upload->display_errors();
//                echo '</div>';
            }else {
                echo '<div class="alert alert-block alert-error fade in" style="display:inherit;">';
                echo $this->upload->display_errors();
                echo '</div>';
            }
        }
    }

    public function validate() {
        $this->load->helper('form');
        $form_data = $this->input->post();

        // Set the rules for validating the form
        $this->formSetRules($form_data);

        if ($this->form_validation->run() == FALSE) {
            $this->validateForm($form_data);
        } else {
            $this->insertDB($form_data);

            if ($this->session->userdata('logged_in'))
                $this->sendMail($form_data);

            $data['comunicado'] = $form_data['comunicado'];
            $this->load->view('templates/form_success', $data);

            // if($form_data['generatepdf']) {
            //     $this->generatePDFForm($form_data['comunicado']);
            // }
        }
    }

    public function createForm() {
        $formLoad = $this->input->post();

        $formLoad['typeOfForm'] = 'create';
        $data = $this->dataForm($formLoad);

        $form['data'] = $data;

        $this->load->view('templates/form', $form);
    }

    public function loadFormCall() {
        $this->load->helper('form');

        $form_data = $this->input->post();

        $this->loadForm($form_data["nroOcorrencia"]);
    }

    public function validateUpdate() {
        $numeroRegistro = trim($_POST["id"]);
        $validation_model = $this->form_validations_model;
        $codProfilerUser = trim($this->session->userdata("profile_user"));
        $userIbamaNet = ($codProfilerUser == "0" || $codProfilerUser == "3"); 
        $userName = $this->session->userdata("username");

        $status = "";
        $mensagem = "";

        if ($numeroRegistro == "") {
            $status = 'false';
            $mensagem = "Número do Registro não informado.";
        } else if (!$validation_model->numRegistroExists($numeroRegistro)) {
            $status = 'false';
            $mensagem = "Número do Registro não encontrado.";
        } else if ($validation_model->dadosLegados($numeroRegistro)) {
            $status = 'false';
            $mensagem = "Dados Legados não podem ser alterados.";
        } else if ($userIbamaNet && !$validation_model->userCadastrouOcorrencia($numeroRegistro, $userName)) {
            $status = 'false';
            $mensagem = "Alteração não pode ser realizada, porque a ocorrência não foi criada por este usuário.";
        } else {
            $status = 'true';
            $mensagem = "Informações válidas.";
        }

        $result = array("status" => $status, "mensagem" => $mensagem);        

        echo json_encode($result);
    }

    private function loadForm($nro_ocorrencia) {
        $this->load->helper('form');

        $formLoad = $this->form_model->load($nro_ocorrencia);


        if ($formLoad != "") {
            $formLoad['typeOfForm'] = "load";

            // $this->firephp->log($formLoad);
            $data = $this->dataForm($formLoad);
            // $this->firephp->log($data);

            $form['data'] = $data;

            $validadedForm = $this->load->view('templates/form', $form);
        } else {
            $this->load->view('templates/form_fail');
        }
    }

    public function validateForm($form_data) {
        $this->load->helper('form');

        $data = $this->dataForm($form_data);

        if (isset($data['shapeLoaded'])) {
            $data['shapeLoaded'] += array(
                'checked' => 'checked'
            );
        }
        $form['data'] = $data;

        $this->load->view('templates/form', $form);
    }

    public function dataForm($formLoad) {
        // $this->firephp->log($formLoad);
        //
        // Numero do Comunicado
        //
        $data['comunicado'] = isset($formLoad['comunicado']) ? $formLoad['comunicado'] : '';


        //
        // Storing usefull informations
        //
        // Tipo do Comunicado (Load, Create, Validate)
        $data['typeOfForm'] = array(
            'id' => 'typeOfForm',
            'name' => 'typeOfForm',
            'type' => 'hidden',
            'value' => $formLoad['typeOfForm']
        );
        // Setting up where the edit framework should get its polygons (from database of from the page)
        $data['shapeLoaded'] = array(
            'id' => 'shapeLoaded',
            'name' => 'shapeLoaded',
            'type' => 'hidden',
            'value' => 'on'
        );
        // Saves in the form if it is a oil form or not
        if (isset($formLoad['hasOleo']) and ( $formLoad['hasOleo'] == 'S')) {
            $data['hasOleo'] = array(
                'id' => 'hasOleo',
                'name' => 'hasOleo',
                'type' => 'hidden',
                'value' => 'S'
            );
        }
        // Saves in the form if it is made by a ibama employee
        if (isset($formLoad['isServIBAMA'])) {
            $data['isServIBAMA'] = array(
                'id' => 'isServIBAMA',
                'name' => 'isServIBAMA',
                'type' => 'hidden',
                'value' => 'on'
            );
        }


        //
        // 1. Localizacao
        //
        // Input Latitude
        $data['inputLat'] = array(
            'id' => 'inputLat',
            'name' => 'inputLat',
            'type' => 'text',
            'class' => 'input-small',
            'placeholder' => 'Latitude',
            'value' => set_value('inputLat', isset($formLoad['inputLat']) ? $formLoad['inputLat'] : "")
        );
        // if(set_value('semLocalizacao') == "on"){
        //     $data['inputLat'] += array(
        //         'disabled' => 'disabled'
        //     );
        // }
        // Input Longitude
        $data['inputLng'] = array(
            'id' => 'inputLng',
            'name' => 'inputLng',
            'type' => 'text',
            'class' => 'input-small',
            'placeholder' => 'Longitude',
            'value' => set_value('inputLng', isset($formLoad['inputLng']) ? $formLoad['inputLng'] : "")
        );
        // if(set_value('semLocalizacao') == "on"){
        //     $data['inputLng'] += array(
        //         'disabled' => 'disabled'
        //     );
        // }
        // Currently disabled
        // Select EPSG
        // $data['inputEPSG'] = array(
        //     '4674'         => 'SIRGAS 2000 [4674]',
        //     '900913'       => 'Google [900913]',
        //     '4326'         => 'WGS84 [4326]',
        //     '4291'         => 'SAD69 [4291]'
        // );
        // if(isset($formLoad['inputEPSG'])) {
        //     $data['inputEPSG_Selected'] = $formLoad['inputEPSG'];
        // }
        // Checkbox Oceano
        $data['oceano'] = array(
            'id' => 'oceano',
            'name' => 'oceano',
            'type' => 'checkbox',
            'value' => 'on',
        );
        if (isset($formLoad['oceano'])) {
            $data['oceano'] += array(
                'checked' => 'checked'
            );
        }
        // if(set_value('semLocalizacao') == "on"){
        //     $data['oceano'] += array(
        //         'disabled' => 'disabled'
        //     );
        // }
        // // Input Bacia Sedimentar
        // $data['inputBaciaSed'] = array(
        //     'id'           => 'inputBaciaSed',
        //     'name'         => 'inputBaciaSed',
        //     'type'         => 'text',
        //     'class'        => 'input-medium-large',
        //     'placeholder'  => 'Nome da Bacia Sedimentar'
        // );
        // Select Bacia Sedimentar
        $data['dropdownBaciaSedimentar'] = $this->form_model->getBaciasSed();
        // if (isset($formLoad['oceano'])) {
        $data['id_bacia'] = isset($formLoad['oceano']) ? $formLoad['dropdownBaciaSedimentar'] : "";
        // }
        // if(set_value('semLocalizacao') == "on"){
        //     $data['dropdownBaciaSedimentar'] += array(
        //         'disabled' => 'disabled'
        //     );
        // }
        // Select UF
        $data['dropdownUF'] = $this->form_model->getUFs();
        $data['id_uf'] = isset($formLoad['dropdownUF']) ? $formLoad['dropdownUF'] : "";
        // Select Municipio
        $data['dropdownMunicipio'] = $this->form_model->getMunicipios($data['id_uf']);
        $data['id_municipio'] = isset($formLoad['dropdownMunicipio']) ? $formLoad['dropdownMunicipio'] : "";
        // Input Endereço
        $data['inputEndereco'] = array(
            'id' => 'inputEndereco',
            'name' => 'inputEndereco',
            'type' => 'text',
            'class' => 'input-large',
            'placeholder' => '',
            'maxlength' => '512',
            'value' => set_value('inputEndereco', isset($formLoad['inputEndereco']) ? $formLoad['inputEndereco'] : "")
        );
        // if(set_value('semLocalizacao') == "on"){
        //     $data['inputEndereco'] += array(
        //         'disabled' => 'disabled'
        //     );
        // }
        // Checkbox Sem Localização
        // $data['semLocalizacao'] = array(
        //     'id'           => 'semLocalizacao',
        //     'name'         => 'semLocalizacao',
        //     'type'         => 'checkbox',
        // );
        // if(set_value('semLocalizacao') == "on"){
        //     $data['semLocalizacao'] += array(
        //         'checked'  => 'checked'
        //     );
        // }
        //
        // 2. Data e Hora do Acidente
        //
        // Input Data Observação
        $data['inputDataObs'] = array(
            'id' => 'inputDataObs',
            'name' => 'inputDataObs',
            'type' => 'text',
            'class' => 'input-medium',
            'placeholder' => 'DD/MM/AAAA',
            'value' => isset($formLoad['inputDataObs']) ? $formLoad['inputDataObs'] : set_value('inputDataObs')
        );
        if (isset($formLoad['semDataObs'])) {
            $data['inputDataObs'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input Hora Observação
        $data['inputHoraObs'] = array(
            'id' => 'inputHoraObs',
            'name' => 'inputHoraObs',
            'type' => 'text',
            'class' => 'input-medium',
            'placeholder' => 'HH:MM',
            'value' => set_value('inputHoraObs', isset($formLoad['inputHoraObs']) ? $formLoad['inputHoraObs'] : "")
        );
        if (isset($formLoad['semDataObs'])) {
            $data['inputHoraObs'] += array(
                'disabled' => 'disabled'
            );
        }
        // Select Dia Semana Observação
        $data['diaObsSemana'] = array(
            '' => 'Data Inválida',
            '0' => 'Domingo',
            '1' => 'Segunda',
            '2' => 'Terça',
            '3' => 'Quarta',
            '4' => 'Quinta',
            '5' => 'Sexta',
            '6' => 'Sábado'
        );
        // Radio Periodo Matutino Observação
        $data['PerObsMatu'] = array(
            'id' => 'PerObsMatu',
            'name' => 'PeriodoObs',
            'type' => 'radio',
            'value' => 'obsMatutino',
        );
        if (isset($formLoad['semDataObs'])) {
            $data['PerObsMatu'] += array(
                'disabled' => 'disabled'
            );
        }
        // Radio Periodo Vespertino Observação
        $data['PerObsVesper'] = array(
            'id' => 'PerObsVesper',
            'name' => 'PeriodoObs',
            'type' => 'radio',
            'value' => 'obsVespertino',
        );
        if (isset($formLoad['semDataObs'])) {
            $data['PerObsVesper'] += array(
                'disabled' => 'disabled'
            );
        }
        // Radio Periodo Noturno Observação
        $data['PerObsNotu'] = array(
            'id' => 'PerObsNotu',
            'name' => 'PeriodoObs',
            'type' => 'radio',
            'value' => 'obsNoturno',
        );
        if (isset($formLoad['semDataObs'])) {
            $data['PerObsNotu'] += array(
                'disabled' => 'disabled'
            );
        }
        // Radio Periodo Madrugada Observação
        $data['PerObsMadru'] = array(
            'id' => 'PerObsMadru',
            'name' => 'PeriodoObs',
            'type' => 'radio',
            'value' => 'obsMadrugada',
        );
        if (isset($formLoad['semDataObs'])) {
            $data['PerObsMadru'] += array(
                'disabled' => 'disabled'
            );
        }
        // Periodo Observação Selected
        $data['PeriodoObs'] = isset($formLoad['PeriodoObs']) ? $formLoad['PeriodoObs'] : '';
        // Checkbox Sem Data Observação
        $data['semDataObs'] = array(
            'id' => 'semDataObs',
            'name' => 'semDataObs',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semDataObs'])) {
            $data['semDataObs'] += array(
                'checked' => 'checked'
            );
        }
        // Input Data Incidente
        $data['inputDataInci'] = array(
            'id' => 'inputDataInci',
            'name' => 'inputDataInci',
            'type' => 'text',
            'class' => 'input-medium',
            'placeholder' => 'DD/MM/AAAA',
            'value' => isset($formLoad['inputDataInci']) ? $formLoad['inputDataInci'] : set_value('inputDataInci')
        );
        if (isset($formLoad['semDataInci'])) {
            $data['inputDataInci'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input Hora Incidente
        $data['inputHoraInci'] = array(
            'id' => 'inputHoraInci',
            'name' => 'inputHoraInci',
            'type' => 'datetime',
            'class' => 'input-medium',
            'placeholder' => 'HH:MM',
            'value' => set_value('inputHoraInci', isset($formLoad['inputHoraInci']) ? $formLoad['inputHoraInci'] : "")
        );
        if (isset($formLoad['semDataInci'])) {
            $data['inputHoraInci'] += array(
                'disabled' => 'disabled'
            );
        }
        // Select Dia Semana Incidente
        $data['diaInciSemana'] = array(
            '' => 'Data Inválida',
            '0' => 'Domingo',
            '1' => 'Segunda',
            '2' => 'Terça',
            '3' => 'Quarta',
            '4' => 'Quinta',
            '5' => 'Sexta',
            '6' => 'Sábado'
        );
        // Radio Periodo Matutino Incidente
        $data['PerInciMatu'] = array(
            'id' => 'PerInciMatu',
            'name' => 'PeriodoInci',
            'type' => 'radio',
            'value' => 'inciMatutino',
        );
        if (isset($formLoad['semDataInci'])) {
            $data['PerInciMatu'] += array(
                'disabled' => 'disabled'
            );
        }
        // Radio Periodo Vespertino Incidente
        $data['PerInciVesper'] = array(
            'id' => 'PerInciVesper',
            'name' => 'PeriodoInci',
            'type' => 'radio',
            'value' => 'inciVespertino',
        );
        if (isset($formLoad['semDataInci'])) {
            $data['PerInciVesper'] += array(
                'disabled' => 'disabled'
            );
        }
        // Radio Periodo Noturno Incidente
        $data['PerInciNotu'] = array(
            'id' => 'PerInciNotu',
            'name' => 'PeriodoInci',
            'type' => 'radio',
            'value' => 'inciNoturno',
        );
        if (isset($formLoad['semDataInci'])) {
            $data['PerInciNotu'] += array(
                'disabled' => 'disabled'
            );
        }
        // Radio Periodo Madrugada Incidente
        $data['PerInciMadru'] = array(
            'id' => 'PerInciMadru',
            'name' => 'PeriodoInci',
            'type' => 'radio',
            'value' => 'inciMadrugada',
        );
        if (isset($formLoad['semDataInci'])) {
            $data['PerInciMadru'] += array(
                'disabled' => 'disabled'
            );
        }
        // Periodo Incidente Selected
        $data['PeriodoInci'] = isset($formLoad['PeriodoInci']) ? $formLoad['PeriodoInci'] : '';
        // Checkbox Data Incidente Feriado
        $data['dtFeriado'] = array(
            'id' => 'dtFeriado',
            'name' => 'dtFeriado',
            'type' => 'checkbox',
        );
        if (isset($formLoad['dtFeriado'])) {
            $data['dtFeriado'] += array(
                'checked' => 'checked'
            );
        }
        if (isset($formLoad['semDataInci'])) {
            $data['dtFeriado'] += array(
                'disabled' => 'disabled'
            );
        }
        // Checkbox Sem Data Incidente
        $data['semDataInci'] = array(
            'id' => 'semDataInci',
            'name' => 'semDataInci',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semDataInci'])) {
            $data['semDataInci'] += array(
                'checked' => 'checked'
            );
        }


        //
        // 3. Origem do acidente
        //
        // Input Complemento Origem
        $data['inputCompOrigem'] = array(
            'id' => 'inputCompOrigem',
            'name' => 'inputCompOrigem',
            'rows' => '2',
            'class' => 'input-large',
            'maxlength' => '150',
            'value' => set_value('inputCompOrigem', isset($formLoad['inputCompOrigem']) ? $formLoad['inputCompOrigem'] : '')
        );
        if (isset($formLoad['semOrigem'])) {
            $data['inputCompOrigem'] += array(
                'disabled' => 'disabled'
            );
        }
        // Checkbox Sem Origem
        $data['semOrigem'] = array(
            'id' => 'semOrigem',
            'name' => 'semOrigem',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semOrigem'])) {
            $data['semOrigem'] += array(
                'checked' => 'checked'
            );
        }
        // Checkbox Tipo Localização
        if (isset($formLoad['tipoLocalizacao'])) {
            $data['tipoLocalizacao'] = $formLoad['tipoLocalizacao'];
        }


        //
        // 4. Tipo do Evento
        //
        // Input Nome Navio
        $data['inputNomeNavio'] = array(
            'id' => 'inputNomeNavio',
            'name' => 'inputNomeNavio',
            'type' => 'text',
            'class' => 'input-medium',
            'maxlength' => '150',
            'value' => set_value('inputNomeNavio', isset($formLoad['inputNomeNavio']) ? $formLoad['inputNomeNavio'] : '')
        );
        if (isset($formLoad['semNavioInstalacao']) or ( isset($formLoad['typeOfOrigin']) and $formLoad['typeOfOrigin'] == 'instalacao')) {
            $data['inputNomeNavio'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input Nome Instalação
        $data['inputNomeInstalacao'] = array(
            'id' => 'inputNomeInstalacao',
            'name' => 'inputNomeInstalacao',
            'type' => 'text',
            'class' => 'input-medium',
            'maxlength' => '150',
            'value' => set_value('inputNomeInstalacao', isset($formLoad['inputNomeInstalacao']) ? $formLoad['inputNomeInstalacao'] : '')
        );
        if (isset($formLoad['semNavioInstalacao']) or ( isset($formLoad['typeOfOrigin']) and $formLoad['typeOfOrigin'] == 'navio')) {
            $data['inputNomeInstalacao'] += array(
                'disabled' => 'disabled'
            );
        }
        // Sem Instalacao/Navio
        $data['semNavioInstalacao'] = array(
            'id' => 'semNavioInstalacao',
            'name' => 'semNavioInstalacao',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semNavioInstalacao'])) {
            $data['semNavioInstalacao'] += array(
                'checked' => 'checked'
            );
        }
        // Input Complemento Evento
        $data['inputCompEvento'] = array(
            'id' => 'inputCompEvento',
            'name' => 'inputCompEvento',
            'rows' => '2',
            'class' => 'input-large',
            'maxlength' => '150',
            'value' => set_value('inputCompEvento', isset($formLoad['inputCompEvento']) ? $formLoad['inputCompEvento'] : '')
        );
        if (isset($formLoad['semEvento'])) {
            $data['inputCompEvento'] += array(
                'disabled' => 'disabled'
            );
        }
        // Checkbox Sem Evento
        $data['semEvento'] = array(
            'id' => 'semEvento',
            'name' => 'semEvento',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semEvento'])) {
            $data['semEvento'] += array(
                'checked' => 'checked'
            );
        }
        // Checkbox Tipo Evento
        if (isset($formLoad['tipoEvento'])) {
            $data['tipoEvento'] = $formLoad['tipoEvento'];
        }


        //
        // 5. Tipo de Produto
        //
        //  Checkbox Produto Não Perigoso
        $data['produtoNaoPerigoso'] = array(
            'id' => 'produtoNaoPerigoso',
            'name' => 'produtoNaoPerigoso',
            'type' => 'checkbox',
        );
        if (isset($formLoad['produtoNaoPerigoso']) and ( $formLoad['produtoNaoPerigoso'] == 't')) {
            $data['produtoNaoPerigoso'] += array(
                'checked' => 'checked'
            );
        }
        // Checkbox Produto Não Se Aplica
        $data['produtoNaoAplica'] = array(
            'id' => 'produtoNaoAplica',
            'name' => 'produtoNaoAplica',
            'type' => 'checkbox',
        );
        if (isset($formLoad['produtoNaoAplica']) and ( $formLoad['produtoNaoAplica'] == 't')) {
            $data['produtoNaoAplica'] += array(
                'checked' => 'checked'
            );
        }

        // Checkbox Produto Não Especificado
        $data['produtoNaoEspecificado'] = array(
            'id' => 'produtoNaoEspecificado',
            'name' => 'produtoNaoEspecificado',
            'type' => 'checkbox',
        );
        if (isset($formLoad['produtoNaoEspecificado']) and ( $formLoad['produtoNaoEspecificado'] == 't')) {
            $data['produtoNaoEspecificado'] += array(
                'checked' => 'checked'
            );
        }

        $data['semProduto'] = array(
            'id' => 'semProduto',
            'name' => 'semProduto',
            'type' => 'checkbox',
        );

        if (isset($formLoad['semProduto'])) {
            $data['semProduto'] += array(
                'checked' => 'checked'
            );
        }

        // Oil Form
        if (isset($formLoad['hasOleo'])) {
            // Input Tipo Substância
            $data['inputTipoSubstancia'] = array(
                'id' => 'inputTipoSubstancia',
                'name' => 'inputTipoSubstancia',
                'type' => 'text',
                'class' => 'input',
                'placeholder' => 'Tipo da Substância',
                'value' => set_value('inputTipoSubstancia', isset($formLoad['inputTipoSubstancia']) ? $formLoad['inputTipoSubstancia'] : '')
            );
            if (isset($formLoad['semSubstancia'])) {
                $data['inputTipoSubstancia'] += array(
                    'disabled' => 'disabled'
                );
            }
            // Input Volume Estimado
            $data['inputVolumeEstimado'] = array(
                'id' => 'inputVolumeEstimado',
                'name' => 'inputVolumeEstimado',
                'type' => 'text',
                'class' => 'input-small',
                'maxlength' => '14',
                'placeholder' => 'Valor',
                'value' => set_value('inputVolumeEstimado', isset($formLoad['inputVolumeEstimado']) ? $formLoad['inputVolumeEstimado'] : '')
            );
            if (isset($formLoad['semSubstancia'])) {
                $data['inputVolumeEstimado'] += array(
                    'disabled' => 'disabled'
                );
            }
            // Checkbox Sem Substância
            $data['semSubstancia'] = array(
                'id' => 'semSubstancia',
                'name' => 'semSubstancia',
                'value' => 'on',
                'type' => 'checkbox'
            );
            if (isset($formLoad['semSubstancia'])) {
                $data['semSubstancia'] += array(
                    'checked' => 'checked'
                );
            }
        }


        //
        // 6. Detalhes do acidente
        //
        // Input Causa Provável
        $data['inputCausaProvavel'] = array(
            'id' => 'inputCausaProvavel',
            'name' => 'inputCausaProvavel',
            'rows' => '2',
            'class' => 'input-large',
            'maxlength' => '2000',
            'value' => set_value('inputCausaProvavel', isset($formLoad['inputCausaProvavel']) ? $formLoad['inputCausaProvavel'] : '')
        );

        if (isset($formLoad['semCausa'])) {
            $data['inputCausaProvavel'] += array(
                'disabled' => 'disabled'
            );
        }
        // Checkbox Sem Causa
        $data['semCausa'] = array(
            'id' => 'semCausa',
            'name' => 'semCausa',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semCausa'])) {
            $data['semCausa'] += array(
                'checked' => 'checked'
            );
        }
        // Radio Situação Descarga Paralizada
        $data['SitParal'] = array(
            'id' => 'SitParal',
            'name' => 'SituacaoDescarga',
            'type' => 'radio',
            'value' => set_value('SitParal', 1)
        );
        if (isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '1') {
            $data['SitParal'] += array(
                'checked' => 'checked'
            );
        }
        // Radio Situação Descarga Não Paralizada
        $data['SitNaoParal'] = array(
            'id' => 'SitNaoParal',
            'name' => 'SituacaoDescarga',
            'type' => 'radio',
            'value' => set_value('SitNaoParal', 2)
        );
        if (isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '2') {
            $data['SitNaoParal'] += array(
                'checked' => 'checked'
            );
        }
        // Radio Situação Descarga Sem Condições
        $data['SitSemCondi'] = array(
            'id' => 'SitSemCondi',
            'name' => 'SituacaoDescarga',
            'type' => 'radio',
            'value' => set_value('SitSemCondi', 3)
        );
        if (isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '3') {
            $data['SitSemCondi'] += array(
                'checked' => 'checked'
            );
        }
        // Radio Situação Descarga Não Se Aplica
        $data['SitNaoSeApl'] = array(
            'id' => 'SitNaoSeApl',
            'name' => 'SituacaoDescarga',
            'type' => 'radio',
            'value' => set_value('SitNaoSeApl', 4)
        );
        if (isset($formLoad['SituacaoDescarga']) and $formLoad['SituacaoDescarga'] == '4') {
            $data['SitNaoSeApl'] += array(
                'checked' => 'checked'
            );
        }


        //
        // 7. Ocorrências e/ou ambientes atingidos
        //
        // Input Complemento Danos
        $data['inputCompDano'] = array(
            'id' => 'inputCompDano',
            'name' => 'inputCompDano',
            'rows' => '2',
            'maxlength' => '1000',
            'class' => 'input-large',
            'placeholder' => 'Insira a descrição do dano e informações complementares',
            'value' => set_value('inputCompDano', isset($formLoad['inputCompDano']) ? $formLoad['inputCompDano'] : "")
        );
        if (isset($formLoad['semDanos'])) {
            $data['inputCompDano'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input Descrição Danos
        // $data['inputDesDanos'] = array(
        //     'id'           => 'inputDesDanos',
        //     'name'         => 'inputDesDanos',
        //     'rows'         => '2',
        //     'maxlength'    => '2500',
        //     'class'        => 'input-large',
        //     'value'        => set_value('inputDesDanos', isset($formLoad['inputDesDanos']) ? $formLoad['inputDesDanos'] : "")
        // );
        /*
          if(isset($formLoad['semDanos'])){
          $data['inputDesDanos'] += array(
          'disabled' => 'disabled'
          );
          }
         */
        // Checkbox Sem Danos
        $data['semDanos'] = array(
            'id' => 'semDanos',
            'name' => 'semDanos',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semDanos'])) {
            $data['semDanos'] += array(
                'checked' => 'checked'
            );
        }
        // Checkbox Danos Identificados
        if (isset($formLoad['tipoDanoIdentificado'])) {
            $data['tipoDanoIdentificado'] = $formLoad['tipoDanoIdentificado'];
        }


        //
        // 8. Indentificacao dos responsáveis
        //
        // Input Nome Responsável
        $data['inputResponsavel'] = array(
            'id' => 'inputResponsavel',
            'name' => 'inputResponsavel',
            'type' => 'text',
            'class' => 'input',
            'placeholder' => '',
            'maxlength' => '150',
            'value' => set_value('inputResponsavel', isset($formLoad['inputResponsavel']) ? $formLoad['inputResponsavel'] : '')
        );
        if (isset($formLoad['semResponsavel'])) {
            $data['inputResponsavel'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input CPF/CNPJ Responsável
        $data['inputCPFCNPJ'] = array(
            'id' => 'inputCPFCNPJ',
            'name' => 'inputCPFCNPJ',
            'type' => 'text',
            'class' => 'input',
            'placeholder' => '',
            'maxlength' => '20',
            'value' => set_value('inputCPFCNPJ', isset($formLoad['inputCPFCNPJ']) ? $formLoad['inputCPFCNPJ'] : '')
        );
        if (isset($formLoad['semResponsavel'])) {
            $data['inputCPFCNPJ'] += array(
                'disabled' => 'disabled'
            );
        }
        // Select Licença Ambiental
        $data['slctLicenca'] = array(
            '0' => 'Sem informação',
            '1' => 'Licença ambiental federal',
            '2' => 'Licença ambiental estadual',
            '3' => 'Licença ambiental municipal'
        );
        // Licença Ambiental Selected
        $data['id_licenca'] = isset($formLoad['slctLicenca']) ? $formLoad['slctLicenca'] : "0";
        // Checkbox Sem Responsável
        $data['semResponsavel'] = array(
            'id' => 'semResponsavel',
            'name' => 'semResponsavel',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semResponsavel'])) {
            $data['semResponsavel'] += array(
                'checked' => 'checked'
            );
        }


        //
        // 9. Instituicao atuando no local
        //
        // Input Nome Instituição Atuando
        $data['inputInfoInstituicaoNome'] = array(
            'id' => 'inputInfoInstituicaoNome',
            'name' => 'inputInfoInstituicaoNome',
            'type' => 'text',
            'class' => 'input',
            'placeholder' => 'Nome do Responsável',
            'maxlength' => '128',
            'value' => set_value('inputInfoInstituicaoNome', isset($formLoad['inputInfoInstituicaoNome']) ? $formLoad['inputInfoInstituicaoNome'] : "")
        );
        if (isset($formLoad['semInstituicao'])) {
            $data['inputInfoInstituicaoNome'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input Telefone Instituição Atuando
        $data['inputInfoInstituicaoTelefone'] = array(
            'id' => 'inputInfoInstituicaoTelefone',
            'name' => 'inputInfoInstituicaoTelefone',
            'type' => 'text',
            'class' => 'input-small',
            'placeholder' => '(99)99999999',
            'maxlength' => '12',
            'value' => set_value('inputInfoInstituicaoTelefone', isset($formLoad['inputInfoInstituicaoTelefone']) ? $formLoad['inputInfoInstituicaoTelefone'] : "")
        );
        if (isset($formLoad['semInstituicao'])) {
            $data['inputInfoInstituicaoTelefone'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input Complemento Instituição Atuando
        $data['inputCompInstituicao'] = array(
            'id' => 'inputCompInstituicao',
            'name' => 'inputCompInstituicao',
            'rows' => '2',
            'class' => 'input-large',
            'maxlength' => '150',
            'value' => set_value('inputCompInstituicao', isset($formLoad['inputCompInstituicao']) ? $formLoad['inputCompInstituicao'] : "")
        );
        if (isset($formLoad['semInstituicao'])) {
            $data['inputCompInstituicao'] += array(
                'disabled' => 'disabled'
            );
        }
        // Checkbox Sem Instituição Atuando
        $data['semInstituicao'] = array(
            'id' => 'semInstituicao',
            'name' => 'semInstituicao',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semInstituicao'])) {
            $data['semInstituicao'] += array(
                'checked' => 'checked'
            );
        }
        // Checkbox Instituição Atuando
        if (isset($formLoad['instituicaoAtuandoLocal'])) {
            $data['instituicaoAtuandoLocal'] = $formLoad['instituicaoAtuandoLocal'];
        }


        //
        // 10. Procedimentos adotados
        //
        // Radio Plano Emergencia Sim
        $data['planoEmergSim'] = array(
            'id' => 'planoEmergSim',
            'name' => 'planoEmergencia',
            'type' => 'radio',
            'value' => set_value('planoEmergSim', 1)
        );
        if (isset($formLoad['planoEmergencia']) && ($formLoad['planoEmergencia'] == '1')) {
            $data['planoEmergSim'] += array(
                'checked' => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['planoEmergSim'] += array(
                'disabled' => 'disabled'
            );
        }
        // Radio Plano Emergencia Não
        $data['planoEmergNao'] = array(
            'id' => 'planoEmergNao',
            'name' => 'planoEmergencia',
            'type' => 'radio',
            'value' => set_value('planoEmergNao', 0)
        );
        if (isset($formLoad['planoEmergencia']) && ($formLoad['planoEmergencia'] == '0')) {
            $data['planoEmergNao'] += array(
                'checked' => 'checked'
            );
        }

        if (isset($formLoad['semProcedimentos'])) {
            $data['planoEmergNao'] += array(
                'disabled' => 'disabled'
            );
        }

        // Radio Plano Sem informação
        $data['planoEmergSemInformacao'] = array(
            'id' => 'planoEmergSemInformacao',
            'name' => 'planoEmergencia',
            'type' => 'radio',
            'value' => set_value('planoEmergSemInformacao', -1)
        );
        if (isset($formLoad['planoEmergencia']) && ($formLoad['planoEmergencia'] == '-1')) {
            $data['planoEmergSemInformacao'] += array(
                'checked' => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['planoEmergSemInformacao'] += array(
                'disabled' => 'disabled'
            );
        }

        // Checkbox Plano Acionado
        $data['planoAcionado'] = array(
            'id' => 'planoAcionado',
            'name' => 'planoAcionado',
            'type' => 'checkbox',
            'value' => 'on'
        );
        if (isset($formLoad['planoAcionado'])) {
            $data['planoAcionado'] += array(
                'checked' => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['planoAcionado'] += array(
                'disabled' => 'disabled'
            );
        }
        // Checkbox Outras Medidas Tomadas
        $data['outrasMedidas'] = array(
            'id' => 'outrasMedidas',
            'name' => 'outrasMedidas',
            'type' => 'checkbox',
        );
        if (isset($formLoad['outrasMedidas'])) {
            $data['outrasMedidas'] += array(
                'checked' => 'checked'
            );
        }
        if (isset($formLoad['semProcedimentos'])) {
            $data['outrasMedidas'] += array(
                'disabled' => 'disabled'
            );
        }
        // Input Outras Medidas Tomadas
        $data['inputMedidasTomadas'] = array(
            'id' => 'inputMedidasTomadas',
            'name' => 'inputMedidasTomadas',
            'rows' => '2',
            'class' => 'input-large',
            'maxlength' => '1000',
            'value' => set_value('inputMedidasTomadas', isset($formLoad['inputMedidasTomadas']) ? $formLoad['inputMedidasTomadas'] : '')
        );
        if (isset($formLoad['semProcedimentos'])) {
            $data['inputMedidasTomadas'] += array(
                'disabled' => 'disabled'
            );
        }
        // Checkbox Sem Procedimentos
        $data['semProcedimentos'] = array(
            'id' => 'semProcedimentos',
            'name' => 'semProcedimentos',
            'type' => 'checkbox',
        );
        if (isset($formLoad['semProcedimentos'])) {
            $data['semProcedimentos'] += array(
                'checked' => 'checked'
            );
        }


        //
        // 11. Identificação do Comunicante
        //
        // Input Nome Comunicante (Informante)

        if ($this->session->userdata('logged_in')) {
            $data['inputNomeInformante'] = array(
                'readonly' => 'readonly',
                'id' => 'inputNomeInformante',
                'name' => 'inputNomeInformante',
                'class' => 'input-large',
                'maxlength' => '150',
                'value' => set_value('inputNomeInformante', ($formLoad['typeOfForm'] == 'create') ? $this->session->userdata('name') : $formLoad['inputNomeInformante'])
            );
        } else {
            $data['inputNomeInformante'] = array(
                'id' => 'inputNomeInformante',
                'name' => 'inputNomeInformante',
                'class' => 'input-large',
                'maxlength' => '150',
                'value' => set_value('inputNomeInformante', ($formLoad['typeOfForm'] == 'create') ? $this->session->userdata('name') : $formLoad['inputNomeInformante'])
            );
        }

        // Input Funcão Navio Comunicante (Informante)
        $data['inputCargoFunc'] = array(
            'id' => 'inputCargoFunc',
            'name' => 'inputCargoFunc',
            'class' => 'input-large',
            'maxlength' => '150',
            'value' => set_value('inputCargoFunc', isset($formLoad['inputCargoFunc']) ? $formLoad['inputCargoFunc'] : '')
        );
        // Input Funcão Identificação Comunicante (Acidente Ambiental)
        $data['inputInstEmp'] = array(
            'id' => 'inputInstEmp',
            'name' => 'inputInstEmp',
            'class' => 'input-large',
            'maxlength' => '150',
            'value' => set_value('inputInstEmp', isset($formLoad['inputInstEmp']) ? $formLoad['inputInstEmp'] : '')
        );
        // Input Email Comunicante (Informante)

        if ($this->session->userdata('logged_in')) {
            $data['inputEmailInformante'] = array(
                'readonly' => 'readonly',
                'id' => 'inputEmailInformante',
                'name' => 'inputEmailInformante',
                'class' => 'input-large',
                'maxlength' => '150',
                'value' => set_value('inputEmailInformante', ($formLoad['typeOfForm'] == 'create') ? $this->session->userdata('mail') : $formLoad['inputEmailInformante'])
            );
        } else {
            $data['inputEmailInformante'] = array(
                'id' => 'inputEmailInformante',
                'name' => 'inputEmailInformante',
                'class' => 'input-large',
                'maxlength' => '150',
                'value' => set_value('inputEmailInformante', ($formLoad['typeOfForm'] == 'create') ? $this->session->userdata('mail') : $formLoad['inputEmailInformante'])
            );
        }

        // // Input Funcão Navio Comunicante (Informante)
        // $data['inputCargoFunc'] = array(
        //     'id' => 'inputCargoFunc',
        //     'name' => 'inputCargoFunc',
        //     'class' => 'input-large',
        //     'maxlength' => '150',
        //     'value' => set_value('inputCargoFunc', isset($formLoad['inputCargoFunc']) ? $formLoad['inputCargoFunc'] : '')
        // );
        // Input Telefone Comunicante (Informante)
        $data['inputTelInformante'] = array(
            'id' => 'inputTelInformante',
            'name' => 'inputTelInformante',
            'class' => 'input-large',
            'maxlength' => '13',
            'value' => set_value('inputTelInformante', isset($formLoad['inputTelInformante']) ? $formLoad['inputTelInformante'] : '')
        );


        //
        // 12. Informacoes gerais
        //
        // Input Descrição Ocorrência
        $data['inputDesOcorrencia'] = array(
            'id' => 'inputDesOcorrencia',
            'name' => 'inputDesOcorrencia',
            'rows' => '2',
            'class' => 'input-large',
            'maxlength' => '2500',
            'value' => set_value('inputDesOcorrencia', isset($formLoad['inputDesOcorrencia']) ? $formLoad['inputDesOcorrencia'] : '')
        );
        // Input Descrição Observação Ocorrência
        $data['inputDesObs'] = array(
            'id' => 'inputDesObs',
            'name' => 'inputDesObs',
            'rows' => '2',
            'maxlength' => '2000',
            'class' => 'input-large',
            'value' => set_value('inputDesObs', isset($formLoad['inputDesObs']) ? $formLoad['inputDesObs'] : '')
        );


        //
        // 14. Fonte da Informação.
        //
        // Checkbox Fonte Informação
        if (isset($formLoad['tipoFonteInformacao'])) {

            $data['tipoFonteInformacao'] = $formLoad['tipoFonteInformacao'];
        }

        $data['inputDescOutrasFontInfo'] = array(
            'id' => 'inputDescOutrasFontInfo',
            'name' => 'inputDescOutrasFontInfo',
            'rows' => '2',
            'maxlength' => '150',
            'class' => 'input-large',
            'value' => set_value('inputDescOutrasFontInfo', isset($formLoad['desc_outras_fontes']) ? $formLoad['desc_outras_fontes'] : '')
        );


        // Checkbox Validado
        $data['validado'] = array(
            'id' => 'validado',
            'name' => 'validado',
            'type' => 'checkbox',
            'style' => 'float: right; margin-left: 10px; margin-right:10px;'
                // 'value'        => 'on'
        );
        if (isset($formLoad['validado']) && $formLoad['validado'] == 'S') {
            $data['validado'] += array(
                'checked' => 'checked'
            );
        }




        return $data;
    }

    public function sendMail($form_data) {

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
            'charset' => "utf-8"
        );

        $this->load->library("email", $config);
        $this->email->set_newline("\r\n");

        $this->email->from("ibama.siema@gmail.com", "SIEMA");
        $this->email->to("ibama.siema@gmail.com");

        $this->email->subject("Ibama – Comunicado de Acidente Ambiental");

        if (array_key_exists("inputHoraObs", $form_data) && array_key_exists("inputDataObs", $form_data))
            $message_body = $this->sendmail_model->getEmailBody(1, $form_data['comunicado'], $form_data["inputHoraObs"], $form_data["inputDataObs"]);
        else
            $message_body = $this->sendmail_model->getEmailBody(1, $form_data['comunicado'], null, null);


        $this->email->message($message_body);

        if ($this->email->send())
            $this->firephp->log("Email enviado com sucesso!");
        else
            $this->firephp->log("Erro ao enviar o email");
        // $this->firephp->log($this->email->print_debugger());
    }

    function do_upload($form_data) {
        $path = $form_data['comunicado'];
        $finalPath = "/var/www/siema/assets/uploads/" . $path;


        //verify if path already exists, remove and create folder.
        if (is_dir($finalPath)) {
            $diretorio = dir($finalPath);
            while ($arquivo = $diretorio->read()) {
                if (($arquivo != '.') && ($arquivo != '..'))
                    unlink($finalPath . '/' . $arquivo);
            }

            rmdir($finalPath);
            if (!file_exists($finalPath)) {
                mkdir($finalPath);
            }
        } else {
            mkdir($finalPath);
        }
        $config['upload_path'] = $finalPath;
        $config['allowed_types'] = 'gif|jpg|png|doc|jpeg|doc|xls|xlsx|pdf';
        $config['max_size'] = '15000';
        $this->load->library('upload', $config);

        $this->upload->do_upload();


        //%error = array('error' => $this->upload->display_errors());
    }

    public function generatePDFForm() {

        $nro_ocorrencia = $_POST['nro_ocorrencia'];

        $this->load->helper('form');

        // $this->form_model->generatePdfData($nro_ocorrencia);

        $formLoad = $this->form_model->generatePdfData($nro_ocorrencia);

        if ($formLoad != "") {
            $formLoad['typeOfForm'] = "load";

            // $data = $this->dataForm($formLoad);
            // $form['data'] = $data;

            $form['data'] = $formLoad;

            $this->load->helper(array('dompdf', 'file'));
            $validadedForm = $this->load->view('templates/generate_pdf', $form, true);
            // $validadedForm = $this->load->view('templates/form', $form, true);
            pdf_create($validadedForm, $nro_ocorrencia);
        }
    }

}
