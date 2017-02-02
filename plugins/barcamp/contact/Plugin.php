<?php namespace Barcamp\Contact;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerNavigation()
    {
        return [
            'contact' => [
                'label' => 'Zprávy',
                'url' => Backend::url('barcamp/contact/messages'),
                'icon' => 'icon-phone',
                'permissions' => ['barcamp.contact.*'],
                'order' => 600,
				'sideMenu' => [
                    'messages' => [
                        'label' => 'Zprávy',
                        'icon' => 'icon-file-text-o',
                        'url' => Backend::url('barcamp/contact/messages'),
                        'permissions' => ['barcamp.contact.*'],
                    ],
				]
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'barcamp.contact.*' => ['label' => 'Zobrazení kontaktních formulářů'],
        ];
    }

    public function registerComponents()
    {
        return [
            'Barcamp\Contact\Components\ContactForm' => 'contactForm',
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'barcamp.contact::mail.contact' => 'Zpráva z kontaktního formuláře',
        ];
    }
}
