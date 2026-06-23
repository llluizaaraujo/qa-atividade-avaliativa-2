<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Livro;
use App\Models\Autor;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_livros()
    {
        Livro::factory()->count(3)->create();
        $response = $this->get(route('livros.index'));
        $response->assertStatus(200);
        $response->assertViewHas('livros');
    }

    public function test_can_show_livro()
    {
        $livro = Livro::factory()->create();
        $response = $this->get(route('livros.show', $livro->id));
        $response->assertStatus(200);
        $response->assertViewHas('livro');
    }

    public function test_can_show_create_form()
    {
        Autor::factory()->count(3)->create();
        $response = $this->get(route('livros.create'));
        $response->assertStatus(200);
        $response->assertViewHas('autores');
    }

    public function test_can_store_livro()
    {
        $autor = Autor::factory()->create();

        $data = [
            'autor_id' => $autor->id,
            'titulo' => 'Livro de Teste',
            'isbn' => '123-456-789',
            'data_publicacao' => '2020-01-01',
        ];

        $response = $this->post(route('livros.store'), $data);

        $response->assertRedirect(route('livros.index'));
        $this->assertDatabaseHas('livros', ['titulo' => 'Livro de Teste']);
    }

    public function test_can_show_edit_form()
    {
        $livro = Livro::factory()->create();
        $response = $this->get(route('livros.edit', $livro->id));
        $response->assertStatus(200);
        $response->assertViewHas('livro');
        $response->assertViewHas('autores');
    }

    public function test_can_update_livro()
    {
        $livro = Livro::factory()->create();
        $autor = Autor::factory()->create();

        $data = [
            'autor_id' => $autor->id,
            'titulo' => 'Livro Atualizado',
            'isbn' => '987-654-321',
            'data_publicacao' => '2022-02-02',
        ];

        $response = $this->put(route('livros.update', $livro->id), $data);

        $response->assertRedirect(route('livros.index'));
        $this->assertDatabaseHas('livros', [
            'id' => $livro->id,
            'titulo' => 'Livro Atualizado'
        ]);
    }

    public function test_can_destroy_livro()
    {
        $livro = Livro::factory()->create();

        $response = $this->delete(route('livros.destroy', $livro->id));

        $response->assertRedirect(route('livros.index'));
        $this->assertDatabaseMissing('livros', ['id' => $livro->id]);
    }
}
