<?php

namespace Barcamp\Partners;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerNavigation()
    {
        return [
            'partners' => [
                'label' => 'Partneři',
                'url' => Backend::url('barcamp/partners/partners'),
                'icon' => 'icon-building-o',
                'permissions' => ['barcamp.partners.*'],
                'order' => 550,
                'sideMenu' => [
                    'partners' => [
                        'label' => 'Partneři',
                        'url' => Backend::url('barcamp/partners/partners'),
                        'icon' => 'icon-building-o',
                        'permissions' => ['barcamp.partners.*'],
                        'order' => 100,
                    ],
                    'categories' => [
                        'label' => 'Kategorie',
                        'url' => Backend::url('barcamp/partners/categories'),
                        'icon' => 'icon-folder',
                        'permissions' => ['barcamp.partners.*'],
                        'order' => 200,
                    ],
                ],
            ],
        ];
    }

    public function registerComponents()
    {
        return [
            'Barcamp\Partners\Components\Partners' => 'partners',
        ];
    }
}
