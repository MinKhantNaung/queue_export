<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AppendMoreUsers implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $chunkIndex,
        public $chunkSize,
        public $folder
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::query()
            ->skip($this->chunkIndex * $this->chunkSize)
            ->take($this->chunkSize)
            ->get()
            ->map(function ($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                ];
            });

        $file = storage_path("app/{$this->folder}/users.csv");
        $open = fopen($file, 'a+');
        foreach ($users as $user) {
            fputcsv($open, $user);
        }
        fclose($open);
    }
}
