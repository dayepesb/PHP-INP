'use strict';
angular
    .module('viewJob')
    .component('viewJob', {
        templateUrl: '../homepage/app/viewJob/viewJob.template.html',
        controller: ['$scope','$http', '$routeParams', function viewJobController($scope,$http, $routeParams) {
            var self = this;
            $http.get('/job/view/json?id=' + $routeParams.id).then(function (response) {
                self.jobView = response.data;
            });
            $scope.delete=function () {
                var data = {
                    id : $routeParams.id
                };
                $http.post('/job/delete',data).then(function(response){
                    alert(response.data);
                    location.replace('/');
                });
            };
        }]
    });