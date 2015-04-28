'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:instituicao
 * @description
 * # instituicao
 */
angular.module('estatisticasApp')
  .directive('instituicao', function () {
    return {
      templateUrl: 'views/accordions/instituicao.html',
      restrict: 'E',
      controller: function($scope){

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
