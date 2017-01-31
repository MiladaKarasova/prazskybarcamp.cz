<?php namespace Barcamp\Talks\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Categories Back-end Controller.
 */
class Categories extends Controller
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
        'barcamp.talks.categories',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Barcamp.Talks', 'talks', 'categories');
    }

    public function listOverrideColumnValue($record, $columnName)
    {
        if ($columnName == 'color') {
            return '<div style="width:18px;height:18px;background-color:'.$record->color.'"></div>';
        }
    }
}
