<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BasicAuth;
use App\Http\Requests\FileUpload;
use App\Models\File;
use Illuminate\Support\Str;

class FilesController extends Controller
{

    public function __construct()
    {
        $this->middleware(BasicAuth::class, ['only' => ['store']]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/file/{uuid}",
     *     summary="Retrieve Single File by UUID",
     *     operationId="retrieveSingleFile",
     *     tags={"Files"},
     *     @OA\Parameter(
     *         description="Unique Identifier of File",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function show(string $uuid)
    {
        $fileDetails = File::query()->where('uuid', '=', $uuid)->firstOrFail();

        return response()->download(
            storage_path('app/public/') . $fileDetails->path,
            $fileDetails->file_name,
            [],
            'attachment'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/file",
     *     summary="Create File",
     *     operationId="createFile",
     *     security={{"bearerAuth": {}}},
     *     tags={"Files"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(FileUpload $request)
    {
        if ($request->hasFile('file')) {
            $ext = $request->file('file')->getClientOriginalExtension();
            $fileName = md5(time()) . "." . $ext;
            $filePath = $request->file('file')->storePubliclyAs('uploaded_images', $fileName, 'public');
        }

        if ($file = File::query()->create([
            'uuid' => Str::uuid(),
            'name' => pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME),
            'path' => $filePath,
            'file_name' => $fileName,
            'mime_type' => $request->file('file')->getMimeType(),
            'size' => $request->file('file')->getSize(),
        ])) {
            return response()->json([
                'success' => true,
                'message' => $file->toArray()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Could Not Upload File'
        ])->setStatusCode(500);
    }
}
