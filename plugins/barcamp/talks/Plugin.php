<?php namespace Barcamp\Talks;

use Backend;
use Barcamp\Talks\Facades\TalksFacade;
use System\Classes\PluginBase;

/**
 * Barcamp.Talks Plugin Information File.
 */
class Plugin extends PluginBase
{
    /** @var array Plugin dependencies. */
    public $require = [
        'RainLab.User',
        'Barcamp.Site',
    ];

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Barcamp\Talks\Components\RegistrationForm' => 'registrationForm',
            'Barcamp\Talks\Components\TalkCategories' => 'talkCategories',
            'Barcamp\Talks\Components\Talks' => 'talks',
            'Barcamp\Talks\Components\OneTalk' => 'onetalk'
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'talks' => [
                'label'       => 'Program',
                'url'         => Backend::url('barcamp/talks/talks'),
                'icon'        => 'icon-calendar',
                'permissions' => ['barcamp.talks.*'],
                'order'       => 500,
                'sideMenu' => [
                    'talks' => [
                        'label'       => 'Přednášky',
                        'url'         => Backend::url('barcamp/talks/talks'),
                        'icon'        => 'icon-bullhorn',
                        'permissions' => ['barcamp.talks.talks'],
                        'order'       => 100,
                    ],
                    'categories' => [
                        'label'       => 'Kategorie',
                        'url'         => Backend::url('barcamp/talks/categories'),
                        'icon'        => 'icon-folder',
                        'permissions' => ['barcamp.talks.categories'],
                        'order'       => 200,
                    ],
                    'types' => [
                        'label'       => 'Typy',
                        'url'         => Backend::url('barcamp/talks/types'),
                        'icon'        => 'icon-list',
                        'permissions' => ['barcamp.talks.types'],
                        'order'       => 300,
                    ],
                    'votes' => [
                        'label'       => 'Hlasování',
                        'url'         => Backend::url('barcamp/talks/votes'),
                        'icon'        => 'icon-comment-o',
                        'permissions' => ['barcamp.talks.votes'],
                        'order'       => 400,
                    ],
                ],
            ],
        ];
    }

    /**
     * Register backend settings.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Správa programu',
                'description' => 'Nastavení programu, limitu přednášek',
                'category'    => 'Program',
                'icon'        => 'icon-calendar',
                'class'       => 'Barcamp\Talks\Models\Settings',
                'order'       => 500,
                'permissions' => ['barcamp.talks.*'],
            ],
        ];
    }

    /**
     * Register scheduler. CRON tab has to be configured!
     *
     * @param $schedule
     */
    public function registerSchedule($schedule)
    {
        $schedule->call(function () {
            /** @var TalksFacade $facade */
            $facade = $this->app->make('Barcamp\Talks\Facades\TalksFacade');
            $facade->recalculateVotes();

        })->hourly()->name('Recalculate all votes.');
    }
}
