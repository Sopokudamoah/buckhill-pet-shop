<?php


use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

it('has brand', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'metadata' => ['brand' => Brand::factory()->create()->uuid]
    ]);

    $this->assertNotNull($product->brand());
});

it('has no brand', function () {
    $product = Product::factory()->for(Category::factory())->create([
        'metadata' => []
    ]);

    $this->assertNull($product->brand());
});
