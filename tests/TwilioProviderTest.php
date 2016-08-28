<?php

namespace NotificationChannels\Twilio\Test;

use Illuminate\Contracts\Foundation\Application;
use Mockery;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioProvider;
use PHPUnit_Framework_TestCase;
use Services_Twilio as Twilio;
use ArrayAccess;

class TwilioProviderTest extends PHPUnit_Framework_TestCase
{
    /** @var TwilioProvider */
    protected $provider;

    /** @var App */
    protected $app;

    public function setUp()
    {
        parent::setUp();

        $this->app = Mockery::mock(App::class);
        $this->provider = new TwilioProvider($this->app);

        $this->app->shouldReceive('flush');
    }

    /** @test */
    public function it_gives_an_instantiated_twilio_object_when_the_channel_asks_for_it()
    {
        $this->app->shouldReceive('offsetGet')
            ->with('config')
            ->andReturn(['services.twilio' => ['account_sid' => 'sid', 'auth_token' => 'token'], 'services.twilio.from' => 'from']);

        $this->app->shouldReceive('when')->with(TwilioChannel::class)->twice()->andReturn($this->app);

        $this->app->shouldReceive('needs')->with(Twilio::class)->once()->andReturn($this->app);
        $this->app->shouldReceive('needs')->with('$from')->once()->andReturn($this->app);

        $this->app->shouldReceive('give')->with(Mockery::on(function ($twilio) {
            return $twilio === 'from' || $twilio() instanceof Twilio;
        }))->once();

        $this->provider->boot();
    }
}

interface App extends Application, ArrayAccess
{
}
