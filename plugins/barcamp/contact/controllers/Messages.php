<?php namespace Barcamp\Contact\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Messages extends Controller {

	public $implement = [
		'Backend.Behaviors.ListController'
	];

	public $listConfig = 'config_list.yaml';

	public function __construct()
	{
		parent::__construct();

		BackendMenu::setContext('Barcamp.Contact', 'contact', 'messages');
	}
}
