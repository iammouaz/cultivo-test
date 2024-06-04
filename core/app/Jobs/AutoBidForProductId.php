<?php

namespace App\Jobs;

use App\Services\BidService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoBidForProductId implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $product_id;
    /**
     * @var BidService
     */
    private $bidService;

    public function __construct($product_id)
    {
        $this->bidService = app('bidService');
        $this->product_id = $product_id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->bidService->autoBidByProductId($this->product_id);

    }
}
