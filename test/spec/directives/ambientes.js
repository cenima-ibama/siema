'use strict';

describe('Directive: ambientes', function () {

  // load the directive's module
  beforeEach(module('estatisticasApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<ambientes></ambientes>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the ambientes directive');
  }));
});
