angular.module('app.controllers')
    .controller('ProjectNotesListController',
    ['$scope', '$routeParams', 'ProjectNotes',  function($scope, $routeParams, ProjectNotes) {
        $scope.projectNotes = ProjectNotes.query({id: $routeParams.id});
    }]);