'use strict';
angular
    .module('viewJob')
    .component('viewJob', {
        templateUrl: '../homepage/app/viewJob/viewJob.template.html',
        controller: ['$http', '$routeParams', function viewJobController($http, $routeParams) {
            var self = this;
            $http.get('/job/view/json?id=' + $routeParams.id).then(function (response) {
                self.jobView = response.data;
            });
        }]
    });