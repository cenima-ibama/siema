<header id="navbar" class="navbar navbar-inverse">
    <div id="barra-brasil" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;">
        <ul id="menu-barra-temp" style="list-style:none;">
            <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a></li>
            <li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
        </ul>
    </div>


    <div class="navbar-inner">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <!-- <a class="brand" href="#"><img src="<?= base_url() ?>/assets/img/ibama.png" style="margin:-2px 2px 0 10px;"> <span class="label label-inverse">v1.0a</span></a> -->
        <a class="brand" href="#"><img src="<?= base_url() ?>/assets/img/logo_ibama_icone.png" style="margin:-2px 2px 0 10px;"> <span style="font-size:15px;color:#FFFFFF;"> <strong>SIEMA</strong> </span> <span class="label label-inverse" style="font-size:9px;">v1.0a</span></a>
        <div class="nav-collapse collapse">
            <ul class="nav">
                <li class="active"><a id="btn-map" href="#"><i class="icon-globe icon-white"></i> Mapa </a></li>
                <li><a id="btn-charts" href="#"><i class="icon-bar-chart icon-white"></i> Estatística </a></li>
                <li><a id="btn-form" data-toggle="modal" href="#addMeModal"><i class="icon-plus-sign icon-white"></i> Informar Acidente </a></li>

                <?php
                $profileUser = $this->session->userdata('profile_user');

                if ($this->session->userdata('logged_in')/* and in_array($this->session->userdata('username'), $this->userEnableList) */) {
                    echo '<li class=""><a id="btn-consult" href="#"><i class="icon-search icon-white"></i> Consultas </a></li>';

                    // if ($profileUser == 1 || $profileUser == 2)
                        // echo '<li><a id="btn-manag" href="#"><i class="icon-list-alt icon-white"></i> Cadastros </a></li>';
                }
                //  else  {
                //   echo '<li class="" style="display:none;"><a id="btn-consult" href="#"><i class="icon-search icon-white"></i> Consultas </a></li>';
                //   echo '<li style="display:none;"><a id="btn-manag" href="#"><i class="icon-list-alt icon-white"></i> Cadastros </a></li>';
                // }
                ?>

        <!-- <li class=""><a id="btn-consult" href="#"><i class="icon-search icon-white"></i> Consultas </a></li> -->
        <!-- <li><a id="btn-manag" href="#"><i class="icon-list-alt icon-white"></i> Cadastros </a></li> -->
            </ul>
            <!--
                  <ul class="nav pull-left" style="margin-top:10px;margin-right:10px;color: #FFFFFF;">
                    <li class=""><strong> SIEMA </strong></li>
                  </ul>
            -->
            <?php
            if ($this->session->userdata('logged_in')) {
                echo '<ul class="nav pull-right">';
                echo '<li id="li-login" class="dropdown">';
                echo('<a id="btn-logged" class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-user icon-white"></i> ' . $name . '<strong class="caret"></strong></a>');
                echo '<ul class="dropdown-menu">';
                echo '<li>' . anchor(base_url() . '/index.php/auth/logout', 'Logout', '') . '</li>';
                echo '</ul>';
                echo '</li>';
                echo '</ul>';
            } else {
                echo '<ul class="nav pull-right">';
                echo '<li id="li-login" class=""><a id="btn-login" href="#"><i class="icon-user icon-white"></i> Login</a></li>';
                echo '</ul>';
            }
            ?>

        </div>
    </div>
</header>
