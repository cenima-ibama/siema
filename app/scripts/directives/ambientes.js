'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:ambientes
 * @description
 * # ambientes
 */
angular.module('estatisticasApp')
  .directive('ambientes', function () {
    return {
      templateUrl: 'views/accordions/ambientes.html',
      restrict: 'E',
      controller: function($scope){

        $scope.ambientes.complementar = "";
        $scope.ambientes.semAmbientes = false;

        $scope.ambientes.ambientes = [
            {"name": "Óbitos/feridos", "value":"false"},
            {"name": "População afetada/evacuada", "value":"false"},
            {"name": "Suspensão de abastecimento de água", "value":"false"},
            {"name": "Rio/córrego", "value":"false"},
            {"name": "Lago", "value":"false"},
            {"name": "Mar", "value":"false"},
            {"name": "Praia", "value":"false"},
            {"name": "Solo", "value":"false"},
            {"name": "Águas subterrâneas", "value":"false"},
            {"name": "Atmosfera", "value":"false"},
            {"name": "Flora", "value":"false"},
            {"name": "Fauna", "value":"false"},
            {"name": "Unidade de Conservação Federal", "value":"false"},
            {"name": "Unidade de Conservação Estadual/Municipal", "value":"false"},
            {"name": "Outro(s)", "value":"false"}
        ];

        // $scope.loginIn = function(user, pass){
        //   // $cookies.user = {user: user, password: pass};
        //   RestApi.login({},{
        //       username: user,
        //       password: pass
        //     },function success(data, status){
        //       $scope.user = data.user;
        //       Auth.setUser(data.user);
        //       $location.path("/page2");
        //     },function error(data, status){
        //         console.log('!ERROR! ' + data);
        //     }
        //   );
        // }

      },
    };
  });
