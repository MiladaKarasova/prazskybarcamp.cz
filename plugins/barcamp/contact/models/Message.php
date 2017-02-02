<?php namespace Barcamp\Contact\Models;

use App;
use Carbon\Carbon;
use Config;
use Model;
use October\Rain\Database\Traits\SoftDeleting as SoftDeletingTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;
use Request;

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
     * Before create filter - add IP address and user_agent attributes.
     */
    public function beforeCreate()
    {
        $this->locale = App::getLocale();

        $this->ip = Request::server('REMOTE_ADDR');
        $this->ip_forwarded = Request::server('HTTP_X_FORWARDED_FOR');
        $this->user_agent = Request::server('HTTP_USER_AGENT');
    }

    /**
     * Set machine scope.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeMachine($query)
    {
        $ip = Request::server('REMOTE_ADDR');
        $ip_forwarded = Request::server('HTTP_X_FORWARDED_FOR');
        $user_agent = Request::server('HTTP_USER_AGENT');

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
