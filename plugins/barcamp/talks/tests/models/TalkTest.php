<?php namespace Barcamp\Talks\Tests\Models;

use App;
use Barcamp\Talks\Models\Talk;
use PluginTestCase;

class TalkTest extends PluginTestCase
{
    public function testVote()
    {
        $talk = Talk::find(1);

        // vote
        $votes = $talk->addVote();
        $this->assertEquals(1, $votes);

        // vote again
        $votes = $talk->addVote();
        $this->assertEquals(false, $votes);
    }

    public function testCrossVote()
    {
        $talk = Talk::find(1);

        // vote
        $votes = $talk->addVote();
        $this->assertEquals(1, $votes);

        // another
        $talk = Talk::find(2);

        // vote
        $votes = $talk->addVote();
        $this->assertEquals(1, $votes);
    }
}
