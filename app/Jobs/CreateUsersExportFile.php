<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateUsersExportFile implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $chunkSize,
        public $folder
    ) {
    }

    public function handle()
    {
        $users = User::query()
            ->take($this->chunkSize)
            ->get();

        Storage::disk('local')->makeDirectory($this->folder);

        return (new FastExcel($this->usersGenerator($users)))
            ->export(storage_path("app/{$this->folder}/users.csv"), function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->id,
                    'email' => $user->id,
                    // ....
                ];
            });
    }

    private function usersGenerator($users)
    {
        foreach ($users as $user) {
            yield $user;
        }
    }
}
