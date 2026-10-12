<?php

namespace Database\Seeders;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
use App\Domains\Catalog\Models\BundleProduct;
use App\Domains\Catalog\Models\Product;
use App\Domains\Content\Enums\PreviewStatus;
use App\Domains\Content\Enums\ResourceStatus;
use App\Domains\Content\Enums\ResourceType;
use App\Domains\Content\Models\LearningResource;
use App\Domains\Content\Models\ResourcePreview;
use App\Domains\Content\Models\ResourceSkill;
use App\Domains\Content\Models\ResourceVersion;
use App\Domains\Curriculum\Models\SchoolClass;
use App\Domains\Curriculum\Models\Skill;
use App\Domains\Membership\Enums\LearningProgramStatus;
use App\Domains\Membership\Models\LearningItem;
use App\Domains\Membership\Models\LearningProgram;
use App\Domains\Membership\Models\LearningWeek;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PilotContentSeeder extends Seeder
{
    private User $admin;

    public function run(): void
    {
        $this->admin = User::firstOrCreate(
            ['email' => 'admin@brightlearners.in'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $classes = SchoolClass::query()->orderBy('sort_order')->get();
        if ($classes->isEmpty()) {
            $this->call(CurriculumSeeder::class);
            $classes = SchoolClass::query()->orderBy('sort_order')->get();
        }

        // 1. Free Resources (18 resources + 3 starter checks across classes 1-3)
        $this->seedFreeResources($classes);

        // 2. 12 Topic Packs (4 each for Class 1, 2, 3)
        $topicPacks = $this->seedTopicPacks($classes);

        // 3. 2 Flagship Ebooks
        $ebooks = $this->seedFlagshipEbooks();

        // 4. 3 Bundles
        $this->seedBundles($classes, $topicPacks);

        // 5. Complete Membership Month for Classes 1–3 + Buffer Month
        $this->seedMembershipPrograms($classes);
    }

    /**
     * @param  Collection<int, SchoolClass>  $classes
     */
    private function seedFreeResources($classes): void
    {
        foreach ($classes as $class) {
            $skills = Skill::query()
                ->whereHas('classes', fn ($q) => $q->where('classes.id', $class->id))
                ->get();

            if ($skills->isEmpty()) {
                continue;
            }

            // Starter check
            $starterCheck = $this->createResource(
                title: "{$class->name} Readiness & Starter Check",
                slug: Str::slug("{$class->name} starter check"),
                type: ResourceType::StarterCheck,
                class: $class,
                skill: $skills->first(),
                isFree: true,
                summary: "Diagnostic starter check for {$class->name} foundational skills.",
            );

            // 6 free worksheets/guides per class
            for ($i = 1; $i <= 6; $i++) {
                $skill = $skills->get($i % $skills->count()) ?? $skills->first();
                $this->createResource(
                    title: "{$class->name} Practice Sheet #{$i} - {$skill->name}",
                    slug: Str::slug("{$class->name} practice sheet {$i} {$skill->slug}"),
                    type: ResourceType::Worksheet,
                    class: $class,
                    skill: $skill,
                    isFree: true,
                    summary: "Free introductory learning sheet covering {$skill->name}.",
                );
            }
        }
    }

    /**
     * @param  Collection<int, SchoolClass>  $classes
     * @return array<string, list<Product>>
     */
    private function seedTopicPacks($classes): array
    {
        $packsByClass = [];

        foreach ($classes as $class) {
            $packsByClass[$class->slug] = [];
            $skills = Skill::query()
                ->whereHas('classes', fn ($q) => $q->where('classes.id', $class->id))
                ->get();

            $packThemes = [
                'Numbers & Arithmetic Mastery',
                'Reading Comprehension & Phonics',
                'Foundational Problem Solving',
                'Core Thinking & Observation',
            ];

            foreach ($packThemes as $index => $theme) {
                $slug = Str::slug("{$class->name} {$theme}");
                $product = Product::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => "{$class->name} {$theme} Pack",
                        'type' => ProductType::TopicPack,
                        'primary_class_id' => $class->id,
                        'regular_price' => 7900, // ₹79
                        'sale_price' => null,
                        'currency' => 'INR',
                        'short_description' => "Complete 4-worksheet practice pack for {$class->name} {$theme}.",
                        'description' => 'Designed for targeted home practice. Includes detailed answer keys and step-by-step parent explanations.',
                        'status' => ProductStatus::Active,
                        'published_at' => now(),
                        'member_discount_eligible' => true,
                    ]
                );

                // Attach 3-4 worksheets to each pack
                $resources = [];
                for ($w = 1; $w <= 4; $w++) {
                    $skill = $skills->get(($index + $w) % $skills->count()) ?? $skills->first();
                    $resource = $this->createResource(
                        title: "{$class->name} {$theme} - Worksheet {$w}",
                        slug: Str::slug("{$class->name} {$theme} ws {$w}"),
                        type: ResourceType::Worksheet,
                        class: $class,
                        skill: $skill,
                        isFree: false,
                        summary: "Targeted worksheet for {$theme} ({$class->name}).",
                    );
                    $resources[$resource->id] = ['sort_order' => $w, 'version_policy' => 'latest_published'];
                }

                $product->resources()->sync($resources);
                $packsByClass[$class->slug][] = $product;
            }
        }

        return $packsByClass;
    }

    /**
     * @return list<Product>
     */
    private function seedFlagshipEbooks(): array
    {
        // 16.5: Maths Through Games with reviewed puzzles and instructions
        $mathsEbook = Product::query()->updateOrCreate(
            ['slug' => 'maths-through-games-foundation'],
            [
                'name' => 'Maths Through Games (Flagship Edition)',
                'type' => ProductType::ActivityEbook,
                'primary_class_id' => null,
                'regular_price' => 49900, // ₹499
                'sale_price' => 39900,
                'currency' => 'INR',
                'short_description' => 'Over 80 hands-on math puzzles, games and activities for early grades.',
                'description' => 'Flagship learning guide with full answer explanations, printable puzzle boards, and family game instructions.',
                'status' => ProductStatus::Active,
                'published_at' => now(),
                'member_discount_eligible' => true,
            ]
        );

        $mathsResource = $this->createResource(
            title: 'Maths Through Games Flagship Book PDF',
            slug: 'maths-through-games-flagship-pdf',
            type: ResourceType::ParentGuide,
            class: null,
            skill: Skill::query()->first(),
            isFree: false,
            summary: 'Complete high-resolution ebook with answers and puzzle keys.',
            pageCount: 84,
        );
        $mathsEbook->resources()->sync([$mathsResource->id => ['sort_order' => 1, 'version_policy' => 'latest_published']]);

        // 16.6: Phonics & Reading Guide kept separate from general vocabulary
        $readingEbook = Product::query()->updateOrCreate(
            ['slug' => 'foundational-phonics-and-reading'],
            [
                'name' => 'Foundational Phonics & Reading Parent Guide',
                'type' => ProductType::ActivityEbook,
                'primary_class_id' => null,
                'regular_price' => 39900, // ₹399
                'currency' => 'INR',
                'short_description' => 'Child-level structured reading strategies and sound blending charts.',
                'description' => 'Expert parent handbook for teaching reading and phonics at home systematically.',
                'status' => ProductStatus::Active,
                'published_at' => now(),
                'member_discount_eligible' => true,
            ]
        );

        $readingResource = $this->createResource(
            title: 'Foundational Phonics Parent Guide PDF',
            slug: 'foundational-phonics-parent-guide-pdf',
            type: ResourceType::ParentGuide,
            class: null,
            skill: Skill::query()->first(),
            isFree: false,
            summary: 'Comprehensive phonics and reading guide for parents.',
            pageCount: 56,
        );
        $readingEbook->resources()->sync([$readingResource->id => ['sort_order' => 1, 'version_policy' => 'latest_published']]);

        return [$mathsEbook, $readingEbook];
    }

    /**
     * @param  Collection<int, SchoolClass>  $classes
     * @param  array<string, list<Product>>  $topicPacks
     */
    private function seedBundles($classes, array $topicPacks): void
    {
        foreach ($classes as $class) {
            $packs = $topicPacks[$class->slug] ?? [];
            if (count($packs) < 2) {
                continue;
            }

            $bundle = Product::query()->updateOrCreate(
                ['slug' => Str::slug("{$class->name} complete mastery bundle")],
                [
                    'name' => "{$class->name} Complete Mastery Bundle",
                    'type' => ProductType::Bundle,
                    'primary_class_id' => $class->id,
                    'regular_price' => 24900, // ₹249
                    'sale_price' => 19900,
                    'currency' => 'INR',
                    'short_description' => "All 4 focused topic packs for {$class->name} together at 35% savings.",
                    'description' => "Includes full access to all worksheets, answer keys, and parent guidance notes for {$class->name}.",
                    'status' => ProductStatus::Active,
                    'published_at' => now(),
                    'member_discount_eligible' => true,
                ]
            );

            // Connect child products
            BundleProduct::query()->where('bundle_id', $bundle->id)->delete();
            foreach ($packs as $order => $pack) {
                BundleProduct::query()->create([
                    'bundle_id' => $bundle->id,
                    'product_id' => $pack->id,
                    'sort_order' => $order + 1,
                ]);
            }
        }
    }

    /**
     * @param  Collection<int, SchoolClass>  $classes
     */
    private function seedMembershipPrograms($classes): void
    {
        foreach ($classes as $class) {
            // Month 1 (Live)
            $programMonth1 = LearningProgram::query()->updateOrCreate(
                [
                    'class_id' => $class->id,
                    'month' => 1,
                    'year' => 2026,
                ],
                [
                    'title' => "{$class->name} - Term 1 Starter Month",
                    'status' => LearningProgramStatus::Published,
                    'publish_at' => now()->subMonth(),
                ]
            );

            for ($w = 1; $w <= 4; $w++) {
                $week = LearningWeek::query()->updateOrCreate(
                    [
                        'program_id' => $programMonth1->id,
                        'week_number' => $w,
                    ],
                    [
                        'title' => "Week {$w}: Foundational Practice",
                        'emphasis' => "Key skills mastery for Week {$w}",
                        'release_at' => now()->subWeeks(4 - $w),
                        'released_at' => now()->subWeeks(4 - $w),
                    ]
                );

                LearningItem::query()->where('week_id', $week->id)->delete();
                for ($it = 1; $it <= 3; $it++) {
                    $itemResource = $this->createResource(
                        title: "{$class->name} M1 W{$w} Activity {$it}",
                        slug: Str::slug("{$class->name} m1 w{$w} act {$it}"),
                        type: ResourceType::Worksheet,
                        class: $class,
                        skill: Skill::query()->first(),
                        isFree: false,
                        summary: "Membership activity for {$class->name} Month 1 Week {$w}.",
                    );

                    LearningItem::query()->create([
                        'week_id' => $week->id,
                        'resource_id' => $itemResource->id,
                        'sort_order' => $it,
                        'required' => true,
                        'guidance' => 'Spend 15 minutes reviewing problem sets together.',
                    ]);
                }
            }

            // Month 2 (Buffer month - ready in advance per 16.4)
            $programMonth2 = LearningProgram::query()->updateOrCreate(
                [
                    'class_id' => $class->id,
                    'month' => 2,
                    'year' => 2026,
                ],
                [
                    'title' => "{$class->name} - Term 1 Extension Month",
                    'status' => LearningProgramStatus::Ready,
                    'publish_at' => now()->addDays(15),
                ]
            );

            for ($w = 1; $w <= 4; $w++) {
                $week = LearningWeek::query()->updateOrCreate(
                    [
                        'program_id' => $programMonth2->id,
                        'week_number' => $w,
                    ],
                    [
                        'title' => "Week {$w}: Skill Advancement",
                        'emphasis' => "Deep dive problem solving for Week {$w}",
                        'release_at' => now()->addDays(15 + ($w * 7)),
                        'released_at' => null,
                    ]
                );

                LearningItem::query()->where('week_id', $week->id)->delete();
                for ($it = 1; $it <= 3; $it++) {
                    $itemResource = $this->createResource(
                        title: "{$class->name} M2 W{$w} Activity {$it}",
                        slug: Str::slug("{$class->name} m2 w{$w} act {$it}"),
                        type: ResourceType::Worksheet,
                        class: $class,
                        skill: Skill::query()->first(),
                        isFree: false,
                        summary: "Advance activity for {$class->name} Month 2 Week {$w}.",
                    );

                    LearningItem::query()->create([
                        'week_id' => $week->id,
                        'resource_id' => $itemResource->id,
                        'sort_order' => $it,
                        'required' => true,
                        'guidance' => 'Check student solutions using the attached answer sheet.',
                    ]);
                }
            }
        }
    }

    private function createResource(
        string $title,
        string $slug,
        ResourceType $type,
        ?SchoolClass $class,
        ?Skill $skill,
        bool $isFree,
        string $summary,
        int $pageCount = 4,
    ): LearningResource {
        $resource = LearningResource::query()->updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'type' => $type,
                'summary' => $summary,
                'description' => $summary.' Designed for clear printing and active retention.',
                'learning_objective' => 'Practice and solidify key learning points with confidence.',
                'difficulty' => 'core',
                'estimated_minutes' => 15,
                'page_count' => $pageCount,
                'language' => 'en',
                'has_answer_key' => true,
                'low_ink_available' => true,
                'licence_type' => 'household',
                'is_free' => $isFree,
                'ai_assisted' => false,
                'featured' => false,
                'status' => ResourceStatus::Published,
                'created_by' => $this->admin->id,
            ]
        );

        $version = ResourceVersion::query()
            ->where('resource_id', $resource->id)
            ->where('version', '1.0')
            ->first();

        if ($version === null) {
            $version = ResourceVersion::query()->create([
                'resource_id' => $resource->id,
                'version' => '1.0',
                'file_path' => "resources/{$resource->id}/v1.0.pdf",
                'checksum' => hash('sha256', $resource->slug.'v1.0'),
                'preview_status' => PreviewStatus::Ready,
                'is_current' => true,
                'published_at' => now(),
                'created_by' => $this->admin->id,
            ]);
        }

        ResourcePreview::query()->updateOrCreate(
            [
                'resource_version_id' => $version->id,
                'page_no' => 1,
            ],
            [
                'image_path' => "previews/{$resource->id}/p1.webp",
                'width' => 816,
                'height' => 1056,
                'sort_order' => 1,
            ]
        );

        if ($skill !== null && $class !== null) {
            ResourceSkill::query()->updateOrCreate(
                [
                    'resource_id' => $resource->id,
                    'skill_id' => $skill->id,
                    'class_id' => $class->id,
                ],
                [
                    'is_primary' => true,
                ]
            );
        }

        return $resource;
    }
}
