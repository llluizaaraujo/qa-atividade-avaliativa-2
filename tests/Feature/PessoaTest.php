<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pessoa;

class PessoaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_pessoas()
    {
        Pessoa::factory()->count(3)->create();
        $response = $this->get(route('pessoas.index'));
        $response->assertStatus(200);
        $response->assertViewHas('pessoas');
    }

    public function test_can_show_create_form()
    {
        $response = $this->get(route('pessoas.create'));
        $response->assertStatus(200);
    }

    public function test_can_store_pessoa()
    {
        $data = [
            'name' => 'Fulano de Tal',
            'email' => 'fulano@example.com',
            'telefone' => '11999999999',
            'matricula' => '123456',
            'password' => 'senha123',
            'confirmPassword' => 'senha123',
        ];

        $response = $this->post(route('pessoas.store'), $data);

        $response->assertRedirect(route('pessoas.index'));
        $response->assertSessionHas('message', 'Pessoa criada com sucesso!');
        $this->assertDatabaseHas('pessoas', ['email' => 'fulano@example.com']);
    }

    public function test_cannot_store_pessoa_with_mismatched_passwords()
    {
        $data = [
            'name' => 'Ciclano',
            'email' => 'ciclano@example.com',
            'telefone' => '11999999999',
            'matricula' => '654321',
            'password' => 'senha123',
            'confirmPassword' => 'senhaDIFERENTE',
        ];

        $response = $this->post(route('pessoas.store'), $data);

        $response->assertRedirect(); // redirect back
        $response->assertSessionHas('error', 'As senhas não coincidem!');
        $this->assertDatabaseMissing('pessoas', ['email' => 'ciclano@example.com']);
    }

    public function test_can_show_edit_form()
    {
        $pessoa = Pessoa::factory()->create();
        $response = $this->get(route('pessoas.edit', $pessoa->id));
        $response->assertStatus(200);
        $response->assertViewHas('pessoa');
    }

    public function test_edit_form_returns_error_if_not_found()
    {
        $response = $this->get(route('pessoas.edit', 9999));
        $response->assertRedirect(route('pessoas.index'));
        $response->assertSessionHas('error', 'Pessoa não encontrada');
    }

    public function test_can_update_pessoa()
    {
        $pessoa = Pessoa::factory()->create();

        $data = [
            'name' => 'Nome Atualizado',
            'email' => 'atualizado@example.com',
            'telefone' => '11888888888',
            'matricula' => '111111',
            'password' => 'novaSenha',
            'confirmPassword' => 'novaSenha',
        ];

        $response = $this->put(route('pessoas.update', $pessoa->id), $data);

        $response->assertRedirect(route('pessoas.index'));
        $response->assertSessionHas('message', 'Pessoa atualizada com sucesso!');
        $this->assertDatabaseHas('pessoas', [
            'id' => $pessoa->id, 
            'email' => 'atualizado@example.com'
        ]);
    }

    public function test_update_pessoa_fails_with_mismatched_passwords()
    {
        $pessoa = Pessoa::factory()->create([
            'email' => 'original@example.com'
        ]);

        $data = [
            'name' => 'Nome',
            'email' => 'novo@example.com',
            'telefone' => '11',
            'matricula' => '22',
            'password' => 'senha',
            'confirmPassword' => 'errada',
        ];

        $response = $this->put(route('pessoas.update', $pessoa->id), $data);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'As senhas não coincidem!');
        $this->assertDatabaseHas('pessoas', [
            'id' => $pessoa->id, 
            'email' => 'original@example.com' // Should remain original
        ]);
    }

    public function test_destroy_pessoa_is_empty_and_does_not_delete()
    {
        $pessoa = Pessoa::factory()->create();
        
        $response = $this->delete(route('pessoas.destroy', $pessoa->id));
        
        $response->assertStatus(200); // Because it returns an empty response (method is empty)
        $this->assertDatabaseHas('pessoas', ['id' => $pessoa->id]); // Not deleted
    }
}
