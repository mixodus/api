<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

//FASE 2

require 'fase2/api.php';
Route::group(['middleware' => ['user.token', 'cors','log.route']], function ($router) {
	//Home
	Route::get('/home', 'API\MainController@index');
	Route::get('/level', 'API\MainController@Level');  
	Route::get('/event/all_ongoing', 'API\MainController@allOngoing');
	Route::get('/home_event', 'API\MainController@homeEvent'); 
	Route::get('/home_news', 'API\MainController@homeNews'); 

	//User
	Route::get('/profile', 'API\UserController@getProfile'); 
	Route::put('/profile', 'API\UserController@updateProfile'); 
	Route::post('/profile', 'API\UserController@completeProfile'); 
	Route::post('/profile/skill', 'API\UserController@updateSkill'); 
	Route::post('/profile/change_password', 'API\UserController@changePassword'); 
	Route::post('/profile/photo', 'API\UserController@uploadPicture'); 

	//News
	Route::get('/news', 'API\NewsController@index'); 
	Route::get('/news/detail/{id}', 'API\NewsController@detail'); 

	//Jobs
	Route::get('/job_post', 'API\JobsController@index'); 
	Route::get('/job_post/detail/{id}', 'API\JobsController@detail');
	Route::get('/job_post/progress', 'API\JobsController@userJobsApplication');
	Route::post('/job_post/apply', 'API\JobsController@applyJobsApplication');

	//Events
	Route::get('/event', 'API\EventController@index');
	Route::get('/event/event_type/{id}', 'API\EventController@EventType');
	Route::post('/event/join', 'API\EventController@joinEvent'); 
	Route::get('/event/detail/{id}', 'API\EventController@detail');  
	Route::get('/event/countries', 'API\GeneralController@getCountryList');  
	Route::get('/event/history/{id}', 'API\EventController@HistoryEvent'); //belum ditest dummy data

	//Challenge
	Route::get('/challenge', 'API\ChallengeController@index');
	Route::get('/challenge/history', 'API\ChallengeController@history');
	Route::get('/challenge/detail/{id}', 'API\ChallengeController@detail');
	Route::get('/challenge/quiz', 'API\ChallengeController@quiz');
	Route::post('/challenge/join', 'API\ChallengeController@join');
	Route::post('/challenge/quiz', 'API\ChallengeController@answer'); //answer quiz
	Route::get('/challenge/achievement', 'API\ChallengeController@achievement');
	Route::get('/challenge/achievement_all', 'API\ChallengeController@achievementAll');

	//Referral
	Route::get('/referral', 'API\ReferralController@getReferralMember');
	Route::get('/referral/success', 'API\ReferralController@getReferralMemberSuccess');
	Route::post('/referral', 'API\ReferralController@AssignMember');

	//Admin
	Route::get('/admin', 'API\Dashboard\AuthUser\AdminController@index');

	//Menu
	Route::get('/menu', 'API\Dashboard\UserManagement\MenuController@index');
	Route::get('/menu-show', 'API\Dashboard\UserManagement\MenuController@show');
	Route::post('/menu-create', 'API\Dashboard\UserManagement\MenuController@store');
	Route::put('/menu-update/{id}', 'API\Dashboard\UserManagement\MenuController@update');

	//Access Role
	Route::get('/settings/roles', 'API\Dashboard\UserManagement\RolesController@index');
	Route::get('/settings/roles-show', 'API\Dashboard\UserManagement\RolesController@show');
	Route::get('/settings/roles-edit', 'API\Dashboard\UserManagement\RolesController@edit');
	Route::post('/settings/roles-create', 'API\Dashboard\UserManagement\RolesController@store');
	Route::put('/settings/roles-update/{id}', 'API\Dashboard\UserManagement\RolesController@update');
	Route::delete('/settings/roles-delete/{id}', 'API\Dashboard\UserManagement\RolesController@destroy');

	//Permission
	Route::get('/settings/permissions', 'API\Dashboard\UserManagement\PermissionController@index');
	Route::get('/settings/permissions-show', 'API\Dashboard\UserManagement\PermissionController@show');
	Route::post('/settings/permissions-create', 'API\Dashboard\UserManagement\PermissionController@store');
	Route::put('/settings/permissions-update/{id}', 'API\Dashboard\UserManagement\PermissionController@update');
	Route::delete('/settings/permissions-delete/{id}', 'API\Dashboard\UserManagement\PermissionController@destroy');
	//Notif
	Route::get('/notif', 'API\NotifController@index');
	Route::get('/notif/detail_by/{id}', 'API\NotifController@detail');
	Route::get('/notif/new_notif', 'API\NotifController@newNotif');
	Route::put('/notif', 'API\NotifController@update');

	//employee project experience
	Route::get('/project', 'API\ProjectExperienceController@index');
	Route::post('/project', 'API\ProjectExperienceController@create');
	Route::put('/project', 'API\ProjectExperienceController@update');
	Route::delete('/project', 'API\ProjectExperienceController@delete');

	//employee education
	Route::get('/education', 'API\EducationController@index');
	Route::post('/education', 'API\EducationController@create');
	Route::put('/education', 'API\EducationController@update');
	Route::delete('/education', 'API\EducationController@delete');

	//people
	Route::get('/people', 'API\PeopleController@index');

	//certification
	Route::get('/certification', 'API\CertificationController@index');
	Route::get('/certification/certif_by_id/{id}', 'API\CertificationController@detail');
	Route::delete('/certification', 'API\CertificationController@delete');
	Route::post('/certification', 'API\CertificationController@postData');

	
	//employee work experience
	Route::get('/work_experience', 'API\WorkExperienceController@index');
	Route::post('/work_experience', 'API\WorkExperienceController@create');
	Route::put('/work_experience', 'API\WorkExperienceController@update');
	Route::delete('/work_experience', 'API\WorkExperienceController@delete');

	//point
	Route::get('/point', 'API\PointController@index');
	Route::get('/point/leaderboard_month', 'API\PointController@leaderboardMonth'); 
	// Route::get('/point/leaderboard_challenge', 'API\PointController@leaderboardChallenge');//invalid old code
	


});

