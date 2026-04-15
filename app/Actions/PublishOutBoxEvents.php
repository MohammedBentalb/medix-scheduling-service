<?php

namespace App\Actions;

use App\Enums\OutboxStatusEnum;
use App\Models\OutboxEvent;
use App\Services\RabbitMQPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class PublishOutBoxEvents implements ShouldQueue {
    use Queueable;

    public function handle(RabbitMQPublisher $publisher) {
        OutboxEvent::where('status', OutboxStatusEnum::PENDING)->limit(100)->get()->each(
            function(OutboxEvent $event) use ($publisher){
                try{
                    $publisher->publish($event->topic, $event->payload);
                    $event->update(['status' => OutboxStatusEnum::PUBLISHED]);
                }catch(Throwable $th){
                    Log::error('failed tp publish to outbox');
                    $event->increment('attempts');
                    if($event->attempts >= 5) $event->update(['status' => OutboxStatusEnum::FAILED]);
                }
            }
        );
    }
}
