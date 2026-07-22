<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            // Ranks (VIP Ranks)
            [
                'name' => 'VIP',
                'description' => 'Enjoy your VIP status on LegacySMP! Includes /fly, /feed, /heal, colored chat, and more exclusive benefits.',
                'price' => 25000,
                'category' => 'rank',
                'stock' => -1,
                'image_url' => '/images/ranks/vip.png',
                'is_active' => true,
                'features' => [
                    '/fly command',
                    '/feed command',
                    '/heal command',
                    'Colored chat',
                    'Join full server',
                    'VIP tag',
                ],
                'commands' => [
                    'lp user {username} parent add vip',
                    'broadcast &a{username} &ehas become a &aVIP&e!',
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'VIP+',
                'description' => 'All VIP benefits plus extra features: /god, /vanish, /nick, and priority queue access.',
                'price' => 50000,
                'category' => 'rank',
                'stock' => -1,
                'image_url' => '/images/ranks/vip-plus.png',
                'is_active' => true,
                'features' => [
                    'All VIP features',
                    '/god command',
                    '/vanish command',
                    '/nick command',
                    'Priority queue',
                    'VIP+ tag',
                    'Access to VIP+ only areas',
                ],
                'commands' => [
                    'lp user {username} parent add vipplus',
                    'broadcast &a{username} &ehas become a &6VIP+&e!',
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'MVP',
                'description' => 'The ultimate rank! Everything from VIP+ plus /weather, /time, worldedit basics, and a special MVP cape.',
                'price' => 100000,
                'category' => 'rank',
                'stock' => -1,
                'image_url' => '/images/ranks/mvp.png',
                'is_active' => true,
                'features' => [
                    'All VIP+ features',
                    '/weather command',
                    '/time command',
                    'WorldEdit basics',
                    'MVP cape',
                    'Exclusive MVP chat prefix',
                    'Access to all server areas',
                    'Priority support',
                ],
                'commands' => [
                    'lp user {username} parent add mvp',
                    'broadcast &b{username} &ehas become a &bMVP&e!',
                    'give {username} cape_mvp 1',
                ],
                'sort_order' => 3,
            ],
            // Items
            [
                'name' => 'Spawner Bundle',
                'description' => 'Get 5 random spawners! Includes a chance for rare spawners like Piglin Brute or Warden.',
                'price' => 15000,
                'category' => 'item',
                'stock' => 50,
                'image_url' => '/images/items/spawner-bundle.png',
                'is_active' => true,
                'features' => [
                    '5 random spawners',
                    'Rare spawner chance (10%)',
                    'Soulbound (stay on death)',
                ],
                'commands' => [
                    'spawner give {username} random 5',
                ],
                'sort_order' => 10,
            ],
            [
                'name' => 'Tool Upgrade Kit',
                'description' => 'Upgrade your tools to Netherite with custom enchants! Includes Efficiency VI and Unbreaking IV.',
                'price' => 20000,
                'category' => 'item',
                'stock' => 100,
                'image_url' => '/images/items/tool-kit.png',
                'is_active' => true,
                'features' => [
                    'Full Netherite tool set',
                    'Efficiency VI enchant',
                    'Unbreaking IV enchant',
                    'Fortune IV on pickaxe',
                    'Soulbound (stay on death)',
                ],
                'commands' => [
                    'give {username} netherite_pickaxe{efficiency:6,unbreaking:4,fortune:4,soulbound:1} 1',
                    'give {username} netherite_axe{efficiency:6,unbreaking:4,soulbound:1} 1',
                    'give {username} netherite_shovel{efficiency:6,unbreaking:4,soulbound:1} 1',
                    'give {username} netherite_hoe{efficiency:6,unbreaking:4,soulbound:1} 1',
                ],
                'sort_order' => 11,
            ],
            // Crates
            [
                'name' => 'Legendary Crate Key',
                'description' => 'Open the legendary crate for a chance at exclusive items, rare spawners, and epic gear!',
                'price' => 10000,
                'category' => 'crate',
                'stock' => 200,
                'image_url' => '/images/crates/legendary-key.png',
                'is_active' => true,
                'features' => [
                    'Legendary crate loot table',
                    '5% chance for mythical item',
                    'Guaranteed rare item',
                ],
                'commands' => [
                    'crate give {username} legendary 1',
                ],
                'sort_order' => 20,
            ],
            [
                'name' => 'Mystery Box (x5)',
                'description' => '5 Mystery Boxes filled with random loot! From common items to legendary rewards.',
                'price' => 25000,
                'category' => 'crate',
                'stock' => 150,
                'image_url' => '/images/crates/mystery-box.png',
                'is_active' => true,
                'features' => [
                    '5 Mystery Boxes',
                    'Random loot rewards',
                    'Possible legendary drops',
                ],
                'commands' => [
                    'crate give {username} mystery 5',
                ],
                'sort_order' => 21,
            ],
            // Keys
            [
                'name' => 'Vote Key (x10)',
                'description' => '10 Vote Keys to open the Vote Crate! Get voting rewards, coins, and exclusive items.',
                'price' => 5000,
                'category' => 'key',
                'stock' => -1,
                'image_url' => '/images/keys/vote-key.png',
                'is_active' => true,
                'features' => [
                    '10 Vote Keys',
                    'Vote crate rewards',
                    'Server coins bonus',
                ],
                'commands' => [
                    'crate give {username} vote 10',
                ],
                'sort_order' => 30,
            ],
            [
                'name' => 'Season Pass',
                'description' => 'Access the current season pass rewards! Unlock exclusive seasonal items and cosmetics.',
                'price' => 35000,
                'category' => 'key',
                'stock' => 500,
                'image_url' => '/images/keys/season-pass.png',
                'is_active' => true,
                'features' => [
                    'Current season pass access',
                    'Exclusive seasonal rewards',
                    'XP boost for 2 weeks',
                ],
                'commands' => [
                    'seasonpass give {username}',
                ],
                'sort_order' => 31,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully!');
    }
}

