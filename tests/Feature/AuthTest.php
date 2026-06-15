class AuthTest extends TestCase { 

    use RefreshDatabase; 

    public function test_usuario_puede_registrarse(): void { 
        $response = $this->postJson('/api/register', [ 
            'name'                  => 'Juan López', 
            'email'                 => 'juan@test.com', 
            'password'              => 'password123', 
            'password_confirmation' => 'password123', 
        ]); 

        $response->assertStatus(201) 
                 ->assertJsonStructure(['token', 'user']); 
        $this->assertDatabaseHas('users', ['email' => 'juan@test.com']); 
    } 

    public function test_login_con_credenciales_incorrectas(): void { 
        $response = $this->postJson('/api/login', [ 
            'email'    => 'noexiste@test.com', 
            'password' => 'wrongpass', 
        ]);

        $response->assertStatus(401); 
    } 

} 