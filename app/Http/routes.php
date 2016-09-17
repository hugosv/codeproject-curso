<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::post('oauth/access_token', function () {
    return Response::json(Authorizer::issueAccessToken());
});

Route::group(['middleware' => 'oauth'], function() {

//    /**
//     * Aplique o processo de Autorização em todos os endpoints de nossa API
//     */
//    Route::group(['middleware' => 'CheckProjectOwner'], function () {
//        Route::resource('project', 'Projectcontroller', ['except' => ['create', 'edit']]);
//    });

    Route::resource('client', 'ClientController', ['except' => ['create', 'edit']]);
    Route::resource('project', 'ProjectController', ['except' => ['create', 'edit']]);
    Route::resource('project.note', 'ProjectNoteController', ['except' => ['create', ' edit']]);
    Route::resource('project.task', 'ProjectTaskController', ['except' => ['create', 'edit']]);
    Route::resource('project.members', 'ProjectMemberController', ['only' => ['index',  'store', 'show', 'destroy']]);
    Route::post('project/{id}/file', 'ProjectFileController@store');
    Route::delete('project/{id}/file/{file_id}', 'ProjectFileController@destroy');

});