<?php namespace Barcamp\Talks\Models;

use Lang;
use Model;
use System\Models\MailTemplate;
use RainLab\User\Models\User as UserModel;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'barcamp_talks_settings';

    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->talks_count = 100;
        $this->program_ready = false;
    }
}
