<?php

namespace Barcamp\Site\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\UserGroup;

class Team extends ComponentBase
{
    public $all;

    public function componentDetails()
    {
        return [
            'name' => 'Barcamp Team Members',
            'description' => 'List all team members',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->all = $this->page['team'] = $this->listUsers();
    }

    protected function listUsers()
    {
        $team = UserGroup::where('code', 'team')->first();
        if (!$team) {
            return null;
        }

        return $team->users;
    }
}
