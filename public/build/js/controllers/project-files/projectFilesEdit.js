angular.module('app.controllers')
    .controller('ProjectFilesEditController',
    ['$scope', '$routeParams', '$location', 'ProjectFiles',  function($scope, $routeParams, $location, ProjectFiles) {

        $scope.projectFiles = ProjectFiles.get({
            id: $routeParams.id,
            idFile: $routeParams.idFile
        });

        $scope.save = function() {
            if($scope.form.$valid) {
                ProjectFiles.update({
                    id: $scope.projectFiles.project_id,
                    idFile: $scope.projectFiles.id
                }, $scope.projectFiles, function () {
                    $location.path('/project/' + $scope.projectFiles.project_id + '/files');
                });
            }
        }

    }]);