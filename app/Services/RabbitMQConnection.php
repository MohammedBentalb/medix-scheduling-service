<?php

namespace App\Services;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Throwable;

class RabbitMQConnection {

    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    
    public function __construct() {
        try{
            $this->connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST', 'rabbit'),
                env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASSWORD', 'guest'),
                env('RABBITMQ_VHOST', '/'),
            );
            $this->channel = $this->connection->channel();
            $this->channel->exchange_declare('scheduling.events', 'topic', false, true, false);
            $this->channel->queue_declare('appointments.queue', false, true, false, false);
            $this->channel->queue_bind('appointments.queue', 'scheduling.events', 'appointment.booked');
            $this->channel->queue_bind('appointments.queue', 'scheduling.events', 'appointment.deleted');

            // users queue
            $this->channel->exchange_declare('users.events', 'topic', false, true, false);
            $this->channel->queue_declare('users.queue', false, true, false, false);
            $this->channel->queue_bind('users.queue', 'users.events', 'user.name_updated');
            $this->channel->queue_bind('users.queue', 'users.events', 'user.deleted');
        }catch(Throwable $th){
            throw new \RuntimeException('failed connnecting to RabbitMQ', 0, $th);
        }
    }


    public function channel(){
        return $this->channel;
    }
}
