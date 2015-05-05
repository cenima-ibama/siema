'use strict';

/**
 * @ngdoc function
 * @name estatisticasApp.controller:AcidenteCtrl
 * @description
 * # AcidenteCtrl
 * Controller of the estatisticasApp
 */
angular.module('estatisticasApp')
  .controller('AcidenteCtrl', function ($scope, RestApi) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

    $scope.accordions = ['localizacao',
                         'mapa',
                         'datas',
                         'origem',
                         'evento',
                         'produtos',
                         'detalhes',
                         'ambiente',
                         'empresa',
                         'instituicao',
                         'acoes',
                         'gerais',
                         'comunicante',
                         'fonte'];

    angular.forEach($scope.accordions, function(obj) {
      $scope[obj] = {};
      $scope[obj].subPanel = "";
    });

    // $scope.acao = 'criar';
    $scope.acao = 'carregar';
    $scope.oleo = false;
    $scope.logado = false;

    // $scope.nro_ocorrencia = '201531732431';
    // $scope.nro_ocorrencia = '201491928814';
    // $scope.nro_ocorrencia = '201492328850';
    $scope.nro_ocorrencia = '201491939644';
    // $scope.nro_ocorrencia = '201492528839';

    RestApi.query({query: 'ufs'},
      function success(data, status){
        $scope.ufs = [];
        angular.forEach(data, function(value, key){
          $scope.ufs.push({'name' : value.sigla, 'value': value.id_uf});
        });
      }
    );

    RestApi.query({query: 'bacias'},
      function success(data, status){
        $scope.bacias = [];
        angular.forEach(data, function(value, key){
          $scope.bacias.push({'name' : value.nome, 'value': value.id_bacia_sedimentar});
        });
      }
    );

    $scope.carregarMunicipios = function($uf) {
      RestApi.query({query: 'municipios', uf:$uf},
        function success(data, status){
          $scope.municipios = [];
          angular.forEach(data, function(value, key){
            $scope.municipios.push({'name' : value.nome, 'value': value.cod_ibge});
          });
        }
      );
    }


    RestApi.query({query: 'origens'},
      function success(data, status){
        $scope.origens = [];
        angular.forEach(data, function(value, key){
          $scope.origens.push({'name' : value.des_tipo_localizacao, 'id': value.id_tipo_localizacao, 'value': false});
        });
      }
    );

    RestApi.query({query: 'eventos'},
      function success(data, status){
        $scope.eventos = [];
        angular.forEach(data, function(value, key){
          $scope.eventos.push({'name' : value.nome, 'id': value.id_tipo_evento, 'value': false});
        });
      }
    );

    RestApi.query({query: 'ambientes'},
      function success(data, status){
        $scope.ambientes = [];
        angular.forEach(data, function(value, key){
          $scope.ambientes.push({'name' : value.nome, 'id': value.id_tipo_dano_identificado, 'value': false});
        });
      }
    );

    RestApi.query({query: 'instituicoes'},
      function success(data, status){
        $scope.instituicoes = [];
        angular.forEach(data, function(value, key){
          $scope.instituicoes.push({'name' : value.nome, 'id': value.id_instituicao_atuando_local, 'value': false});
        });
      }
    );

    RestApi.query({query: 'fontes'},
      function success(data, status){
        $scope.fontes = [];
        angular.forEach(data, function(value, key){
          $scope.fontes.push({'name' : value.nome, 'id': value.id_tipo_fonte_informacao, 'value': false});
        });
      }
    );

    $scope.licencas = [
      {name: 'Licença ambiental federal', value: '1'},
      {name: 'Licença ambiental estadual', value: '2'},
      {name: 'Licença ambiental municipal', value: '3'},
    ];

    $scope.unidades =  [
      {name: 'Metro Cúbico (m3)', value: 'm3'},
      {name: 'Litro (L)', value: 'L'},
      {name: 'Tonelada (T)', value: 'T'},
      {name: 'Quilograma (Kg)', value: 'Kg'},
    ];


    if ($scope.acao == 'carregar') {
      RestApi.query({query: 'carregar_ocorrencia', ocorrencia:$scope.nro_ocorrencia},
        function success(data, status){
          $scope.oleo = data[0].ocorrencia_oleo == 'S' ? true : false;

          $scope.$broadcast('carregar_localizacao', data);
          $scope.$broadcast('carregar_datas', data);
          $scope.$broadcast('carregar_origem', data);
          $scope.$broadcast('carregar_evento', data);
          // $scope.$broadcast('carregar_produtos', ret);
          $scope.$broadcast('carregar_detalhes', data);
          $scope.$broadcast('carregar_ambientes', data);
          $scope.$broadcast('carregar_empresa', data);
          $scope.$broadcast('carregar_instituicao', data);
          $scope.$broadcast('carregar_acoes', data);
          $scope.$broadcast('carregar_gerais', data);
          $scope.$broadcast('carregar_comunicante', data);
          // $scope.$broadcast('carregar_arquivos', ret);
          $scope.$broadcast('carregar_fonte', data);
        }
      );
    } else if ($scope.acao == 'deletar') {
      RestApi.query({query: 'deletar_ocorrencia'},
        function success(data, status){

        }
      );
    }

    // $scope.bacias = [
    //   { name: "Bacia 1", value: "1" },
    //   { name: "Bacia 2", value: "2" },
    //   { name: "Bacia 3", value: "3" },
    //   { name: "Bacia 4", value: "4" }
    // ];


    // $scope.municipios = [
    //   { name: "Municipio 1", value: "1" },
    //   { name: "Municipio 2", value: "2" },
    //   { name: "Municipio 3", value: "3" },
    //   { name: "Municipio 4", value: "4" }
    // ];

    // $scope.toggleAccordion = function($accordion) {
    //   if ($scope[$accordion].show == 'in') {
    //     angular.forEach($scope.accordions, function(obj) {
    //       $scope[obj].show = "";
    //     });
    //   } else {
    //     angular.forEach($scope.accordions, function(obj) {
    //       $scope[obj].show = "";
    //     });
    //     $scope[$accordion].show = 'in';
    //   }
    // };

  });
