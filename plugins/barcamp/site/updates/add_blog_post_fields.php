<?php namespace Barcamp\Site\Updates;

use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;
use Schema;

class AddBlogPostFields extends Migration
{
    public function up()
    {
        Schema::table('rainlab_blog_posts', function ($table)
        {
            $table->string('type', 30)->nullable()->after('content_html');
            $table->string('link', 300)->nullable()->after('type');
            $table->smallInteger('minutes')->nullable()->after('link');
        });
    }

    public function down()
    {
        Schema::table('rainlab_blog_posts', function ($table) {
            $table->dropColumn(['type', 'link', 'minutes']);
        });
    }
}
