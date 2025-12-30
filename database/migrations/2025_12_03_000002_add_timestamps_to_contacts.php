<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (!Schema::hasColumn('contacts', 'created_at')) {
                    $table->timestamp('created_at')->nullable()->after('read');
                }
                if (!Schema::hasColumn('contacts', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable()->after('created_at');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (Schema::hasColumn('contacts', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
                if (Schema::hasColumn('contacts', 'created_at')) {
                    $table->dropColumn('created_at');
                }
            });
        }
    }
};
