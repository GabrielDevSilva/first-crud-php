<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

class UsersCSVData implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

  public $header;
  public $data;

  /**
   * Create a new job instance.
   */
  public function __construct($data, $header)
  {
    $this->data = $data;
    $this->header = $header;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    foreach ($this->data as $user) {
      $userInput = array_combine($this->header, $user);
      User::create($userInput);
    }
  }
}
