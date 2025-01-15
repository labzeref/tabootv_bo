<?php

namespace App\Http\Controllers;

use App\Models\TempMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TempMediaController extends Controller
{
    public function getUuid(Request $request)
    {
        $tempMedia = TempMedia::create([
            'user_id' => auth()->id(),
            'chunk_count' => $request->total_chunks,
            'file_extension' => $request->file_extension,
        ]);

        $response = [
            'media_uuid' => $tempMedia->uuid,
        ];

        return $this->sendResponse($response);
    }

    public function upload(Request $request)
    {
        $chunkingCompleted = false;
        $tempMedia = TempMedia::where('uuid', $request->media_uuid)->first();

        $chunk = $tempMedia->chunks()->create([
            'index' => $request->index,
            'offset' => $request->offset,
        ]);

        $chunk->addMediaFromRequest('chunk')->toMediaCollection('file');

        $chunk->update(['uploaded' => true]);

        $totalUploadedChunks = DB::table('temp_media_chunks')->where('temp_media_id', $tempMedia->id)->where('uploaded', true)->count();

        //        Log::info("chunk upload");
        //        Log::info("request index $request->index");
        //        Log::info("request offset $request->offset");
        //        Log::info("total chunk $tempMedia->chunk_count");
        //        Log::info("total uploaded chunk $totalUploadedChunks");

        if ($tempMedia->chunk_count === $totalUploadedChunks) {

            //            Log::info("Started merging");

            $directoryPath = storage_path('app/chunk_merging');

            if (! is_dir($directoryPath)) {
                mkdir($directoryPath);
            }

            $mergedFilePath = "$directoryPath/$tempMedia->uuid.$tempMedia->file_extension";

            $mergedFile = fopen($mergedFilePath, 'w');

            foreach ($tempMedia->chunks()->orderBy('index')->get() as $index => $chunk) {
                //                Log::info("merging chunk $index");
                $inputStream = fopen($chunk->getFirstMediaPath('file'), 'r');
                while (! feof($inputStream)) {
                    fwrite($mergedFile, fread($inputStream, 8192)); // Read and write in chunks
                }
                fclose($inputStream);
                //                Log::info("merged chunk $index");
            }

            fclose($mergedFile);

            //            Log::info("merge complete");

            $tempMedia->addMedia($mergedFilePath)->toMediaCollection('file');
            $chunkingCompleted = true;
        }

        $response = [
            'media_uuid' => $tempMedia->uuid,
            'chucking_completed' => $chunkingCompleted,
        ];

        //        Log::info("------------------------------------------");

        return $this->sendResponse($response);
    }

    public function destroy(TempMedia $tempMedia)
    {
        $tempMedia->delete();

        return $this->sendResponse();
    }

    private function isMimetypeVideo(string $mimetype): bool
    {
        return str_starts_with($mimetype, 'video/');
    }
}
