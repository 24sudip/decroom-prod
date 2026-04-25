<?php

use App\AppSettings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_setting', function (Blueprint $table) {
            $table->id();
            $table->string('title', 250);
            $table->string('logo_lg', 250);
            $table->string('logo_sm', 250);
            $table->string('logo_dark_sm', 250);
            $table->string('logo_dark_lg', 250);
            $table->string('favicon', 250);
            $table->string('footer_left', 250);
            $table->string('footer_right', 250);
            $table->timestamps();
        });

        AppSettings::create([
            "title"        => "Live Well Pharmacy",
            "logo_sm"      => "logo-light1.png",
            "logo_lg"      => "logo-light.png",
            "logo_dark_sm" => "logo.png",
            "logo_dark_lg" => "logo-dark.png",
            "favicon"      => "favicon.ico",
            "footer_left"  => "Live Well Pharmacy",
            "footer_right" => "Design & Develop by QuickTech IT",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_setting');
    }
};
