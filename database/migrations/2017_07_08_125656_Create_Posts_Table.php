<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $t){
           $t->increments('id');
           $t->string('slug',70);
           $t->integer('user_id')->unsigned()->nullable();
           $t->timestamps();
           $t->timestamp('published_at')->nullable();
           $t->integer('category_id')->unsigned()->nullable();
           $t->string('title', 55)->nullable();
           $t->string('description', 155)->nullable();
           $t->string('keywords', 250)->nullable();
           $t->string('name');
           $t->text('summary');
           $t->text('story')->nullable();
           $t->integer('views')->unsigned()->default(0);

           $t->index('slug');
            $t->index('published_at');
            $t->index('user_id');
            $t->index('category_id');
            $t->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $t->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Shema::dropIfExits('posts');
    }
}
