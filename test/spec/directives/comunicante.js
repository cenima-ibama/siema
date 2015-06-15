'use strict';

describe('Directive: comunicante', function () {

  // load the directive's module
  beforeEach(module('estatisticasApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<comunicante></comunicante>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the comunicante directive');
  }));
});
