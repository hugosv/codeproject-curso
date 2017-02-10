angular.module('app.controllers')
    .controller('MemberProjectsList', ['$scope', '$routeParams', 'Project', function($scope, $routeParams, Project){

            $scope.projects = [];
            $scope.totalProjects = 0;
            $scope.projectsPerPage = 8;

            $scope.pagination = {
                current: 1
            };

            $scope.pageChanged = function(newPage){
                getResultsPage(newPage);
            };

            function getResultsPage(pageNumber){
                Project.memberProjects({
                    page: pageNumber,
                    limit: $scope.projectsPerPage
                }, function(data){
                    $scope.projects = data.data;
                    $scope.totalProjects = data.meta.pagination.total;
                });
            }
            getResultsPage(1);
        }]);