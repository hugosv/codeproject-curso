angular.module('app.controllers')
    .controller('ProjectFilesListController',
    ['$scope', '$routeParams', 'ProjectFiles',  function($scope, $routeParams, ProjectFiles) {
        $scope.projectFiles = ProjectFiles.query({id: $routeParams.id});
    }]);