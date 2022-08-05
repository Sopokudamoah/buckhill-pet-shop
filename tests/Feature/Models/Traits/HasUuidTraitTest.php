<?php


use App\Models\Traits\HasUuid;
use App\Models\User;

test('uuid generated when model is created', function () {
    $this->assertTrue(in_array(HasUuid::class, class_uses(User::class)));

    $user = User::factory()->make();

    $this->assertNull($user->uuid);
    $user->save();

    $this->assertNotNull($user->uuid);
});


test('can find model by uuid', function () {
    $user = User::factory()->create();

    $model = User::findByUuid($user->uuid);
    $this->assertModelExists($model);
});
