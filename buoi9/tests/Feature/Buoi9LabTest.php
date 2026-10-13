<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Buoi9LabTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_articles_but_must_login_to_create(): void
    {
        $article = Article::factory()->create();

        $this->get(route('articles.index'))->assertOk()->assertSee($article->title);
        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('Bạn không phải tác giả của bài viết này.');
        $this->get(route('articles.create'))->assertRedirect(route('login'));
    }

    public function test_store_validation_keeps_old_input_and_vietnamese_messages(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('articles.create'))
            ->followingRedirects()
            ->post(route('articles.store'), [
                'title' => 'Tieu de hop le',
                'body' => 'ngan',
                'tags' => 'laravel',
            ]);

        $response->assertOk();
        $response->assertSee('Tieu de hop le');
        $response->assertSee('Nội dung tối thiểu phải có 10 ký tự');
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_forbidden_word_and_duplicate_title_are_rejected(): void
    {
        $user = User::factory()->create();
        Article::factory()->for($user)->create(['title' => 'Tieu de da co']);

        $this->actingAs($user)
            ->post(route('articles.store'), [
                'title' => 'Bai viet spam hom nay',
                'body' => 'Noi dung du dai de hop le.',
            ])
            ->assertSessionHasErrors('title');

        $this->actingAs($user)
            ->post(route('articles.store'), [
                'title' => 'Tieu de da co',
                'body' => 'Noi dung du dai de hop le.',
            ])
            ->assertSessionHasErrors([
                'title' => 'Tiêu đề đã tồn tại, vui lòng chọn tiêu đề khác',
            ]);
    }

    public function test_valid_article_with_image_is_stored(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('articles.store'), [
                'title' => 'Bai viet co anh minh hoa',
                'body' => 'Noi dung du dai de luu bai viet.',
                'tags' => 'anh, laravel',
                'image' => UploadedFile::fake()->image('minhhoa.png'),
            ])
            ->assertRedirect(route('articles.index'));

        $article = Article::first();
        $this->assertNotNull($article);
        $this->assertSame($user->id, $article->user_id);
        $this->assertSame('anh, laravel', $article->tags);
        Storage::disk('public')->assertExists($article->image_path);

        $this->get(route('articles.index'))->assertSee('Tạo bài viết thành công');
        $this->get(route('articles.show', $article))
            ->assertSee(asset('storage/'.$article->image_path), false);
    }

    public function test_non_image_upload_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('articles.store'), [
                'title' => 'Bai viet tep sai',
                'body' => 'Noi dung du dai de luu bai viet.',
                'image' => UploadedFile::fake()->create('tai-lieu.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('image');
    }

    public function test_only_author_can_update_or_delete(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $article = Article::factory()->for($author)->create([
            'title' => 'Bai cua tac gia',
            'body' => 'Noi dung goc du dai.',
        ]);

        $this->actingAs($other)
            ->put(route('articles.update', $article), [
                'title' => 'Sua trai phep',
                'body' => 'Noi dung bi sua trai phep.',
            ])
            ->assertForbidden();

        $this->actingAs($other)
            ->delete(route('articles.destroy', $article))
            ->assertForbidden();

        $this->actingAs($other)
            ->get(route('articles.show', $article))
            ->assertOk()
            ->assertDontSee('>Sửa<', false);

        $this->actingAs($author)
            ->get(route('articles.show', $article))
            ->assertSee('Sửa');

        $this->actingAs($author)
            ->put(route('articles.update', $article), [
                'title' => 'Bai cua tac gia',
                'body' => 'Noi dung da cap nhat boi tac gia.',
            ])
            ->assertRedirect(route('articles.show', $article));

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'body' => 'Noi dung da cap nhat boi tac gia.',
        ]);

        Article::factory()->for($author)->create(['title' => 'Bai trung tieu de']);

        $this->actingAs($author)
            ->put(route('articles.update', $article), [
                'title' => 'Bai trung tieu de',
                'body' => 'Noi dung van hop le khi trung.',
            ])
            ->assertSessionHasErrors('title');
    }

    public function test_admin_middleware_blocks_non_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);

        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.articles.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Khu vực quản trị');
    }

    public function test_login_route_is_throttled(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'demo@huit.edu.vn',
                'password' => 'wrong-password',
            ])->assertStatus(302);
        }

        $this->post('/login', [
            'email' => 'demo@huit.edu.vn',
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }

    public function test_public_info_api_is_throttled(): void
    {
        for ($i = 0; $i < 60; $i++) {
            $this->getJson('/api/public-info')->assertOk()->assertJson(['status' => 'ok']);
        }

        $this->getJson('/api/public-info')->assertStatus(429);
    }
}
