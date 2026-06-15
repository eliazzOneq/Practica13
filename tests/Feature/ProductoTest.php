<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoTest extends TestCase { 

    use RefreshDatabase; 

    private function actingAsAdmin() { 
        $admin = User::factory()->create(['rol' => 'admin']); 
        return $this->actingAs($admin, 'sanctum'); 
    } 

    public function test_puede_listar_productos(): void { 
        Producto::factory(5)->create(); 
        $this->actingAsAdmin() 
             ->getJson('/api/productos') 
             ->assertOk() 
             ->assertJsonCount(5, 'data'); 
    } 

    public function test_puede_crear_producto(): void { 
        $this->actingAsAdmin() 
             ->postJson('/api/productos', [ 
                 'nombre' => 'Laptop Dell', 
                 'precio' => 1299.99, 
                 'stock'  => 10, 
             ]) 
             ->assertCreated() 
             ->assertJsonPath('data.nombre', 'Laptop Dell'); 

        $this->assertDatabaseHas('productos', 
            ['nombre' => 'Laptop Dell']); 
    } 

  

    public function test_cliente_no_puede_eliminar(): void { 
        $cliente  = User::factory()->create(['rol' => 'cliente']); 
        $producto = Producto::factory()->create(); 
        $this->actingAs($cliente, 'sanctum') 
             ->deleteJson("/api/productos/{$producto->id}") 
             ->assertForbidden(); 
    } 
} 