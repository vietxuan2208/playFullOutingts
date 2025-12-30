<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('contacts')) {
            if (!Schema::hasColumn('contacts', 'ip')) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table->string('ip')->nullable()->after('message');
                });
            }

            if (!Schema::hasColumn('contacts', 'user_agent')) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table->string('user_agent', 512)->nullable()->after('ip');
                });
            }

            if (!Schema::hasColumn('contacts', 'read')) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table->boolean('read')->default(false)->after('user_agent');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (Schema::hasColumn('contacts', 'read')) {
                    $table->dropColumn('read');
                }
                if (Schema::hasColumn('contacts', 'user_agent')) {
                    $table->dropColumn('user_agent');
                }
                if (Schema::hasColumn('contacts', 'ip')) {
                    $table->dropColumn('ip');
                }
            });
        }
    }
};
