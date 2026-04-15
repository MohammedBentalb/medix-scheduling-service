<?php

namespace App\Console\Commands;

use App\Actions\SendHeartbeatAction;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-heartbeat')]
#[Description('Sends a heartbeat to the discovery service every minute')]
class SendHeartbeat extends Command {

    public function __construct(private SendHeartbeatAction $heartbeatAction) {
        parent::__construct();
    }

    public function handle(): void {
        $response = $this->heartbeatAction->execute();
        $this->info($response->status());
    }
}
