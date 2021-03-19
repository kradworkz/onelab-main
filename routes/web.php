<?php

use App\Models\Article;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $articles = Article::orderBy('created_at','DESC')->take(3)->get();
    return view('welcome')->with('articles',$articles);
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
   
    Route::get('/chatbox', 'ChatboxController@index'); 

}); 



Route::middleware(['role:Administrator','auth'])->group(function () {
   
    // Route::get('/agency/types', 'Admin\PageController@agencytype'); 
    Route::get('/agencies', 'Admin\PageController@agencies'); 
    Route::get('/staffs', 'Admin\PageController@staffs'); 
    Route::get('/dropdownlists', 'Admin\PageController@dropdownlists'); 
    Route::get('/testservices', 'Admin\PageController@testservices'); 
    Route::get('/packages', 'Admin\PageController@packages'); 
    Route::get('/addons', 'Admin\PageController@addons'); 
    Route::get('/articles', 'Admin\PageController@articles'); 

}); 












Route::prefix('request')->group(function () {

    Route::get('/regions', 'Admin\AgencyController@regions');
    Route::get('/provinces/{id}', 'Admin\AgencyController@provinces');
    Route::get('/municipalities/{id}', 'Admin\AgencyController@municipalities');

    Route::post('/articles/{type}', 'Admin\ArticleController@public');
    Route::get('/articlepage/{type}', 'Admin\ArticleController@paginated');
    Route::post('/article/store', 'Admin\ArticleController@store');
    Route::put('/article/store', 'Admin\ArticleController@store');

    Route::prefix('admin')->group(function () {

        Route::post('/users', 'Admin\UserController@index'); 
        Route::post('/user/store', 'Admin\UserController@store');
        Route::put('/user/store', 'Admin\UserController@store');
        Route::put('/user/status', 'Admin\UserController@status');
        
        Route::post('/dropdownlist/store', 'Admin\DropdownlistController@store');
        Route::get('/dropdownlist/{type}', 'Admin\DropdownlistController@index');
        Route::get('/dropdowns/{type}', 'Admin\DropdownlistController@lists');
        Route::post('/dropdownlist/search', 'Admin\DropdownlistController@search');
        Route::get('/dropdownlist/{id}', 'Admin\DropdownlistController@paginated');
      
        Route::get('/agencies/paginated/{id}', 'Admin\AgencyController@paginated');
        Route::get('/agency/lists/{type}', 'Admin\AgencyController@lists');
        Route::post('/agency/search', 'Admin\AgencyController@search');
        Route::post('/agency/store', 'Admin\AgencyController@store');
        Route::put('/agency/store', 'Admin\AgencyController@store'); 

        Route::post('/sampletest/search', 'Admin\TestServiceController@sampletestsearch');
        Route::post('/sampletest/store', 'Admin\TestServiceController@sampletest');

        Route::get('/testmethods/{id}/{agency}/{sampletype}', 'Admin\TestServiceController@methods');
        Route::post('/methodreference/store', 'Admin\TestServiceController@methodreference');
        Route::post('/methodreference/search', 'Admin\TestServiceController@methodreferencesearch');

        Route::post('/testservice/store', 'Admin\TestServiceController@store');
        Route::get('/testservices/list/{id}/{agency}', 'Admin\TestServiceController@testservices');
        Route::get('/testservices/{id}/{agency}', 'Admin\TestserviceController@paginated');

        Route::get('/packages/{id}/{agency}', 'Admin\TestServiceController@packages');
        Route::post('/package/store', 'Admin\TestServiceController@package');
        Route::put('/package/store', 'Admin\TestServiceController@package');
        Route::get('/addons/{id}/{agency}', 'Admin\TestServiceController@addons');

        Route::get('/agencies', 'Admin\AgencyController@agencyaddress');
        // Route::get('/sampletypes/{id}', 'Admin\TestserviceController@sampletypes');

        // Route::post('/testservice/store', 'Admin\TestserviceController@store');
        // Route::get('/testservice/list', 'Admin\TestserviceController@list');
        
        // Route::post('/testservices/search', 'Admin\TestserviceController@search');

        // Route::post('/package/store', 'Admin\PackageController@store');
        // Route::get('/package/list', 'Admin\PackageController@list');
        // Route::get('/packages/{id}', 'Admin\PackageController@index');
        // Route::post('/packages/search', 'Admin\PackageController@search');

    });

    Route::prefix('chatbox')->group(function () {

        //Single
        Route::post('/contact/search', 'ChatController@search');
        Route::get('/contacts', 'ChatController@contacts');
        Route::post('/conversation', 'ChatController@conversation');
        Route::get('/chat/{id}', 'ChatController@chat')->name('chat');
        Route::post('/chat/message/send', 'ChatController@send')->name('chat.send');
        Route::post('/chat/message/send/file', 'ChatController@sendFilesInConversation')->name('chat.send.file');

         //Group
        Route::get('/groups', 'ChatController@groups');
        Route::post('/group/conversation', 'ChatController@groupConversation');
        Route::post('/group/chat/create', 'ChatController@createGroup'); 
        Route::post('/group/chat/message/send', 'ChatController@sendGroup');
        Route::post('/group/chat/add', 'ChatController@addUser');
        Route::delete('/group/chat/{id}', 'ChatController@removeUser');
        
    });

});