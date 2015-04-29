'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:empresa
 * @description
 * # empresa
 */
angular.module('estatisticasApp')
  .directive('empresa', function () {
    return {
      templateUrl: 'views/accordions/empresa.html',
      restrict: 'E',
      controller: function($scope){

        $scope.licencas = [
          {name: 'Licença ambiental federal'},
          {name: 'Licença ambiental estadual'},
          {name: 'Licença ambiental municipal'},
        ]

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
