<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'WebController@homeview');


Route::get('login', 'WebController@loginview')->name('login');

Route::post('/user-login', 'AuthController@userlogin');

/*========================================== Start of Admin Dashboard Routes===========================================*/
Route::get('admin-dashboard', 'AdminController@admindashboard');
Route::get('company-info', 'AdminController@appdata');
Route::post('update-app-data-general', 'AdminController@updateappdatageneral');
Route::post('update-app-data-contact', 'AdminController@updateappdatacontact');
Route::post('update-app-data-logo', 'AdminController@updateappdatalogo');
Route::post('update-app-data-letterhead', 'AdminController@updateappdataletterhead');
Route::post('update-app-data-terms', 'AdminController@updateappdataterms');
Route::get('employees', 'AdminController@employees');
Route::post('insert-employee', 'AdminController@insertemployee');
Route::post('delete-employee', 'AdminController@deleteemployee');
Route::post('edit-employee', 'AdminController@editemployee');
Route::get('users', 'AdminController@users');
Route::post('insert-user', 'AdminController@insertuser');
Route::post('delete-user', 'AdminController@deleteuser');
Route::post('edit-user', 'AdminController@edituser');
Route::get('admin-profile', 'AdminController@profile');
Route::post('change-password', 'AdminController@changepassword');
/*========================================== End of Admin Dashboard Routes===========================================*/

/*========================================== Start of Admin Web Routes===========================================*/
Route::get('admin-hero', 'AdminController@hero');
Route::post('admin-update-hero-message', 'AdminController@adminupdateheromessage');
Route::post('admin-update-hero-background-image', 'AdminController@adminupdateherobackgroundimage');
Route::post('admin-update-hero-display-image', 'AdminController@adminupdateherodisplayimage');

Route::get('admin-header', 'AdminController@header');
Route::post('admin-update-header-info', 'AdminController@adminupdateheaderinfo');
Route::post('admin-update-navbar-logo', 'AdminController@adminupdatenavbarlogo');
Route::post('admin-update-website-icon', 'AdminController@adminupdatewebsiteicon');



Route::get('admin-footer', 'AdminController@footer');
Route::post('admin-update-footer-info', 'AdminController@adminupdatefooterinfo');
Route::post('admin-update-footer-logo', 'AdminController@adminupdatefooterlogo');



Route::get('admin-about', 'AdminController@about');
Route::post('admin-update-about-info', 'AdminController@adminupdateaboutinfo');
Route::post('admin-update-about-image', 'AdminController@adminupdateaboutimage');



Route::get('admin-services', 'AdminController@services');
Route::post('admin-insert-service', 'AdminController@admininsertservice');
Route::post('admin-update-service', 'AdminController@adminupdateservice');
Route::post('admin-delete-service', 'AdminController@admindeleteservice');
Route::post('admin-update-services-title', 'AdminController@adminupdateservicestitle');



Route::get('admin-privacy', 'AdminController@privacy');
Route::post('admin-insert-privacy', 'AdminController@admininsertprivacy');
Route::post('admin-update-privacy', 'AdminController@adminupdateprivacy');
Route::post('admin-delete-privacy', 'AdminController@admindeleteprivacy');
Route::post('admin-update-privacy-title', 'AdminController@adminupdateprivacytitle');


/*========================================== End of Admin Web Routes===========================================*/