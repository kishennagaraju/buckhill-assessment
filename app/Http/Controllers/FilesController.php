<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUpload;
use App\Models\File;
use Illuminate\Support\Str;

class FilesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  FileUpload  $request
     * @return \Illuminate\Http\JsonResponse
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

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
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
}
