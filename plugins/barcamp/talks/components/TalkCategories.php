<?php namespace Barcamp\Talks\Components;

use Barcamp\Talks\Models\Category;
use Cms\Classes\ComponentBase;

class TalkCategories extends ComponentBase
{
    public $categories;

    public function componentDetails()
    {
        return [
            'name'        => 'Talk categories',
            'description' => 'Show talk categories',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * On component run.
     */
    public function onRun()
    {
        $this->categories = $this->page['categories'] = Category::isEnabled()->orderBy('sort_order')->get();
    }
}
