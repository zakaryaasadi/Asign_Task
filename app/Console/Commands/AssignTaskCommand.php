<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tookan\Dependency\Singleton;
use Tookan\Service\QueueService;

class AssignTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:assign_task';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $queueService;
    public function __construct()
    {
        parent::__construct();
        $this->queueService = Singleton::Create(QueueService::class);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->queueService = Singleton::Create(QueueService::class);
        if(!$this->queueService->isProcessing()){
            $this->queueService->run();
        }
        return Command::SUCCESS;
    }
}
