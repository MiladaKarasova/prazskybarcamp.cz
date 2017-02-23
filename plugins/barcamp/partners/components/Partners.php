<?php

namespace Barcamp\Partners\Components;

use Cms\Classes\ComponentBase;
use Barcamp\Partners\Models\Category;
use Barcamp\Partners\Models\Partner;

class Partners extends ComponentBase
{
    public $all;

    public function componentDetails()
    {
        return [
            'name' => 'Barcamp Partners management',
            'description' => 'List all categories with partners'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->all = $this->page['all'] = $this->listCategories();
    }

    protected function listCategories()
    {
        return Category::isEnabled()->orderBy('sort_order', 'ASC')->get();
    }
}
