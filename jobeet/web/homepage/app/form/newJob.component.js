'use strict';

angular
    .module('viewJob')
    .component('newJob', {
        templateUrl: '../homepage/app/form/newJob.template.html'
    });
var app = angular.module('viewJob');
app.controller('newJobController', ['$http', '$scope', function ($http, $scope) {
    $scope.saveJob = function (job) {
        var self = this;
        var data = {
            category: job.category,
            type: job.type,
            company: job.company,
            url: job.url,
            position: job.position,
            location: job.location,
            description: job.description,
            howtoapply: job.howToApply,
            ispublic: job.isPublic,
            mail: job.mail
        };
        $http.post('/new/post', data)
            .then(function (response) {
                console.log(response.data);
                self.job = response.data;
                location.replace('/#/job/view/'+self.job.id);
            });
    }
}]);