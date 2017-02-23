<?php

namespace Barcamp\Partners\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Partners extends Controller
{
    public $implement = [
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\ReorderController',
    ];

    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'barcamp.partners.partners',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Barcamp.Partners', 'partners', 'partners');
    }
}
