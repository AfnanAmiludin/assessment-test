<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ObjectSentece;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ObjectSentenceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        Storage::fake('public');
    }

    public function test_user_harus_login_untuk_mengakses_endpoint()
    {
        $response = $this->getJson('/api/object-sentences');
        
        $response->assertStatus(401);
    }

    public function test_dapat_mengambil_semua_data_object_sentences()
    {
        ObjectSentece::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/object-sentences');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson(['success' => true]);
    }

    public function test_dapat_mengambil_detail_object_sentence()
    {
        $sentence = ObjectSentece::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/object-sentences/{$sentence->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $sentence->id,
                    'sentence' => $sentence->sentence
                ]
            ]);
    }

    public function test_mengembalikan_404_jika_data_tidak_ditemukan()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/object-sentences/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Data not found'
            ]);
    }

    public function test_dapat_membuat_object_sentence_baru()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $data = [
            'sentence' => 'Seekor kucing sedang bermain',
            'description' => 'Gambar kucing lucu',
            'image' => $file,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/object-sentences', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Data created successfully'
            ]);
    }

    public function test_validasi_gagal_jika_sentence_kosong()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $data = [
            'sentence' => '',
            'image' => $file
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/object-sentences', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation error'
            ]);
    }

    public function test_validasi_gagal_jika_image_tidak_ada()
    {
        $data = [
            'sentence' => 'Test sentence'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/object-sentences', $data);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation error'
            ]);
    }

    public function test_dapat_mengupdate_object_sentence()
    {
        $sentence = ObjectSentece::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg');
        
        $data = [
            'sentence' => 'Seekor kucing sedang bermain',
            'description' => 'Gambar kucing lucu',
            'image' => $file,
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/object-sentences/{$sentence->id}", $data);
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data updated successfully'
            ]);
    }

    public function test_dapat_menghapus_object_sentence()
    {
        $sentence = ObjectSentece::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/object-sentences/{$sentence->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Data deleted successfully'
            ]);
    }

    public function test_mengembalikan_404_ketika_menghapus_data_yang_tidak_ada()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson('/api/object-sentences/999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Data not found'
            ]);
    }
}