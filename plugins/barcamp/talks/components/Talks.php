<?php namespace Barcamp\Talks\Components;

use Barcamp\Talks\Models\Talk;
use Cms\Classes\ComponentBase;

class Talks extends ComponentBase
{
    public $talks;

    public function componentDetails()
    {
        return [
            'name'        => 'Talks',
            'description' => 'Show talks',
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
        $this->talks = $this->page['talks'] = $this->getTalks();
    }

    /**
     * Get talks.
     *
     * @return mixed
     */
    public function getTalks()
    {
        return Talk::isApproved()->with('user', 'category')->orderBy('votes')->limit(100)->get();
    }
}
