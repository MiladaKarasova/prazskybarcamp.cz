<?php namespace Barcamp\Contact\Models;

use App;
use Carbon\Carbon;
use Config;
use Model;
use October\Rain\Database\Traits\SoftDeleting as SoftDeletingTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;

/**
 * Class Message.
 *
 * @package Barcamp\Contact\Models
 */
class Message extends Model
{
    use SoftDeletingTrait;

    use ValidationTrait;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'barcamp_contact_messages';

    public $rules = [
        'name' => 'required|between:3,100',
        'email' => 'required|email',
        'message' => 'required|min:5|max:3000',
    ];

    public $customMessages = [
        'name.required' => 'Musíte vyplnit Vaše jméno a příjmení.',
        'email.required' => 'Napište nám prosím i Váš e-mail.',
        'message.required' => 'Text zprávy musí mít alespoň 5 znaků.',
    ];

    public $attributeNames = [
        'name' => 'Jméno a příjmení',
        'message' => 'Vaše zpráva',
        'email' => 'E-mail',
        'phone' => 'Telefon',
        'time' => 'Čas',
    ];

    protected $dates = ['deleted_at'];

    /** @var array Values are fields accessible to mass assignment. */
    protected $fillable = [
        'name', 'email', 'address', 'phone', 'time', 'message', 'locale',
    ];

    /**
     * Before create filter
     * - add IP address and user_agent attributes
     */
    public function beforeCreate()
    {
        $this->locale = App::getLocale();

        $this->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $this->ip_forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        $this->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * Set machine scope
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeMachine($query)
    {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $ip_forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

        return $query->whereIp($ip)->whereIpForwarded($ip_forwarded)->whereUserAgent($user_agent);
    }

    /**
     * If some message exists in last one minute.
     *
     * @return bool
     */
    public function isExistInLastTime()
    {
        // protection time
        $time = Config::get('barcamp.contact::config.protection_time', '-30 seconds');
        $timeLimit = Carbon::parse($time)->toDateTimeString();

        // try to find some message
        $item = self::machine()->where('created_at', '>', $timeLimit)->first();

        return $item !== null;
    }
}
