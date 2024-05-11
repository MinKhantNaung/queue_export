<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Http\File;
use App\Jobs\AppendMoreUsers;
use App\Jobs\CreateUsersExportFile;
use App\Models\Excel;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function export()
    {
        $chunkSize = 10000;
        $usersCount = User::count();
        $numberOfChunks = ceil($usersCount / $chunkSize);

        $folder = now()->toDateString() . '-' . str_replace(':', '-', now()->toTimeString());

        $batches = [
            new CreateUsersExportFile($chunkSize, $folder)
        ];

        if ($usersCount > $chunkSize) {
            $numberOfChunks = $numberOfChunks - 1;
            for ($numberOfChunks; $numberOfChunks > 0; $numberOfChunks--) {
                $batches[] = new AppendMoreUsers($numberOfChunks, $chunkSize, $folder);
            }
        }

        Bus::batch($batches)
            ->name('Export Users')
            ->then(function (Batch $batch) use ($folder) {
                $path = "exports/{$folder}/users.csv";
                // upload file to s3
                $file = storage_path("app/{$folder}/users.csv");
                Storage::disk('public')->put($path, file_get_contents($file));

                Excel::create([
                    'path' => $path
                ]);
            })
            ->catch(function (Batch $batch, Throwable $e) {
                logger($e);
            })
            ->finally(function (Batch $batch) use ($folder) {
                // delete local file
                Storage::disk('local')->deleteDirectory($folder);
            })
            ->dispatch();

        return redirect()->back();
    }
}
