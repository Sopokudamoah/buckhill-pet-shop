<?php


use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Models\File;

test('admin can upload an image', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $file = UploadedFile::fake()->image('payment-receipt.png');

    $response = apiTest()->withToken($token)->post(route('api.v1.file.upload'), [
       'file' => $file
   ]);

    $response->assertStatus(200);
    $data = $response->json(['data']);

    $uuid = $data['uuid'];
    $this->assertNotNull($uuid);

    $file = File::findByUuid($uuid);

    $this->assertModelExists($file);

    $this->assertFileExists(Storage::drive('images')->path($file->path));

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('file-upload-200.json', $response->content());
});


test('admin cannot upload a pdf', function () {
    $user = User::factory()->isAdmin()->create();
    $token = $user->createToken()->plainTextToken;

    $file = UploadedFile::fake()->create('payment-receipt.pdf');

    $response = apiTest()->withToken($token)->post(route('api.v1.file.upload'), [
        'file' => $file
    ]);

    $response->assertStatus(422);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('file-upload-422.json', $response->content());
});
