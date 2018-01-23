'use strict';
angular
    .module('list')
    .component('listJobs', {
        templateUrl: '../homepage/app/list/list.template.html',
        controller: function ListController($http) {
            var self = this;
            $http.get('/job/category/json').then(function (response) {
                self.jobsList = response.data;
            });
        }
    });