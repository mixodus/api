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


require 'fase2/api.php';
Route::group(['middleware' => ['app.token', 'cors','log.route']], function ($router) {
	//Home
	Route::get('/home/check_session', 'API\MainController@checkSession');
	Route::get('/home', 'API\MainController@index');
	Route::get('/level', 'API\MainController@Level');  
	Route::get('/event/all_ongoing', 'API\MainController@allOngoing');
	Route::get('/home_event', 'API\MainController@homeEvent'); 
	Route::get('/home_news', 'API\MainController@homeNews'); 
	
	//News
	Route::get('/news', 'API\NewsController@index'); 
	Route::get('/news/detail/{id}', 'API\NewsController@detail'); 
	
	//Jobs
	Route::get('/job_post', 'API\JobsController@index'); 
	Route::get('/job_post/detail/{id}', 'API\JobsController@detail');

	//Events
	Route::get('/event', 'API\EventController@index');
	Route::get('/event/event_type/{id}', 'API\EventController@EventType');
	Route::get('/event/detail/{id}', 'API\EventController@detail');  
	Route::get('/event/countries', 'API\GeneralController@getCountryList');  

	//Challenge
	Route::get('/challenge', 'API\ChallengeController@index');
	Route::get('/challenge/detail/{id}', 'API\ChallengeController@detail');

	//point
	Route::get('/point', 'API\PointController@index');
	Route::get('/point/leaderboard_month', 'API\PointController@leaderboardMonth'); 
	// Route::get('/point/leaderboard_challenge', 'API\PointController@leaderboardChallenge');//invalid old code
});

