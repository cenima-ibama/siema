'use strict';

describe('Directive: pie', function () {

  // load the directive's module
  beforeEach(module('estatisticasApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<pie></pie>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the pie directive');
  }));
});
