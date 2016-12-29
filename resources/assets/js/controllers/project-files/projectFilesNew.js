angular.module('app.controllers')
    .controller('ProjectFilesNewController',
    ['$scope', '$routeParams', '$location', 'appConfig', 'Url', 'Upload',
        function($scope, $routeParams, $location, appConfig, Url, Upload) {

            // Verificando se a função está correta
            // console.log(Url.getUrlResource('/project/{{id}}/file/{{idFile}}'));
            // console.log(Url.getUrlFromUrlSymbol('/project/{{id}}/file/{{idFile}}', {id: 1, idFile: 10}));
            // console.log(Url.getUrlFromUrlSymbol('/project/{{id}}/file/{{idFile}}', {id: '', idFile: 10}));
            // console.log(Url.getUrlFromUrlSymbol('/project/{{id}}/file/{{idFile}}', {id: 1, idFile: ''}));

            $scope.save = function() {
                if($scope.form.$valid) {
                    var url = appConfig.baseUrl + Url.getUrlFromUrlSymbol(appConfig.urls.projectFile, {
                            id: $routeParams.id,
                            idFile: ''
                        });
                    Upload.upload({
                        url: url,
                        fields: {
                            name: $scope.projectFiles.name,
                            description: $scope.projectFiles.description,
                            project_id: $routeParams.id
                        },
                        file: $scope.projectFiles.file
                    }).success(function (data, status, headers, config) {
                        $location.path('/project/' + $routeParams.id + '/files');
                    });
                }
            }

            }
    ]);