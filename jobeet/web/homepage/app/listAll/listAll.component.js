'use strict';
angular
    .module('listAll')
    .component('listJobsForCategory', {
        templateUrl: '../homepage/app/listAll/listAll.template.html',
        controller: ['$http', '$routeParams', function ListAllController($http,$routeParams) {
            var self = this;
            $http.get('/job/category/json?slug=' + $routeParams.slug).then(function (response) {
                self.jobsListAll = response.data;
            });
        }]
    });