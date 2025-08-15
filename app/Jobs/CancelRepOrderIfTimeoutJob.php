<?php

namespace App\Jobs;

use App\Enums\RepOrderStatusEnum;
use App\Models\RepOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CancelRepOrderIfTimeoutJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private RepOrder $repOrder)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->repOrder->status === RepOrderStatusEnum::PENDING) {
            $this->repOrder->update([
                'status' => RepOrderStatusEnum::TIMEOUT
            ]);
        }
    }
}
