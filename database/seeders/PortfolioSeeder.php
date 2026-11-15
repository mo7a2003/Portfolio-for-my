<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Profile / About Data (Mohammed Alhwaiti)
        Profile::updateOrCreate(
            ['id' => 1],
            [
                'full_name' => 'Mohammed Alhwaiti',
                'title' => 'Principal Front-End & 3D Web Graphics Architect',
                'bio' => 'Transforming complex ideas into immersive, responsive web interfaces using cutting-edge UI/UX design and 3D web graphics.',
                'detailed_bio' => 'Passionate front-end developer and web designer with solid experience in building interactive web platforms and modern web applications. Combining advanced front-end engineering with 3D web graphics to deliver exceptional, high-performance user experiences. Graduate of the University College of Applied Sciences (UCAS) in Gaza, holding a Technical Degree in Website Design and Development (Class of 2026).',
                'experience_years' => 1,
                'projects_completed' => 3,
                'education' => 'Technical Degree in Website Design and Development, University College of Applied Sciences (UCAS) - Class of 2026',
                'milestones' => [
                    [
                        'year' => '2026',
                        'title' => 'UCAS Graduation (Class of 2026)',
                        'description' => 'Graduated from the University College of Applied Sciences (UCAS) in Gaza with a Technical Degree in Website Design and Development.',
                    ],
                    [
                        'year' => '2026',
                        'title' => 'Anan Award Winner (WafarCash)',
                        'description' => 'Awarded the prestigious Anan Award for Best Graduation Project at UCAS for designing and architecting WafarCash.',
                    ],
                    [
                        'year' => '2025',
                        'title' => 'VibeLocate AI Platform Launch',
                        'description' => 'Architected and delivered the AI-driven real estate platform featuring interactive maps and neighborhood vibe reports.',
                    ],
                    [
                        'year' => '2024',
                        'title' => 'Wasla Multi-Service Platform',
                        'description' => 'Engineered a comprehensive multi-service web platform integrating open market listings, technician booking, and discounts.',
                    ],
                ],
                'avatar_url' => null,
                'cv_url' => null,
                'email' => 'mohamhwaty@gmail.com',
                'phone' => '+970 59-0000000',
                'availability_status' => 'Available for High-Impact Projects',
            ]
        );

        // 2. Projects (WafarCash, VibeLocate AI, Wasla Platform)
        $projects = [
            [
                'title' => 'WafarCash',
                'subtitle' => 'Promotional & Financial App (Anan Award Winner)',
                'slug' => 'wafarcash-promotional-financial-app',
                'description' => 'Promotional & financial mobile and web application. Winner of the prestigious Anan Award for Best Graduation Project at UCAS. Focused on interactive front-end interfaces, intuitive user journeys, and human-centered UI/UX design.',
                'category' => 'Financial / Mobile UI',
                'year' => '2026',
                'accent_color' => '#10B981',
                'badge' => 'Anan Award Winner',
                'thumbnail_url' => null,
                'image' => null,
                'live_url' => null,
                'github_url' => 'https://github.com/mohammed-alhwaiti/wafarcash',
                'technologies' => ['Vue.js', 'Tailwind CSS', 'JavaScript (ES6+)', 'Figma', 'UI/UX Design', 'Chart.js', 'REST API'],
                'status' => 'completed',
                'is_featured' => true,
            ],
            [
                'title' => 'VibeLocate AI',
                'subtitle' => 'AI-Driven Real Estate Platform',
                'slug' => 'vibelocate-ai-real-estate-platform',
                'description' => 'Next-generation AI-driven real estate platform featuring property rentals, sales, interactive map navigation, and neighborhood vibe reports to help buyers and renters find their ideal location.',
                'category' => 'AI / Real Estate',
                'year' => '2025',
                'accent_color' => '#3B82F6',
                'badge' => 'AI Powered',
                'thumbnail_url' => null,
                'image' => null,
                'live_url' => 'https://vibelocate.vercel.app',
                'github_url' => 'https://github.com/mohammed-alhwaiti/vibelocate-ai',
                'technologies' => ['Vue.js', 'Tailwind CSS', 'Interactive Maps', 'AI Integration', 'JavaScript (ES6+)', 'REST API'],
                'status' => 'completed',
                'is_featured' => true,
            ],
            [
                'title' => 'Wasla Platform',
                'subtitle' => 'Multi-Service Web Platform',
                'slug' => 'wasla-multi-service-platform',
                'description' => 'Comprehensive multi-service web platform integrating open market listings, verified store discounts, on-demand technician bookings, and swift delivery options built for seamless user engagement.',
                'category' => 'Marketplace & Services',
                'year' => '2024',
                'accent_color' => '#F59E0B',
                'badge' => 'Multi-Service',
                'thumbnail_url' => null,
                'image' => null,
                'live_url' => 'https://wasla-platform.vercel.app',
                'github_url' => 'https://github.com/mohammed-alhwaiti/wasla-platform',
                'technologies' => ['Vue.js', 'Tailwind CSS', 'JavaScript (ES6+)', 'Design Systems', 'Responsive UI', 'REST API'],
                'status' => 'completed',
                'is_featured' => true,
            ],
        ];

        // Clean out any legacy mock projects if present to ensure pure real portfolio data
        Project::whereNotIn('slug', array_column($projects, 'slug'))->delete();

        foreach ($projects as $project) {
            Project::updateOrCreate(['slug' => $project['slug']], $project);
        }

        // 3. Skills (Frontend & Frameworks, UI/UX & Design, Tools & Workflow)
        $skills = [
            // Frontend & Frameworks
            [
                'name' => 'Vue.js',
                'category' => 'Frontend & Frameworks',
                'icon' => 'vue',
                'image' => null,
                'proficiency_level' => 95,
                'order_index' => 1,
            ],
            [
                'name' => 'Tailwind CSS',
                'category' => 'Frontend & Frameworks',
                'icon' => 'tailwind',
                'image' => null,
                'proficiency_level' => 95,
                'order_index' => 2,
            ],
            [
                'name' => 'JavaScript (ES6+)',
                'category' => 'Frontend & Frameworks',
                'icon' => 'javascript',
                'image' => null,
                'proficiency_level' => 92,
                'order_index' => 3,
            ],
            [
                'name' => 'HTML5',
                'category' => 'Frontend & Frameworks',
                'icon' => 'html5',
                'image' => null,
                'proficiency_level' => 98,
                'order_index' => 4,
            ],
            [
                'name' => 'CSS3',
                'category' => 'Frontend & Frameworks',
                'icon' => 'css3',
                'image' => null,
                'proficiency_level' => 96,
                'order_index' => 5,
            ],

            // UI/UX & Design
            [
                'name' => 'Figma',
                'category' => 'UI/UX & Design',
                'icon' => 'figma',
                'image' => null,
                'proficiency_level' => 94,
                'order_index' => 6,
            ],
            [
                'name' => 'UI/UX Prototyping',
                'category' => 'UI/UX & Design',
                'icon' => 'layout',
                'image' => null,
                'proficiency_level' => 90,
                'order_index' => 7,
            ],
            [
                'name' => 'Design Systems',
                'category' => 'UI/UX & Design',
                'icon' => 'layers',
                'image' => null,
                'proficiency_level' => 88,
                'order_index' => 8,
            ],

            // Tools & Workflow
            [
                'name' => 'Git & GitHub',
                'category' => 'Tools & Workflow',
                'icon' => 'git',
                'image' => null,
                'proficiency_level' => 90,
                'order_index' => 9,
            ],
            [
                'name' => 'Postman',
                'category' => 'Tools & Workflow',
                'icon' => 'send',
                'image' => null,
                'proficiency_level' => 88,
                'order_index' => 10,
            ],
            [
                'name' => 'Supabase',
                'category' => 'Tools & Workflow',
                'icon' => 'database',
                'image' => null,
                'proficiency_level' => 86,
                'order_index' => 11,
            ],
        ];

        // Clean out old legacy skills
        Skill::whereNotIn('name', array_column($skills, 'name'))->delete();

        foreach ($skills as $skill) {
            Skill::updateOrCreate(['name' => $skill['name']], $skill);
        }

        // 4. Articles
        $articles = [
            [
                'title' => 'Building Immersive 3D Web Interfaces with Three.js and Vue.js',
                'slug' => 'building-immersive-3d-web-interfaces-with-threejs-and-vuejs',
                'category' => '3D & Web Graphics',
                'excerpt' => 'A deep dive into integrating Three.js canvas lifecycles with Vue 3 reactivity to build responsive, performant spatial web experiences.',
                'content' => "As modern web experiences move from flat pages into interactive digital dimensions, marrying the declarative power of Vue 3 with the imperative WebGL pipeline of Three.js opens unprecedented creative horizons.\n\nIn this technical breakdown, we explore establishing clean canvas boundaries, binding dynamic camera perspective changes to scroll positions, and structuring reactive state managers that feed real-time shader uniform updates without dropping below 60 frames per second.\n\nKey takeaways include setting up custom geometry buffers, handling window resize debouncing smoothly, and optimizing texture memory disposal when switching between portfolio showcases.",
                'image' => null,
                'read_time' => '6 min read',
                'is_published' => true,
                'published_at' => now()->subDays(25),
            ],
            [
                'title' => 'Mastering Scalable Design Systems in Figma for Modern Front-End Workflows',
                'slug' => 'mastering-scalable-design-systems-in-figma-for-modern-frontend-workflows',
                'category' => 'UI/UX & Design',
                'excerpt' => 'How to construct modular design tokens, autolayout components, and seamless handoff pipelines connecting Figma directly to Tailwind CSS.',
                'content' => "A robust design system bridges the gap between design vision and front-end reality. Without a unified token taxonomy, styling discrepancies multiply across viewport breakpoints.\n\nThis guide explores how we configure semantic spacing, color modes, typography scales, and component variants in Figma using variables and auto-layout, then export them cleanly into Tailwind CSS configuration presets.\n\nBy unifying naming conventions across Figma components and Vue template partials, development iteration speeds double while visual consistency remains pixel-perfect.",
                'image' => null,
                'read_time' => '5 min read',
                'is_published' => true,
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Architecting Award-Winning Graduation Projects: The Story Behind WafarCash',
                'slug' => 'architecting-award-winning-graduation-projects-wafarcash',
                'category' => 'Case Study',
                'excerpt' => 'Reflections and architectural insights on designing WafarCash, winner of the UCAS Anan Award for Best Graduation Project.',
                'content' => "Building WafarCash was an exhilarating journey of balancing complex financial tracking workflows with frictionless mobile-first user interfaces.\n\nRecognized with the Anan Award for Best Graduation Project at the University College of Applied Sciences (UCAS) in Gaza (Class of 2026), WafarCash stands as a testament to deep user research, iterative prototyping, and performance-first front-end engineering.\n\nIn this retrospective, we break down the design challenges faced, user journey optimizations, and the architecture that earned the project top recognition.",
                'image' => null,
                'read_time' => '7 min read',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
        ];

        Article::whereNotIn('slug', array_column($articles, 'slug'))->delete();

        foreach ($articles as $article) {
            Article::updateOrCreate(['slug' => $article['slug']], $article);
        }

        // 5. Certificates & Awards
        $certificates = [
            [
                'title' => 'Anan Award for Best Graduation Project',
                'issuer' => 'University College of Applied Sciences (UCAS)',
                'date' => '2026',
                'badge' => '1st Place Winner',
                'accent' => 'amber',
                'credential_id' => 'UCAS-ANAN-2026-01',
                'description' => 'Honored with the prestigious Anan Award for Best Graduation Project at UCAS Gaza for architecting and designing the user-centric interactive front-end and spatial design system for WafarCash.',
                'skills' => ['Vue.js', 'UI/UX Architecture', 'Design Systems', 'Figma', 'Interactive Front-End'],
                'verify_url' => 'https://ucas.edu.ps',
                'image' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Technical Degree in Website Design & Development',
                'issuer' => 'University College of Applied Sciences (UCAS)',
                'date' => '2026',
                'badge' => 'Honor Graduate',
                'accent' => 'emerald',
                'credential_id' => 'UCAS-TECH-2026-98',
                'description' => 'Graduated with high distinction specializing in web application design, modern JavaScript frameworks, 3D web graphics, and responsive full-stack architectures.',
                'skills' => ['Frontend Architecture', '3D Web Graphics', 'Vue.js', 'Tailwind CSS', 'JavaScript (ES6+)', 'PHP / Laravel'],
                'verify_url' => 'https://ucas.edu.ps',
                'image' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'AI & Spatial Web Graphics Fellowship',
                'issuer' => 'Taqat Academy',
                'date' => '2025',
                'badge' => 'Fellowship Distinction',
                'accent' => 'blue',
                'credential_id' => 'TAQAT-AI-2025-412',
                'description' => 'Intensive engineering fellowship focusing on artificial intelligence integration, interactive map visualization, and high-performance front-end web platforms leading to the launch of VibeLocate AI.',
                'skills' => ['AI Integration', 'Interactive Maps', 'Vue 3', 'Web Performance', 'RESTful API'],
                'verify_url' => 'https://vibelocate.vercel.app',
                'image' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        Certificate::whereNotIn('title', array_column($certificates, 'title'))->delete();

        foreach ($certificates as $cert) {
            Certificate::updateOrCreate(['title' => $cert['title']], $cert);
        }

        // 6. Social Media & Contact Channels (Mohammed Alhwaiti)
        $socialLinks = [
            [
                'platform' => 'GitHub',
                'platform_name' => 'GitHub',
                'handle' => '@mohammed-alhwaiti',
                'category' => 'Development',
                'url' => 'https://github.com/mohammed-alhwaiti',
                'icon' => 'github',
                'accent_color' => 'gray',
                'is_direct_contact' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'platform' => 'LinkedIn',
                'platform_name' => 'LinkedIn',
                'handle' => 'Mohammed Alhwaiti',
                'category' => 'Professional',
                'url' => 'https://linkedin.com/in/mohammed-alhwaiti',
                'icon' => 'linkedin',
                'accent_color' => 'blue',
                'is_direct_contact' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'platform' => 'Email',
                'platform_name' => 'Email',
                'handle' => 'mohamhwaty@gmail.com',
                'category' => 'Direct Contact',
                'url' => 'mailto:mohamhwaty@gmail.com',
                'icon' => 'mail',
                'accent_color' => 'amber',
                'is_direct_contact' => true,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'platform' => 'WhatsApp',
                'platform_name' => 'WhatsApp',
                'handle' => '+970 59-0000000',
                'category' => 'Direct Contact',
                'url' => 'https://wa.me/970590000000',
                'icon' => 'message-circle',
                'accent_color' => 'emerald',
                'is_direct_contact' => true,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'platform' => 'VibeLocate Platform',
                'platform_name' => 'VibeLocate Platform',
                'handle' => 'vibelocate.vercel.app',
                'category' => 'Live Products',
                'url' => 'https://vibelocate.vercel.app',
                'icon' => 'globe',
                'accent_color' => 'indigo',
                'is_direct_contact' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        SocialLink::whereNotIn('platform', array_column($socialLinks, 'platform'))->delete();

        foreach ($socialLinks as $link) {
            SocialLink::updateOrCreate(['platform' => $link['platform']], $link);
        }

        // 7. Contact Messages (Initial sample for testing admin inbox)
        ContactMessage::firstOrCreate(
            ['email' => 'tech-partner@innovations.dev'],
            [
                'name' => 'Collaborator Team',
                'message' => 'Congratulations on the Anan Award for WafarCash! We were impressed by your VibeLocate AI platform and would love to collaborate on a new front-end & 3D WebGL project.',
                'is_read' => false,
            ]
        );
    }
}
