<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
	protected $table = 'xin_system_setting';
	public $primarykey = 'setting_id';
	
	public $timestamps = true;
	protected $fillable = [
		'setting_id',
		'application_name',
		'default_currency',
		'default_currency_id',
		'default_currency_symbol',
		'show_currency',
		'currency_position',
		'notification_position',
		'notification_close_btn',
		'notification_bar',
		'enable_registration',
		'login_with',
		'date_format_xi',
		'employee_manage_own_contact',
		'employee_manage_own_profile',
		'employee_manage_own_qualification',
		'employee_manage_own_work_experience',
		'employee_manage_own_document',
		'employee_manage_own_picture',
		'employee_manage_own_social',
		'employee_manage_own_bank_account',
		'enable_attendance',
		'enable_clock_in_btn',
		'enable_email_notification',
		'payroll_include_day_summary',
		'payroll_include_hour_summary',
		'payroll_include_leave_summary',
		'enable_job_application_candidates',
		'job_logo',
		'payroll_logo',
		'is_payslip_password_generate',
		'payslip_password_format',
		'enable_profile_background',
		'enable_policy_link',
		'enable_layout',
		'job_application_format',
		'project_email',
		'holiday_email',
		'leave_email',
		'payslip_email',
		'award_email',
		'recruitment_email',
		'announcement_email',
		'training_email',
		'task_email',
		'compact_sidebar',
		'fixed_header',
		'fixed_sidebar',
		'boxed_wrapper',
		'layout_static',
		'system_skin',
		'animation_effect',
		'animation_effect_modal',
		'animation_effect_topmenu',
		'footer_text',
		'system_timezone',
		'system_ip_address',
		'system_ip_restriction',
		'google_maps_api_key',
		'module_recruitment',
		'module_travel',
		'module_performance',
		'module_files',
		'module_awards',
		'module_training',
		'module_inquiry',
		'module_language',
		'module_orgchart',
		'module_accounting',
		'module_events',
		'module_goal_tracking',
		'module_assets',
		'module_projects_tasks',
		'module_chat_box',
		'enable_page_rendered',
		'enable_current_year',
		'employee_login_id',
		'enable_auth_background',
		'hr_version',
		'hr_release_date',
		'updated_at'
	];
	protected $hidden = ['updated_at'];
}
