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

        // $this->oracleDB();

        // $this->primeiro_acesso();

        $this->load->view('templates/home', $data);

    }

    public function oracleDB() {


        $conn = oci_connect('sisreg', 'sisregdes', 'exd01-scan.ibama.gov.br/dsnv_manut');
        if (!$conn) {
            $e = oci_error();
            // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r($e['message']);echo'<hr></pre>';exit;
            $this->firephp->log($e);
        } // end iF;

        // Montar pl/sql function
        $sql = 'begin


                    :erro := sisreg.pkg_pessoa_base.sel_pessoa( p_cursor        => :p_cursor
                                                               ,p_cpf_cnpj_nome => :p_cpf_cnpj_nome );
                 end;';

        if (!$p_str = oci_parse($conn,$sql)) {
            // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r(oci_error($conn));echo'<hr></pre>';exit;
            $this->firephp->log(oci_error($conn));
            oci_close($conn);
        };

        // $p_cpf_cnpj_nome = $_POST[ 'cpf_cnpj_nome' ];
        $p_cpf_cnpj_nome = '1811126693';

        // Bind dos dados de entrada
        oci_bind_by_name( $p_str, ":p_cpf_cnpj_nome", $p_cpf_cnpj_nome );

        // Bind dos variaveis de saida
        $p_cursor = oci_new_cursor($conn);
        oci_bind_by_name($p_str,':p_cursor',$p_cursor,-1,OCI_B_CURSOR);
        oci_bind_by_name($p_str,":erro",$erro,250);

        // Executar
        $r = oci_execute($p_str,OCI_DEFAULT);
        if ( !$r ) {
            $e = oci_error($p_str);
            oci_close($conn);
            // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r($e['message']);echo'<hr></pre>';exit;
            $this->firephp->log($e);
        }

        //Executar Cursor
        if ( !oci_execute($p_cursor,OCI_DEFAULT) ) {
            $e = oci_error($p_str);
            oci_free_statement($p_str);
            oci_close($conn);
            // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r($e['message']);echo'<hr></pre>';exit;
            $this->firephp->log($e);
        }

        // while ($data = oci_fetch_row($p_cursor)) {
        //     // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r($data);echo'<hr></pre>';
        //     $this->firephp->log($data);
        // }

        oci_fetch_all($p_cursor, $data);

        $this->firephp->log($data);

        oci_free_statement($p_cursor);



        // AUTENTICACAO



        // $sql = 'begin
        //             :erro := sisreg.pkg_pessoa_base.autenticar( p_num_pessoa        => :p_num_pessoa
        //                                                        ,p_senha             => :p_senha
        //                                                        ,p_seq_app_modulo    => :p_seq_app_modulo);
        //          end;';

        // if (!$p_str = oci_parse($conn,$sql)) {
        //     // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r(oci_error($conn));echo'<hr></pre>';exit;
        //     $this->firephp->log(oci_error($conn));
        //     oci_close($conn);
        // };

        // $p_senha = 123456789;
        // // $num_pessoa = (int) $data['NUM_PESSOA'][0];
        // $num_pessoa = 5799374;
        // // $p_seq_app_modulo = 1;

        // // $this->firephp->log($num_pessoa);

        // // Bind dos dados de entrada
        // oci_bind_by_name( $p_str, ":p_num_pessoa", $num_pessoa );
        // oci_bind_by_name( $p_str, ":p_senha", $p_senha );

        // // Bind dos variaveis de saida
        // oci_bind_by_name($p_str,':p_seq_app_modulo',$p_seq_app_modulo,25);
        // oci_bind_by_name($p_str,":erro",$erro,250);

        // // Executar
        // $r = oci_execute($p_str,OCI_DEFAULT);
        // if ( !$r ) {
        //     $e = oci_error($p_str);
        //     oci_close($conn);
        //     $this->firephp->log($e);
        // }

        // // oci_fetch_all($p_str,$data);

        // // while ($data = oci_fetch_row($p_str)) {
        // //     // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r($data);echo'<hr></pre>';
        // //     $this->firephp->log($data);
        // // }

        // // $this->firephp->log($p_str);
        // // $this->firephp->log($data);
        // $this->firephp->log($p_seq_app_modulo);



        // RECUPERACAO_SENHA



        // $sql = 'begin
        //             :erro := sisreg.pkg_pessoa_base.recuperacao_senha( p_num_cnpj_cpf        => :p_num_cnpj_cpf
        //                                                        ,p_dat_nasc_const_emp         => :p_dat_nasc_const_emp
        //                                                        ,p_des_email                  => :p_des_email
        //                                                        ,p_seq_app_modulo             => :p_seq_app_modulo
        //                                                        ,p_num_pessoa                 => :p_num_pessoa
        //                                                        ,p_des_senha                  => :p_des_senha);
        //          end;';

        // if (!$p_str = oci_parse($conn,$sql)) {
        //     // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r(oci_error($conn));echo'<hr></pre>';exit;
        //     $this->firephp->log(oci_error($conn));
        //     oci_close($conn);
        // };

        // $p_num_cnpj_cpf = 01433540355;
        // $p_dat_nasc_const_emp = "10.06.1987"; // Q FORMATO SE DEVE PASSAR?
        // $p_des_email = "";
        // $p_seq_app_modulo = 10144;
        // // $p_num_pessoa = 5799374;
        // // $p_des_senha = 987654321;

        // // $this->firephp->log($num_pessoa);

        // // Bind dos dados de entrada
        // oci_bind_by_name( $p_str, ":p_num_cnpj_cpf", $p_num_cnpj_cpf );
        // oci_bind_by_name( $p_str, ":p_dat_nasc_const_emp", $p_dat_nasc_const_emp );
        // oci_bind_by_name( $p_str, ":p_des_email", $p_des_email );
        // oci_bind_by_name( $p_str, ":p_seq_app_modulo", $p_seq_app_modulo );

        // // Bind dos variaveis de saida
        // oci_bind_by_name($p_str,':p_num_pessoa',$p_num_pessoa,25);
        // oci_bind_by_name($p_str,':p_des_senha',$p_des_senha,25);
        // oci_bind_by_name($p_str,":erro",$erro,250);

        // // Executar
        // $r = oci_execute($p_str,OCI_DEFAULT);
        // if ( !$r ) {
        //     $e = oci_error($p_str);
        //     oci_close($conn);
        //     $this->firephp->log($e);
        // }

        // // oci_fetch_all($p_str,$data);

        // // while ($data = oci_fetch_row($p_str)) {
        // //     // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r($data);echo'<hr></pre>';
        // //     $this->firephp->log($data);
        // // }

        // // $this->firephp->log($p_str);
        // // $this->firephp->log($data);
        // $this->firephp->log($num_pessoa);
        // $this->firephp->log($p_des_senha);



        // ALTERAR_PESSOA



        // $sql = 'begin
        //             :erro := sisreg.pkg_pessoa_base.alterar_pessoa( p_num_pessoa             => :p_num_pessoa
        //                                                        ,p_nom_pessoa                 => :p_nom_pessoa
        //                                                        ,p_end_pessoa                 => :p_end_pessoa
        //                                                        ,p_des_bairro                 => :p_des_bairro
        //                                                        ,p_cod_municipio              => :p_cod_municipio
        //                                                        ,p_num_cep                    => :p_num_cep
        //                                                        ,p_num_fone                   => :p_num_fone
        //                                                        ,p_num_fax                    => :p_num_fax
        //                                                        ,p_des_email                  => :p_des_email
        //                                                        ,p_des_observ                 => :p_des_observ
        //                                                        ,p_num_caixa_postal           => :p_num_caixa_postal
        //                                                        ,p_cod_origem_ult_alteracao   => :p_cod_origem_ult_alteracao);
        //          end;';

        // if (!$p_str = oci_parse($conn,$sql)) {
        //     // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r(oci_error($conn));echo'<hr></pre>';exit;
        //     $this->firephp->log(oci_error($conn));
        //     oci_close($conn);
        // };

        // $p_num_pessoa = 5799374;
        // $p_nom_pessoa = 'Caio Cavalcanti de Aguiar Castro';
        // $p_end_pessoa = 'SQS 214';
        // $p_des_bairro = 'Asa Sul';
        // $p_cod_municipio = '5300108';
        // $p_num_cep = '12345678';
        // $p_num_fone = NULL;
        // $p_num_fax = NULL;
        // $p_des_email = 'caio.castro1@hexgis.com';
        // $p_des_observ = NULL;
        // $p_num_caixa_postal = NULL;
        // $p_cod_origem_ult_alteracao = 8;


        // // $this->firephp->log($num_pessoa);

        // // Bind dos dados de entrada
        // oci_bind_by_name( $p_str, ":p_num_pessoa", $p_num_pessoa );
        // oci_bind_by_name( $p_str, ":p_nom_pessoa", $p_nom_pessoa );
        // oci_bind_by_name( $p_str, ":p_end_pessoa", $p_end_pessoa );
        // oci_bind_by_name( $p_str, ":p_des_bairro", $p_des_bairro );
        // oci_bind_by_name( $p_str, ":p_cod_municipio", $p_cod_municipio );
        // oci_bind_by_name( $p_str, ":p_num_cep", $p_num_cep );
        // oci_bind_by_name( $p_str, ":p_num_fone", $p_num_fone );
        // oci_bind_by_name( $p_str, ":p_num_fax", $p_num_fax );
        // oci_bind_by_name( $p_str, ":p_des_email", $p_des_email );
        // oci_bind_by_name( $p_str, ":p_des_observ", $p_des_observ );
        // oci_bind_by_name( $p_str, ":p_num_caixa_postal", $p_num_caixa_postal );
        // oci_bind_by_name( $p_str, ":p_cod_origem_ult_alteracao", $p_cod_origem_ult_alteracao );

        // // Bind dos variaveis de saida
        // oci_bind_by_name($p_str,":erro",$erro,250);

        // // Executar
        // $r = oci_execute($p_str,OCI_DEFAULT);
        // if ( !$r ) {
        //     $e = oci_error($p_str);
        //     oci_close($conn);
        //     $this->firephp->log($e);
        // }

        // $this->firephp->log($erro);


        // GERAR_SENHA_SUBSISTEMA - OK



        // $sql = 'begin
        //             :erro := sisreg.pkg_pessoa_base.gerar_senha_subsistema_web( p_num_pessoa        => :p_num_pessoa
        //                                                                        ,p_senha             => :p_senha
        //                                                                        ,p_seq_app_modulo    => :p_seq_app_modulo);
        //          end;';

        // if (!$p_str = oci_parse($conn,$sql)) {
        //     // echo '<pre>'.__FILE__.': '.__LINE__.'<hr>';print_r(oci_error($conn));echo'<hr></pre>';exit;
        //     $this->firephp->log(oci_error($conn));
        //     oci_close($conn);
        // };

        // // $num_pessoa = (int) $data['NUM_PESSOA'][0];
        // $num_pessoa = 5799374;
        // $p_seq_app_modulo = 10144;
        // $p_senha = NULL;

        // // $this->firephp->log($num_pessoa);

        // // Bind dos dados de entrada
        // oci_bind_by_name( $p_str, ":p_num_pessoa", $num_pessoa);
        // oci_bind_by_name($p_str, ":p_seq_app_modulo", $p_seq_app_modulo);
        // oci_bind_by_name( $p_str, ":p_senha", $p_senha, 50);
        // oci_bind_by_name($p_str, ":erro", $erro,250);

        // // Executar
        // $r = oci_execute($p_str,OCI_DEFAULT);
        // if ( !$r ) {
        //     $e = oci_error($p_str);
        //     oci_close($conn);
        //     $this->firephp->log($e);
        // }

        // $this->firephp->log($p_senha);


        // $this->firephp->log($p_str);
        // $this->firephp->log($data);
        // $this->firephp->log($num_pessoa);
        // $this->firephp->log($p_seq_app_modulo);


        oci_free_statement($p_str);
        oci_close($conn);

    }

}
