<?php

namespace Tests\Feature\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\BuckhillBaseTesting;

class FilesControllerTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    public function test_file_upload_success()
    {
        Storage::fake(storage_path('app/public/uploaded_images'));

        $this->post('/api/v1/file', [
            'file' => UploadedFile::fake()->image('test.png')
        ], ['Authorization' => $this->getAuthTokenForAdmin()]);

        $file = $this->decodeResponseJson()['message'];
        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('media', ['uuid' => $file['uuid']]);
    }

    public function test_file_upload_failure_unauthorized()
    {
        Storage::fake(storage_path('app/public/uploaded_images'));

        $this->post('/api/v1/file', [
            'file' => UploadedFile::fake()->image('test.png')
        ]);

        $this->assertResponseStatus(401);
    }

    public function test_get_file_success()
    {
        Storage::fake(storage_path('app/public/uploaded_images'));

        $this->post('/api/v1/file', [
            'file' => UploadedFile::fake()->image('test.png')
        ], ['Authorization' => $this->getAuthTokenForAdmin()]);

        $file = $this->decodeResponseJson()['message'];

        $this->get('/api/v1/file/' . $file['uuid']);

        $this->response->assertHeader('Content-Disposition', 'attachment; filename=' . $file['file_name']);
    }

    public function test_get_file_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->get('/api/v1/file/132442342');
    }
}
