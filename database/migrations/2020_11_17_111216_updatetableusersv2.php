<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetableusersv2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            UPDATE xin_employees SET date_of_birth = NULL WHERE date_of_birth='0000-00-00' OR date_of_birth=NULL;
            ALTER TABLE xin_employees MODIFY first_name varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY last_name varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY user_role_id int(11) DEFAULT 0;
            ALTER TABLE xin_employees MODIFY designation_id int(11) NULL;
            ALTER TABLE xin_employees MODIFY hourly_grade_id int(11) DEFAULT 0;
            ALTER TABLE xin_employees MODIFY monthly_grade_id int(11) DEFAULT 0;
            ALTER TABLE xin_employees MODIFY date_of_joining varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY date_of_leaving varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY marital_status varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY salary varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY wages_type int(11) DEFAULT 0;
            ALTER TABLE xin_employees MODIFY address MEDIUMTEXT NULL;
            ALTER TABLE xin_employees MODIFY profile_picture mediumtext NULL;
            ALTER TABLE xin_employees MODIFY profile_background mediumtext NULL;
            ALTER TABLE xin_employees MODIFY resume mediumtext NULL;
            ALTER TABLE xin_employees MODIFY skype_id varchar(200) NULL;
            ALTER TABLE xin_employees MODIFY contact_no varchar(200) NULL;
            ALTER TABLE xin_employees MODIFY facebook_link mediumtext NULL;
            ALTER TABLE xin_employees MODIFY twitter_link mediumtext NULL;
            ALTER TABLE xin_employees MODIFY blogger_link mediumtext NULL;
            ALTER TABLE xin_employees MODIFY linkdedin_link mediumtext NULL;
            ALTER TABLE xin_employees MODIFY google_plus_link mediumtext NULL;
            ALTER TABLE xin_employees MODIFY instagram_link varchar(255) NULL;
            ALTER TABLE xin_employees MODIFY pinterest_link varchar(255) NULL;
            ALTER TABLE xin_employees MODIFY youtube_link varchar(255) NULL;
            ALTER TABLE xin_employees MODIFY is_active tinyint(1) NULL DEFAULT 1;
            ALTER TABLE xin_employees MODIFY last_login_date varchar(255) NULL;
            ALTER TABLE xin_employees MODIFY last_logout_date varchar(255) NULL;
            ALTER TABLE xin_employees MODIFY last_login_ip varchar(255) NULL;
            ALTER TABLE xin_employees MODIFY is_logged_in int(111) NULL DEFAULT 1;
            ALTER TABLE xin_employees MODIFY online_status int(111) NULL;
            ALTER TABLE xin_employees MODIFY fixed_header varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY compact_sidebar varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY boxed_wrapper varchar(150) NULL;
            ALTER TABLE xin_employees MODIFY country varchar(30) NULL;
            ALTER TABLE xin_employees MODIFY province varchar(50) NULL;
            ALTER TABLE xin_employees MODIFY summary varchar(255) NULL;
            ALTER TABLE xin_employees MODIFY created_at varchar(200) NULL;
            ALTER TABLE xin_employees MODIFY job_title varchar(100) NULL;
            ALTER TABLE xin_employees MODIFY skill_text text NULL;
            ALTER TABLE xin_employees MODIFY last_position varchar(100) NULL;
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
