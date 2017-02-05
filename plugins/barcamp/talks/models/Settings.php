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

    public $attachOne = [
        'program' => 'System\Models\File',
        'press' => 'System\Models\File',
        'logo' => 'System\Models\File',
    ];

    public function initSettingsData()
    {
        $this->talks_count = 100;
        $this->registration_approved = true;
        $this->program_ready = false;
    }
}
