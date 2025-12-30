<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (!Schema::hasColumn('contacts', 'reply')) {
                    $table->text('reply')->nullable()->after('message');
                }
                if (!Schema::hasColumn('contacts', 'replied_at')) {
                    $table->timestamp('replied_at')->nullable()->after('reply');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (Schema::hasColumn('contacts', 'replied_at')) {
                    $table->dropColumn('replied_at');
                }
                if (Schema::hasColumn('contacts', 'reply')) {
                    $table->dropColumn('reply');
                }
            });
        }
    }
};
