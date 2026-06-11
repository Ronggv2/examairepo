<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('question_sets', function (Blueprint $table) {
            $table->json('subjects')->nullable()->after('description');
        });

        DB::table('question_sets')
            ->whereNotNull('subject')
            ->update(['subjects' => DB::raw('JSON_ARRAY(subject)')]);

        Schema::table('question_sets', function (Blueprint $table) {
            if (Schema::hasColumn('question_sets', 'subject')) {
                $table->dropColumn('subject');
            }
        });
    }

    public function down(): void
    {
        Schema::table('question_sets', function (Blueprint $table) {
            $table->string('subject')->nullable()->after('description');
        });

        DB::table('question_sets')
            ->whereNotNull('subjects')
            ->update(['subject' => DB::raw('JSON_UNQUOTE(JSON_EXTRACT(subjects, "$[0]"))')]);

        Schema::table('question_sets', function (Blueprint $table) {
            if (Schema::hasColumn('question_sets', 'subjects')) {
                $table->dropColumn('subjects');
            }
        });
    }
};
