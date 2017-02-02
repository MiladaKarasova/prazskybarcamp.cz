<?php
/**
 * Created by PhpStorm.
 * User: tomaskazatel
 * Date: 02.02.17
 * Time: 15:07
 */

namespace Barcamp\Talks\Components;

use AjaxException;
use Cms\Classes\ComponentBase;
use Barcamp\Talks\Facades\TalksFacade;
use App;
use Session;

class OneTalk extends ComponentBase
{

    public $talk;

    public function componentDetails()
    {
        return [
            'name'        => 'Talk',
            'description' => 'Show talk',
        ];
    }

    public function defineProperties()
    {
        return [
            'hash' => [
                'title'     => 'Talk',
                'default'   => '{{ :hash }}',
                'type'      => 'string'
            ]
        ];
    }

    public function onRun()
    {
        $this->talk = $this->page['talk'] = $this->getTalk();
        $this->page['token'] = Session::token();
    }

    protected function getTalk()
    {
        $hash = $this->property('hash');
        $facade = $this->getFacade();

        return $facade->getTalkByHash($hash);

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