<?php namespace Barcamp\Talks\Components;

use AjaxException;
use App;
use Barcamp\Talks\Facades\TalksFacade;
use Cms\Classes\ComponentBase;

class Talks extends ComponentBase
{
    public $talks;

    public function componentDetails()
    {
        return [
            'name'        => 'Talks',
            'description' => 'Show talks',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * On component run.
     */
    public function onRun()
    {
        $this->talks = $this->page['talks'] = $this->getTalks();
    }

    /**
     * On vote action.
     */
    public function onVote()
    {
        // get hash
        $hash = post('hash');
        if (!$hash) {
            throw new AjaxException(['error' => 'Chybný ověřovací kód, zkuste prosím hlasovat znovu!']);
        }

        // get talk
        $facade = $this->getFacade();
        $talk = $facade->getTalkByHash($hash);

        // talk does not exists
        if (!$talk) {
            throw new AjaxException(['error' => 'Tento talk neexistuje!']);
        }

        // vote
        $talk->vote();
    }

    /**
     * Get talks.
     *
     * @return mixed
     */
    public function getTalks()
    {
        $facade = $this->getFacade();

        return $facade->getApprovedTalks();
    }

    /**
     * Get Talks facade.
     *
     * @return TalksFacade
     */
    private function getFacade()
    {
        return App::make(TalksFacade::class);
    }
}