Route::group(['middleware' => ['user.token', 'cors','log.route']], function ($router) {

	//User
	Route::get('/profile', 'API\UserController@getProfile'); 
	Route::put('/profile', 'API\UserController@updateProfile')->middleware('log.route:user,Update-Profile,action'); 
	Route::post('/profile', 'API\UserController@completeProfile')->middleware('log.route:user,Complete-Profile,action');  
	Route::get('/profile/friend/{id}', 'API\UserController@friendProfile'); 
	Route::post('/profile/skill', 'API\UserController@updateSkill')->middleware('log.route:user,Update-Skill,action');
	Route::post('/profile/change_password', 'API\UserController@changePassword')->middleware('log.route:user,Change-Password,action');
	Route::post('/profile/photo', 'API\UserController@uploadPicture')->middleware('log.route:user,Update-Photo,action');
	Route::get('/profile/check-npwp', 'API\UserController@checkNpwp'); 
	Route::post('/profile/npwp', 'API\UserController@updateNpwp')->middleware('log.route:user,Update-NPWP,action'); 

	//Jobs
	Route::get('/job_post/progress', 'API\JobsController@userJobsApplication');
	Route::post('/job_post/apply', 'API\JobsController@applyJobsApplication')->middleware('log.route:jobs,Apply-Job,action');

	//Events
	Route::post('/event/join', 'API\EventController@joinEvent')->middleware('log.route:join_event,action');
	Route::get('/event/history/{id}', 'API\EventController@HistoryEvent'); //belum ditest dummy data

	//Challenge
	Route::get('/challenge/history', 'API\ChallengeController@history');
	Route::get('/challenge/quiz', 'API\ChallengeController@quiz');
	Route::post('/challenge/join', 'API\ChallengeController@join')->middleware('log.route:challenge,Join-Challenge,action');
	Route::post('/challenge/quiz', 'API\ChallengeController@answer')->middleware('log.route:challenge,Answer-Quiz,action');
	Route::get('/challenge/achievement', 'API\ChallengeController@achievement');
	Route::get('/challenge/achievement_all', 'API\ChallengeController@achievementAll');

	//Referral
	Route::get('/referral', 'API\ReferralController@getReferralMember');
	Route::get('/referral/success', 'API\ReferralController@getReferralMemberSuccess');
	Route::post('/referral', 'API\ReferralController@AssignMember')->middleware('log.route:referral,Assign-Member,action');

	//Admin Dashboard
	Route::get('/admin', 'API\Dashboard\AuthUser\AdminController@index');

	//Menu Dashboard
	Route::get('/menu', 'API\Dashboard\UserManagement\MenuController@index');
	Route::get('/menu-show', 'API\Dashboard\UserManagement\MenuController@show');
	Route::post('/menu-create', 'API\Dashboard\UserManagement\MenuController@store');
	Route::put('/menu-update/{id}', 'API\Dashboard\UserManagement\MenuController@update');

	//Access Role Dashboard
	Route::get('/settings/roles', 'API\Dashboard\UserManagement\RolesController@index');
	Route::get('/settings/roles-show', 'API\Dashboard\UserManagement\RolesController@show');
	Route::get('/settings/roles-edit', 'API\Dashboard\UserManagement\RolesController@edit');
	Route::post('/settings/roles-create', 'API\Dashboard\UserManagement\RolesController@store');
	Route::put('/settings/roles-update/{id}', 'API\Dashboard\UserManagement\RolesController@update');
	Route::delete('/settings/roles-delete/{id}', 'API\Dashboard\UserManagement\RolesController@destroy');

	//Permission Dashboard
	Route::get('/settings/permissions', 'API\Dashboard\UserManagement\PermissionController@index');
	Route::get('/settings/permissions-show', 'API\Dashboard\UserManagement\PermissionController@show');
	Route::post('/settings/permissions-create', 'API\Dashboard\UserManagement\PermissionController@store');
	Route::put('/settings/permissions-update/{id}', 'API\Dashboard\UserManagement\PermissionController@update');
	Route::delete('/settings/permissions-delete/{id}', 'API\Dashboard\UserManagement\PermissionController@destroy');

	//Admin Dashboard
	Route::get('/admin/show', 'API\Dashboard\AuthUser\AdminController@show');
	Route::put('/admin/admin-update/{id}', 'API\Dashboard\AuthUser\AdminController@update');
	Route::delete('/admin/admin-delete/{id}', 'API\Dashboard\AuthUser\AdminController@destroy');

	//Employee Dashboard
	Route::get('/user-management/employee', 'API\Dashboard\UserManagement\EmployeeController@index');
	Route::get('/user-management/employee-show', 'API\Dashboard\UserManagement\EmployeeController@show');
	Route::get('/user-management/employee-create', 'API\Dashboard\UserManagement\EmployeeController@store');
	Route::put('/user-management/employee-update/{id}', 'API\Dashboard\UserManagement\EmployeeController@update');
	Route::delete('/user-management/employee-delete', 'API\Dashboard\UserManagement\EmployeeController@destroy');

	//Jobs Dashboard
	Route::get('/jobs', 'API\Dashboard\MenuPage\JobsController@index');
	Route::get('/jobs/show/{id}', 'API\Dashboard\MenuPage\JobsController@show');
	Route::post('/jobs/create', 'API\Dashboard\MenuPage\JobsController@store');
	Route::put('/jobs/update/{id}', 'API\Dashboard\MenuPage\JobsController@update');
	Route::delete('/jobs/delete', 'API\Dashboard\MenuPage\JobsController@destroy');

	//Location Dashboard
	Route::get('/location/country', 'API\Dashboard\Location\LocationController@get_country');
	Route::get('/location/province', 'API\Dashboard\Location\LocationController@get_province');
	Route::get('/location/city/{id}', 'API\Dashboard\Location\LocationController@get_city');
	Route::get('/location/district/{id}', 'API\Dashboard\Location\LocationController@get_district');
	Route::get('/location/subdistrict/{id}', 'API\Dashboard\Location\LocationController@get_subDistrict');

	//Company Dashboard
	Route::get('/company', 'API\Dashboard\MenuPage\CompanyController@index');

	//Employee Level Dashboard
	Route::get('/user-management/employee-level', 'API\Dashboard\UserManagement\LevelController@index');
	Route::get('/user-management/employee-level/detail', 'API\Dashboard\UserManagement\LevelController@show');
	Route::post('/user-management/employee-level/create', 'API\Dashboard\UserManagement\LevelController@store');

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



});

