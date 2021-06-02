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

//getVersion
	Route::get('/app-version', 'API\AppVersionController@version');


require 'fase2/api.php';
Route::group(['middleware' => ['app.token', 'cors','log.route']], function ($router) {
	//check Version
	Route::get('/home/check-version', 'API\MainController@checkVersion');
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

	//city by country
	Route::get('/country/city', 'API\CityController@getCityByCountry');


	//Jobs
	Route::get('/job_post', 'API\JobsController@index');
	Route::get('/job_post/detail/{id}', 'API\JobsController@detail');

	//Events
	Route::get('/event', 'API\EventController@index');
	Route::get('/event/event_type/{id}', 'API\EventController@EventType');
	Route::get('/event/detail/{id}', 'API\EventController@detail');
	Route::get('/event/countries', 'API\GeneralController@getCountryList');
	Route::post('/event/hackathon/file', 'API\EventController@HackathonUploadFile');

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

	Route::post('user/device_id', 'API\UserController@postDeviceID')->middleware('log.route:user,Post-DeviceID,action');

	//Jobs
	Route::get('/job_post/progress', 'API\JobsController@userJobsApplication');
	Route::get('/job_post/{id}/users', 'API\JobsController@getUsersByJobID');
	Route::post('/job_post/apply', 'API\JobsController@applyJobsApplication')->middleware('log.route:jobs,Apply-Job,action');

	//Events
	Route::post('/event/join', 'API\EventController@joinEvent')->middleware('log.route:join_event,action');
	Route::get('/event/history/{id}', 'API\EventController@HistoryEvent'); //belum ditest dummy data

	//Voting
	Route::get('/votes/candidates', 'API\VoteController@showCandidates');
	//Route::post('/votes/assign-candidate', 'API\VoteController@assignCandidate');
	//Route::post('/votes/update-candidate/{id}', 'API\VoteController@updateCandidate');
	//Route::get('/votes/delete-candidate', 'API\VoteController@deleteCandidate');
	Route::get('/votes', 'API\VoteController@voteResult');
	Route::post('/votes', 'API\VoteController@assignVote');
	Route::get('/votes/reset', 'API\VoteController@resetVote');
	//Route::post('/votes/topic', 'API\VoteController@assignTopic');
	//Route::post('/votes/update-topic/{id}', 'API\VoteController@updateTopic');
	

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
	
	//Notif
	Route::get('/notif', 'API\NotifController@index');
	Route::get('/notif/detail_by/{id}', 'API\NotifController@detail');
	Route::get('/notif/new_notif', 'API\NotifController@newNotif');
	Route::put('/notif', 'API\NotifController@update');

	//OneSignal
	Route::get('/onesignal/pushnotification', 'API\OneSignalController@pushNotification');
	Route::get('/onesignal/getnotification', 'API\OneSignalController@getNotification');
	Route::get('/onesignal/push', 'API\OneSignalController@pushNotificationManual');

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

	//dashboard===========================
	//Admin Dashboard
	Route::get('/admin', 'API\Dashboard\AuthUser\AdminController@index');

	//Menu Dashboard
	Route::get('/menu', 'API\Dashboard\UserManagement\MenuController@index');
	Route::get('/menu-show', 'API\Dashboard\UserManagement\MenuController@show');
	Route::post('/menu-create', 'API\Dashboard\UserManagement\MenuController@store');
	Route::put('/menu-update/{id}', 'API\Dashboard\UserManagement\MenuController@update');

	//Access Role Dashboard
	Route::get('/settings/roles', 'API\Dashboard\UserManagement\RolesController@index');
	Route::get('/settings/roles-show', 'API\Dashboard\UserManagement\RolesController@show')->middleware('log.dashboard:role,Admin,get-data');
	Route::get('/settings/roles-edit', 'API\Dashboard\UserManagement\RolesController@edit')->middleware('log.dashboard:role,Admin,action');
	Route::post('/settings/roles-create', 'API\Dashboard\UserManagement\RolesController@store')->middleware('log.dashboard:role,Admin,action');
	Route::put('/settings/roles-update/{id}', 'API\Dashboard\UserManagement\RolesController@update')->middleware('log.dashboard:role,Admin,action');
	Route::delete('/settings/roles-delete/{id}', 'API\Dashboard\UserManagement\RolesController@destroy')->middleware('log.dashboard:role,Admin,remove-data');

	//Permission Dashboard
	Route::get('/settings/permissions', 'API\Dashboard\UserManagement\PermissionController@index');
	Route::get('/settings/permissions-show', 'API\Dashboard\UserManagement\PermissionController@show');
	Route::post('/settings/permissions-create', 'API\Dashboard\UserManagement\PermissionController@store');
	Route::put('/settings/permissions-update/{id}', 'API\Dashboard\UserManagement\PermissionController@update');
	Route::delete('/settings/permissions-delete/{id}', 'API\Dashboard\UserManagement\PermissionController@destroy');

	//Admin Dashboard
	Route::get('/admin/show', 'API\Dashboard\AuthUser\AdminController@show')->middleware('log.dashboard:news,Admin,get-data');
	Route::put('/admin/admin-update/{id}', 'API\Dashboard\AuthUser\AdminController@update')->middleware('log.dashboard:admin,Admin,action');
	Route::delete('/admin/admin-delete/{id}', 'API\Dashboard\AuthUser\AdminController@destroy')->middleware('log.dashboard:admin,Admin,remove-data');

	//Employee Dashboard
	Route::get('/user-management/employee', 'API\Dashboard\UserManagement\EmployeeController@index');
	Route::get('/user-management/employee-show', 'API\Dashboard\UserManagement\EmployeeController@show')->middleware('log.dashboard:employee,Admin,get-data');
	Route::get('/user-management/employee-create', 'API\Dashboard\UserManagement\EmployeeController@store')->middleware('log.dashboard:employee,Admin,action');
	Route::put('/user-management/employee-update/{id}', 'API\Dashboard\UserManagement\EmployeeController@update')->middleware('log.dashboard:employee,Admin,action');
	Route::delete('/user-management/employee-delete', 'API\Dashboard\UserManagement\EmployeeController@destroy')->middleware('log.dashboard:employee,Admin,remove-data');;

	//Jobs Dashboard
	Route::get('/jobs', 'API\Dashboard\MenuPage\JobsController@index');
	Route::get('/jobs/show/{id}', 'API\Dashboard\MenuPage\JobsController@show')->middleware('log.dashboard:jobs,Admin,get-data');
	Route::post('/jobs/create', 'API\Dashboard\MenuPage\JobsController@store')->middleware('log.dashboard:jobs,Admin,action');
	Route::put('/jobs/update/{id}', 'API\Dashboard\MenuPage\JobsController@update')->middleware('log.dashboard:jobs,Admin,action');
	Route::delete('/jobs/delete', 'API\Dashboard\MenuPage\JobsController@destroy')->middleware('log.dashboard:jobs,Admin,remove-data');

	//Location Dashboard
	Route::get('/location/country', 'API\Dashboard\Location\LocationController@get_country');
	Route::get('/location/province', 'API\Dashboard\Location\LocationController@get_province');
	Route::get('/location/country/{id}', 'API\Dashboard\Location\LocationController@get_countryByID');
	Route::get('/location/province/{id}', 'API\Dashboard\Location\LocationController@get_provinceByID');
	Route::get('/location/city/{id}', 'API\Dashboard\Location\LocationController@get_city');
	Route::get('/location/city/id/{id}', 'API\Dashboard\Location\LocationController@get_cityByID');
	Route::get('/location/district/{id}', 'API\Dashboard\Location\LocationController@get_district');
	Route::get('/location/subdistrict/{id}', 'API\Dashboard\Location\LocationController@get_subDistrict');

	//Company Dashboard
	Route::get('/company', 'API\Dashboard\MenuPage\CompanyController@index');
	Route::get('/company/{id}', 'API\Dashboard\MenuPage\CompanyController@getCompanyByID');

	//Employee Level Dashboard
	Route::get('/user-management/employee-level', 'API\Dashboard\UserManagement\LevelController@index');
	Route::get('/user-management/employee-level/detail', 'API\Dashboard\UserManagement\LevelController@show')->middleware('log.dashboard:level,Admin,get-data');;
	Route::post('/user-management/employee-level/create', 'API\Dashboard\UserManagement\LevelController@store')->middleware('log.dashboard:level,Admin,action');

	//News Dashboard
	Route::get('/news-dashboard', 'API\Dashboard\MenuPage\NewsController@index');
	Route::get('/news-show', 'API\Dashboard\MenuPage\NewsController@show')->middleware('log.dashboard:news,Admin,get-data');
	Route::post('/news-create', 'API\Dashboard\MenuPage\NewsController@store')->middleware('log.dashboard:news,Admin,action');
	Route::post('/news-update', 'API\Dashboard\MenuPage\NewsController@update')->middleware('log.dashboard:news,Admin,action');
	Route::delete('/news-delete', 'API\Dashboard\MenuPage\NewsController@destroy')->middleware('log.dashboard:news,Admin,remove-data');
	Route::get('/news-type', 'API\Dashboard\MenuPage\NewsController@getNewstype');

	//Comment Dashboard
	Route::post('/news-comment', 'API\Dashboard\MenuPage\NewsController@addComment')->middleware('log.dashboard:comment,Admin,action');;
	Route::post('/news-reply-comment', 'API\Dashboard\MenuPage\NewsController@addReplyComment')->middleware('log.dashboard:comment,Admin,action');
	Route::delete('/news-comment-delete', 'API\Dashboard\MenuPage\NewsController@deleteComment')->middleware('log.dashboard:comment,Admin,remove-data');
	Route::delete('/news-repcomment-delete', 'API\Dashboard\MenuPage\NewsController@deleteReplyComment')->middleware('log.dashboard:comment,Admin,remove-data');

	//Log Dashboard
	Route::get('/log/dashboard-log', 'API\Dashboard\MenuPage\LogController@dashboardLog');
	Route::get('/log/dashboard-log/show', 'API\Dashboard\MenuPage\LogController@dashboardLogShow');
	Route::get('/log/mobile-log', 'API\Dashboard\MenuPage\LogController@mobileLog');
	Route::get('/log/mobile-log/show', 'API\Dashboard\MenuPage\LogController@mobileLogShow');

	//Event Dashboard
	Route::get('/event-list', 'API\Dashboard\MenuPage\EventController@index');
	Route::get('/event-show', 'API\Dashboard\MenuPage\EventController@show')->middleware('log.dashboard:comment,Admin,get-data');
	Route::post('/event-create', 'API\Dashboard\MenuPage\EventController@store')->middleware('log.dashboard:comment,Admin,action');
	Route::post('/event-update', 'API\Dashboard\MenuPage\EventController@update')->middleware('log.dashboard:comment,Admin,action');
	Route::delete('/event-delete', 'API\Dashboard\MenuPage\EventController@destroy')->middleware('log.dashboard:comment,Admin,remove-data');
	Route::get('/event-type', 'API\Dashboard\MenuPage\EventController@eventType');
	Route::put('/event/participant-status/update', 'API\Dashboard\MenuPage\EventController@registerStatus')->middleware('log.dashboard:comment,Admin,action');

	//Referral Dashboard
	Route::get('/dashboard/referral/allMobile', 'API\Dashboard\ReferralController@getAllMobileReferralMember');
	Route::get('/dashboard/referral/allWeb', 'API\Dashboard\ReferralController@getAllWebReferralMember');
	Route::get('/dashboard/referral', 'API\Dashboard\ReferralController@getReferralMember');
	Route::get('/dashboard/referral/success', 'API\Dashboard\ReferralController@getReferralMemberSuccess');
	Route::get('/dashboard/referral/update/{id}', 'API\Dashboard\ReferralController@getReferralByID');
	Route::get('/dashboard/referral/update/{id}/status', 'API\Dashboard\ReferralController@getReferralStatusByID');
	Route::post('/dashboard/referral', 'API\Dashboard\ReferralController@AssignMember');
	Route::post('/dashboard/referral/update/{id}', 'API\Dashboard\ReferralController@UpdateReferralMember');
	Route::post('/dashboard/referral/update/{id}/status', 'API\Dashboard\ReferralController@UpdateReferralStatus');

	//voting dashboard
	Route::get('/votes/candidate', 'API\Dashboard\VoteController@showCandidates');
	Route::get('/votes/candidate/{id}', 'API\Dashboard\VoteController@showCandidateByID');
	Route::post('/votes/assign-candidate', 'API\Dashboard\VoteController@assignCandidate');
	Route::post('/votes/update-candidate/{id}', 'API\Dashboard\VoteController@updateCandidate');
	Route::get('/votes/delete-candidate/{id}', 'API\Dashboard\VoteController@deleteCandidate');
	Route::get('/votes/topic', 'API\Dashboard\VoteController@topics');
	Route::get('/votes/topic/{id}', 'API\Dashboard\VoteController@topicByID');
	Route::post('/votes/create-topic', 'API\Dashboard\VoteController@assignTopic');
	Route::get('/votes/delete-topic/{id}', 'API\Dashboard\VoteController@deleteTopic');
	Route::post('/votes/update-topic/{id}', 'API\Dashboard\VoteController@updateTopic');

	Route::group(['prefix' => 'dashboard'], function () {
		Route::get('/hacktown', 'API\Dashboard\MenuPage\EventController@hacktown');
		Route::post('/hacktown', 'API\Dashboard\MenuPage\EventController@hacktownCreateEdit');
		Route::get('/hacktown/participant', 'API\Dashboard\MenuPage\EventController@hacktownParticipant');
		Route::post('/hacktown/participant/update-status', 'API\Dashboard\MenuPage\EventController@hacktownParticipantUpdate');
		//api send mail
		Route::post('/hacktown/participant/update-statusv2', 'API\Dashboard\MenuPage\EventController@hacktownParticipantUpdateV2');
	});
	
});

