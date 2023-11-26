<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class MediaController extends Controller
{
    /**
     * Display paginated files liste
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse|array
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse|array
    {
        // $media = DB::table('media')->select('attachable_id', 'attachable_type', 'id')->get();
        // foreach ($media as $file) {
        //     DB::table('has_media')->insert([
        //         'attachable_id' => $file->attachable_id,
        //         'attachable_type' => $file->attachable_type,
        //         'media_id' => $file->id
        //     ]);
        // }

        $media = getMedia();
        $filter = request('filter', null);
        $fetchFilters = request()->has('fetchFilters');
        $search = request('search', null);
        $sort = request('sort', null);
        $perPage = request('perPage', 10);

        try {
            if ($fetchFilters) {
                return $this->filters($media);
            }

            if ($sort) {
                $media = $media->reorder()->sortByMultiple($sort);
            }

            if ($search) {
                $media = $media->search(['m.original_name'], $search);
            }

            if ($filter) {
                $media = $this->filter($media, $filter);
            }

            $media = $media->paginate($perPage)->onEachSide(1);
            return MediaResource::collection($media);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ]);
        }
    }

    /**
     * @param string $media
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $media): \Illuminate\Http\JsonResponse
    {
        $media = getMedia($media)->reorder();

        $media = MediaResource::collection($media->get());
        $all = request()->has('all');
        if (!$all) {
            return response()->json($media->first());
        }
        return response()->json($media);
    }

    /**
     * Store files into database and storage
     *
     * @param \App\Http\Requests\Media\StoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|array|null
     */
    public function store(StoreRequest $request): \Illuminate\Http\JsonResponse|array|null
    {
        return DB::transaction(function () use ($request) {
            $files = request()->file();
            $key = is_array($files) && count($files) ? array_keys($files)[0] : $files;
            $media = $request->validated()[$key];
            $uploadedFiles = is_array($media) ? [] : null;

            if (is_array($media)) {
                foreach ($media as $file) {
                    array_push($uploadedFiles, $this->storeInDatabase($request, $file));
                }
            } else {
                $uploadedFiles = [$this->storeInDatabase($request, $media)];
            }
            return $uploadedFiles;
        });
        try {
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload file to public storage (with support for image size optimization)
     *
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return array
     */
    private function uploadFile(UploadedFile $file, string $folder): array
    {
        $hashName = $file->hashName();
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimetype = $file->getClientMimeType();
        $size = $file->getSize();
        $storageFolder = 'public/' . $folder;
        $storageFolder = Str::endsWith($storageFolder, '/') ? substr($storageFolder, 0, strlen($storageFolder) - 1) : $storageFolder;
        if (!Storage::directoryExists($storageFolder)) {
            Storage::createDirectory($storageFolder);
        }
        Storage::putFileAs($storageFolder, $file, $hashName);
        return compact('hashName', 'originalName', 'folder', 'storageFolder', 'extension', 'mimetype', 'size', 'file');
    }

    /**
     * @param \App\Http\Requests\Media\StoreRequest $request
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return stdClass|\Illuminate\Http\JsonResponse
     */
    private function storeInDatabase(StoreRequest $request, UploadedFile $file): stdClass|\Illuminate\Http\JsonResponse
    {
        try {
            $folder = $request->folder;
            extract($this->uploadFile($file, $folder));
            $attachable = $request->has('attachable') ? $request->attachable : [];
            $attachableId = $attachable && isset($request->attachable['id']) && !empty($request->attachable['id']) ? $request->attachable['id'] : null;
            $attachableType = $attachable && isset($request->attachable['type']) && !empty($request->attachable['type']) ? $request->attachable['type'] : null;

            $id = Str::uuid();

            // $payload = $this->getPayload($attachableId, $attachableType, $folder);

            $insertedFile = DB::table('media')->insert([
                'id' => $id,
                'original_name' => $originalName,
                'hash_name' => $hashName,
                'folder' => $folder,
                'extension' => $extension,
                'mimetype' => $mimetype,
                'size' => $size,
                'uploaded_by_id' => auth()->user()->id,
                'created_at' => now(),
                // 'payload' => $payload,
            ]);
            $media = DB::table('has_media')->insert([
                'attachable_id' => $attachableId,
                'attachable_type' => $attachableType,
                'media_id' => $id,
            ]);

            $file = getSingleMedia($id);

            return $file;
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Destroy media from storage and database
     *
     * @param Media $media
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Media $media): \Illuminate\Http\JsonResponse
    {
        try {
            $message = DELETE_SUCCESS;
            $status = true;
            $code = 200;
            // delete file from database
            if ($media->delete()) {
                // delete file from storage
                if (Storage::exists($media->path)) {
                    if (!Storage::delete($media->path)) {
                        $message = "Une erreur s'est produite lors de la tentative de suppression du fichier";
                        $status = false;
                        $code = 500;
                    }
                } elseif (file_exists('storage/' . $media->path)) {
                    if (!unlink('storage/' . $media->path)) {
                        $message = "Une erreur s'est produite lors de la tentative de suppression du fichier";
                        $status = false;
                        $code = 500;
                    }
                }
            } else {
                $message = "Une erreur s'est produite lors de la tentative de suppression des informations du fichier";
                $status = false;
                $code = 500;
            }

            return response()->json([
                'payload' => $media,
                'message' => $message,
                'status' => $status,
            ], $code);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Destroy multiple media from storage and database
     *
     * @param string $media
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyMultiple(string $media): \Illuminate\Http\JsonResponse
    {
        try {
            $media = Media::whereIn('id', explode(',', $media))->get();
            $deleteStatus = collect([]);
            foreach ($media as $file) {
                // delete file from database
                if ($file->delete()) {
                    // delete file from storage
                    if (Storage::exists($file->path)) {
                        $line = new stdClass;
                        $line->file = $file;
                        $line->deleted = Storage::delete($file->path);
                        $deleteStatus->push($line);
                    }
                } elseif (file_exists('storage/' . $file->path)) {
                    $line = new stdClass;
                    $line->file = $file;
                    $line->deleted = unlink('storage/' . $file->path);
                    $deleteStatus->push($line);
                }
            }
            $hasErrors = $deleteStatus->filter(fn ($item) => !$item->deleted);
            $status = $hasErrors->count();
            $message = $status ? DELETE_ERROR : DELETE_SUCCESS;
            $code = $status ? 500 : 200;
            return response()->json([
                'payload' => $deleteStatus,
                'message' => $message,
                'status' => !$status,
            ], $code);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Get media filters data
     *
     * @param Builder $media
     *
     * @return array
     */
    public function filters(Builder $media): array
    {
        $users = $media
            ->distinct()
            ->select('m.uploaded_by_id', 'u.first_name', 'u.last_name', 'u.id')
            ->reorder()
            ->get()->map(function ($item) {
                $item->id = $item->id ?: '-';
                $item->full_name = $item->first_name && $item->last_name ? ucfirst(strtolower($item->first_name)) . ' ' . ucfirst(strtolower($item->last_name)) : 'Système';
                unset($item->first_name, $item->last_name, $item->uploaded_by_id);
                return $item;
            });
        $users = formatForSelect(recursivelyToArray($users), 'full_name');
        return compact('users');
    }

    /**
     * Filter data
     *
     * @param Builder $media
     * @param array $filter
     *
     * @return Builder
     */
    public function filter(Builder $media, array $filter): Builder
    {
        if (isset($filter['users'])) {
            $values = explode(',', $filter['users']);
            $values = array_map(function ($item) {
                $item = $item == '-' ? null : $item;
                return $item;
            }, $values);
            $media = $media->whereIn('uploaded_by_id', $values);
            if (in_array(null, $values)) {
                $media = $media->orWhereNull('uploaded_by_id');
            }
        }

        if (isset($filter['types'])) {
            $values = explode(',', $filter['types']);
            foreach ($values as $value) {
                $media = $media->orWhereRaw($value);
            }
        }

        return $media;
    }

    private function getPayload(string|int $attachableId, string $attachableType, string $folder)
    {
        // TODO
    }
}
