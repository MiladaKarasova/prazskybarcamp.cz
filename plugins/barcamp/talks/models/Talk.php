<?php namespace Barcamp\Talks\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete as SoftDeleteTrait;
use October\Rain\Database\Traits\Validation as ValidationTrait;
use Request;
use Str;

/**
 * Talk Model.
 */
class Talk extends Model
{
    use SoftDeleteTrait;

    use ValidationTrait;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'barcamp_talks_talks';

    /**
     * @var array Model rules.
     */
    public $rules = [
        'name' => 'required|min:10',
        'hash' => 'unique:barcamp_talks_talks',
        'annotation' => 'required',
        'approved' => 'boolean',
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'user_id', 'category_id', 'type_id', 'name', 'annotation', 'note',
    ];

    /**
     * @var array Datetime fields.
     */
    public $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['voted'];

    /**
     * Has many relationship.
     *
     * @var array
     */
    public $hasMany = [
        'vote' => 'Barcamp\Talks\Models\Vote',
    ];

    /**
     * Belongs to relationships.
     *
     * @var array
     */
    public $belongsTo = [
        'category' => 'Barcamp\Talks\Models\Category',
        'type' => 'Barcamp\Talks\Models\Type',
        'user' => [
            'RainLab\User\Models\User',
            'scope' => 'isActivated',
        ],
    ];

    /**
     * Before create reservation.
     */
    public function beforeCreate()
    {
        $this->hash = $this->getUniqueHash($this->name);
        $this->ip = Request::server('REMOTE_ADDR');
        $this->ip_forwarded = Request::server('HTTP_X_FORWARDED_FOR');
        $this->user_agent = Request::server('HTTP_USER_AGENT');
    }

    /**
     * Vote for the talk.
     *
     * @return int|bool
     */
    public function addVote()
    {
        // check if some votes exists
        if ($this->getVotedAttribute() !== false) {
            return false;
        }

        // create vote
        $this->vote()->create();

        // increment redundant counter
        $this->increaseVotes();

        return $this->votes;
    }

    /**
     * Increment vote counter.
     */
    public function increaseVotes()
    {
        $this->votes += 1;
        $this->save();
    }

    /**
     * Recalculate all votes.
     */
    public function recalculateVotes()
    {
        $votes = $this->vote()->count();
        if ($this->votes != $votes) {
            $this->votes = $votes;
            $this->save();
        }
    }

    /**
     * Fetch only approved talks.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsApproved($query)
    {
        return $query
            ->where('approved', true)
            ->whereHas('user', function ($user) {
                $user->isActivated();
            });
    }

    /**
     * Fetch only approved talks with date.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeHasDate($query)
    {
        return $query->isApproved()->whereNotNull('date');
    }

    /**
     * Fetch only waiting talks.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsWaiting($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Get voted attribute - if current user voted or not.
     *
     * @return bool
     */
    public function getVotedAttribute()
    {
        $votes = $this->vote()->fromTheSameMachine()->first();

        return $votes !== null;
    }

    /**
     * Generate unique number for each talk. So you can reference particular talk by hash instead of internal ID.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getUniqueHash($name)
    {
        return substr(md5($name . Str::random(8)), 0, 24);
    }
}
