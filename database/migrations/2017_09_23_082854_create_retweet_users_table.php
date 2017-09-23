<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetweetUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retweet_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tweet_url_id')->unsigned();
            $table->foreign('tweet_url_id')->references('id')->on('tweet_urls')->onDelete('cascade');

            $table->string('user_name');
            $table->string('profile_image_url');
            $table->integer('followers_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retweet_users');
    }
}
