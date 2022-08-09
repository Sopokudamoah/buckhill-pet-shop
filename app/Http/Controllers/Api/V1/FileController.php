<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\V1\FileUploadRequest;
use App\Http\Resources\V1\BaseApiResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @group Files endpoint
 *
 * This endpoint handles the upload and download methods for the Files.
 */
class FileController extends Controller
{
    /**
     * Upload file
     *
     * @authenticated
     *
     * @responseFile status=200 storage/responses/file-upload-200.json
     * @responseFile status=422 scenario="when validation fails" storage/responses/file-upload-422.json
     */
    public function upload(FileUploadRequest $request)
    {
        /** @var UploadedFile $uploaded_file */
        $uploaded_file = $request->validated(['file']);

        $file = File::create([
            'name' => $uploaded_file->getClientOriginalName(),
            'type' => $uploaded_file->getClientMimeType(),
            'path' => Storage::drive('images')->putFile('', $uploaded_file),
            'size' => $uploaded_file->getSize()
        ]);

        return (new BaseApiResource())->message("File uploaded successfully")->data(['uuid' => $file->uuid]);
    }

    public function download(Request $request)
    {
    }
}
