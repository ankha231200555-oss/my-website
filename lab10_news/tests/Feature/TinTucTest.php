<?php
namespace Tests\Feature;
use App\Models\DanhMuc;
use App\Models\TinTuc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class TinTucTest extends TestCase {
    use RefreshDatabase;
    private function article(array $data = []): TinTuc {
        return TinTuc::create(array_merge(['tieude'=>'Bài kiểm thử', 'noidung'=>'Nội dung', 'ngaydang'=>now()->toDateString()], $data));
    }
    public function test_forms_and_list_render(): void {
        $tin = $this->article();
        $this->get('/admin/tin')->assertOk()->assertSee('Bài kiểm thử');
        $this->get('/admin/tin/create')->assertOk()->assertSee('multipart/form-data', false);
        $this->get('/admin/tin/'.$tin->id.'/edit')->assertOk()->assertSee('Bài kiểm thử');
    }
    public function test_create_without_image_defaults_date(): void {
        $dm= DanhMuc::create(['ten'=>'Công nghệ']);
        $this->post('/admin/tin', ['tieude'=>'Tin mới','noidung'=>'Nội dung','danhmuc_id'=>$dm->id])->assertRedirect('/admin/tin')->assertSessionHas('ok');
        $tin=TinTuc::firstOrFail();
        $this->assertSame(now()->toDateString(),$tin->ngaydang->toDateString());
        $this->assertSame('Công nghệ',$tin->danhMuc->ten);
        $this->assertStringContainsString('no-image.svg',$tin->thumb_url);
    }
    public function test_upload_and_thumbnail(): void {
        Storage::fake('public');
        $this->post('/admin/tin',['tieude'=>'Có ảnh','noidung'=>'Nội dung','hinhanh_up'=>UploadedFile::fake()->image('anh.png')])->assertRedirect('/admin/tin');
        $tin=TinTuc::firstOrFail(); Storage::disk('public')->assertExists($tin->hinhanh_path);
        $this->assertStringContainsString('storage/news/',$tin->thumb_url);
    }
    public function test_replacing_image_cleans_old_image(): void {
        Storage::fake('public');
        Storage::disk('public')->put('news/old.png','old image');
        $tin=$this->article(['hinhanh_path'=>'news/old.png']);
        $this->put('/admin/tin/'.$tin->id,['tieude'=>'Đã sửa','noidung'=>'Nội dung mới','hinhanh_up'=>UploadedFile::fake()->image('new.jpg')])->assertRedirect('/admin/tin');
        Storage::disk('public')->assertMissing('news/old.png');
        Storage::disk('public')->assertExists($tin->fresh()->hinhanh_path);
    }
    public function test_edit_without_upload_preserves_image(): void {
        Storage::fake('public'); Storage::disk('public')->put('news/keep.jpg','image');
        $tin=$this->article(['hinhanh_path'=>'news/keep.jpg']);
        $this->put('/admin/tin/'.$tin->id,['tieude'=>'Tiêu đề mới','noidung'=>'Nội dung','ngaydang'=>''])->assertRedirect('/admin/tin');
        $this->assertSame('news/keep.jpg',$tin->fresh()->hinhanh_path);
        Storage::disk('public')->assertExists('news/keep.jpg');
    }
    public function test_soft_delete_restore_and_permanent_delete(): void {
        Storage::fake('public'); Storage::disk('public')->put('news/a.png','image');
        $tin=$this->article(['hinhanh_path'=>'news/a.png']);
        $this->delete('/admin/tin/'.$tin->id)->assertRedirect('/admin/tin');
        $this->assertSoftDeleted($tin); Storage::disk('public')->assertExists('news/a.png');
        $this->get('/admin/tin')->assertDontSee('Bài kiểm thử');
        $this->get('/admin/tin?trash=1')->assertSee('Bài kiểm thử');
        $this->post('/admin/tin/'.$tin->id.'/restore')->assertRedirect('/admin/tin?trash=1');
        $this->assertNotSoftDeleted($tin);
        $this->delete('/admin/tin/'.$tin->id);
        $this->delete('/admin/tin/'.$tin->id.'/force')->assertRedirect('/admin/tin?trash=1');
        $this->assertDatabaseMissing('tin_tucs',['id'=>$tin->id]); Storage::disk('public')->assertMissing('news/a.png');
    }
    public function test_live_article_cannot_be_force_deleted_or_restored(): void {
        $tin=$this->article();
        $this->delete('/admin/tin/'.$tin->id.'/force')->assertNotFound();
        $this->post('/admin/tin/'.$tin->id.'/restore')->assertNotFound();
        $this->assertDatabaseHas('tin_tucs',['id'=>$tin->id,'deleted_at'=>null]);
    }
    public function test_required_fields_and_lengths_are_validated(): void {
        $this->post('/admin/tin',[])->assertSessionHasErrors(['tieude','noidung']);
        $this->post('/admin/tin',['tieude'=>str_repeat('a',201),'tomtat'=>str_repeat('a',301),'noidung'=>'a'])->assertSessionHasErrors(['tieude','tomtat']);
        $this->assertDatabaseCount('tin_tucs',0);
    }
    public function test_invalid_upload_does_not_create_article(): void {
        Storage::fake('public');
        foreach ([UploadedFile::fake()->create('file.txt',10,'text/plain'), UploadedFile::fake()->image('huge.jpg')->size(2049), UploadedFile::fake()->image('file.gif')] as $file) {
            $this->post('/admin/tin',['tieude'=>'Sai ảnh','noidung'=>'Nội dung','hinhanh_up'=>$file])->assertSessionHasErrors('hinhanh_up');
        }
        $this->assertDatabaseCount('tin_tucs',0);
        $this->assertEmpty(Storage::disk('public')->allFiles());
    }
    public function test_invalid_category_and_date_are_rejected(): void {
        $this->post('/admin/tin',['tieude'=>'A','noidung'=>'B','danhmuc_id'=>999,'ngaydang'=>'not-date'])->assertSessionHasErrors(['danhmuc_id','ngaydang']);
    }
    public function test_search_and_pagination_preserve_query(): void {
        for ($i=0;$i<12;$i++) $this->article(['tieude'=>'Laravel '.$i]);
        $this->article(['tieude'=>'Không trùng']);
        $this->get('/admin/tin?kw=Laravel')->assertOk()->assertDontSee('Không trùng')->assertSee('kw=Laravel', false)->assertSee('page=2', false);
        $response=$this->get('/admin/tin?kw=Laravel&page=2')->assertOk();
        $this->assertCount(2,$response->viewData('rows'));
        // A literal zero must remain a valid search keyword.
        $this->get('/admin/tin?kw=0')->assertSee('Laravel 0')->assertDontSee('Không trùng');
    }
    public function test_deleted_article_is_not_editable_and_unknown_ids_are_404(): void {
        $tin=$this->article(); $tin->delete();
        $this->get('/admin/tin/'.$tin->id.'/edit')->assertNotFound();
        $this->get('/admin/tin/999/edit')->assertNotFound();
    }
    public function test_deleting_category_keeps_article(): void {
        $dm=DanhMuc::create(['ten'=>'Danh mục tạm']); $tin=$this->article(['danhmuc_id'=>$dm->id]);
        $dm->delete(); $this->assertNull($tin->fresh()->danhmuc_id);
    }
}
