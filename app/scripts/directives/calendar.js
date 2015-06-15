'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:calendar
 * @description
 * # calendar
 */
angular.module('estatisticasApp')
  .directive('calendar', function () {
    return {
      require: 'ngModel',
      restrict: 'EA',
      link: function (scope, el, attr, ngModel) {
        var date = new Date();
        var dia = date.getDate();
        if(dia<10)
          dia = "0" + dia.toString();
        var mes = date.getMonth()+1;
        if(mes < 10)
          mes = "0" + mes.toString();
        var ano = date.getFullYear();


        var finalDate = dia + '/' + mes + '/' + ano;
        var initialDate = dia + '/' + mes + '/' + (ano-1);

        $(el).datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true,
          language: "pt-BR",
          startDate: initialDate,
          endDate: finalDate

          // onSelect: function (dateText) {
          //   scope.$apply(function () {
          //     ngModel.$setViewValue(dateText);
          //   });
          // }
        });
      }
    };
  });
