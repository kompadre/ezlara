<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\Tienda;
use Tests\TestCase;

class ModelTest extends TestCase
{
    public function test_models_instantiate_and_save()
    {
        $p = Producto::findOrCreateByName('test-' . time());
        $this->assertInstanceOf( Producto::class, $p );

        $t = Tienda::findOrCreateByName('test-' . time());
        $this->assertInstanceOf( Tienda::class, $t );

        $t->productos()->attach($p->id, ['cantidad' => 4]);
        $t->productos()->detach();
        $t->delete();
        $p->delete();
    }
}
