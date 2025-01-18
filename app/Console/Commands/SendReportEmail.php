<?php

namespace App\Console\Commands;

use App\Repository\SaleRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReportEmail extends Command
{
    //public function __construct(SaleReo) {
    //    $this->var = $var;
    //}
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-report-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    }
}
