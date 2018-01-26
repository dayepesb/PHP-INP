'use strict';

angular.module('editJob')
    .component('editJob',{
       templateUrl : '../homepage/app/edit/editJob.template.html',
       controller : ['$http','$routeParams', function ($http,$routeParams) {
           var self = this;
           $http.get('/job/view/json?id='+$routeParams.id).then( function(response){
               self.edit = response.data;
               $http.get('/category/job/json?id='+self.edit.categoryId).then( function(response){
                   self.category = response.data;
               });
           });
       }]
    });