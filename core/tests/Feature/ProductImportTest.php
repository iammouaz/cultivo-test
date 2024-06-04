<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;


class ProductImportTest extends TestCase
{
    public function testImportAccountFromCsv(): void
    {
        $event = Event::find(14);
        $this->assertNotNull($event);
        $event->products()->delete();

        $this->productService = app('productService');
        $pathCsv = base_path('tests/Feature/Files/products.csv');
        $this->productService->importProductFromCsv($pathCsv,'test',basename($pathCsv));

        $products = Product::query()->where('event_id',14)->get();
        $this->assertEquals(13, $products->count());


    }
}
