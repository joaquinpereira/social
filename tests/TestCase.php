<?php

namespace Tests;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertClassUsesTrait($trait, $class)
    {
        $this->assertArrayHasKey(
            $trait,
            class_uses($class),
            "{$class} class must use {$trait} trait"
        );
    }

    protected function assertDontBroadcastToCurrentUser($event,$socketId = 'socket-id')
    {
        $this->assertInstanceOf(ShouldBroadcast::class, $event);

        $this->assertEquals(
            $socketId, //Genrated by Broadcast::shouldReceived('socket')->andReturn('socket-id');
            $event->socket,
            'The event '. get_class($event) .' must call the method "dontBroadcastToCurrentUser" in the constructor'
        );
    }
    protected function assertEventChaneltype($chanelType, $event)
    {
        $types = [
            'public' => Channel::class,
            'private' => PrivateChannel::class,
            'presence' => PresenceChannel::class
        ];

        $this->assertEquals($types[$chanelType], get_class($event->broadcastOn()));
    }

    protected function assertEventChanelName($chanelName, $event)
    {
        $this->assertEquals($chanelName, $event->broadcastOn()->name);
    }
}
