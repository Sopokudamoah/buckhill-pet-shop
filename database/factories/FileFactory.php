<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $filename = fake()->uuid().'.png';
        $file = UploadedFile::fake()->image($filename, 200, 200);

        return [
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientMimeType(),
            'path' => Storage::drive('images')->putFile('', $file),
            'size' => $file->getSize()
        ];
    }
}
