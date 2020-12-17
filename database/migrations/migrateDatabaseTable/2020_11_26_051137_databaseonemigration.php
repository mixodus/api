<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Databaseonemigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TABLE xin_activity_point (
            activity_point_id int(11) NOT NULL,
            activity_point_name varchar(200) NOT NULL,
            activity_point_code varchar(200) NOT NULL,
            activity_point_point int(11) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          );
          
          CREATE TABLE xin_advance_salaries (
            advance_salary_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            month_year varchar(255) NOT NULL,
            advance_amount varchar(255) NOT NULL,
            one_time_deduct varchar(50) NOT NULL,
            monthly_installment varchar(255) NOT NULL,
            total_paid varchar(255) NOT NULL,
            reason text NOT NULL,
            status int(11) DEFAULT NULL,
            is_deducted_from_salary int(11) DEFAULT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_announcements (
            announcement_id int(11) NOT NULL,
            title varchar(200) NOT NULL,
            start_date varchar(200) NOT NULL,
            end_date varchar(200) NOT NULL,
            company_id int(111) NOT NULL,
            department_id int(111) NOT NULL,
            published_by int(111) NOT NULL,
            summary mediumtext NOT NULL,
            description mediumtext NOT NULL,
            is_active tinyint(1) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_assets (
            assets_id int(111) NOT NULL,
            assets_category_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            company_asset_code varchar(255) NOT NULL,
            name varchar(255) NOT NULL,
            purchase_date varchar(255) NOT NULL,
            invoice_number varchar(255) NOT NULL,
            manufacturer varchar(255) NOT NULL,
            serial_number varchar(255) NOT NULL,
            warranty_end_date varchar(255) NOT NULL,
            asset_note text NOT NULL,
            asset_image varchar(255) NOT NULL,
            is_working int(11) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_assets_categories (
            assets_category_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            category_name varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_attendance_time (
            time_attendance_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            attendance_date varchar(255) NOT NULL,
            clock_in varchar(255) NOT NULL,
            clock_in_ip_address varchar(255) NOT NULL,
            clock_out varchar(255) NOT NULL,
            clock_out_ip_address varchar(255) NOT NULL,
            clock_in_out varchar(255) NOT NULL,
            clock_in_latitude varchar(150) NOT NULL,
            clock_in_longitude varchar(150) NOT NULL,
            clock_out_latitude varchar(150) NOT NULL,
            clock_out_longitude varchar(150) NOT NULL,
            time_late varchar(255) NOT NULL,
            early_leaving varchar(255) NOT NULL,
            overtime varchar(255) NOT NULL,
            total_work varchar(255) NOT NULL,
            total_rest varchar(255) NOT NULL,
            attendance_status varchar(100) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_awards (
            award_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(200) NOT NULL,
            award_type_id int(200) NOT NULL,
            gift_item varchar(200) NOT NULL,
            cash_price varchar(200) NOT NULL,
            award_photo varchar(255) NOT NULL,
            award_month_year varchar(200) NOT NULL,
            award_information mediumtext NOT NULL,
            description mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_award_type (
            award_type_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            award_type varchar(200) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_challenge (
            challenge_id int(11) NOT NULL,
            challenge_title varchar(200) NOT NULL,
            challenge_type_id int(11) NOT NULL,
            challenge_point int(11) NOT NULL,
            challenge_point_every_task int(11) NOT NULL,
            challenge_expired_date date NOT NULL,
            challenge_description text NOT NULL,
            challenge_long_desciption text NOT NULL,
            challenge_photo varchar(255) NOT NULL,
            challenge_total_task int(11) NOT NULL,
            challenge_icon_trophy varchar(255) NOT NULL,
            challenge_title_trophy varchar(100) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          );
          
          CREATE TABLE xin_challenge_participant (
            id int(11) NOT NULL,
            challenge_id int(11) NOT NULL,
            list_quiz_id varchar(100) NOT NULL,
            list_quiz_answer varchar(100) NOT NULL,
            employee_id int(11) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL,
            total_point int(11) NOT NULL,
            total_current_point int(11) NOT NULL DEFAULT 0,
            total_current_task int(11) NOT NULL,
            total_task int(11) NOT NULL,
            is_achieve tinyint(1) NOT NULL DEFAULT 0
          );
          
          CREATE TABLE xin_challenge_quiz (
            id int(11) NOT NULL,
            challenge_id int(11) NOT NULL,
            question varchar(255) NOT NULL,
            a varchar(255) NOT NULL,
            b varchar(255) NOT NULL,
            c varchar(255) NOT NULL,
            answer char(2) NOT NULL,
            point int(11) NOT NULL
          );
          
          CREATE TABLE xin_challenge_type (
            challenge_type_id int(11) NOT NULL,
            challenge_type_name varchar(200) NOT NULL,
            challenge_type_notes varchar(200) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          );
          
          CREATE TABLE xin_chat_messages (
            message_id int(11) UNSIGNED NOT NULL,
            from_id varchar(40) NOT NULL DEFAULT '',
            to_id varchar(50) NOT NULL DEFAULT '',
            message_frm varchar(255) NOT NULL,
            is_read int(11) NOT NULL DEFAULT 0,
            message_content longtext NOT NULL,
            message_date varchar(255) DEFAULT NULL,
            recd tinyint(1) NOT NULL DEFAULT 0,
            message_type varchar(255) NOT NULL DEFAULT ''
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_clients (
            client_id int(111) NOT NULL,
            name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            client_username varchar(255) NOT NULL,
            client_password varchar(255) NOT NULL,
            client_profile varchar(255) NOT NULL,
            contact_number varchar(255) NOT NULL,
            company_name varchar(255) NOT NULL,
            gender varchar(200) NOT NULL,
            website_url varchar(255) NOT NULL,
            address_1 mediumtext NOT NULL,
            address_2 mediumtext NOT NULL,
            city varchar(255) NOT NULL,
            state varchar(255) NOT NULL,
            zipcode varchar(255) NOT NULL,
            country int(111) NOT NULL,
            is_active int(11) NOT NULL,
            last_logout_date varchar(255) NOT NULL,
            last_login_date varchar(255) NOT NULL,
            last_login_ip varchar(255) NOT NULL,
            is_logged_in int(11) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_companies (
            company_id int(111) NOT NULL,
            type_id int(111) NOT NULL,
            name varchar(255) NOT NULL,
            trading_name varchar(255) NOT NULL,
            username varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            registration_no varchar(255) NOT NULL,
            government_tax varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            logo varchar(255) NOT NULL,
            contact_number varchar(255) NOT NULL,
            website_url varchar(255) NOT NULL,
            address_1 mediumtext NOT NULL,
            address_2 mediumtext NOT NULL,
            city varchar(255) NOT NULL,
            state varchar(255) NOT NULL,
            zipcode varchar(255) NOT NULL,
            country int(111) NOT NULL,
            is_active int(11) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_company_documents (
            document_id int(11) NOT NULL,
            license_name varchar(255) NOT NULL,
            company_id int(11) NOT NULL,
            expiry_date varchar(255) NOT NULL,
            license_number varchar(255) NOT NULL,
            license_notification int(11) NOT NULL,
            added_by int(11) NOT NULL,
            document varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          );
          
          CREATE TABLE xin_company_info (
            company_info_id int(111) NOT NULL,
            logo varchar(255) NOT NULL,
            logo_second varchar(255) NOT NULL,
            sign_in_logo varchar(255) NOT NULL,
            favicon varchar(255) NOT NULL,
            website_url mediumtext NOT NULL,
            starting_year varchar(255) NOT NULL,
            company_name varchar(255) NOT NULL,
            company_email varchar(255) NOT NULL,
            company_contact varchar(255) NOT NULL,
            contact_person varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(255) NOT NULL,
            address_1 mediumtext NOT NULL,
            address_2 mediumtext NOT NULL,
            city varchar(255) NOT NULL,
            state varchar(255) NOT NULL,
            zipcode varchar(255) NOT NULL,
            country int(111) NOT NULL,
            updated_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_company_policy (
            policy_id int(111) NOT NULL,
            company_id int(111) NOT NULL,
            title varchar(255) NOT NULL,
            description longtext NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_company_type (
            type_id int(111) NOT NULL,
            name varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_contract_type (
            contract_type_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            name varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_countries (
            country_id int(11) NOT NULL,
            country_code varchar(255) NOT NULL,
            country_name varchar(255) NOT NULL,
            country_flag varchar(255) NOT NULL
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_currencies (
            currency_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            name varchar(255) NOT NULL,
            code varchar(255) NOT NULL,
            symbol varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_currency_converter (
            currency_converter_id int(11) NOT NULL,
            usd_currency varchar(11) NOT NULL DEFAULT '1',
            to_currency_title varchar(200) NOT NULL,
            to_currency_rate varchar(200) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_database_backup (
            backup_id int(111) NOT NULL,
            backup_file varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_departments (
            department_id int(11) NOT NULL,
            department_name varchar(200) NOT NULL,
            company_id int(11) NOT NULL,
            location_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(200) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_designations (
            designation_id int(11) NOT NULL,
            top_designation_id int(11) NOT NULL DEFAULT 0,
            department_id int(200) NOT NULL,
            sub_department_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            designation_name varchar(200) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(200) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_document_type (
            document_type_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            document_type varchar(255) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_email_template (
            template_id int(111) NOT NULL,
            template_code varchar(255) NOT NULL,
            name varchar(255) NOT NULL,
            subject varchar(255) NOT NULL,
            message longtext NOT NULL,
            status tinyint(2) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employees (
            user_id int(11) NOT NULL,
            employee_id varchar(200) DEFAULT NULL,
            office_shift_id int(111) DEFAULT NULL,
            fullname varchar(255) NOT NULL,
            first_name varchar(200) NOT NULL,
            last_name varchar(200) NOT NULL,
            username varchar(200) NOT NULL,
            email varchar(200) NOT NULL,
            password varchar(200) NOT NULL,
            date_of_birth date DEFAULT NULL,
            gender varchar(200) DEFAULT NULL,
            e_status int(11) DEFAULT NULL,
            user_role_id int(100) NOT NULL,
            department_id int(100) DEFAULT NULL,
            sub_department_id int(11) DEFAULT NULL,
            designation_id int(100) NOT NULL,
            company_id int(111) DEFAULT NULL,
            salary_template varchar(255) DEFAULT NULL,
            hourly_grade_id int(111) NOT NULL,
            monthly_grade_id int(111) NOT NULL,
            date_of_joining varchar(200) NOT NULL,
            date_of_leaving varchar(255) NOT NULL,
            marital_status varchar(255) NOT NULL,
            salary varchar(200) NOT NULL,
            wages_type int(11) NOT NULL,
            basic_salary varchar(200) NOT NULL DEFAULT '0',
            daily_wages varchar(200) NOT NULL DEFAULT '0',
            salary_ssempee varchar(200) NOT NULL DEFAULT '0',
            salary_ssempeer varchar(200) DEFAULT '0',
            salary_income_tax varchar(200) NOT NULL DEFAULT '0',
            salary_overtime varchar(200) NOT NULL DEFAULT '0',
            salary_commission varchar(200) NOT NULL DEFAULT '0',
            salary_claims varchar(200) NOT NULL DEFAULT '0',
            salary_paid_leave varchar(200) NOT NULL DEFAULT '0',
            salary_director_fees varchar(200) NOT NULL DEFAULT '0',
            salary_bonus varchar(200) NOT NULL DEFAULT '0',
            salary_advance_paid varchar(200) NOT NULL DEFAULT '0',
            address mediumtext NOT NULL,
            profile_picture mediumtext NOT NULL,
            profile_background mediumtext NOT NULL,
            resume mediumtext NOT NULL,
            skype_id varchar(200) NOT NULL,
            contact_no varchar(200) NOT NULL,
            facebook_link mediumtext NOT NULL,
            twitter_link mediumtext NOT NULL,
            blogger_link mediumtext NOT NULL,
            linkdedin_link mediumtext NOT NULL,
            google_plus_link mediumtext NOT NULL,
            instagram_link varchar(255) NOT NULL,
            pinterest_link varchar(255) NOT NULL,
            youtube_link varchar(255) NOT NULL,
            is_active tinyint(1) NOT NULL,
            last_login_date varchar(255) NOT NULL,
            last_logout_date varchar(255) NOT NULL,
            last_login_ip varchar(255) NOT NULL,
            is_logged_in int(111) NOT NULL,
            online_status int(111) NOT NULL,
            fixed_header varchar(150) NOT NULL,
            compact_sidebar varchar(150) NOT NULL,
            boxed_wrapper varchar(150) NOT NULL,
            leave_categories varchar(255) NOT NULL DEFAULT '0',
            country varchar(30) NOT NULL,
            province varchar(50) NOT NULL,
            summary varchar(255) NOT NULL,
            zip_code varchar(10) DEFAULT NULL,
            created_at varchar(200) NOT NULL,
            job_title varchar(100) NOT NULL,
            city_of_birth varchar(30) DEFAULT NULL,
            religion varchar(20) DEFAULT NULL,
            current_salary double DEFAULT NULL,
            expected_salary double DEFAULT NULL,
            ctc_salary double DEFAULT NULL,
            programming_skill varchar(255) DEFAULT NULL,
            database_skill varchar(255) DEFAULT NULL,
            other_skill varchar(255) DEFAULT NULL,
            skill_text text NOT NULL,
            last_position varchar(100) NOT NULL,
            preferred_specialization varchar(100) DEFAULT NULL,
            start_work_year varchar(4) DEFAULT NULL,
            currency_salary varchar(4) DEFAULT NULL,
            points int(11) NOT NULL DEFAULT 0,
            cash int(11) NOT NULL DEFAULT 0
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_bankaccount (
            bankaccount_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            is_primary int(11) NOT NULL,
            account_title varchar(255) NOT NULL,
            account_number varchar(255) NOT NULL,
            bank_name varchar(255) NOT NULL,
            bank_code varchar(255) NOT NULL,
            bank_branch mediumtext NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_complaints (
            complaint_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            complaint_from int(111) NOT NULL,
            title varchar(255) NOT NULL,
            complaint_date varchar(255) NOT NULL,
            complaint_against mediumtext NOT NULL,
            description mediumtext NOT NULL,
            status tinyint(2) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_contacts (
            contact_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            relation varchar(255) NOT NULL,
            is_primary int(111) NOT NULL,
            is_dependent int(111) NOT NULL,
            contact_name varchar(255) NOT NULL,
            work_phone varchar(255) NOT NULL,
            work_phone_extension varchar(255) NOT NULL,
            mobile_phone varchar(255) NOT NULL,
            home_phone varchar(255) NOT NULL,
            work_email varchar(255) NOT NULL,
            personal_email varchar(255) NOT NULL,
            address_1 mediumtext NOT NULL,
            address_2 mediumtext NOT NULL,
            city varchar(255) NOT NULL,
            state varchar(255) NOT NULL,
            zipcode varchar(255) NOT NULL,
            country varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_contract (
            contract_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            contract_type_id int(111) NOT NULL,
            from_date varchar(255) NOT NULL,
            designation_id int(111) NOT NULL,
            title varchar(255) NOT NULL,
            to_date varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_documents (
            document_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            document_type_id int(111) NOT NULL,
            date_of_expiry varchar(255) NOT NULL,
            title varchar(255) NOT NULL,
            notification_email varchar(255) NOT NULL,
            is_alert tinyint(1) NOT NULL,
            description mediumtext NOT NULL,
            document_file varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_exit (
            exit_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            exit_date varchar(255) NOT NULL,
            exit_type_id int(111) NOT NULL,
            exit_interview int(111) NOT NULL,
            is_inactivate_account int(111) NOT NULL,
            reason mediumtext NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_exit_type (
            exit_type_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            type varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_immigration (
            immigration_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            document_type_id int(111) NOT NULL,
            document_number varchar(255) NOT NULL,
            document_file varchar(255) NOT NULL,
            issue_date varchar(255) NOT NULL,
            expiry_date varchar(255) NOT NULL,
            country_id varchar(255) NOT NULL,
            eligible_review_date varchar(255) NOT NULL,
            comments mediumtext NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_leave (
            leave_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            contract_id int(111) NOT NULL,
            casual_leave varchar(255) NOT NULL,
            medical_leave varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_location (
            office_location_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            location_id int(111) NOT NULL,
            from_date varchar(255) NOT NULL,
            to_date varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_project_experiences (
            id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            work_experience_id int(111) NOT NULL,
            project_name varchar(255) NOT NULL,
            position varchar(255) DEFAULT NULL,
            jobdesc varchar(255) DEFAULT NULL,
            start_period date DEFAULT NULL,
            end_period date DEFAULT NULL,
            created_at varchar(255) NOT NULL,
            tools varchar(255) DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_promotions (
            promotion_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            title varchar(255) NOT NULL,
            promotion_date varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_qualification (
            qualification_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            name varchar(255) NOT NULL,
            education_level_id int(111) NOT NULL,
            language_id int(111) DEFAULT NULL,
            skill_id mediumtext DEFAULT NULL,
            description mediumtext DEFAULT NULL,
            created_at varchar(255) NOT NULL,
            gpa varchar(4) DEFAULT NULL,
            field_of_study varchar(30) DEFAULT NULL,
            start_period date DEFAULT NULL,
            end_period date DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_resignations (
            resignation_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            notice_date varchar(255) NOT NULL,
            resignation_date varchar(255) NOT NULL,
            reason mediumtext NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_shift (
            emp_shift_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            shift_id int(111) NOT NULL,
            from_date varchar(255) NOT NULL,
            to_date varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_terminations (
            termination_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            terminated_by int(111) NOT NULL,
            termination_type_id int(111) NOT NULL,
            termination_date varchar(255) NOT NULL,
            notice_date varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            status tinyint(2) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_transfer (
            transfer_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            transfer_date varchar(255) NOT NULL,
            transfer_department int(111) NOT NULL,
            transfer_location int(111) NOT NULL,
            description mediumtext NOT NULL,
            status tinyint(2) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_travels (
            travel_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            start_date varchar(255) NOT NULL,
            end_date varchar(255) NOT NULL,
            visit_purpose varchar(255) NOT NULL,
            visit_place varchar(255) NOT NULL,
            travel_mode int(111) DEFAULT NULL,
            arrangement_type int(111) DEFAULT NULL,
            expected_budget varchar(255) NOT NULL,
            actual_budget varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            status tinyint(2) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_warnings (
            warning_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            warning_to int(111) NOT NULL,
            warning_by int(111) NOT NULL,
            warning_date varchar(255) NOT NULL,
            warning_type_id int(111) NOT NULL,
            subject varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            status tinyint(2) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_employee_work_experience (
            work_experience_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            company_name varchar(255) NOT NULL,
            start_period date DEFAULT NULL,
            end_period date DEFAULT NULL,
            post varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_events (
            event_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            event_title varchar(255) NOT NULL,
            event_date varchar(255) NOT NULL,
            event_time varchar(255) NOT NULL,
            event_note mediumtext NOT NULL,
            event_charge double DEFAULT NULL,
            event_banner varchar(100) NOT NULL,
            event_longitude varchar(50) DEFAULT NULL,
            event_latitude varchar(50) DEFAULT NULL,
            event_place varchar(255) DEFAULT NULL,
            event_speaker varchar(100) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_events_participant (
            id int(11) NOT NULL,
            event_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            status varchar(20) DEFAULT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_expenses (
            expense_id int(11) NOT NULL,
            employee_id int(200) NOT NULL,
            company_id int(11) NOT NULL,
            expense_type_id int(200) NOT NULL,
            billcopy_file mediumtext NOT NULL,
            amount varchar(200) NOT NULL,
            purchase_date varchar(200) NOT NULL,
            remarks mediumtext NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 0,
            status_remarks mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_expense_type (
            expense_type_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            name varchar(200) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_file_manager (
            file_id int(111) NOT NULL,
            user_id int(111) NOT NULL,
            department_id int(111) NOT NULL,
            file_name varchar(255) NOT NULL,
            file_size varchar(255) NOT NULL,
            file_extension varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_file_manager_settings (
            setting_id int(111) NOT NULL,
            allowed_extensions mediumtext NOT NULL,
            maximum_file_size varchar(255) NOT NULL,
            is_enable_all_files varchar(255) NOT NULL,
            updated_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_finance_bankcash (
            bankcash_id int(111) NOT NULL,
            account_name varchar(255) NOT NULL,
            account_balance varchar(255) NOT NULL,
            account_number varchar(255) NOT NULL,
            branch_code varchar(255) NOT NULL,
            bank_branch text NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_finance_deposit (
            deposit_id int(111) NOT NULL,
            account_type_id int(111) NOT NULL,
            amount varchar(255) NOT NULL,
            deposit_date varchar(255) NOT NULL,
            category_id int(111) NOT NULL,
            payer_id int(111) NOT NULL,
            payment_method int(111) NOT NULL,
            deposit_reference varchar(255) NOT NULL,
            deposit_file varchar(255) NOT NULL,
            description text NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_finance_expense (
            expense_id int(111) NOT NULL,
            account_type_id int(111) NOT NULL,
            amount varchar(255) NOT NULL,
            expense_date varchar(255) NOT NULL,
            category_id int(111) NOT NULL,
            payee_id int(111) NOT NULL,
            payment_method int(111) NOT NULL,
            expense_reference varchar(255) NOT NULL,
            expense_file varchar(255) NOT NULL,
            description text NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_finance_payees (
            payee_id int(11) NOT NULL,
            payee_name varchar(255) NOT NULL,
            contact_number varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_finance_payers (
            payer_id int(11) NOT NULL,
            payer_name varchar(255) NOT NULL,
            contact_number varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_finance_transactions (
            transaction_id int(111) NOT NULL,
            account_type_id int(111) NOT NULL,
            deposit_id int(111) NOT NULL,
            expense_id int(111) NOT NULL,
            transfer_id int(111) NOT NULL,
            transaction_type varchar(255) NOT NULL,
            total_amount varchar(255) NOT NULL,
            transaction_debit varchar(255) NOT NULL,
            transaction_credit varchar(255) NOT NULL,
            transaction_date varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_finance_transfer (
            transfer_id int(111) NOT NULL,
            from_account_id int(111) NOT NULL,
            to_account_id int(111) NOT NULL,
            transfer_date varchar(255) NOT NULL,
            transfer_amount varchar(255) NOT NULL,
            payment_method varchar(111) NOT NULL,
            transfer_reference varchar(255) NOT NULL,
            description text NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_goal_tracking (
            tracking_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            tracking_type_id int(200) NOT NULL,
            start_date varchar(200) NOT NULL,
            end_date varchar(200) NOT NULL,
            subject varchar(255) NOT NULL,
            target_achiement varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            goal_progress varchar(200) NOT NULL,
            goal_status int(11) NOT NULL DEFAULT 0,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_goal_tracking_type (
            tracking_type_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            type_name varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_holidays (
            holiday_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            event_name varchar(200) NOT NULL,
            description mediumtext NOT NULL,
            start_date varchar(200) NOT NULL,
            end_date varchar(200) NOT NULL,
            is_publish tinyint(1) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_hourly_templates (
            hourly_rate_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            hourly_grade varchar(255) NOT NULL,
            hourly_rate varchar(255) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_hrsale_invoices (
            invoice_id int(111) NOT NULL,
            invoice_number varchar(255) NOT NULL,
            project_id int(111) NOT NULL,
            invoice_date varchar(255) NOT NULL,
            invoice_due_date varchar(255) NOT NULL,
            sub_total_amount varchar(255) NOT NULL,
            discount_type varchar(11) NOT NULL,
            discount_figure varchar(255) NOT NULL,
            total_tax varchar(255) NOT NULL,
            total_discount varchar(255) NOT NULL,
            grand_total varchar(255) NOT NULL,
            invoice_note mediumtext NOT NULL,
            status tinyint(1) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_hrsale_invoices_items (
            invoice_item_id int(111) NOT NULL,
            invoice_id int(111) NOT NULL,
            project_id int(111) NOT NULL,
            item_name varchar(255) NOT NULL,
            item_tax_type varchar(255) NOT NULL,
            item_tax_rate varchar(255) NOT NULL,
            item_qty varchar(255) NOT NULL,
            item_unit_price varchar(255) NOT NULL,
            item_sub_total varchar(255) NOT NULL,
            sub_total_amount varchar(255) NOT NULL,
            total_tax varchar(255) NOT NULL,
            discount_type int(11) NOT NULL,
            discount_figure varchar(255) NOT NULL,
            total_discount varchar(255) NOT NULL,
            grand_total varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_income_categories (
            category_id int(11) NOT NULL,
            name varchar(200) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_jobs (
            job_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            job_title varchar(255) NOT NULL,
            designation_id int(111) NOT NULL,
            job_type int(225) NOT NULL,
            is_featured int(11) NOT NULL,
            job_vacancy int(100) NOT NULL,
            gender varchar(100) NOT NULL,
            minimum_experience varchar(255) NOT NULL,
            date_of_closing varchar(200) NOT NULL,
            short_description mediumtext NOT NULL,
            long_description mediumtext NOT NULL,
            status int(11) NOT NULL,
            province varchar(20) DEFAULT NULL,
            country varchar(20) DEFAULT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_job_applications (
            application_id int(111) NOT NULL,
            job_id int(111) NOT NULL,
            user_id int(111) NOT NULL,
            email varchar(50) NOT NULL,
            contact_no varchar(20) NOT NULL,
            message mediumtext NOT NULL,
            job_resume mediumtext NOT NULL,
            application_status varchar(200) NOT NULL,
            application_remarks mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_job_categories (
            category_id int(11) NOT NULL,
            category_name varchar(255) NOT NULL,
            category_url varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_job_interviews (
            job_interview_id int(111) NOT NULL,
            job_id int(111) NOT NULL,
            interviewers_id varchar(255) NOT NULL,
            interview_place varchar(255) NOT NULL,
            interview_date varchar(255) NOT NULL,
            interview_time varchar(255) NOT NULL,
            interviewees_id varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_job_type (
            job_type_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            type varchar(255) NOT NULL,
            type_url varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_languages (
            language_id int(111) NOT NULL,
            language_name varchar(255) NOT NULL,
            language_code varchar(255) NOT NULL,
            language_flag varchar(255) NOT NULL,
            is_active int(11) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_leave_applications (
            leave_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(222) NOT NULL,
            leave_type_id int(222) NOT NULL,
            from_date varchar(200) NOT NULL,
            to_date varchar(200) NOT NULL,
            applied_on varchar(200) NOT NULL,
            reason mediumtext NOT NULL,
            remarks mediumtext NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_leave_type (
            leave_type_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            type_name varchar(200) NOT NULL,
            days_per_year varchar(200) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_level (
            level_id int(11) NOT NULL,
            level_name varchar(200) NOT NULL,
            level_code varchar(200) NOT NULL,
            level_icon varchar(200) NOT NULL,
            level_min_point int(11) NOT NULL,
            level_max_point int(11) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          );
          
          CREATE TABLE xin_make_payment (
            make_payment_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            department_id int(111) NOT NULL,
            company_id int(111) NOT NULL,
            location_id int(111) NOT NULL,
            designation_id int(111) NOT NULL,
            payment_date varchar(200) NOT NULL,
            basic_salary varchar(255) NOT NULL,
            payment_amount varchar(255) NOT NULL,
            gross_salary varchar(255) NOT NULL,
            total_allowances varchar(255) NOT NULL,
            total_deductions varchar(255) NOT NULL,
            net_salary varchar(255) NOT NULL,
            house_rent_allowance varchar(255) NOT NULL,
            medical_allowance varchar(255) NOT NULL,
            travelling_allowance varchar(255) NOT NULL,
            dearness_allowance varchar(255) NOT NULL,
            provident_fund varchar(255) NOT NULL,
            tax_deduction varchar(255) NOT NULL,
            security_deposit varchar(255) NOT NULL,
            overtime_rate varchar(255) NOT NULL,
            is_advance_salary_deduct int(11) NOT NULL,
            advance_salary_amount varchar(255) NOT NULL,
            is_payment tinyint(1) NOT NULL,
            payment_method int(11) NOT NULL,
            hourly_rate varchar(255) NOT NULL,
            total_hours_work varchar(255) NOT NULL,
            comments mediumtext NOT NULL,
            status tinyint(1) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_meetings (
            meeting_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            meeting_title varchar(255) NOT NULL,
            meeting_date varchar(255) NOT NULL,
            meeting_time varchar(255) NOT NULL,
            meeting_note mediumtext NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_news (
            news_id int(11) NOT NULL,
            news_title varchar(200) NOT NULL,
            news_type_id int(11) NOT NULL,
            news_url varchar(255) NOT NULL,
            news_photo varchar(255) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          );
          
          CREATE TABLE xin_news_type (
            news_type_id int(11) NOT NULL,
            news_type_name varchar(200) NOT NULL,
            news_type_description varchar(350) DEFAULT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          );
          
          CREATE TABLE xin_office_location (
            location_id int(11) NOT NULL,
            company_id int(111) NOT NULL,
            location_head int(111) NOT NULL,
            location_manager int(111) NOT NULL,
            location_name varchar(200) NOT NULL,
            email varchar(255) NOT NULL,
            phone varchar(255) NOT NULL,
            fax varchar(255) NOT NULL,
            address_1 mediumtext NOT NULL,
            address_2 mediumtext NOT NULL,
            city varchar(255) NOT NULL,
            state varchar(255) NOT NULL,
            zipcode varchar(255) NOT NULL,
            country int(111) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(200) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_office_shift (
            office_shift_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            shift_name varchar(255) NOT NULL,
            default_shift int(111) NOT NULL,
            monday_in_time varchar(222) NOT NULL,
            monday_out_time varchar(222) NOT NULL,
            tuesday_in_time varchar(222) NOT NULL,
            tuesday_out_time varchar(222) NOT NULL,
            wednesday_in_time varchar(222) NOT NULL,
            wednesday_out_time varchar(222) NOT NULL,
            thursday_in_time varchar(222) NOT NULL,
            thursday_out_time varchar(222) NOT NULL,
            friday_in_time varchar(222) NOT NULL,
            friday_out_time varchar(222) NOT NULL,
            saturday_in_time varchar(222) NOT NULL,
            saturday_out_time varchar(222) NOT NULL,
            sunday_in_time varchar(222) NOT NULL,
            sunday_out_time varchar(222) NOT NULL,
            created_at varchar(222) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_payment_method (
            payment_method_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            method_name varchar(255) NOT NULL,
            payment_percentage varchar(200) DEFAULT NULL,
            account_number varchar(200) DEFAULT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_payroll_custom_fields (
            payroll_custom_id int(11) NOT NULL,
            allow_custom_1 varchar(255) NOT NULL,
            is_active_allow_1 int(11) NOT NULL,
            allow_custom_2 varchar(255) NOT NULL,
            is_active_allow_2 int(11) NOT NULL,
            allow_custom_3 varchar(255) NOT NULL,
            is_active_allow_3 int(11) NOT NULL,
            allow_custom_4 varchar(255) NOT NULL,
            is_active_allow_4 int(11) NOT NULL,
            allow_custom_5 varchar(255) NOT NULL,
            is_active_allow_5 int(111) NOT NULL,
            deduct_custom_1 varchar(255) NOT NULL,
            is_active_deduct_1 int(11) NOT NULL,
            deduct_custom_2 varchar(255) NOT NULL,
            is_active_deduct_2 int(11) NOT NULL,
            deduct_custom_3 varchar(255) NOT NULL,
            is_active_deduct_3 int(11) NOT NULL,
            deduct_custom_4 varchar(255) NOT NULL,
            is_active_deduct_4 int(11) NOT NULL,
            deduct_custom_5 varchar(255) NOT NULL,
            is_active_deduct_5 int(11) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_performance_appraisal (
            performance_appraisal_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id int(111) NOT NULL,
            appraisal_year_month varchar(255) NOT NULL,
            customer_experience int(111) NOT NULL,
            marketing int(111) NOT NULL,
            management int(111) NOT NULL,
            administration int(111) NOT NULL,
            presentation_skill int(111) NOT NULL,
            quality_of_work int(111) NOT NULL,
            efficiency int(111) NOT NULL,
            integrity int(111) NOT NULL,
            professionalism int(111) NOT NULL,
            team_work int(111) NOT NULL,
            critical_thinking int(111) NOT NULL,
            conflict_management int(111) NOT NULL,
            attendance int(111) NOT NULL,
            ability_to_meet_deadline int(111) NOT NULL,
            remarks mediumtext NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_performance_indicator (
            performance_indicator_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            designation_id int(111) NOT NULL,
            customer_experience int(111) NOT NULL,
            marketing int(111) NOT NULL,
            management int(111) NOT NULL,
            administration int(111) NOT NULL,
            presentation_skill int(111) NOT NULL,
            quality_of_work int(111) NOT NULL,
            efficiency int(111) NOT NULL,
            integrity int(111) NOT NULL,
            professionalism int(111) NOT NULL,
            team_work int(111) NOT NULL,
            critical_thinking int(111) NOT NULL,
            conflict_management int(111) NOT NULL,
            attendance int(111) NOT NULL,
            ability_to_meet_deadline int(111) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_projects (
            project_id int(111) NOT NULL,
            title varchar(255) NOT NULL,
            client_id int(100) NOT NULL,
            start_date varchar(255) NOT NULL,
            end_date varchar(255) NOT NULL,
            company_id int(111) NOT NULL,
            assigned_to mediumtext NOT NULL,
            priority varchar(255) NOT NULL,
            summary mediumtext NOT NULL,
            description mediumtext NOT NULL,
            added_by int(111) NOT NULL,
            project_progress varchar(255) NOT NULL,
            project_note longtext NOT NULL,
            status tinyint(2) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_projects_attachment (
            project_attachment_id int(11) NOT NULL,
            project_id int(200) NOT NULL,
            upload_by int(255) NOT NULL,
            file_title varchar(255) NOT NULL,
            file_description mediumtext NOT NULL,
            attachment_file mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_projects_bugs (
            bug_id int(11) NOT NULL,
            project_id int(111) NOT NULL,
            user_id int(200) NOT NULL,
            attachment_file varchar(255) NOT NULL,
            title varchar(255) NOT NULL,
            status tinyint(1) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_projects_discussion (
            discussion_id int(11) NOT NULL,
            project_id int(111) NOT NULL,
            user_id int(200) NOT NULL,
            client_id int(11) NOT NULL,
            attachment_file varchar(255) NOT NULL,
            message mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_qualification_education_level (
            education_level_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            name varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_qualification_language (
            language_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            name varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_qualification_skill (
            skill_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            name varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_recruitment_pages (
            page_id int(11) NOT NULL,
            page_title varchar(255) NOT NULL,
            page_details mediumtext NOT NULL,
            status int(11) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_recruitment_subpages (
            subpages_id int(11) NOT NULL,
            page_id int(11) NOT NULL,
            sub_page_title varchar(255) NOT NULL,
            sub_page_details mediumtext NOT NULL,
            status int(11) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_reference (
            id int(11) NOT NULL,
            name varchar(100) NOT NULL,
            category varchar(20) NOT NULL
          );
          
          CREATE TABLE xin_referral (
            referral_id int(11) NOT NULL,
            referral_name varchar(100) NOT NULL,
            referral_email varchar(100) NOT NULL,
            referral_contact_no varchar(100) NOT NULL,
            referral_status varchar(20) NOT NULL,
            referral_employee_id int(11) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_reset_password (
            id int(11) NOT NULL,
            code varchar(40) NOT NULL,
            expired_at datetime NOT NULL,
            created_at datetime NOT NULL,
            email varchar(100) NOT NULL,
            is_used tinyint(1) NOT NULL DEFAULT 0
          );
          
          CREATE TABLE xin_salary_allowances (
            allowance_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            allowance_title varchar(200) DEFAULT NULL,
            allowance_amount varchar(200) DEFAULT NULL,
            created_at varchar(200) DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_bank_allocation (
            bank_allocation_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            payment_method_id int(11) NOT NULL,
            pay_percent varchar(200) NOT NULL,
            acc_number varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_loan_deductions (
            loan_deduction_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            loan_deduction_title varchar(200) NOT NULL,
            start_date varchar(200) NOT NULL,
            end_date varchar(200) NOT NULL,
            monthly_installment varchar(200) NOT NULL,
            loan_time varchar(200) NOT NULL,
            loan_deduction_amount varchar(200) NOT NULL,
            total_paid varchar(200) NOT NULL,
            reason text NOT NULL,
            status int(11) NOT NULL,
            is_deducted_from_salary int(11) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_overtime (
            salary_overtime_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            overtime_type varchar(200) NOT NULL,
            no_of_days varchar(100) NOT NULL DEFAULT '0',
            overtime_hours varchar(100) NOT NULL DEFAULT '0',
            overtime_rate varchar(100) NOT NULL DEFAULT '0'
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_payslips (
            payslip_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            department_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            designation_id int(11) NOT NULL,
            salary_month varchar(200) NOT NULL,
            wages_type int(11) NOT NULL,
            basic_salary varchar(200) NOT NULL,
            daily_wages varchar(200) NOT NULL,
            salary_ssempee varchar(200) NOT NULL,
            salary_ssempeer varchar(200) NOT NULL,
            salary_income_tax varchar(200) NOT NULL,
            salary_commission varchar(200) NOT NULL,
            salary_claims varchar(200) NOT NULL,
            salary_paid_leave varchar(200) NOT NULL,
            salary_director_fees varchar(200) NOT NULL,
            salary_advance_paid varchar(200) NOT NULL,
            total_allowances varchar(200) NOT NULL,
            total_loan varchar(200) NOT NULL,
            total_overtime varchar(200) NOT NULL,
            statutory_deductions varchar(200) NOT NULL,
            net_salary varchar(200) NOT NULL,
            other_payment varchar(200) NOT NULL,
            payment_method int(11) NOT NULL,
            pay_comments mediumtext NOT NULL,
            is_payment int(11) NOT NULL,
            year_to_date varchar(200) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_payslip_allowances (
            payslip_allowances_id int(11) NOT NULL,
            payslip_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            allowance_title varchar(200) NOT NULL,
            allowance_amount varchar(200) NOT NULL,
            salary_month varchar(200) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_payslip_loan (
            payslip_loan_id int(11) NOT NULL,
            payslip_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            loan_title varchar(200) NOT NULL,
            loan_amount varchar(200) NOT NULL,
            salary_month varchar(200) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_payslip_overtime (
            payslip_overtime_id int(11) NOT NULL,
            payslip_id int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            overtime_title varchar(200) NOT NULL,
            overtime_salary_month varchar(200) NOT NULL,
            overtime_no_of_days varchar(200) NOT NULL,
            overtime_hours varchar(200) NOT NULL,
            overtime_rate varchar(200) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_salary_templates (
            salary_template_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            salary_grades varchar(255) NOT NULL,
            basic_salary varchar(255) NOT NULL,
            overtime_rate varchar(255) NOT NULL,
            house_rent_allowance varchar(255) NOT NULL,
            medical_allowance varchar(255) NOT NULL,
            travelling_allowance varchar(255) NOT NULL,
            dearness_allowance varchar(255) NOT NULL,
            security_deposit varchar(255) NOT NULL,
            provident_fund varchar(255) NOT NULL,
            tax_deduction varchar(255) NOT NULL,
            gross_salary varchar(255) NOT NULL,
            total_allowance varchar(255) NOT NULL,
            total_deduction varchar(255) NOT NULL,
            net_salary varchar(255) NOT NULL,
            added_by int(111) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_sub_departments (
            sub_department_id int(11) NOT NULL,
            department_id int(11) NOT NULL,
            department_name varchar(200) NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_support_tickets (
            ticket_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            ticket_code varchar(200) NOT NULL,
            subject varchar(255) NOT NULL,
            employee_id int(111) NOT NULL,
            ticket_priority varchar(255) NOT NULL,
            department_id int(111) NOT NULL,
            assigned_to mediumtext NOT NULL,
            message mediumtext NOT NULL,
            description mediumtext NOT NULL,
            ticket_remarks mediumtext NOT NULL,
            ticket_status varchar(200) NOT NULL,
            ticket_note mediumtext NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_support_ticket_files (
            ticket_file_id int(111) NOT NULL,
            ticket_id int(111) NOT NULL,
            employee_id int(111) NOT NULL,
            ticket_files varchar(255) NOT NULL,
            file_size varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_system_setting (
            setting_id int(111) NOT NULL,
            application_name varchar(255) NOT NULL,
            default_currency varchar(255) NOT NULL,
            default_currency_id int(11) NOT NULL,
            default_currency_symbol varchar(255) NOT NULL,
            show_currency varchar(255) NOT NULL,
            currency_position varchar(255) NOT NULL,
            notification_position varchar(255) NOT NULL,
            notification_close_btn varchar(255) NOT NULL,
            notification_bar varchar(255) NOT NULL,
            enable_registration varchar(255) NOT NULL,
            login_with varchar(255) NOT NULL,
            date_format_xi varchar(255) NOT NULL,
            employee_manage_own_contact varchar(255) NOT NULL,
            employee_manage_own_profile varchar(255) NOT NULL,
            employee_manage_own_qualification varchar(255) NOT NULL,
            employee_manage_own_work_experience varchar(255) NOT NULL,
            employee_manage_own_document varchar(255) NOT NULL,
            employee_manage_own_picture varchar(255) NOT NULL,
            employee_manage_own_social varchar(255) NOT NULL,
            employee_manage_own_bank_account varchar(255) NOT NULL,
            enable_attendance varchar(255) NOT NULL,
            enable_clock_in_btn varchar(255) NOT NULL,
            enable_email_notification varchar(255) NOT NULL,
            payroll_include_day_summary varchar(255) NOT NULL,
            payroll_include_hour_summary varchar(255) NOT NULL,
            payroll_include_leave_summary varchar(255) NOT NULL,
            enable_job_application_candidates varchar(255) NOT NULL,
            job_logo varchar(255) NOT NULL,
            payroll_logo varchar(255) NOT NULL,
            is_payslip_password_generate int(11) NOT NULL,
            payslip_password_format varchar(255) NOT NULL,
            enable_profile_background varchar(255) NOT NULL,
            enable_policy_link varchar(255) NOT NULL,
            enable_layout varchar(255) NOT NULL,
            job_application_format mediumtext NOT NULL,
            project_email varchar(255) NOT NULL,
            holiday_email varchar(255) NOT NULL,
            leave_email varchar(255) NOT NULL,
            payslip_email varchar(255) NOT NULL,
            award_email varchar(255) NOT NULL,
            recruitment_email varchar(255) NOT NULL,
            announcement_email varchar(255) NOT NULL,
            training_email varchar(255) NOT NULL,
            task_email varchar(255) NOT NULL,
            compact_sidebar varchar(255) NOT NULL,
            fixed_header varchar(255) NOT NULL,
            fixed_sidebar varchar(255) NOT NULL,
            boxed_wrapper varchar(255) NOT NULL,
            layout_static varchar(255) NOT NULL,
            system_skin varchar(255) NOT NULL,
            animation_effect varchar(255) NOT NULL,
            animation_effect_modal varchar(255) NOT NULL,
            animation_effect_topmenu varchar(255) NOT NULL,
            footer_text varchar(255) NOT NULL,
            system_timezone varchar(200) NOT NULL,
            system_ip_address varchar(255) NOT NULL,
            system_ip_restriction varchar(200) NOT NULL,
            google_maps_api_key mediumtext NOT NULL,
            module_recruitment varchar(100) NOT NULL,
            module_travel varchar(100) NOT NULL,
            module_performance varchar(100) NOT NULL,
            module_files varchar(100) NOT NULL,
            module_awards varchar(100) NOT NULL,
            module_training varchar(100) NOT NULL,
            module_inquiry varchar(100) NOT NULL,
            module_language varchar(100) NOT NULL,
            module_orgchart varchar(100) NOT NULL,
            module_accounting varchar(111) NOT NULL,
            module_events varchar(100) NOT NULL,
            module_goal_tracking varchar(100) NOT NULL,
            module_assets varchar(100) NOT NULL,
            module_projects_tasks varchar(100) NOT NULL,
            module_chat_box varchar(100) NOT NULL,
            enable_page_rendered varchar(255) NOT NULL,
            enable_current_year varchar(255) NOT NULL,
            employee_login_id varchar(200) NOT NULL,
            enable_auth_background varchar(11) NOT NULL,
            hr_version varchar(200) NOT NULL,
            hr_release_date varchar(100) NOT NULL,
            updated_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_tasks (
            task_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            project_id int(111) NOT NULL,
            created_by int(111) NOT NULL,
            task_name varchar(255) NOT NULL,
            assigned_to varchar(255) NOT NULL,
            start_date varchar(200) NOT NULL,
            end_date varchar(200) NOT NULL,
            task_hour varchar(200) NOT NULL,
            task_progress varchar(200) NOT NULL,
            description mediumtext NOT NULL,
            task_status int(5) NOT NULL,
            task_note mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_tasks_attachment (
            task_attachment_id int(11) NOT NULL,
            task_id int(200) NOT NULL,
            upload_by int(255) NOT NULL,
            file_title varchar(255) NOT NULL,
            file_description mediumtext NOT NULL,
            attachment_file mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_tasks_comments (
            comment_id int(11) NOT NULL,
            task_id int(200) NOT NULL,
            user_id int(200) NOT NULL,
            task_comments mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_tax_types (
            tax_id int(111) NOT NULL,
            name varchar(255) NOT NULL,
            rate varchar(255) NOT NULL,
            type varchar(255) NOT NULL,
            description mediumtext NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_termination_type (
            termination_type_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            type varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_theme_settings (
            theme_settings_id int(11) NOT NULL,
            fixed_layout varchar(200) NOT NULL,
            fixed_footer varchar(200) NOT NULL,
            boxed_layout varchar(200) NOT NULL,
            page_header varchar(200) NOT NULL,
            footer_layout varchar(200) NOT NULL,
            statistics_cards varchar(200) NOT NULL,
            statistics_cards_background varchar(200) NOT NULL,
            employee_cards varchar(200) NOT NULL,
            card_border_color varchar(200) NOT NULL,
            compact_menu varchar(200) NOT NULL,
            flipped_menu varchar(200) NOT NULL,
            right_side_icons varchar(200) NOT NULL,
            bordered_menu varchar(200) NOT NULL,
            form_design varchar(200) NOT NULL,
            is_semi_dark int(11) NOT NULL,
            semi_dark_color varchar(200) NOT NULL,
            top_nav_dark_color varchar(200) NOT NULL,
            menu_color_option varchar(200) NOT NULL,
            export_orgchart varchar(100) NOT NULL,
            export_file_title mediumtext NOT NULL,
            org_chart_layout varchar(200) NOT NULL,
            org_chart_zoom varchar(100) NOT NULL,
            org_chart_pan varchar(100) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_tickets_attachment (
            ticket_attachment_id int(11) NOT NULL,
            ticket_id int(200) NOT NULL,
            upload_by int(255) NOT NULL,
            file_title varchar(255) NOT NULL,
            file_description mediumtext NOT NULL,
            attachment_file mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_tickets_comments (
            comment_id int(11) NOT NULL,
            ticket_id int(200) NOT NULL,
            user_id int(200) NOT NULL,
            ticket_comments mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_trainers (
            trainer_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            first_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            contact_number varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            designation_id int(111) NOT NULL,
            expertise mediumtext NOT NULL,
            address mediumtext NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_training (
            training_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            employee_id varchar(200) NOT NULL,
            training_type_id int(200) NOT NULL,
            trainer_id int(200) NOT NULL,
            start_date varchar(200) NOT NULL,
            finish_date varchar(200) NOT NULL,
            training_cost varchar(200) NOT NULL,
            training_status int(200) NOT NULL,
            description mediumtext NOT NULL,
            performance varchar(200) NOT NULL,
            remarks mediumtext NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_training_types (
            training_type_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            type varchar(255) NOT NULL,
            created_at varchar(200) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_transaction_point (
            id int(11) NOT NULL,
            activity_point_code varchar(50) NOT NULL,
            point int(11) NOT NULL,
            employee_id int(11) NOT NULL,
            challenge_id int(11) NOT NULL,
            status int(11) NOT NULL,
            created_at datetime NOT NULL,
            modified_at datetime NOT NULL
          );
          
          CREATE TABLE xin_travel_arrangement_type (
            arrangement_type_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            type varchar(255) NOT NULL,
            status tinyint(1) NOT NULL DEFAULT 1,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_users (
            user_id int(11) NOT NULL,
            user_role varchar(30) NOT NULL DEFAULT 'administrator',
            first_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            company_name varchar(255) NOT NULL,
            company_logo varchar(255) NOT NULL,
            user_type int(11) NOT NULL,
            email varchar(255) NOT NULL,
            username varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            profile_photo varchar(255) NOT NULL,
            profile_background varchar(255) NOT NULL,
            contact_number varchar(255) NOT NULL,
            gender varchar(20) NOT NULL,
            address_1 text NOT NULL,
            address_2 text NOT NULL,
            city varchar(255) NOT NULL,
            state varchar(255) NOT NULL,
            zipcode varchar(255) NOT NULL,
            country int(11) NOT NULL,
            last_login_date varchar(255) NOT NULL,
            last_login_ip varchar(255) NOT NULL,
            is_logged_in int(11) NOT NULL,
            is_active int(11) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_user_roles (
            role_id int(11) NOT NULL,
            company_id int(11) NOT NULL,
            role_name varchar(200) NOT NULL,
            role_access varchar(200) NOT NULL,
            role_resources text NOT NULL,
            created_at varchar(200) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          CREATE TABLE xin_warning_type (
            warning_type_id int(111) NOT NULL,
            company_id int(11) NOT NULL,
            type varchar(255) NOT NULL,
            created_at varchar(255) NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
          
          
          ALTER TABLE ci_sessions
            ADD KEY ci_sessions_timestamp (timestamp);
          
          ALTER TABLE keys
            ADD PRIMARY KEY (id);
          
          ALTER TABLE users
            ADD PRIMARY KEY (id,username);
          
          ALTER TABLE xin_activity_point
            ADD PRIMARY KEY (activity_point_id);
          
          ALTER TABLE xin_advance_salaries
            ADD PRIMARY KEY (advance_salary_id);
          
          ALTER TABLE xin_announcements
            ADD PRIMARY KEY (announcement_id);
          
          ALTER TABLE xin_assets
            ADD PRIMARY KEY (assets_id);
          
          ALTER TABLE xin_assets_categories
            ADD PRIMARY KEY (assets_category_id);
          
          ALTER TABLE xin_attendance_time
            ADD PRIMARY KEY (time_attendance_id);
          
          ALTER TABLE xin_awards
            ADD PRIMARY KEY (award_id);
          
          ALTER TABLE xin_award_type
            ADD PRIMARY KEY (award_type_id);
          
          ALTER TABLE xin_challenge
            ADD PRIMARY KEY (challenge_id);
          
          ALTER TABLE xin_challenge_participant
            ADD PRIMARY KEY (id);
          
          ALTER TABLE xin_challenge_quiz
            ADD PRIMARY KEY (id);
          
          ALTER TABLE xin_challenge_type
            ADD PRIMARY KEY (challenge_type_id);
          
          ALTER TABLE xin_chat_messages
            ADD PRIMARY KEY (message_id);
          
          ALTER TABLE xin_clients
            ADD PRIMARY KEY (client_id);
          
          ALTER TABLE xin_companies
            ADD PRIMARY KEY (company_id);
          
          ALTER TABLE xin_company_documents
            ADD PRIMARY KEY (document_id);
          
          ALTER TABLE xin_company_info
            ADD PRIMARY KEY (company_info_id);
          
          ALTER TABLE xin_company_policy
            ADD PRIMARY KEY (policy_id);
          
          ALTER TABLE xin_company_type
            ADD PRIMARY KEY (type_id);
          
          ALTER TABLE xin_contract_type
            ADD PRIMARY KEY (contract_type_id);
          
          ALTER TABLE xin_countries
            ADD PRIMARY KEY (country_id);
          
          ALTER TABLE xin_currencies
            ADD PRIMARY KEY (currency_id);
          
          ALTER TABLE xin_currency_converter
            ADD PRIMARY KEY (currency_converter_id);
          
          ALTER TABLE xin_database_backup
            ADD PRIMARY KEY (backup_id);
          
          ALTER TABLE xin_departments
            ADD PRIMARY KEY (department_id);
          
          ALTER TABLE xin_designations
            ADD PRIMARY KEY (designation_id);
          
          ALTER TABLE xin_document_type
            ADD PRIMARY KEY (document_type_id);
          
          ALTER TABLE xin_email_template
            ADD PRIMARY KEY (template_id);
          
          ALTER TABLE xin_employees
            ADD PRIMARY KEY (user_id);
          
          ALTER TABLE xin_employee_bankaccount
            ADD PRIMARY KEY (bankaccount_id);
          
          ALTER TABLE xin_employee_complaints
            ADD PRIMARY KEY (complaint_id);
          
          ALTER TABLE xin_employee_contacts
            ADD PRIMARY KEY (contact_id);
          
          ALTER TABLE xin_employee_contract
            ADD PRIMARY KEY (contract_id);
          
          ALTER TABLE xin_employee_documents
            ADD PRIMARY KEY (document_id);
          
          ALTER TABLE xin_employee_exit
            ADD PRIMARY KEY (exit_id);
          
          ALTER TABLE xin_employee_exit_type
            ADD PRIMARY KEY (exit_type_id);
          
          ALTER TABLE xin_employee_immigration
            ADD PRIMARY KEY (immigration_id);
          
          ALTER TABLE xin_employee_leave
            ADD PRIMARY KEY (leave_id);
          
          ALTER TABLE xin_employee_location
            ADD PRIMARY KEY (office_location_id);
          
          ALTER TABLE xin_employee_project_experiences
            ADD PRIMARY KEY (id);
          
          ALTER TABLE xin_employee_promotions
            ADD PRIMARY KEY (promotion_id);
          
          ALTER TABLE xin_employee_qualification
            ADD PRIMARY KEY (qualification_id);
          
          ALTER TABLE xin_employee_resignations
            ADD PRIMARY KEY (resignation_id);
          
          ALTER TABLE xin_employee_shift
            ADD PRIMARY KEY (emp_shift_id);
          
          ALTER TABLE xin_employee_terminations
            ADD PRIMARY KEY (termination_id);
          
          ALTER TABLE xin_employee_transfer
            ADD PRIMARY KEY (transfer_id);
          
          ALTER TABLE xin_employee_travels
            ADD PRIMARY KEY (travel_id);
          
          ALTER TABLE xin_employee_warnings
            ADD PRIMARY KEY (warning_id);
          
          ALTER TABLE xin_employee_work_experience
            ADD PRIMARY KEY (work_experience_id);
          
          ALTER TABLE xin_events
            ADD PRIMARY KEY (event_id);
          
          ALTER TABLE xin_events_participant
            ADD PRIMARY KEY (id);
          
          ALTER TABLE xin_expenses
            ADD PRIMARY KEY (expense_id);
          
          ALTER TABLE xin_expense_type
            ADD PRIMARY KEY (expense_type_id);
          
          ALTER TABLE xin_file_manager
            ADD PRIMARY KEY (file_id);
          
          ALTER TABLE xin_file_manager_settings
            ADD PRIMARY KEY (setting_id);
          
          ALTER TABLE xin_finance_bankcash
            ADD PRIMARY KEY (bankcash_id);
          
          ALTER TABLE xin_finance_deposit
            ADD PRIMARY KEY (deposit_id);
          
          ALTER TABLE xin_finance_expense
            ADD PRIMARY KEY (expense_id);
          
          ALTER TABLE xin_finance_payees
            ADD PRIMARY KEY (payee_id);
          
          ALTER TABLE xin_finance_payers
            ADD PRIMARY KEY (payer_id);
          
          ALTER TABLE xin_finance_transactions
            ADD PRIMARY KEY (transaction_id);
          
          ALTER TABLE xin_finance_transfer
            ADD PRIMARY KEY (transfer_id);
          
          ALTER TABLE xin_goal_tracking
            ADD PRIMARY KEY (tracking_id);
          
          ALTER TABLE xin_goal_tracking_type
            ADD PRIMARY KEY (tracking_type_id);
          
          ALTER TABLE xin_holidays
            ADD PRIMARY KEY (holiday_id);
          
          ALTER TABLE xin_hourly_templates
            ADD PRIMARY KEY (hourly_rate_id);
          
          ALTER TABLE xin_hrsale_invoices
            ADD PRIMARY KEY (invoice_id);
          
          ALTER TABLE xin_hrsale_invoices_items
            ADD PRIMARY KEY (invoice_item_id);
          
          ALTER TABLE xin_income_categories
            ADD PRIMARY KEY (category_id);
          
          ALTER TABLE xin_jobs
            ADD PRIMARY KEY (job_id);
          
          ALTER TABLE xin_job_applications
            ADD PRIMARY KEY (application_id);
          
          ALTER TABLE xin_job_categories
            ADD PRIMARY KEY (category_id);
          
          ALTER TABLE xin_job_interviews
            ADD PRIMARY KEY (job_interview_id);
          
          ALTER TABLE xin_job_type
            ADD PRIMARY KEY (job_type_id);
          
          ALTER TABLE xin_languages
            ADD PRIMARY KEY (language_id);
          
          ALTER TABLE xin_leave_applications
            ADD PRIMARY KEY (leave_id);
          
          ALTER TABLE xin_leave_type
            ADD PRIMARY KEY (leave_type_id);
          
          ALTER TABLE xin_level
            ADD PRIMARY KEY (level_id);
          
          ALTER TABLE xin_make_payment
            ADD PRIMARY KEY (make_payment_id);
          
          ALTER TABLE xin_meetings
            ADD PRIMARY KEY (meeting_id);
          
          ALTER TABLE xin_news
            ADD PRIMARY KEY (news_id);
          
          ALTER TABLE xin_news_type
            ADD PRIMARY KEY (news_type_id);
          
          ALTER TABLE xin_office_location
            ADD PRIMARY KEY (location_id);
          
          ALTER TABLE xin_office_shift
            ADD PRIMARY KEY (office_shift_id);
          
          ALTER TABLE xin_payment_method
            ADD PRIMARY KEY (payment_method_id);
          
          ALTER TABLE xin_payroll_custom_fields
            ADD PRIMARY KEY (payroll_custom_id);
          
          ALTER TABLE xin_performance_appraisal
            ADD PRIMARY KEY (performance_appraisal_id);
          
          ALTER TABLE xin_performance_indicator
            ADD PRIMARY KEY (performance_indicator_id);
          
          ALTER TABLE xin_projects
            ADD PRIMARY KEY (project_id);
          
          ALTER TABLE xin_projects_attachment
            ADD PRIMARY KEY (project_attachment_id);
          
          ALTER TABLE xin_projects_bugs
            ADD PRIMARY KEY (bug_id);
          
          ALTER TABLE xin_projects_discussion
            ADD PRIMARY KEY (discussion_id);
          
          ALTER TABLE xin_qualification_education_level
            ADD PRIMARY KEY (education_level_id);
          
          ALTER TABLE xin_qualification_language
            ADD PRIMARY KEY (language_id);
          
          ALTER TABLE xin_qualification_skill
            ADD PRIMARY KEY (skill_id);
          
          ALTER TABLE xin_recruitment_pages
            ADD PRIMARY KEY (page_id);
          
          ALTER TABLE xin_recruitment_subpages
            ADD PRIMARY KEY (subpages_id);
          
          ALTER TABLE xin_reference
            ADD PRIMARY KEY (id);
          
          ALTER TABLE xin_referral
            ADD PRIMARY KEY (referral_id);
          
          ALTER TABLE xin_reset_password
            ADD PRIMARY KEY (id);
          
          ALTER TABLE xin_salary_allowances
            ADD PRIMARY KEY (allowance_id);
          
          ALTER TABLE xin_salary_bank_allocation
            ADD PRIMARY KEY (bank_allocation_id);
          
          ALTER TABLE xin_salary_loan_deductions
            ADD PRIMARY KEY (loan_deduction_id);
          
          ALTER TABLE xin_salary_overtime
            ADD PRIMARY KEY (salary_overtime_id);
          
          ALTER TABLE xin_salary_payslips
            ADD PRIMARY KEY (payslip_id);
          
          ALTER TABLE xin_salary_payslip_allowances
            ADD PRIMARY KEY (payslip_allowances_id);
          
          ALTER TABLE xin_salary_payslip_loan
            ADD PRIMARY KEY (payslip_loan_id);
          
          ALTER TABLE xin_salary_payslip_overtime
            ADD PRIMARY KEY (payslip_overtime_id);
          
          ALTER TABLE xin_salary_templates
            ADD PRIMARY KEY (salary_template_id);
          
          ALTER TABLE xin_sub_departments
            ADD PRIMARY KEY (sub_department_id);
          
          ALTER TABLE xin_support_tickets
            ADD PRIMARY KEY (ticket_id);
          
          ALTER TABLE xin_support_ticket_files
            ADD PRIMARY KEY (ticket_file_id);
          
          ALTER TABLE xin_system_setting
            ADD PRIMARY KEY (setting_id);
          
          ALTER TABLE xin_tasks
            ADD PRIMARY KEY (task_id);
          
          ALTER TABLE xin_tasks_attachment
            ADD PRIMARY KEY (task_attachment_id);
          
          ALTER TABLE xin_tasks_comments
            ADD PRIMARY KEY (comment_id);
          
          ALTER TABLE xin_tax_types
            ADD PRIMARY KEY (tax_id);
          
          ALTER TABLE xin_termination_type
            ADD PRIMARY KEY (termination_type_id);
          
          ALTER TABLE xin_theme_settings
            ADD PRIMARY KEY (theme_settings_id);
          
          ALTER TABLE xin_tickets_attachment
            ADD PRIMARY KEY (ticket_attachment_id);
          
          ALTER TABLE xin_tickets_comments
            ADD PRIMARY KEY (comment_id);
          
          ALTER TABLE xin_trainers
            ADD PRIMARY KEY (trainer_id);
          
          ALTER TABLE xin_training
            ADD PRIMARY KEY (training_id);
          
          ALTER TABLE xin_training_types
            ADD PRIMARY KEY (training_type_id);
          
          ALTER TABLE xin_transaction_point
            ADD PRIMARY KEY (id);
          
          ALTER TABLE xin_travel_arrangement_type
            ADD PRIMARY KEY (arrangement_type_id);
          
          ALTER TABLE xin_users
            ADD PRIMARY KEY (user_id);
          
          ALTER TABLE xin_user_roles
            ADD PRIMARY KEY (role_id);
          
          ALTER TABLE xin_warning_type
            ADD PRIMARY KEY (warning_type_id);
          
          
          ALTER TABLE keys
            MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE users
            MODIFY id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_activity_point
            MODIFY activity_point_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_advance_salaries
            MODIFY advance_salary_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_announcements
            MODIFY announcement_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_assets
            MODIFY assets_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_assets_categories
            MODIFY assets_category_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_attendance_time
            MODIFY time_attendance_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_awards
            MODIFY award_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_award_type
            MODIFY award_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_challenge
            MODIFY challenge_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_challenge_participant
            MODIFY id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_challenge_quiz
            MODIFY id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_challenge_type
            MODIFY challenge_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_chat_messages
            MODIFY message_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_clients
            MODIFY client_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_companies
            MODIFY company_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_company_documents
            MODIFY document_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_company_info
            MODIFY company_info_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_company_policy
            MODIFY policy_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_company_type
            MODIFY type_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_contract_type
            MODIFY contract_type_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_countries
            MODIFY country_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_currencies
            MODIFY currency_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_currency_converter
            MODIFY currency_converter_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_database_backup
            MODIFY backup_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_departments
            MODIFY department_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_designations
            MODIFY designation_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_document_type
            MODIFY document_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_email_template
            MODIFY template_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employees
            MODIFY user_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_bankaccount
            MODIFY bankaccount_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_complaints
            MODIFY complaint_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_contacts
            MODIFY contact_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_contract
            MODIFY contract_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_documents
            MODIFY document_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_exit
            MODIFY exit_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_exit_type
            MODIFY exit_type_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_immigration
            MODIFY immigration_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_leave
            MODIFY leave_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_location
            MODIFY office_location_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_project_experiences
            MODIFY id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_promotions
            MODIFY promotion_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_qualification
            MODIFY qualification_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_resignations
            MODIFY resignation_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_shift
            MODIFY emp_shift_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_terminations
            MODIFY termination_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_transfer
            MODIFY transfer_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_travels
            MODIFY travel_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_warnings
            MODIFY warning_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_employee_work_experience
            MODIFY work_experience_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_events
            MODIFY event_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_events_participant
            MODIFY id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_expenses
            MODIFY expense_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_expense_type
            MODIFY expense_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_file_manager
            MODIFY file_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_file_manager_settings
            MODIFY setting_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_finance_bankcash
            MODIFY bankcash_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_finance_deposit
            MODIFY deposit_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_finance_expense
            MODIFY expense_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_finance_payees
            MODIFY payee_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_finance_payers
            MODIFY payer_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_finance_transactions
            MODIFY transaction_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_finance_transfer
            MODIFY transfer_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_goal_tracking
            MODIFY tracking_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_goal_tracking_type
            MODIFY tracking_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_holidays
            MODIFY holiday_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_hourly_templates
            MODIFY hourly_rate_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_hrsale_invoices
            MODIFY invoice_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_hrsale_invoices_items
            MODIFY invoice_item_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_income_categories
            MODIFY category_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_jobs
            MODIFY job_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_job_applications
            MODIFY application_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_job_categories
            MODIFY category_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_job_interviews
            MODIFY job_interview_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_job_type
            MODIFY job_type_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_languages
            MODIFY language_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_leave_applications
            MODIFY leave_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_leave_type
            MODIFY leave_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_level
            MODIFY level_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_make_payment
            MODIFY make_payment_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_meetings
            MODIFY meeting_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_news
            MODIFY news_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_news_type
            MODIFY news_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_office_location
            MODIFY location_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_office_shift
            MODIFY office_shift_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_payment_method
            MODIFY payment_method_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_payroll_custom_fields
            MODIFY payroll_custom_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_performance_appraisal
            MODIFY performance_appraisal_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_performance_indicator
            MODIFY performance_indicator_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_projects
            MODIFY project_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_projects_attachment
            MODIFY project_attachment_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_projects_bugs
            MODIFY bug_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_projects_discussion
            MODIFY discussion_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_qualification_education_level
            MODIFY education_level_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_qualification_language
            MODIFY language_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_qualification_skill
            MODIFY skill_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_recruitment_pages
            MODIFY page_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_recruitment_subpages
            MODIFY subpages_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_reference
            MODIFY id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_referral
            MODIFY referral_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_reset_password
            MODIFY id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_allowances
            MODIFY allowance_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_bank_allocation
            MODIFY bank_allocation_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_loan_deductions
            MODIFY loan_deduction_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_overtime
            MODIFY salary_overtime_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_payslips
            MODIFY payslip_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_payslip_allowances
            MODIFY payslip_allowances_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_payslip_loan
            MODIFY payslip_loan_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_payslip_overtime
            MODIFY payslip_overtime_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_salary_templates
            MODIFY salary_template_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_sub_departments
            MODIFY sub_department_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_support_tickets
            MODIFY ticket_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_support_ticket_files
            MODIFY ticket_file_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_system_setting
            MODIFY setting_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_tasks
            MODIFY task_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_tasks_attachment
            MODIFY task_attachment_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_tasks_comments
            MODIFY comment_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_tax_types
            MODIFY tax_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_termination_type
            MODIFY termination_type_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_theme_settings
            MODIFY theme_settings_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_tickets_attachment
            MODIFY ticket_attachment_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_tickets_comments
            MODIFY comment_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_trainers
            MODIFY trainer_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_training
            MODIFY training_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_training_types
            MODIFY training_type_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_transaction_point
            MODIFY id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_travel_arrangement_type
            MODIFY arrangement_type_id int(111) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_users
            MODIFY user_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_user_roles
            MODIFY role_id int(11) NOT NULL AUTO_INCREMENT;
          
          ALTER TABLE xin_warning_type
            MODIFY warning_type_id int(111) NOT NULL AUTO_INCREMENT;
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
