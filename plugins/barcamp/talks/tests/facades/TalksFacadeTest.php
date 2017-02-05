<?php namespace Barcamp\Talks\Tests\Facades;

use App;
use Barcamp\Talks\Facades\TalksFacade;
use Barcamp\Talks\Models\Category;
use Barcamp\Talks\Models\Talk;
use Barcamp\Talks\Models\Type;
use PluginTestCase;
use RainLab\User\Models\User;

class TalksFacadeTest extends PluginTestCase
{
    /**
     * Returns tested class.
     *
     * @return TalksFacade
     */
    public function getModel()
    {
        return App::make(TalksFacade::class);
    }

    public function testRegister()
    {
        $facade = $this->getModel();

        // register talk and user
        $data = [
            'category' => 'business',
            'type' => 'speaker',
            'user' => User::first(),
            'talkName' => 'Testing talk name',
            'annotation' => 'Talk annotation',
        ];
        $facade->register($data);

        // find talk
        $talk = Talk::where('name', $data['talkName'])->first();
        $this->assertEquals($data['talkName'], $talk->name);
    }

    public function testCreateUser()
    {
        $facade = $this->getModel();

        // create User
        $data = [
            'email' => 'testing@domain.tld',
            'name' => 'Testing User',
        ];
        $facade->createUser($data);

        // find user
        $user = User::findByEmail($data['email']);
        $this->assertEquals($data['name'], $user->name);
        $this->assertNotEmpty($user->password);
    }

    public function testRecalculateVotes()
    {
        $facade = $this->getModel();

        // add vote and set votes column to zero
        $talk = Talk::first();
        $talk->addVote();
        $talk->votes = 0;

        // get talks with votes
        $onlyWithVotes = true;
        $talks = $facade->getTalks($onlyWithVotes);
        $this->assertEquals(1, $talks->count());

        // recalculate
        $facade->recalculateVotes();

        // check talk if it's recalculated
        $talk = Talk::first();
        $this->assertEquals(1, $talk->votes);
    }

    public function testIsRegistrationApproved()
    {
        $facade = $this->getModel();
        $this->assertTrue($facade->isRegistrationApproved());
    }

    public function testGetTalks()
    {
        $facade = $this->getModel();

        $talks = $facade->getTalks();
        $this->assertEquals(3, $talks->count());

        $onlyWithVotes = true;
        $talks = $facade->getTalks($onlyWithVotes);
        $this->assertEquals(0, $talks->count());
    }

    public function testGetApprovedTalks()
    {
        $facade = $this->getModel();

        $talks = $facade->getApprovedTalks();
        $this->assertEquals(2, $talks->count());
    }

    public function testGetApprovedTalksWithDate()
    {
        $facade = $this->getModel();

        $talks = $facade->getApprovedTalksWithDate();
        $this->assertEquals(0, $talks->count());
    }

    public function testGetTalksLeftCount()
    {
        $facade = $this->getModel();

        $left = $facade->getTalksLeftCount();
        $this->assertEquals(100, $left);
    }

    public function testGetTalkByHash()
    {
        $facade = $this->getModel();

        $talk = Talk::first();
        $talkByHash = $facade->getTalkByHash($talk->hash);

        $this->assertEquals($talk->id, $talkByHash->id);
    }

    public function testGetTalkCategory()
    {
        $model = $this->getModel();
        $type = $model->getTalkCategory('business');
        $this->assertInstanceOf(Category::class, $type);
    }

    public function testGetTalkType()
    {
        $model = $this->getModel();
        $type = $model->getTalkType('speaker');
        $this->assertInstanceOf(Type::class, $type);
    }

    public function testParseSocialNetworksFull()
    {
        $model = $this->getModel();
        $data = "https://www.facebook.com/testing" . "\n";
        $data .= "https://twitter.com/testing" . "\n";
        $data .= "https://www.instagram.com/testing" . "\n";
        $data .= "https://www.linkedin.com/testing" . "\n";
        $data .= "https://www.mysites.com/testing" . "\n";
        $result = $model->parseSocialNetworks($data);

        $this->assertEquals('https://www.facebook.com/testing', $result['link_facebook']);
        $this->assertEquals('https://twitter.com/testing', $result['link_twitter']);
        $this->assertEquals('https://www.instagram.com/testing', $result['link_instagram']);
        $this->assertEquals('https://www.linkedin.com/testing', $result['link_linkedin']);
        $this->assertEquals('https://www.mysites.com/testing', $result['link_web']);
    }

    public function testParseSocialNetworksPartial()
    {
        $model = $this->getModel();
        $data = "facebook.com/testing" . "\n";
        $data .= "mysites.com/testing" . "\n";
        $result = $model->parseSocialNetworks($data);

        $this->assertEquals('https://facebook.com/testing', $result['link_facebook']);
        $this->assertEquals('http://mysites.com/testing', $result['link_web']);
    }

    public function testParseSocialNetworksPartialWithWww()
    {
        $model = $this->getModel();
        $data = "www.facebook.com/testing" . "\n";
        $data .= "www.mysites.com/testing" . "\n";
        $result = $model->parseSocialNetworks($data);

        $this->assertEquals('https://www.facebook.com/testing', $result['link_facebook']);
        $this->assertEquals('http://www.mysites.com/testing', $result['link_web']);
    }

    public function testParseSocialNetworksEmpty()
    {
        $model = $this->getModel();
        $data = '';
        $result = $model->parseSocialNetworks($data);

        $this->assertEmpty($result);
    }
}
