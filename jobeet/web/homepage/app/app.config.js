'use strict';
angular
    .module('jobeetApp')
    .config(['$locationProvider', '$routeProvider',
        function config($locationProvider, $routeProvider) {
            $routeProvider
                .when('/', {
                    template: '<list-jobs></list-jobs>'
                }).when('/job/view/:id', {
                template: '<view-job></view-job>'
            }).when("/job/new/job", {
                template: '<new-job></new-job>'
            }).when("/job/edit/:id", {
                template: '<edit-job></edit-job>'
            }).when("/category/:slug", {
                template: '<list-jobs-for-category></list-jobs-for-category>'
            }).otherwise('/');
        }
    ]);