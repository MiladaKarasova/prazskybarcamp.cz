<?php namespace Barcamp\Talks\Components;

use AjaxException;
use App;
use Barcamp\Talks\Facades\TalksFacade;
use Cms\Classes\ComponentBase;
use Session;

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
        $this->page['token'] = Session::token();
    }

    /**
     * On vote action.
     */
    public function onVote()
    {
        // check CSRF token
        if (post('token') !== Session::token()) {
            throw new AjaxException('Platnost stránky vypršela. Odešlete prosím hlasování znovu.');
        }

        // get hash
        $hash = post('hash');
        if (!$hash) {
            throw new AjaxException('Chybný ověřovací kód, zkuste prosím hlasovat znovu!');
        }

        // get talk
        $facade = $this->getFacade();
        $talk = $facade->getTalkByHash($hash);

        // talk does not exists
        if (!$talk) {
            throw new AjaxException('Omlouváme se, ale tato přednáška již neexistuje, nebo ještě není schválená!');
        }

        // vote
        $votes = $talk->addVote();
        if ($votes === false) {
            throw new AjaxException('Omlouváme se, ale lze hlasovat pouze jednou!');
        }
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
