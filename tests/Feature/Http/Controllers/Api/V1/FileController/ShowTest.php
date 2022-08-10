<?php


use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Models\File;

test('user can view an image', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $file = UploadedFile::fake()->image('payment-receipt.png');

    $response = apiTest()->withToken($token)->post(route('api.v1.file.upload'), [
        'file' => $file
    ]);

    $response->assertStatus(200);
    $data = $response->json(['data']);

    $uuid = $data['uuid'];

    $response = apiTest()->withToken($token)->get(route('api.v1.file.show', $uuid));

    $response->assertStatus(200);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('file-show-200.json', $response->content());
});


test('user can download an image', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $file = UploadedFile::fake()->image('payment-receipt.png');

    $response = apiTest()->withToken($token)->post(route('api.v1.file.upload'), [
        'file' => $file
    ]);

    $response->assertStatus(200);
    $data = $response->json(['data']);

    $uuid = $data['uuid'];

    $response = apiTest()->withToken($token)->get(route('api.v1.file.show', ['file' => $uuid, 'download' => true]));

    $response->assertStatus(200);
    $response->assertDownload();

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('file-show-200.json', $response->content());
});


test('user cannot show a non-existing image', function () {
    $user = User::factory()->create();
    $token = $user->createToken()->plainTextToken;

    $file = UploadedFile::fake()->create('payment-receipt.pdf');

    $uuid = fake()->uuid();

    $this->assertDatabaseCount(File::where('uuid', '=', $uuid), 0);

    $response = apiTest()->withToken($token)->get(route('api.v1.file.show', $uuid), [
        'file' => $file
    ]);

    $response->assertStatus(404);

    #The line below is of generating response samples for API documentation
//    Storage::drive('responses')->put('file-show-404.json', $response->content());
});
