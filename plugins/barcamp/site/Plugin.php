<?php namespace Barcamp\Site;

use Backend;
use Event;
use File;
use RainLab\Blog\Controllers\Posts;
use RainLab\Blog\Models\Post;
use RainLab\User\Controllers\Users;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use Yaml;

/**
 * Barcamp.Site Plugin Information File.
 */
class Plugin extends PluginBase
{
    /** @var array Plugin dependencies. */
    public $require = [
        'RainLab.Blog',
        'RainLab.User',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Barcamp Site',
            'description' => 'Site plugin for overriding and seeding other plugins.',
            'author'      => 'Vojta Svoboda',
            'icon'        => 'icon-beer',
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $this->extendUserModel();
        $this->extendUsersController();
        $this->extendUsersListing();
        $this->extendBackendMenus();
        $this->extendBlogPost();
    }

    /**
     * Extend RainLab.User model with new fields (phone, social networks, self promo).
     */
    private function extendUserModel()
    {
        User::extend(function ($model)
        {
            // add new fillable fields
            $model->addFillable([
                'phone', 'link_facebook', 'link_twitter', 'link_instagram', 'link_linkedin', 'link_web', 'self_promo',
            ]);

            // add new model validation rules
            $model->rules['phone'] = 'required|min:9|max:14';
            $model->rules['link_facebook'] = 'url';
            $model->rules['link_twitter'] = 'url';
            $model->rules['link_instagram'] = 'url';
            $model->rules['link_linkedin'] = 'url';
            $model->rules['link_web'] = 'url';
            $model->rules['self_promo'] = 'max:1000';

            // attribute names
            $model->attributeNames['phone'] = 'Telefon';
            $model->attributeNames['link_facebook'] = 'Odkaz na Facebook';
            $model->attributeNames['link_twitter'] = 'Odkaz na Twitter';
            $model->attributeNames['link_instagram'] = 'Odkaz na Instagram';
            $model->attributeNames['link_linkedin'] = 'Odkaz na LinkedIn';
            $model->attributeNames['link_web'] = 'Odkaz na Web';
        });
    }

    /**
     * Extend RainLab.User Users controller with new fields.
     */
    private function extendUsersController()
    {
        Users::extendFormFields(function ($form, $model)
        {
            // apply only for User model
            if (!$model instanceof User) {
                return;
            }

            // remove name, surname and insert it again updated
            $form->removeField('name');
            $form->removeField('surname');

            // add new fields
            $configFile = __DIR__ . '/config/users_fields.yaml';
            $config = Yaml::parse(File::get($configFile));
            $form->addFields($config);

            // add new tab fields
            $configFile = __DIR__ . '/config/users_tab_fields.yaml';
            $config = Yaml::parse(File::get($configFile));
            $form->addTabFields($config);
        });
    }

    /**
     * Extend RainLab.User Users listing.
     */
    private function extendUsersListing()
    {
        Event::listen('backend.list.extendColumns', function ($widget)
        {
            // only for Users controller
            if (!$widget->getController() instanceof Users) {
                return;
            }

            // only for User model
            if (!$widget->model instanceof User) {
                return;
            }

            // add new column
            $widget->addColumns([
                'phone' => [
                    'label'      => 'barcamp.site::lang.user.phone',
                    'sortable'   => true,
                    'searchable' => true,
                ],
            ]);
        });
    }

    /**
     * Override backend menus (add User sidemenu).
     */
    private function extendBackendMenus()
    {
        Event::listen('backend.menu.extendItems', function ($manager)
        {
            // Add submenu to RainLab.User plugin
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'users' => [
                    'label'       => 'Uživatelé',
                    'url'         => Backend::url('rainlab/user/users'),
                    'icon'        => 'icon-user',
                    'permissions' => ['rainlab.users.*'],
                    'order'       => 500,
                ],
                'usergroups' => [
                    'label'       => 'Skupiny',
                    'url'         => Backend::url('rainlab/user/usergroups'),
                    'icon'        => 'icon-users',
                    'permissions' => ['rainlab.users.access_groups'],
                    'order'       => 600,
                ],
            ]);

            // Remove FAQ proposals submenu
            $manager->removeSideMenuItem('VojtaSvoboda.Faq', 'faq', 'proposals');
        });
    }

    /**
     * Extends RainLab.Blog Post (add fields type, link and minutes).
     */
    private function extendBlogPost()
    {
        // extend Post model
        Post::extend(function ($model)
        {
            // add new fillable fields
            $model->addFillable(['type', 'link', 'minutes']);

            // add model required fields
            $model->rules['link'] = 'url';
            $model->rules['minutes'] = 'numeric';
        });

        // extend Posts controller
        Posts::extendFormFields(function ($form, $model)
        {
            // apply only for Post model
            if (!$model instanceof Post) {
                return;
            }

            // add new fields
            $configFile = __DIR__ . '/config/posts_fields.yaml';
            $config = Yaml::parse(File::get($configFile));
            $form->addSecondaryTabFields($config);
        });

        // extend post listing
        Event::listen('backend.list.extendColumns', function ($widget)
        {
            // only for Posts controller
            if (!$widget->getController() instanceof Posts) {
                return;
            }

            // only for Post model
            if (!$widget->model instanceof Post) {
                return;
            }

            // add new column
            $widget->addColumns([
                'type' => [
                    'label' => 'Typ',
                    'sortable' => true,
                    'searchable' => true,
                ],
                'minutes' => [
                    'label' => 'Min',
                    'sortable' => true,
                    'searchable' => true,
                ],
            ]);
        });
    }
}
