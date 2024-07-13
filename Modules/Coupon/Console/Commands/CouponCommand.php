<?php

namespace Modules\Coupon\Console\Commands;

use Illuminate\Console\Command;

class CouponCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CouponCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coupon Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
