<?php

namespace App\Console\Commands;

use App\Actions\Events\ChangeAppointmentNamesAction;
use App\Actions\Events\DeleteUserAppointmentAction;
use App\Services\RabbitMQConnection;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:consume-users-events')]
#[Description('Command description')]
class ConsumeUsersEvents extends Command {

    public function __construct(private RabbitMQConnection $connection, private ChangeAppointmentNamesAction $changeNameAction, private DeleteUserAppointmentAction $deleteUserAppointment) {
        parent::__construct();
    }
    
    public function handle() {
        $channel = $this->connection->channel();
        $channel->basic_consume('users.queue', '', callback:function($msg) use($channel){
            $data = json_decode($msg->body, true);    
            match ($msg->delivery_info['routing_key']) {
                    'user.name_updated' => $this->changeNameAction->execute($data['payload']),
                    'user.deleted' => $this->deleteUserAppointment->execute($data['payload']),
            };
            $channel->basic_ack($msg->delivery_info['delivery_tag']);
        });

        $this->info('Listening for users events...');
        while(true) $channel->wait();
    }
}
