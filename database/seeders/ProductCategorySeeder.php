<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plugin OJS',
                'slug' => 'plugin-ojs',
                'description' => 'Plugin untuk Open Journal Systems (OJS) - Generic, Import/Export, Theme, dan lainnya',
                'icon' => 'ki-duotone ki-code',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Theme OJS',
                'slug' => 'theme-ojs',
                'description' => 'Template dan tema untuk Open Journal Systems (OJS)',
                'icon' => 'ki-duotone ki-color-swatch',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Plugin Generic OJS',
                'slug' => 'plugin-generic-ojs',
                'description' => 'Plugin generic untuk memperluas fungsionalitas OJS',
                'icon' => 'ki-duotone ki-setting-2',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Template Website',
                'slug' => 'template-website',
                'description' => 'Template website siap pakai untuk berbagai kebutuhan',
                'icon' => 'ki-duotone ki-element-11',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Tools & Script',
                'slug' => 'tools-script',
                'description' => 'Tools, script, dan utilitas untuk pengembangan web',
                'icon' => 'ki-duotone ki-wrench',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'E-Book & Tutorial',
                'slug' => 'e-book-tutorial',
                'description' => 'E-book, tutorial, dan materi pembelajaran digital',
                'icon' => 'ki-duotone ki-book',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
