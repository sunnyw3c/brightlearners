<?php

namespace Database\Seeders;

use App\Domains\Catalog\Enums\ProductStatus;
use App\Domains\Catalog\Enums\ProductType;
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
use App\Models\User;
use Illuminate\Database\Seeder;

class ColoringContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@brightlearners.in'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $class1 = SchoolClass::where('slug', 'class-1')->first();
        if (! $class1) {
            $class1 = SchoolClass::firstOrCreate(
                ['slug' => 'class-1'],
                ['name' => 'Class 1', 'sort_order' => 1, 'active' => true]
            );
        }

        $skill = Skill::first();

        $coloringItems = [
            [
                'title' => 'Alphabet Phonics Animals Coloring Book (Letters A-E)',
                'slug' => 'alphabet-phonics-animals-coloring-book',
                'summary' => 'Five delightful animal coloring sheets featuring Letter A to E with bold borders, tracing words, and phonics recognition.',
                'description' => 'Help early learners master letter sounds with cute animal illustrations: A for Alligator, B for Bear, C for Cat, D for Dolphin, and E for Elephant.',
                'learning_objective' => 'Practice fine motor pencil grip while associating letters A-E with their phonetic initial sounds.',
                'page_count' => 5,
                'estimated_minutes' => 20,
                'featured' => true,
            ],
            [
                'title' => 'Indian Wildlife & Jungle Safari Coloring Pages',
                'slug' => 'indian-wildlife-jungle-safari-coloring-pages',
                'summary' => 'Magnificent Bengal Tiger, dancing Indian Peacock, and gentle Asian Elephant ready for colors.',
                'description' => 'Introduce children to the rich biodiversity of India through beautiful outline drawings designed specifically for little hands.',
                'learning_objective' => 'Explore animal habitats and develop visual-spatial coordination using colored pencils or crayons.',
                'page_count' => 4,
                'estimated_minutes' => 15,
                'featured' => true,
            ],
            [
                'title' => 'Festivals of Joy: Diwali Diya & Rangoli Coloring Sheet',
                'slug' => 'festivals-diwali-diya-rangoli-coloring-sheet',
                'summary' => 'Festive oil lamps (diyas), floral rangoli patterns, and decorative fireworks celebrating the festival of lights.',
                'description' => 'A heartwarming cultural coloring activity highlighting the joy of Diwali. Great for school festival projects and home crafts.',
                'learning_objective' => 'Enhance color symmetry, border control, and cultural appreciation.',
                'page_count' => 2,
                'estimated_minutes' => 15,
                'featured' => true,
            ],
            [
                'title' => 'Count & Color: Numbers 1 to 5 Math Fun',
                'slug' => 'count-and-color-numbers-1-to-5-math-fun',
                'summary' => 'Number recognition practice with cute balloons, smiling apples, and color-by-number dots.',
                'description' => 'Combine early numeracy with creative coloring! Kids count the objects and color matching number regions.',
                'learning_objective' => 'Reinforce 1-to-1 correspondence and number identification through hands-on art.',
                'page_count' => 3,
                'estimated_minutes' => 15,
                'featured' => false,
            ],
            [
                'title' => 'Friendly Dinosaurs & Cosmic Space Adventure',
                'slug' => 'friendly-dinosaurs-space-adventure-coloring',
                'summary' => 'Cute Stegosaurus, playful T-Rex, and smiling rocket ships soaring past Saturn and glowing stars.',
                'description' => 'Let imagination blast off with friendly prehistoric creatures and cosmic rockets in clean, bold vector lines.',
                'learning_objective' => 'Encourage creative expression, hand dominance, and relaxation away from screens.',
                'page_count' => 4,
                'estimated_minutes' => 20,
                'featured' => true,
            ],
        ];

        foreach ($coloringItems as $item) {
            $resource = LearningResource::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'title' => $item['title'],
                    'type' => ResourceType::Coloring->value,
                    'summary' => $item['summary'],
                    'description' => $item['description'],
                    'learning_objective' => $item['learning_objective'],
                    'difficulty' => 'easy',
                    'estimated_minutes' => $item['estimated_minutes'],
                    'page_count' => $item['page_count'],
                    'supplies' => 'Crayons, sketch pens, or colored pencils',
                    'language' => 'en',
                    'has_answer_key' => false,
                    'low_ink_available' => true,
                    'licence_type' => 'household',
                    'is_free' => true,
                    'ai_assisted' => false,
                    'featured' => $item['featured'],
                    'status' => ResourceStatus::Published->value,
                    'published_at' => now(),
                    'created_by' => $admin->id,
                ]
            );

            if ($skill && $class1) {
                ResourceSkill::firstOrCreate([
                    'resource_id' => $resource->id,
                    'skill_id' => $skill->id,
                    'class_id' => $class1->id,
                ], [
                    'is_primary' => true,
                ]);
            }

            $version = ResourceVersion::firstOrCreate(
                [
                    'resource_id' => $resource->id,
                    'version' => '1.0',
                ],
                [
                    'file_path' => "resources/{$resource->slug}-v1.0.pdf",
                    'checksum' => hash('sha256', $resource->slug),
                    'preview_status' => PreviewStatus::Ready->value,
                    'is_current' => true,
                    'reviewed_by' => $admin->id,
                    'reviewed_at' => now(),
                    'published_at' => now(),
                    'created_by' => $admin->id,
                ]
            );

            // Generate a couple of preview entries
            for ($p = 1; $p <= min(3, $item['page_count']); $p++) {
                ResourcePreview::firstOrCreate([
                    'resource_version_id' => $version->id,
                    'page_no' => $p,
                ], [
                    'image_path' => "previews/{$resource->slug}-page-{$p}.png",
                    'width' => 1240,
                    'height' => 1754,
                    'sort_order' => $p,
                ]);
            }
        }

        // Seed a commercial Coloring & Activity Workbook in the Shop
        Product::firstOrCreate(
            ['slug' => 'jumbo-preschool-coloring-activity-workbook'],
            [
                'name' => 'Jumbo Creative Arts & Coloring Workbook (50+ Pages)',
                'type' => ProductType::Workbook->value,
                'sku' => 'WB-CLR-01',
                'short_description' => '50+ comprehensive fine-motor practice sheets, phonics coloring, dot-to-dot puzzles, and festive sheets.',
                'description' => 'A master bound compilation featuring 50+ hand-illustrated activities. Perfect for holidays, long road trips, and daily creative quiet time. Includes printable parent achievement certificate.',
                'primary_class_id' => $class1?->id,
                'regular_price' => 19900, // ₹199
                'sale_price' => 14900, // ₹149
                'currency' => 'INR',
                'member_discount_eligible' => true,
                'status' => ProductStatus::Active->value,
                'featured' => true,
                'publish_at' => now(),
                'published_at' => now(),
            ]
        );
    }
}
