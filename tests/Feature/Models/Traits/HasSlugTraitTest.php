<?php


use App\Models\Brand;
use App\Models\Traits\HasSlug;

test('slug generated when model is created', function () {
    $this->assertTrue(in_array(HasSlug::class, class_uses(Brand::class)));

    $brand = Brand::factory()->make();

    $this->assertNull($brand->slug);
    $brand->save();

    $this->assertNotNull($brand->slug);
});


test('slug generated when model is updated', function () {
    $this->assertTrue(in_array(HasSlug::class, class_uses(Brand::class)));

    $brand = Brand::factory()->create();

    $title = fake()->company();
    $slug = Str::slug($title);

    $this->assertNotEquals($brand->title, $title);
    $this->assertNotEquals($brand->slug, $slug);

    $brand->update(['title' => $title]);

    $brand->refresh();

    $this->assertEquals($brand->title, $title);
    $this->assertEquals($brand->slug, $slug);
});
