'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:produtos
 * @description
 * # produtos
 */
angular.module('estatisticasApp')
  .directive('produtos', function ($compile, RestApi) {
    return {
      templateUrl: 'views/accordions/produtos.html',
      restrict: 'E',
      controller: function($scope){

        $scope.lastEle = 1;
        $scope.valueRows = [$scope.lastEle];

        $scope.lastEleNaoOnu = 1001;
        $scope.valueRowsNaoOnu = [$scope.lastEleNaoOnu];


        $scope.produtos.subPanel = $scope.oleo ? '(Itens V do Anexo II do Decreto nº 4.136 de 20 de fevereiro de 2002)' : '';

        // Funcoes para produtos ONU
        $scope.addRowOnu = function(){
            $scope.valueRows.push(++$scope.lastEle);
        }

        $scope.deleteData = function(v){
            var index = $scope.valueRows.indexOf(v);
            if (index > -1)
                 $scope.valueRows.splice(index, 1)
        }
        
        $scope.saveData = function(i){
            document.getElementById(i).addClass('disabled', 'disabled');
        }

        // Funcoes para produtos não ONU
        $scope.addRowNaoOnu = function(){
            $scope.valueRowsNaoOnu.push(++$scope.lastEleNaoOnu);
        
        }

        $scope.deleteDataNaoOnu = function(v){
            var index = $scope.valueRowsNaoOnu.indexOf(v);
            if (index > -1) 
                 $scope.valueRowsNaoOnu.splice(index, 1);
        }

        $scope.saveDataNaoOnu = function(i){
            document.getElementById(i).addClass('disabled', 'disabled');
        }

        //Função para inicializar o popover
        //Chamada no ng-init do html da diretiva
        $scope.popover = function(){
            $('[data-toggle="popover"]').popover({ html : true })
            $("#addTipo").popover({
                html : true, 
                content: function() {
                  return $('#popoverContent').html();
                }
            }).click(function(ev) {
                 //this is workaround needed in order to make ng-click work inside of popover
        
                $compile($('.popover.in').contents())($scope);
            });
        }

        $scope.validate = function(dado){
            var data = ['SELECT', 'select', '*', 'FROM', 'from', '%', 'DROP', 'drop'];
            var o=0;

            angular.forEach(data, function(value, key){
                if(dado == value)
                    o++;
                angular.forEach(dado, function(v, k){
                    if(v == value)
                        o++;
                })
            })

            if(o==0)
                return true;
            else
                return false;

        }

        $scope.addTipo = function(val){
            var d = $scope.validate(val);
            if(d){
                RestApi.query({query: 'fontes'},
                    function success(data, status){
                        alert(data);
                    }
                );
            } else {
                alert('trying sql?');
            }
        }




      },
    };
  });
