<?php namespace Barcamp\Talks\Models;

use Model;
use Request;

/**
 * Vote Model.
 */
class Vote extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'barcamp_talks_votes';

    /**
     * @var array Datetime fields.
     */
    public $dates = ['created_at', 'updated_at'];

    /**
     * Belongs to relationships.
     *
     * @var array
     */
    public $belongsTo = [
        'talk' => 'Barcamp\Talks\Models\Talk',
    ];

    /**
     * Before vote create.
     */
    public function beforeCreate()
    {
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
    public function scopeFromTheSameMachine($query)
    {
        $ip_addr = Request::server('REMOTE_ADDR');
        $ip_forwarded = Request::server('HTTP_X_FORWARDED_FOR');
        $user_agent = Request::server('HTTP_USER_AGENT');

        return $query->whereIp($ip_addr)->whereIpForwarded($ip_forwarded)->whereUserAgent($user_agent);
    }
}
