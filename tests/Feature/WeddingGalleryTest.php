<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WeddingGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@weddinggallery.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'client@weddinggallery.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/weddings');
        $response->assertRedirect('/login');

        $responseCreate = $this->get('/weddings/create');
        $responseCreate->assertRedirect('/login');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@weddinggallery.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/weddings');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_non_admin_cannot_login_to_admin_portal(): void
    {
        $response = $this->post('/login', [
            'email' => 'client@weddinggallery.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_admin_can_create_wedding_with_all_required_fields(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post('/weddings', [
            'couple_name' => 'Budi & Citra',
            'slug' => 'budi-dan-citra',
            'wedding_date' => '2026-11-20',
            'theme' => 'Warm Minimalist',
            'cover_drive_file_id' => 'cover_budi_citra_123',
            'quote' => 'Menemukanmu adalah takdir, mencintaimu adalah pilihan abadi.',
            'pin' => '9988',
            'pin_enabled' => '1',
            'guest_download' => '1',
        ]);

        $this->assertDatabaseHas('weddings', [
            'couple_name' => 'Budi & Citra',
            'slug' => 'budi-dan-citra',
            'theme' => 'Warm Minimalist',
            'cover_drive_file_id' => 'cover_budi_citra_123',
            'quote' => 'Menemukanmu adalah takdir, mencintaimu adalah pilihan abadi.',
            'pin' => '9988',
            'pin_enabled' => 1,
            'guest_download' => 1,
        ]);
    }

    public function test_guest_access_shows_pin_page_when_pin_enabled(): void
    {
        $wedding = Wedding::create([
            'couple_name' => 'Rangga & Cinta',
            'slug' => 'rangga-dan-cinta',
            'theme' => 'Sunset Gold',
            'pin' => '5678',
            'pin_enabled' => true,
            'guest_download' => true,
            'is_published' => true,
            'drive_folder_id' => 'mock_folder_id',
            'quote' => 'Dua hati, satu perjalanan.',
        ]);

        // Public route /{slug}
        $response = $this->get('/' . $wedding->slug);
        $response->assertStatus(200);
        $response->assertSee('Masukkan PIN Akses');
        $response->assertSee('Rangga &amp; Cinta', false);
        // Should contain Sunset Gold theme classes
        $response->assertSee($wedding->theme_config['body_bg']);
    }

    public function test_guest_pin_verification_success_and_failure(): void
    {
        $wedding = Wedding::create([
            'couple_name' => 'Rangga & Cinta',
            'slug' => 'rangga-dan-cinta',
            'theme' => 'Elegance Emerald',
            'pin' => '4321',
            'pin_enabled' => true,
            'guest_download' => true,
            'is_published' => true,
            'drive_folder_id' => 'mock_folder_id',
        ]);

        // 1. Wrong PIN
        $wrongResponse = $this->post('/gallery/' . $wedding->slug . '/verify', [
            'pin' => '0000',
        ]);
        $wrongResponse->assertSessionHasErrors(['pin']);

        // 2. Correct PIN
        $correctResponse = $this->post('/gallery/' . $wedding->slug . '/verify', [
            'pin' => '4321',
        ]);
        $correctResponse->assertRedirect(route('client.gallery', $wedding->slug));
        $this->assertTrue(session("client_pin_verified_{$wedding->slug}"));
    }

    public function test_guest_gallery_displays_hero_cover_and_quote(): void
    {
        $wedding = Wedding::create([
            'couple_name' => 'Faris & Nadia',
            'slug' => 'faris-dan-nadia',
            'theme' => 'Modern Classic',
            'cover_drive_file_id' => 'drive_cover_photo_999',
            'quote' => 'Bersama dalam setiap detak waktu.',
            'pin' => '1122',
            'pin_enabled' => true,
            'guest_download' => true,
            'is_published' => true,
            'drive_folder_id' => 'folder_123',
        ]);

        // Bypass PIN via session
        session(["client_pin_verified_{$wedding->slug}" => true]);

        $response = $this->get(route('client.gallery', $wedding->slug));
        $response->assertStatus(200);
        $response->assertSee('Faris &amp; Nadia', false);
        $response->assertSee('Bersama dalam setiap detak waktu.');
        // Check cover drive image presence
        $response->assertSee('drive_cover_photo_999');
    }

    public function test_conditional_guest_download(): void
    {
        // 1. Wedding with guest_download = TRUE
        $weddingWithDownload = Wedding::create([
            'couple_name' => 'Kevin & Jessica',
            'slug' => 'kevin-dan-jessica',
            'theme' => 'Elegance Emerald',
            'pin' => '1234',
            'pin_enabled' => false, // no PIN required
            'guest_download' => true,
            'is_published' => true,
            'drive_folder_id' => 'folder_k_j',
        ]);

        $photo = Photo::create([
            'wedding_id' => $weddingWithDownload->id,
            'drive_file_id' => 'photo_drive_101',
            'nama_file' => 'wedding_moment.jpg',
            'thumbnail_url' => 'https://example.com/thumb.jpg',
        ]);

        $responseGallery = $this->get(route('client.gallery', $weddingWithDownload->slug));
        $responseGallery->assertSee('Unduh');

        $downloadResponse = $this->postJson(route('client.download.async', [
            'slug' => $weddingWithDownload->slug,
            'fileId' => $photo->drive_file_id,
        ]));
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertJson(['success' => true]);

        // 2. Wedding with guest_download = FALSE
        $weddingNoDownload = Wedding::create([
            'couple_name' => 'Toni & Maya',
            'slug' => 'toni-dan-maya',
            'theme' => 'Warm Minimalist',
            'pin' => '1234',
            'pin_enabled' => false,
            'guest_download' => false, // Download Disabled
            'is_published' => true,
            'drive_folder_id' => 'folder_t_m',
        ]);

        $photo2 = Photo::create([
            'wedding_id' => $weddingNoDownload->id,
            'drive_file_id' => 'photo_drive_102',
            'nama_file' => 'private_moment.jpg',
        ]);

        $responseGalleryNoDl = $this->get(route('client.gallery', $weddingNoDownload->slug));
        // Download button should NOT be in the HTML
        $responseGalleryNoDl->assertDontSee('title="Unduh Foto Ini (Asinkron)"', false);

        // Async API should return 403 Forbidden
        $blockedDownloadResponse = $this->postJson(route('client.download.async', [
            'slug' => $weddingNoDownload->slug,
            'fileId' => $photo2->drive_file_id,
        ]));
        $blockedDownloadResponse->assertStatus(403);
    }

    public function test_admin_can_permanently_delete_wedding_and_all_associated_data(): void
    {
        $this->actingAs($this->admin);

        $wedding = Wedding::create([
            'couple_name' => 'Dimas & Anisa',
            'slug' => 'dimas-dan-anisa',
            'wedding_date' => '2026-12-12',
            'theme' => 'Modern Classic',
            'drive_folder_id' => 'folder_dimas_anisa',
        ]);

        $photo = Photo::create([
            'wedding_id' => $wedding->id,
            'drive_file_id' => 'photo_dimas_1',
            'nama_file' => 'dimas_anisa_1.jpg',
        ]);

        $this->assertDatabaseHas('weddings', ['id' => $wedding->id, 'slug' => 'dimas-dan-anisa']);
        $this->assertDatabaseHas('photos', ['id' => $photo->id]);

        $response = $this->delete(route('weddings.destroy', $wedding->slug));
        $response->assertRedirect(route('weddings.index'));
        $response->assertSessionHas('success');

        // Verify wedding and all related data are completely gone
        $this->assertDatabaseMissing('weddings', ['id' => $wedding->id, 'slug' => 'dimas-dan-anisa']);
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);

        // Verify public slug route returns 404
        $publicResponse = $this->get('/dimas-dan-anisa');
        $publicResponse->assertStatus(404);
    }

    public function test_admin_can_create_new_user(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('users.store'), [
            'name'                  => 'Klien Baru Budi',
            'email'                 => 'budi@example.com',
            'role'                  => 'user',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'name'  => 'Klien Baru Budi',
            'role'  => 'user',
        ]);
    }

    public function test_admin_can_delete_user_but_cannot_delete_self(): void
    {
        $this->actingAs($this->admin);

        // Cannot delete self
        $responseSelf = $this->delete(route('users.destroy', $this->admin->id));
        $responseSelf->assertRedirect(route('users.index'));
        $responseSelf->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        // Can delete other user
        $responseOther = $this->delete(route('users.destroy', $this->regularUser->id));
        $responseOther->assertRedirect(route('users.index'));
        $responseOther->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $this->regularUser->id]);
    }
}
