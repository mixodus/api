<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\RolesModel;

class UserModels extends Model
{
	protected $table = 'xin_employees';
	public $primarykey = 'user_id';
	protected $fillable = [
			'user_id',
			'email',
			'fullname',
			'date_of_birth',
			'gender',
			'contact_no',
			'address',
			'marital_status',
			'country',
			'province',
			'summary',
			'job_title',
			'profile_picture',
			'zip_code',
			'cash',
			'points',
			'skill_text',
			'first_name',
			'last_name',
			'username',
			'password'
	];

	public function work_experience() {
		return $this->hasMany('App\Models\EmployeeWorkExperienceModel', 'employee_id','user_id');
	}
	public function certification() {
		return $this->hasMany('App\Models\EmployeeCertification', 'employee_id','user_id');
	}
	public function jobs_applications() {
		return $this->hasMany('App\Models\JobsApplicationModel', 'user_id','user_id');
	}
	public function awards() {
		return $this->hasMany('App\Models\AwardModel', 'employee_id','user_id');
	}
	public function challenge() {
		return $this->hasMany('App\Models\ChallengeParticipants', 'employee_id','user_id');
	}
	public function qualifications() {
		return $this->hasMany('App\Models\EmployeeQualificationModel', 'employee_id','user_id');
	}
	public function trx_points() {
		return $this->hasMany('App\Models\TransactionsPoints', 'employee_id','user_id');
	}
	public function event() {
		return $this->hasMany('App\Models\EventParticipantModel', 'employee_id','user_id')->with('event');
	}

	//belum ada model ke relasinya
	public function assets() {
		return $this->hasMany('App\Models\AssetsModel', 'employee_id','user_id');
	}
	public function attendance_times() {
		return $this->hasMany('App\Models\AttendanceTimeModel', 'employee_id','user_id');
	}
	public function departments() {
		return $this->hasMany('App\Models\DepartementModel', 'employee_id','user_id');
	}
	public function bank_accounts() {
		return $this->hasMany('App\Models\EmployeeBankAccountModel', 'employee_id','user_id');
	}
	public function contract() {
		return $this->hasOne('App\Models\EmployeeContractModel', 'employee_id','user_id');
	}
	public function documents() {
		return $this->hasMany('App\Models\EmployeeDocumentsModel', 'employee_id','user_id');
	}
	public function resigns() {
		return $this->hasMany('App\Models\EmployeeResignModel', 'employee_id','user_id');
	}
	public function leaves() {
		return $this->hasMany('App\Models\EmployeeLeaveModel', 'employee_id','user_id');
	}
	public function immigration() {
		return $this->hasMany('App\Models\EmployeeImmigrationModel', 'employee_id','user_id');
	}
	public function absents() {
		return $this->hasMany('App\Models\EmployeeLeaveModel', 'employee_id','user_id');
	}
	public function locations() {
		return $this->hasMany('App\Models\EmployeeLocationModel', 'employee_id','user_id');
	}
	public function promotions() {
		return $this->hasMany('App\Models\EmployeePromotionModel', 'employee_id','user_id');
	}
	public function warnings() {
		return $this->hasMany('App\Models\EmployeeWarningModel', 'employee_id','user_id');
	}
	//salary
	public function advance_salaries() {
		return $this->hasMany('App\Models\AdvanceSalaryModel', 'employee_id','user_id');
	}
	public function overtime_salaries() {
		return $this->hasMany('App\Models\OvertimesSalaryModel', 'employee_id','user_id');
	}
	public function loan_deduction_salaries() {
		return $this->hasMany('App\Models\LoanDeductionSalaryModel', 'employee_id','user_id');
	}
	public function allowance_salaries() {
		return $this->hasMany('App\Models\AllowanceSalaryModel', 'employee_id','user_id');
	}
	public function payslip_allowances() {
		return $this->hasMany('App\Models\PayslipAllowanceSalaryModel', 'employee_id','user_id');
	}
	public function payslip_loan() {
		return $this->hasMany('App\Models\PayslipLoanSalaryModel', 'employee_id','user_id');
	}
	public function payslip_overtime() {
		return $this->hasMany('App\Models\PayslipOvertimeSalaryModel', 'employee_id','user_id');
	}
	public function payslip() {
		return $this->hasMany('App\Models\PayslipSalaryModel', 'employee_id','user_id');
	}
	public function role()
    {
        return $this->hasOne(RolesModel::class, 'user_id', 'role_id')
            ->select('access_role');
	}
	
		
}
