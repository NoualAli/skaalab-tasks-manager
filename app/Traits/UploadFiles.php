<?php

namespace App\Traits;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

trait UploadFiles
{
    /**
     * Upload files
     *
     * @param Illuminate\Database\Eloquent\Model $attachable
     * @param array|Illuminate\Http\UploadedFile $files
     * @param string $folder
     *
     * @return void
     */
    public function upload(BaseModel | Model $attachable, array|UploadedFile $files, string $folder = 'media')
    {
        try {
            if (is_array($files)) {
                foreach ($files as $file) {
                    $fileName = $file->hashName();
                    $this->store($attachable, $file, $fileName, $folder);
                }
            } else {
                $fileName = $files->hashName();
                $this->store($attachable, $files, $fileName, $folder);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ]);
        }
    }

    /**
     * Store uploaded file information in database
     *
     * @param Illuminate\Database\Eloquent\Model $attachable
     * @param array|Illuminate\Http\UploadedFile $files
     * @param string $fileName
     * @param string $folder
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function store(Model $attachable, UploadedFile $file, string $fileName, string $folder = 'media')
    {
        return DB::transaction(function () use ($file, $attachable, $fileName, $folder) {
            return $attachable->media()->create([
                'original_name' => $file->getClientOriginalName(),
                'hash_name' => $fileName,
                'folder' => $folder,
                'extension' => $file->getClientOriginalExtension(),
                'mimetype' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        });
    }

    public function move(UploadedFile $file, string $fileName, string $folder = 'media')
    {
        $path = `$folder/$fileName`;
        $file->store($path);
    }
}
