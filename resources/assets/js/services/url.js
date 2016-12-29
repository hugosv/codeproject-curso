angular.module('app.services')
    .service('Url', ['$interpolate', function ($interpolate) {
        return {
            getUrlFromUrlSymbol: function (url, params) {
                // project/{{id}}/file/{{idFile}}
                var urlMod = $interpolate(url)(params);
                return urlMod.replace(/\/\//g, '/')
                    .replace(/\/$/g, '' );
            },
            getUrlResource: function (url) {
                // project/{{id}}/file/{{idFile}} -> project/:id/file/:idFile
                return url.replace(new RegExp('{{', 'g'), ':')
                    .replace(new RegExp('}}', 'g'), '');
            }
        };
    }]);