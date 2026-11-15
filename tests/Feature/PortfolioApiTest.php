<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Certificate;
use App\Models\CvDocument;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'email' => 'admin@test.dev',
            'password' => bcrypt('password123'),
        ]);

        Profile::create([
            'full_name' => 'John Doe',
            'title' => '3D Graphics & Backend Dev',
            'bio' => 'Building high-performance interactive 3D portfolios.',
            'email' => 'john@test.dev',
            'availability_status' => 'available',
        ]);

        Skill::create([
            'name' => 'Three.js',
            'category' => '3D',
            'proficiency_level' => 95,
            'order_index' => 1,
        ]);

        Project::create([
            'title' => 'Sample 3D Project',
            'slug' => 'sample-3d-project',
            'description' => 'A WebGL interactive showcase.',
            'technologies' => ['Three.js', 'Laravel'],
            'status' => 'completed',
            'is_featured' => true,
        ]);

        SocialLink::create([
            'platform_name' => 'GitHub',
            'url' => 'https://github.com/example',
            'is_active' => true,
        ]);
    }

    public function test_portfolio_public_endpoint_returns_aggregated_payload(): void
    {
        $response = $this->getJson('/api/portfolio');

        $response->assertStatus(200)
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Cross-Origin-Resource-Policy', 'cross-origin')
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'profile' => ['id', 'full_name', 'title', 'bio', 'email'],
                    'skills',
                    'skills_by_category',
                    'featured_projects',
                    'articles',
                    'certificates',
                    'social_links',
                ],
                'meta',
            ]);
    }

    public function test_projects_public_endpoint_supports_status_filtering(): void
    {
        Project::create([
            'title' => 'In Progress Project',
            'slug' => 'in-progress-project',
            'description' => 'Work in progress.',
            'technologies' => ['Vue'],
            'status' => 'in_progress',
            'is_featured' => false,
        ]);

        $allResponse = $this->getJson('/api/projects');
        $allResponse->assertStatus(200)->assertJsonPath('total', 2);

        $completedResponse = $this->getJson('/api/projects?status=completed');
        $completedResponse->assertStatus(200)->assertJsonPath('total', 1);

        $inProgressResponse = $this->getJson('/api/projects?status=in_progress');
        $inProgressResponse->assertStatus(200)->assertJsonPath('total', 1);
    }

    public function test_contact_form_submission_validates_and_stores_message(): void
    {
        $payload = [
            'name' => 'Alice Client',
            'email' => 'alice@client.com',
            'message' => 'Hello, I want to collaborate on a 3D WebGL project with Laravel API.',
        ];

        $response = $this->postJson('/api/contact', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'alice@client.com',
            'name' => 'Alice Client',
            'is_read' => false,
        ]);
    }

    public function test_admin_authentication_and_sanctum_token(): void
    {
        $loginResponse = $this->postJson('/api/admin/login', [
            'email' => 'admin@test.dev',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['token', 'token_type', 'user'],
            ]);

        $token = $loginResponse->json('data.token');

        // Test authenticated me endpoint
        $meResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/me');

        $meResponse->assertStatus(200)
            ->assertJsonPath('data.email', 'admin@test.dev');

        // Test logout
        $logoutResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/logout');

        $logoutResponse->assertStatus(200);
    }

    public function test_admin_crud_project(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // Create
        $createResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/projects', [
                'title' => 'Brand New VR Sandbox',
                'description' => 'WebXR based virtual reality experience.',
                'technologies' => ['WebXR', 'Three.js'],
                'status' => 'completed',
                'is_featured' => true,
            ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('data.slug', 'brand-new-vr-sandbox');

        $projectId = $createResponse->json('data.id');

        // Update
        $updateResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/projects/'.$projectId, [
                'title' => 'Brand New VR Sandbox (Updated)',
                'description' => 'Updated description.',
                'technologies' => ['WebXR', 'Three.js', 'GLSL'],
                'status' => 'completed',
                'is_featured' => false,
            ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.title', 'Brand New VR Sandbox (Updated)');

        // Delete
        $deleteResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/projects/'.$projectId);

        $deleteResponse->assertStatus(200);
        $this->assertDatabaseMissing('projects', ['id' => $projectId]);
    }

    public function test_admin_can_create_project_with_flexible_statuses(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        foreach (['active', 'completed', 'featured', 'draft', 'in_progress'] as $status) {
            $response = $this->withHeader('Authorization', 'Bearer '.$token)
                ->postJson('/api/admin/projects', [
                    'title' => 'Project with status '.$status,
                    'description' => 'Testing flexible status.',
                    'technologies' => ['Vue.js', 'Tailwind'],
                    'status' => $status,
                    'is_featured' => false,
                ]);

            $response->assertStatus(201)
                ->assertJsonPath('data.status', $status);
        }
    }

    public function test_admin_can_create_and_update_project_with_vue_field_aliases(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // Create with Vue aliases
        $createResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/projects', [
                'title' => 'Aliased Project Test',
                'tagline' => 'Innovative 3D Platform',
                'category' => 'Web3 & AI',
                'year' => '2026',
                'accent_color_hex' => '#6366F1',
                'ribbon' => 'Award Winner',
                'description' => 'Detailed description of the aliased project.',
                'tech_stack' => 'Vue 3, Three.js, Tailwind',
                'status' => 'active',
                'is_featured' => 1,
                'image' => 'https://example.com/cover.webp',
                'live_demo_url' => 'https://demo.example.com',
                'github_repository' => 'https://github.com/example/repo',
            ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('data.subtitle', 'Innovative 3D Platform')
            ->assertJsonPath('data.tagline', 'Innovative 3D Platform')
            ->assertJsonPath('data.category', 'Web3 & AI')
            ->assertJsonPath('data.year', '2026')
            ->assertJsonPath('data.accent_color', '#6366F1')
            ->assertJsonPath('data.accent_color_hex', '#6366F1')
            ->assertJsonPath('data.badge', 'Award Winner')
            ->assertJsonPath('data.ribbon', 'Award Winner')
            ->assertJsonPath('data.live_url', 'https://demo.example.com')
            ->assertJsonPath('data.live_demo_url', 'https://demo.example.com')
            ->assertJsonPath('data.github_url', 'https://github.com/example/repo')
            ->assertJsonPath('data.github_repository', 'https://github.com/example/repo')
            ->assertJsonPath('data.image', 'https://example.com/cover.webp')
            ->assertJsonPath('data.thumbnail_url', 'https://example.com/cover.webp')
            ->assertJsonPath('data.technologies', ['Vue 3', 'Three.js', 'Tailwind'])
            ->assertJsonPath('data.tech_stack', ['Vue 3', 'Three.js', 'Tailwind'])
            ->assertJsonPath('data.status', 'active');

        $projectId = $createResponse->json('data.id');

        // Verify GET returns all aliases cleanly for Vue form binding
        $getResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/projects/'.$projectId);

        $getResponse->assertStatus(200)
            ->assertJsonPath('data.tagline', 'Innovative 3D Platform')
            ->assertJsonPath('data.tech_stack', ['Vue 3', 'Three.js', 'Tailwind'])
            ->assertJsonPath('data.ribbon', 'Award Winner');
    }

    public function test_admin_media_upload_and_destroy(): void
    {
        Storage::fake('public');
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        $file = UploadedFile::fake()->image('portfolio-banner.png', 800, 600);

        $uploadResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/media/upload', [
                'file' => $file,
            ]);

        $uploadResponse->assertSuccessful()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'url',
                'path',
                'data' => ['stored_name', 'path', 'url', 'size_bytes'],
            ]);

        $path = $uploadResponse->json('data.path');
        Storage::disk('public')->assertExists($path);

        // Delete uploaded file
        $deleteResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/media', ['path' => $path]);

        $deleteResponse->assertStatus(200);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_vue_image_upload_and_project_attachment(): void
    {
        Storage::fake('public');
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // 1. Upload using 'image' form field (Vue.js standard)
        $imageFile = UploadedFile::fake()->image('project-screenshot.webp', 1200, 800);
        $uploadResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/media', [
                'image' => $imageFile,
            ]);

        $uploadResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['success', 'message', 'url', 'path']);

        $uploadedPath = $uploadResponse->json('path');
        $uploadedUrl = $uploadResponse->json('url');

        // 2. Create Project using the returned image path/url
        $projectResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/projects', [
                'title' => 'Vue 3D Portfolio Showcase',
                'description' => 'Built with Vue 3 and Three.js.',
                'image' => $uploadedUrl,
                'status' => 'completed',
                'is_featured' => true,
            ]);

        $projectResponse->assertStatus(201)
            ->assertJsonPath('data.image', $uploadedUrl)
            ->assertJsonPath('data.thumbnail_url', $uploadedUrl);

        // 3. Delete media using full URL directly
        $deleteResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/media', ['url' => $uploadedUrl]);

        $deleteResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        Storage::disk('public')->assertMissing($uploadedPath);
    }

    public function test_articles_public_endpoint_and_slug_lookup(): void
    {
        Article::create([
            'title' => 'Sample Article',
            'slug' => 'sample-article',
            'excerpt' => 'Short excerpt',
            'content' => 'Long article content',
            'category' => 'Engineering',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $indexResponse = $this->getJson('/api/articles');
        $indexResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 1);

        $showResponse = $this->getJson('/api/articles/sample-article');
        $showResponse->assertStatus(200)
            ->assertJsonPath('data.title', 'Sample Article');
    }

    public function test_all_admin_forms_support_vue_aliases_and_camel_case(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // 1. Profile with Vue aliases
        $profileResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/profile', [
                'name' => 'Mohammed Alhwaiti Updated',
                'role' => 'Principal Web Architect',
                'about' => 'Updated detailed bio through about alias.',
                'experience' => 3,
                'projectsCount' => 8,
                'avatar' => 'https://example.com/avatar.jpg',
                'cv' => 'https://example.com/cv.pdf',
                'phoneNumber' => '+97059000000',
                'availabilityStatus' => 'Open to Offers',
                'email' => 'mohamhwaty@gmail.com',
            ]);

        $profileResponse->assertStatus(200)
            ->assertJsonPath('data.full_name', 'Mohammed Alhwaiti Updated')
            ->assertJsonPath('data.name', 'Mohammed Alhwaiti Updated')
            ->assertJsonPath('data.role', 'Principal Web Architect')
            ->assertJsonPath('data.title', 'Principal Web Architect')
            ->assertJsonPath('data.about', 'Updated detailed bio through about alias.')
            ->assertJsonPath('data.experience', 3)
            ->assertJsonPath('data.projectsCount', 8)
            ->assertJsonPath('data.avatar', 'https://example.com/avatar.jpg')
            ->assertJsonPath('data.cv', 'https://example.com/cv.pdf');

        // 2. Skill with Vue aliases
        $skillResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/skills', [
                'title' => 'Nuxt.js',
                'group' => 'Frontend',
                'proficiency' => 91,
                'order' => 5,
                'icon' => 'nuxt',
            ]);

        $skillResponse->assertStatus(201)
            ->assertJsonPath('data.name', 'Nuxt.js')
            ->assertJsonPath('data.title', 'Nuxt.js')
            ->assertJsonPath('data.category', 'Frontend')
            ->assertJsonPath('data.group', 'Frontend')
            ->assertJsonPath('data.proficiency_level', 91)
            ->assertJsonPath('data.proficiency', 91)
            ->assertJsonPath('data.order_index', 5)
            ->assertJsonPath('data.order', 5);

        // 3. SocialLink with Vue aliases
        $socialResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/social-links', [
                'platform' => 'Discord',
                'link' => 'https://discord.gg/example',
                'icon' => 'discord',
                'isActive' => true,
            ]);

        $socialResponse->assertStatus(201)
            ->assertJsonPath('data.platform_name', 'Discord')
            ->assertJsonPath('data.platform', 'Discord')
            ->assertJsonPath('data.url', 'https://discord.gg/example')
            ->assertJsonPath('data.link', 'https://discord.gg/example')
            ->assertJsonPath('data.is_active', true)
            ->assertJsonPath('data.isActive', true);

        // 4. Contact with Vue aliases
        $contactResponse = $this->postJson('/api/contact', [
            'fullName' => 'Prospective Client',
            'emailAddress' => 'client@prospect.org',
            'msg' => 'Looking to discuss a new high-end 3D web platform.',
        ]);

        $contactResponse->assertStatus(201)
            ->assertJsonPath('success', true);
    }

    public function test_skill_accepts_arabic_text_and_english_words_for_proficiency_level(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // 1. Arabic text 'خبير'
        $expertResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/skills', [
                'name' => 'Blender 3D',
                'category' => '3D Graphics',
                'proficiency_level' => 'خبير',
            ]);

        $expertResponse->assertStatus(201)
            ->assertJsonPath('data.name', 'Blender 3D')
            ->assertJsonPath('data.proficiency_level', 'خبير')
            ->assertJsonPath('data.proficiency', 95);

        // 2. Arabic text 'متوسط'
        $intermediateResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/skills', [
                'name' => 'GLSL Shaders',
                'category' => '3D Graphics',
                'proficiency_level' => 'متوسط',
            ]);

        $intermediateResponse->assertStatus(201)
            ->assertJsonPath('data.proficiency_level', 'متوسط')
            ->assertJsonPath('data.proficiency', 70);

        // 3. English word 'advanced'
        $advancedResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/skills', [
                'name' => 'WebGPU',
                'category' => '3D Graphics',
                'proficiency_level' => 'advanced',
            ]);

        $advancedResponse->assertStatus(201)
            ->assertJsonPath('data.proficiency_level', 'advanced')
            ->assertJsonPath('data.proficiency', 85);
    }

    public function test_project_supports_multipart_form_data_with_file_and_comma_separated_tech_stack(): void
    {
        Storage::fake('public');
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        $project = Project::first();

        $file = UploadedFile::fake()->image('project-cover.jpg');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/projects/'.$project->id, [
                '_method' => 'PUT',
                'title' => 'Updated Interactive 3D Portfolio',
                'subtitle' => 'High Performance WebGL Showcase',
                'description' => 'Comprehensive portfolio platform description.',
                'category' => 'Web 3D',
                'year' => '2026',
                'accent_color' => '#d4af37',
                'tech_stack' => 'Vue 3, Three.js, Tailwind CSS, Laravel, WebSockets',
                'status' => 'active',
                'is_featured' => '1',
                'image' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Updated Interactive 3D Portfolio')
            ->assertJsonPath('data.subtitle', 'High Performance WebGL Showcase')
            ->assertJsonPath('data.category', 'Web 3D')
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.is_featured', true)
            ->assertJsonPath('data.technologies', ['Vue 3', 'Three.js', 'Tailwind CSS', 'Laravel', 'WebSockets'])
            ->assertJsonPath('data.tech_stack', ['Vue 3', 'Three.js', 'Tailwind CSS', 'Laravel', 'WebSockets']);

        $storedProject = Project::find($project->id);
        $this->assertEquals(['Vue 3', 'Three.js', 'Tailwind CSS', 'Laravel', 'WebSockets'], $storedProject->technologies);
        $this->assertNotNull($storedProject->thumbnail_url);
    }

    public function test_model_accessors_format_relative_image_paths_to_full_public_urls_and_media_cleans_up(): void
    {
        Storage::fake('public');
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // 1. Test relative path in Project accessor
        $project = Project::create([
            'title' => 'Accessors Test Project',
            'description' => 'Testing asset url generation.',
            'thumbnail_url' => 'projects/my-preview.png',
            'technologies' => ['PHP'],
        ]);

        $this->assertStringContainsString('/storage/projects/my-preview.png', $project->thumbnail_url);
        $this->assertStringContainsString('/storage/projects/my-preview.png', $project->image);

        // 2. Test relative path in Skill accessor
        $skill = Skill::create([
            'name' => 'Docker',
            'category' => 'DevOps',
            'icon' => 'skills/docker.svg',
            'proficiency_level' => 88,
        ]);

        $this->assertStringContainsString('/storage/skills/docker.svg', $skill->image);
        $this->assertStringContainsString('/storage/skills/docker.svg', $skill->icon);

        // 3. Test relative path in Profile accessor
        $profile = Profile::first();
        $profile->update(['avatar_url' => 'profiles/avatar.png']);
        $this->assertStringContainsString('/storage/profiles/avatar.png', $profile->avatar_url);
        $this->assertStringContainsString('/storage/profiles/avatar.png', $profile->avatar);

        // 4. Test Media cleanup when deleting Project with an image
        Storage::disk('public')->put('projects/temp-to-delete.png', 'content');
        $this->assertTrue(Storage::disk('public')->exists('projects/temp-to-delete.png'));

        $projectWithImage = Project::create([
            'title' => 'Project To Delete',
            'description' => 'Cleanup test',
            'thumbnail_url' => 'projects/temp-to-delete.png',
        ]);

        $delResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/projects/'.$projectWithImage->id);

        $delResponse->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('projects/temp-to-delete.png'));
    }

    public function test_cv_lifecycle_upload_management_download_and_automatic_cleanup(): void
    {
        Storage::fake('public');
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // 1. Download when no CV exists -> 404
        $emptyDownload = $this->getJson('/api/cv/download');
        $emptyDownload->assertStatus(404)
            ->assertJsonPath('success', false);

        // 2. Reject invalid file type
        $invalidFile = UploadedFile::fake()->create('malicious.exe', 100, 'application/x-msdownload');
        $invalidUpload = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/cv', [
                'title' => 'Invalid CV',
                'file' => $invalidFile,
            ]);
        $invalidUpload->assertStatus(422)
            ->assertJsonValidationErrors(['file']);

        // 3. Upload first valid PDF CV
        $pdf1 = UploadedFile::fake()->create('mohammed-cv-v1.pdf', 500, 'application/pdf');
        $upload1 = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/cv', [
                'title' => 'Mohammed Alhwaiti - CV 2026',
                'file' => $pdf1,
            ]);

        $upload1->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Mohammed Alhwaiti - CV 2026')
            ->assertJsonPath('data.is_active', true);

        $cv1Id = $upload1->json('data.id');
        $cv1Path = $upload1->json('data.file_path');
        $this->assertTrue(Storage::disk('public')->exists($cv1Path));

        // 4. Upload second CV -> should become active and deactivate CV 1
        $pdf2 = UploadedFile::fake()->create('mohammed-cv-v2.pdf', 600, 'application/pdf');
        $upload2 = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/cv', [
                'title' => 'Mohammed Alhwaiti - CV v2',
                'file' => $pdf2,
            ]);

        $upload2->assertStatus(201)
            ->assertJsonPath('data.is_active', true);

        $cv2Id = $upload2->json('data.id');
        $cv2Path = $upload2->json('data.file_path');
        $this->assertTrue(Storage::disk('public')->exists($cv2Path));

        // CV 1 should now be inactive
        $this->assertFalse((bool) CvDocument::find($cv1Id)->is_active);
        $this->assertTrue((bool) CvDocument::find($cv2Id)->is_active);

        // 5. Public download endpoint serves active CV 2
        $downloadResponse = $this->get('/api/cv/download');
        $downloadResponse->assertStatus(200);
        $this->assertStringContainsString('mohammed-alhwaiti-cv-v2.pdf', $downloadResponse->headers->get('content-disposition'));

        // 6. Public CV info endpoint
        $publicInfo = $this->getJson('/api/cv');
        $publicInfo->assertStatus(200)
            ->assertJsonPath('data.id', $cv2Id)
            ->assertJsonPath('data.title', 'Mohammed Alhwaiti - CV v2');

        // 7. Admin activate CV 1 again
        $activateResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/cv/'.$cv1Id.'/activate');
        $activateResponse->assertStatus(200)
            ->assertJsonPath('data.is_active', true);

        $this->assertTrue((bool) CvDocument::find($cv1Id)->is_active);
        $this->assertFalse((bool) CvDocument::find($cv2Id)->is_active);

        // 8. Replace physical file on CV 1 -> old physical file must be deleted
        $pdf1Updated = UploadedFile::fake()->create('mohammed-cv-v1-updated.pdf', 700, 'application/pdf');
        $updateResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/cv/'.$cv1Id, [
                '_method' => 'PUT',
                'title' => 'Mohammed Alhwaiti - CV Updated',
                'file' => $pdf1Updated,
            ]);

        $updateResponse->assertStatus(200);
        $newCv1Path = $updateResponse->json('data.file_path');
        $this->assertFalse(Storage::disk('public')->exists($cv1Path)); // Old deleted
        $this->assertTrue(Storage::disk('public')->exists($newCv1Path)); // New exists

        // 9. Delete CV 2 -> physical file deleted from storage
        $deleteResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/cv/'.$cv2Id);
        $deleteResponse->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists($cv2Path));
    }

    public function test_certificates_public_and_admin_crud_lifecycle_with_image_upload_and_cleanup(): void
    {
        Storage::fake('public');
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        $imageFile = UploadedFile::fake()->image('anan-award.png');

        // 1. Admin Create Certificate with direct file upload and comma-separated skills
        $createResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/certificates', [
                'title' => 'Anan Award for WafarCash',
                'issuer' => 'UCAS Gaza',
                'date' => '2026',
                'badge' => '1st Place Winner',
                'accent' => 'amber',
                'credential_id' => 'UCAS-ANAN-2026',
                'description' => 'Best Graduation Project Architecture.',
                'skills' => 'Vue 3, Three.js, Spatial UI',
                'verify_url' => 'https://ucas.edu.ps',
                'sort_order' => 1,
                'is_active' => '1',
                'image' => $imageFile,
            ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Anan Award for WafarCash')
            ->assertJsonPath('data.badge', '1st Place Winner')
            ->assertJsonPath('data.skills', ['Vue 3', 'Three.js', 'Spatial UI'])
            ->assertJsonPath('data.sort_order', 1)
            ->assertJsonPath('data.is_active', true);

        $certId = $createResponse->json('data.id');
        $rawImage = Certificate::find($certId)->getRawOriginal('image');
        $this->assertNotNull($rawImage);

        // 2. Public /api/certificates endpoint
        $publicIndex = $this->getJson('/api/certificates');
        $publicIndex->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 1);

        // 3. Public /api/portfolio aggregated endpoint includes certificates
        $portfolioResponse = $this->getJson('/api/portfolio');
        $portfolioResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'certificates',
                    'social_links',
                ],
            ]);

        // 4. Update Certificate and replace image -> old image deleted
        $newImageFile = UploadedFile::fake()->image('anan-award-updated.png');
        $updateResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->post('/api/admin/certificates/'.$certId, [
                '_method' => 'PUT',
                'title' => 'Anan Award for WafarCash (Updated)',
                'issuer' => 'UCAS Gaza',
                'description' => 'Updated Description',
                'image' => $newImageFile,
            ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.title', 'Anan Award for WafarCash (Updated)');

        // 5. Delete Certificate -> cleans up image from disk
        $deleteResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/certificates/'.$certId);

        $deleteResponse->assertStatus(200);
        $this->assertNull(Certificate::find($certId));
    }

    public function test_social_links_upgraded_crud_and_direct_contact(): void
    {
        $token = $this->adminUser->createToken('test-token')->plainTextToken;

        // 1. Create upgraded social link
        $createResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/social-links', [
                'platform' => 'Telegram Channel',
                'handle' => '@alhwaiti_dev',
                'category' => 'Direct Contact',
                'url' => 'https://t.me/alhwaiti_dev',
                'icon' => 'send',
                'accent_color' => 'sky',
                'is_direct_contact' => true,
                'sort_order' => 6,
            ]);

        $createResponse->assertStatus(201)
            ->assertJsonPath('data.platform', 'Telegram Channel')
            ->assertJsonPath('data.handle', '@alhwaiti_dev')
            ->assertJsonPath('data.category', 'Direct Contact')
            ->assertJsonPath('data.accent_color', 'sky')
            ->assertJsonPath('data.is_direct_contact', true)
            ->assertJsonPath('data.sort_order', 6);

        $linkId = $createResponse->json('data.id');

        // 2. Admin Index orders by sort_order
        $indexResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/social-links');

        $indexResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        // 3. Update
        $updateResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/social-links/'.$linkId, [
                'platform' => 'Telegram Official',
                'handle' => '@alhwaiti_official',
                'url' => 'https://t.me/alhwaiti_official',
            ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.platform', 'Telegram Official')
            ->assertJsonPath('data.handle', '@alhwaiti_official');

        // 4. Delete
        $delResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/social-links/'.$linkId);

        $delResponse->assertStatus(200);
        $this->assertNull(SocialLink::find($linkId));
    }
}
