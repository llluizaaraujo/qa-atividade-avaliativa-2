<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Autor;

class AutorTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_autores()
    {
        Autor::factory()->count(3)->create();
        $response = $this->get(route('autores.index'));
        $response->assertStatus(200);
        $response->assertViewHas('autores');
    }

    public function test_can_show_create_form()
    {
        $response = $this->get(route('autores.create'));
        $response->assertStatus(200);
    }

    public function test_can_store_autor()
    {
        $data = [
            'nome' => 'Machado de Assis',
            'nacionalidade' => 'Brasileiro',
            'data_nascimento' => '1839-06-21',
        ];

        $response = $this->post(route('autores.store'), $data);

        $response->assertRedirect(route('autores.index'));
        $response->assertSessionHas('success', 'Autor criado com sucesso.');
        $this->assertDatabaseHas('autores', ['nome' => 'Machado de Assis']);
    }

    public function test_cannot_store_autor_without_nome()
    {
        $data = [
            'nacionalidade' => 'Brasileiro',
        ];

        $response = $this->post(route('autores.store'), $data);

        $response->assertSessionHasErrors('nome');
        $this->assertDatabaseMissing('autores', ['nacionalidade' => 'Brasileiro']);
    }

    public function test_can_show_edit_form()
    {
        $autor = Autor::factory()->create();
        $response = $this->get(route('autores.edit', $autor->id));
        $response->assertStatus(200);
        $response->assertViewHas('autor');
    }

    public function test_can_update_autor()
    {
        $autor = Autor::factory()->create();

        $data = [
            'nome' => 'Nome Atualizado',
            'nacionalidade' => 'Brasileiro',
            'data_nascimento' => '1990-01-01',
        ];

        $response = $this->put(route('autores.update', $autor->id), $data);

        $response->assertRedirect(route('autores.index'));
        $response->assertSessionHas('success', 'Autor atualizado com sucesso.');
        $this->assertDatabaseHas('autores', ['id' => $autor->id, 'nome' => 'Nome Atualizado']);
    }

    public function test_cannot_update_autor_with_invalid_data()
    {
        $autor = Autor::factory()->create();

        $data = [
            'nome' => '', // Inválido
        ];

        $response = $this->put(route('autores.update', $autor->id), $data);

        $response->assertSessionHasErrors('nome');
    }

    public function test_destroy_autor_fails_because_not_implemented()
    {
        $autor = Autor::factory()->create();
        // A rota destroy existe pelo Route::resource, mas o método não existe no controller
        $response = $this->delete(route('autores.destroy', $autor->id));
        
        // Isso deve retornar 500 BadMethodCallException
        $response->assertStatus(500);
    }
}
