<?php

namespace App\Services;

use Illuminate\Support\Str;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher
{
    /**
     * Create a new class instance.
     */
    public function __construct(private RabbitMQConnection $connection) {}

    public function publish(string $topic, array $payload){
        $message = new AMQPMessage(
            json_encode([
                'event_id' => (string) Str::uuid7(),
                'event_type' => $topic,
                'version' => '1.0',
                'timestamp' => now()->toIso8601String(),
                'producer' => config('app.service_name', 'schceduling service'),
                'payload' => $payload
            ]), [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'content_type' => 'application/json'
            ]
        );
        $this->connection->channel()->basic_publish($message, 'scheduling.events', $topic);
    }
}   
