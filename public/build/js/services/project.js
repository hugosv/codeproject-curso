angular.module('app.services')
    .service('Project', ['$resource', 'appConfig', function ($resource, appConfig) {
        return $resource(appConfig.baseUrl + '/project/:id', {id: '@id'}, {
            save: {
                method: 'POST',
                isArray: false,
                transformResponse: function(data) {
                    var obj = angular.fromJson(data);
                    obj.due_date = new Date(obj.due_date);
                    return obj;
                }
            },
            get: {
                method: 'GET',
                isArray: false,
                transformResponse: function (data) {
                    var obj = angular.fromJson(data);
                    obj.due_date = new Date(obj.due_date);
                    return obj;
                }
            },
            update: {
                method: 'PUT'
            }
        });
    }]);