<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Fase2Addnewmenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            INSERT INTO `xin_menus` (`id`, `name`, `icon`, `url`, `type`, `is_parent`, `parent_id`, `level`, `group_name`, `initial`, `class`, `status`) VALUES (NULL, 'Hackathon', 'cib-codesandbox', 'hackathon', 'link', '0', '0', '0', 'hackathon', 'hackathon', 'super admin', '0');
            INSERT INTO `xin_permissions` (`id`, `name`, `group_name`, `menu_id`, `action`, `created_at`, `updated_at`) VALUES (NULL, 'hackathon', 'hackathon', '34', 'view', NULL, NULL);
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
