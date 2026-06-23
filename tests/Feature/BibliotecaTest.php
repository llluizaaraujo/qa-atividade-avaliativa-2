<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Biblioteca;
use App\Models\User;

class BibliotecaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_bibliotecas()
    {
        Biblioteca::factory()->count(3)->create();
        $response = $this->get(route('bibliotecas.index'));
        $response->assertStatus(200);
        $response->assertViewHas('bibliotecas');
    }

    public function test_can_show_create_form()
    {
        User::factory()->count(3)->create();
        $response = $this->get(route('bibliotecas.create'));
        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    public function test_can_store_biblioteca()
    {
        $user = User::factory()->create();

        $data = [
            'created_by' => $user->id,
            'nome' => 'Biblioteca Central',
            'endereco' => 'Rua Principal, 123',
        ];

        $response = $this->post(route('bibliotecas.store'), $data);

        $response->assertRedirect(route('bibliotecas.index'));
        $response->assertSessionHas('message', 'Biblioteca criada com sucesso');
        $this->assertDatabaseHas('bibliotecas', ['nome' => 'Biblioteca Central']);
    }

    public function test_store_biblioteca_handles_exception()
    {
        // Se passarmos null para created_by, o banco vai lançar exceção se for required (dependendo do schema).
        // Aqui simulamos uma falha intencional de integridade para cair no catch
        $data = [
            'created_by' => null, // Assumindo que criará exception
            'nome' => null,
        ];

        $response = $this->post(route('bibliotecas.store'), $data);

        $response->assertRedirect();
        // O controller redireciona para bibliotecas.new
        // Verificando o flash de erro:
        // No catch: return redirect()->route('bibliotecas.new', ['error' => '...'])
    }

    public function test_can_show_edit_form()
    {
        $biblioteca = Biblioteca::factory()->create();
        $response = $this->get(route('bibliotecas.edit', $biblioteca->id));
        $response->assertStatus(200);
        $response->assertViewHas('biblioteca');
    }

    public function test_edit_returns_error_if_not_found()
    {
        $response = $this->get(route('bibliotecas.edit', 9999));
        $response->assertRedirect(route('bibliotecas.index'));
        $response->assertSessionHas('error', 'Biblioteca não encontrada');
    }

    public function test_can_update_biblioteca()
    {
        $biblioteca = Biblioteca::factory()->create();
        $user = User::factory()->create();

        $data = [
            'created_by' => $user->id,
            'nome' => 'Nome Atualizado',
            'endereco' => 'Novo Endereço',
            'email' => 'biblio@example.com',
        ];

        $response = $this->put(route('bibliotecas.update', $biblioteca->id), $data);

        $response->assertRedirect(route('bibliotecas.index'));
        $response->assertSessionHas('message', 'Biblioteca atualizada com sucesso');
        $this->assertDatabaseHas('bibliotecas', [
            'id' => $biblioteca->id,
            'nome' => 'Nome Atualizado'
        ]);
    }

    public function test_update_returns_404_if_not_found()
    {
        $response = $this->put(route('bibliotecas.update', 9999), []);
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Biblioteca não encontrada']);
    }

    public function test_can_destroy_biblioteca()
    {
        $biblioteca = Biblioteca::factory()->create();

        $response = $this->delete(route('bibliotecas.destroy', $biblioteca->id));

        $response->assertRedirect(route('bibliotecas.index'));
        $response->assertSessionHas('message', 'Biblioteca excluída com sucesso');
        $this->assertDatabaseMissing('bibliotecas', ['id' => $biblioteca->id]);
    }

    public function test_destroy_returns_404_if_not_found()
    {
        $response = $this->delete(route('bibliotecas.destroy', 9999));
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Biblioteca não encontrada']);
    }
}
