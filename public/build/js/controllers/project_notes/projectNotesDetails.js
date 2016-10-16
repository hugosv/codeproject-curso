angular.module('app.controllers')
    .controller('ProjectNotesDetailsController',
    ['$scope', '$routeParams', 'ProjectNotes',  function($scope, $routeParams, ProjectNotes) {
        $scope.projectNotes = ProjectNotes.get({id: $routeParams.id, idNote: $routeParams.idNote});
    }]);