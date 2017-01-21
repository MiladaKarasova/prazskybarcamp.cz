<?php namespace Barcamp\Talks\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTypesTable extends Migration
{
    public function up()
    {
        Schema::create('barcamp_talks_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->string('name', 300);
            $table->string('slug', 300);
            $table->smallInteger('sort_order')->nullable();
            $table->boolean('enabled')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barcamp_talks_types');
    }
}
