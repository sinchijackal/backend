<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Book;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function can_get_all_books()
  {
    $book = Book::factory(4)->create();

    $this->getJson(route('books.index'))
      ->assertJsonFragment([
        'title' => $book[0]->title,
      ])->assertJsonFragment([
        'title' => $book[1]->title,
      ]);
  }

  /** @test */
  function can_get_one_book()
  {
    $book = Book::factory()->create();

    $this->getJson(route('books.show', $book))
      ->assertJsonFragment([
        'title' => $book->title,
      ]);
  }

  /** @Test */
  function can_create_book()
  {
    $this->postJson(route('books.store'), [
    ])->assertJsonValidationErrorFor('title');

    $this->postJson(route('books.store'), [
      'title' => 'My New Book',
    ])->assertJsonFragment([
      'title' => 'My New Book',
    ]);

    $this->assertDatabaseHas('books', [
      'title' => 'My New Book',
    ]);
  }

  /** @Test */
  function can_update_book()
  {
    $book = Book::factory()->create();

    $this->patchJson(route('books.update', $book), [
    ])->assertJsonValidationErrorFor('title');

    $this->patchJson(route('books.update', $book), [
      'title' => 'Edited book'
    ])->assertJsonFragment([
      'title' => 'Edited book'
    ]);
    $this->assertDatabaseHas('books', [
      'title' => 'Edited book'
    ]);
  }

  /** @Test */
  function can_delete_book()
  {
    $book = Book::factory()->create();

    $this->deleteJson(route('books.destroy', $book))
      ->assertNoContent();

    $this->assertDatabaseCount('books', 0);
  }
}