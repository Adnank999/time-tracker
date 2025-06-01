<?php

namespace App\Jobs;

use App\Mail\LongTimeLogAlert;
use App\Models\TimeLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLogDurationAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $log;
    public function __construct(TimeLog $log)
    {
        $this->log = $log;
    }

    /**
     * Execute the job.
     */
     public function handle(): void
    {
        $project = $this->log->project;

        if (!$project || !$project->freelancer_id) {
            return;
        }

        $freelancer = $project->freelancer;

        if ($freelancer && $freelancer->email) {
            $log = $this->log; 

            Mail::send('emails.alert', ['log' => $log], function ($message) use ($freelancer) {
                $message->to($freelancer->email)
                        ->subject('Long Time Log Alert');
            });
        }
    }
}
