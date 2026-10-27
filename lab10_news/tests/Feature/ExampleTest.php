<?php
namespace Tests\Feature;
use Tests\TestCase;
class ExampleTest extends TestCase {
    public function test_home_redirects_to_news_admin(): void { $this->get('/')->assertRedirect('/admin/tin'); }
}
