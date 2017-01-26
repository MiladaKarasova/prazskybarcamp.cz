<?php namespace Barcamp\Talks\Models;

use Model;

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
        $this->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $this->ip_forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        $this->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
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
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $ip_forwarded = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

        return $query->whereIp($ip)->whereIpForwarded($ip_forwarded)->whereUserAgent($user_agent);
    }
}
