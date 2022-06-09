<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialiteAutopilotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialite_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('social_id');
            $table->string('name');
            $table->json('options')->nullable();
            $table->string('provider');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('socialite_medias', function (Blueprint $table) {
            $table->id();
            $table->string('file')->nullable();
            $table->string('type')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('socialite_account_media', function (Blueprint $table) {
            $table->foreignId('account_id')->constrained('socialite_accounts');
            $table->foreignId('media_id')->constrained('socialite_medias');
        });

        Schema::create('socialite_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socialite_account_id')->constrained('socialite_accounts');
            $table->json('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socialite_schedules');
        Schema::dropIfExists('socialite_account_media');
        Schema::dropIfExists('socialite_medias');
        Schema::dropIfExists('socialite_accounts');
    }
}
