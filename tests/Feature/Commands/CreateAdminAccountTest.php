<?php


use App\Models\Admin;

test('admin account is created', function () {
    $this->artisan('admin:create');

    $admin = Admin::whereEmail('admin@buckhill.co.uk')->first();

    $this->assertModelExists($admin);
});
