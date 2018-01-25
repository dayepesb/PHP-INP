'use strict';

angular
    .module('viewJob')
    .component('newJob', {
        templateUrl: '../homepage/app/form/newJob.template.html'
    });
var app = angular.module('viewJob');
app.controller('newJobController', ['$http', '$scope', function ($http, $scope) {
    $scope.saveJob = function (job) {
        alert(job.category);
    }
}]);