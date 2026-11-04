<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $costumes = [
            // ═══════════════════════════════════════════════════
            // DEFAULT UNLOCKED (2 characters)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'City Explorer',
                'character_name' => 'Alex',
                'description' => 'Penjelajah kota yang siap memulai petualangan coding!',
                'icon' => 'user',
                'avatar_icon' => 'user',
                'unlock_type' => 'default',
                'unlock_requirement' => null,
                'sprite_idle' => 'characters/City_men_1/Idle.gif',
                'sprite_typing' => 'characters/City_men_1/Walk.gif',
                'sprite_thinking' => 'characters/City_men_1/Attack.gif',
                'sprite_success' => 'characters/City_men_1/Run.gif',
                'sprite_error' => 'characters/City_men_1/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Street Fighter',
                'character_name' => 'Kai',
                'description' => 'Petarung jalanan yang pantang menyerah!',
                'icon' => 'user',
                'avatar_icon' => 'user',
                'unlock_type' => 'default',
                'unlock_requirement' => null,
                'sprite_idle' => 'characters/Fighter/Idle.gif',
                'sprite_typing' => 'characters/Fighter/Walk.gif',
                'sprite_thinking' => 'characters/Fighter/Attack_1.gif',
                'sprite_success' => 'characters/Fighter/Jump.gif',
                'sprite_error' => 'characters/Fighter/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ═══════════════════════════════════════════════════
            // XP UNLOCKED - Tier 1: Beginner (50, 100 XP = 8.5%, 17%)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'Urban Runner',
                'character_name' => 'Sam',
                'description' => 'Pelari cepat urban yang energik!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 50,  // 8.5% - Complete HTML Materi 1
                'sprite_idle' => 'characters/City_men_2/Idle.gif',
                'sprite_typing' => 'characters/City_men_2/Walk.gif',
                'sprite_thinking' => 'characters/City_men_2/Attack.gif',
                'sprite_success' => 'characters/City_men_2/Run.gif',
                'sprite_error' => 'characters/City_men_2/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wild Cowboy',
                'character_name' => 'Jake',
                'description' => 'Koboi tangguh dari wild west!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 100,  // 17% - Complete HTML Materi 1+2
                'sprite_idle' => 'characters/Cowboy_1/Idle.gif',
                'sprite_typing' => 'characters/Cowboy_1/Walk.gif',
                'sprite_thinking' => 'characters/Cowboy_1/Shot.gif',
                'sprite_success' => 'characters/Cowboy_1/Jump.gif',
                'sprite_error' => 'characters/Cowboy_1/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ═══════════════════════════════════════════════════
            // XP UNLOCKED - Tier 2: Intermediate (200, 250 XP = 34%, 43%)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'Tech Girl',
                'character_name' => 'Luna',
                'description' => 'Gadis tech-savvy yang cerdas!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 200,  // 34% - Complete HTML Module
                'sprite_idle' => 'characters/Girl_1/Idle.gif',
                'sprite_typing' => 'characters/Girl_1/Walk.gif',
                'sprite_thinking' => 'characters/Girl_1/Attack.gif',
                'sprite_success' => 'characters/Girl_1/Jump.gif',
                'sprite_error' => 'characters/Girl_1/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Street Hustler',
                'character_name' => 'Rico',
                'description' => 'Hustler jalanan yang lihai!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 250,  // 43% - HTML + CSS Materi 1
                'sprite_idle' => 'characters/Gangsters_1/Idle.gif',
                'sprite_typing' => 'characters/Gangsters_1/Walk.gif',
                'sprite_thinking' => 'characters/Gangsters_1/Attack_1.gif',
                'sprite_success' => 'characters/Gangsters_1/Jump.gif',
                'sprite_error' => 'characters/Gangsters_1/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ═══════════════════════════════════════════════════
            // BADGE UNLOCKED (HTML, CSS, JS)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'Shadow Ninja',
                'character_name' => 'Shinobi',
                'description' => 'Ninja bayangan master HTML!',
                'icon' => 'trophy',
                'avatar_icon' => 'trophy',
                'unlock_type' => 'badge',
                'unlock_requirement' => 1, // HTML Explorer
                'sprite_idle' => 'characters/Shinobi/Idle.gif',
                'sprite_typing' => 'characters/Shinobi/Walk.gif',
                'sprite_thinking' => 'characters/Shinobi/Attack_1.gif',
                'sprite_success' => 'characters/Shinobi/Jump.gif',
                'sprite_error' => 'characters/Shinobi/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samurai Warrior',
                'character_name' => 'Takeshi',
                'description' => 'Samurai master CSS styling!',
                'icon' => 'trophy',
                'avatar_icon' => 'trophy',
                'unlock_type' => 'badge',
                'unlock_requirement' => 2, // CSS Stylist
                'sprite_idle' => 'characters/Samurai/Idle.gif',
                'sprite_typing' => 'characters/Samurai/Walk.gif',
                'sprite_thinking' => 'characters/Samurai/Attack_1.gif',
                'sprite_success' => 'characters/Samurai/Jump.gif',
                'sprite_error' => 'characters/Samurai/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desert Gunslinger',
                'character_name' => 'Colt',
                'description' => 'Penembak jitu JavaScript!',
                'icon' => 'trophy',
                'avatar_icon' => 'trophy',
                'unlock_type' => 'badge',
                'unlock_requirement' => 3, // JS Dynamo
                'sprite_idle' => 'characters/Cowboy_2/Idle.gif',
                'sprite_typing' => 'characters/Cowboy_2/Walk.gif',
                'sprite_thinking' => 'characters/Cowboy_2/Shot.gif',
                'sprite_success' => 'characters/Cowboy_2/Jump.gif',
                'sprite_error' => 'characters/Cowboy_2/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ═══════════════════════════════════════════════════
            // XP UNLOCKED - Tier 3: Advanced (300, 350 XP = 51%, 60%)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'City Veteran',
                'character_name' => 'Max',
                'description' => 'Veteran kota berpengalaman!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 300,  // 51% - HTML + CSS Materi 1+2
                'sprite_idle' => 'characters/City_men_3/Idle.gif',
                'sprite_typing' => 'characters/City_men_3/Walk.gif',
                'sprite_thinking' => 'characters/City_men_3/Attack.gif',
                'sprite_success' => 'characters/City_men_3/Run.gif',
                'sprite_error' => 'characters/City_men_3/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Boss Lady',
                'character_name' => 'Raven',
                'description' => 'Boss wanita yang powerful!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 350,  // 60% - HTML + CSS Module Complete
                'sprite_idle' => 'characters/Girl_2/Idle.gif',
                'sprite_typing' => 'characters/Girl_2/Walk.gif',
                'sprite_thinking' => 'characters/Girl_2/Attack.gif',
                'sprite_success' => 'characters/Girl_2/Jump.gif',
                'sprite_error' => 'characters/Girl_2/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ═══════════════════════════════════════════════════
            // XP UNLOCKED - Tier 4: Expert (400, 450 XP = 68%, 77%)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'Gang Leader',
                'character_name' => 'Vito',
                'description' => 'Pemimpin geng yang ditakuti!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 400,  // 68% - HTML + CSS + some JS
                'sprite_idle' => 'characters/Gangsters_2/Idle.gif',
                'sprite_typing' => 'characters/Gangsters_2/Walk.gif',
                'sprite_thinking' => 'characters/Gangsters_2/Attack_1.gif',
                'sprite_success' => 'characters/Gangsters_2/Jump.gif',
                'sprite_error' => 'characters/Gangsters_2/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wild Sheriff',
                'character_name' => 'Marshall',
                'description' => 'Sheriff penegak hukum!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 450,  // 77% - Most content complete
                'sprite_idle' => 'characters/Cowboy_3/Idle.gif',
                'sprite_typing' => 'characters/Cowboy_3/Walk.gif',
                'sprite_thinking' => 'characters/Cowboy_3/Shot.gif',
                'sprite_success' => 'characters/Cowboy_3/Jump.gif',
                'sprite_error' => 'characters/Cowboy_3/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ═══════════════════════════════════════════════════
            // SPECIAL & LEGENDARY (Module completion, Max XP)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'Elite Enforcer',
                'character_name' => 'Scarlett',
                'description' => 'Elite enforcer yang menyelesaikan semua modul!',
                'icon' => 'trophy',
                'avatar_icon' => 'trophy',
                'unlock_type' => 'module',
                'unlock_requirement' => 3, // Complete JS module
                'sprite_idle' => 'characters/Girl_3/Idle.gif',
                'sprite_typing' => 'characters/Girl_3/Walk.gif',
                'sprite_thinking' => 'characters/Girl_3/Attack.gif',
                'sprite_success' => 'characters/Girl_3/Jump.gif',
                'sprite_error' => 'characters/Girl_3/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kingpin',
                'character_name' => 'Don',
                'description' => 'Kingpin tertinggi dalam hierarki coder!',
                'icon' => 'trophy',
                'avatar_icon' => 'trophy',
                'unlock_type' => 'xp',
                'unlock_requirement' => 1500,
                'sprite_idle' => 'characters/Gangsters_3/Idle.gif',
                'sprite_typing' => 'characters/Gangsters_3/Walk.gif',
                'sprite_thinking' => 'characters/Gangsters_3/Attack_1.gif',
                'sprite_success' => 'characters/Gangsters_3/Jump.gif',
                'sprite_error' => 'characters/Gangsters_3/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ═══════════════════════════════════════════════════
            // XP UNLOCKED - Tier 5: Master (500, 550, 580, 585 XP = 85%, 94%, 99%, 100%)
            // ═══════════════════════════════════════════════════
            [
                'name' => 'Undead Coder',
                'character_name' => 'Zombie',
                'description' => 'Zombie yang masih coding bahkan setelah mati!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 500,  // 85% - Almost everything
                'sprite_idle' => 'characters/Zombie Man/Idle.gif',
                'sprite_typing' => 'characters/Zombie Man/Walk.gif',
                'sprite_thinking' => 'characters/Zombie Man/Attack_1.gif',
                'sprite_success' => 'characters/Zombie Man/Run.gif',
                'sprite_error' => 'characters/Zombie Man/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kingpin',
                'character_name' => 'Don',
                'description' => 'Kingpin tertinggi dalam hierarki coder!',
                'icon' => 'trophy',
                'avatar_icon' => 'trophy',
                'unlock_type' => 'xp',
                'unlock_requirement' => 550,  // 94% - Nearly complete
                'sprite_idle' => 'characters/Gangsters_3/Idle.gif',
                'sprite_typing' => 'characters/Gangsters_3/Walk.gif',
                'sprite_thinking' => 'characters/Gangsters_3/Attack.gif',
                'sprite_success' => 'characters/Gangsters_3/Jump.gif',
                'sprite_error' => 'characters/Gangsters_3/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zombie Queen',
                'character_name' => 'Undead',
                'description' => 'Ratu zombie yang menguasai dark code!',
                'icon' => 'gem',
                'avatar_icon' => 'gem',
                'unlock_type' => 'xp',
                'unlock_requirement' => 580,  // 99% - Final challenge
                'sprite_idle' => 'characters/Zombie Woman/Idle.gif',
                'sprite_typing' => 'characters/Zombie Woman/Walk.gif',
                'sprite_thinking' => 'characters/Zombie Woman/Attack_1.gif',
                'sprite_success' => 'characters/Zombie Woman/Run.gif',
                'sprite_error' => 'characters/Zombie Woman/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Legendary Beast',
                'character_name' => 'Beast',
                'description' => 'Legendary beast untuk master coder sejati!',
                'icon' => 'trophy',
                'avatar_icon' => 'trophy',
                'unlock_type' => 'xp',
                'unlock_requirement' => 585,  // 100% - Perfect completion!
                'sprite_idle' => 'characters/Wild Zombie/Idle.gif',
                'sprite_typing' => 'characters/Wild Zombie/Walk.gif',
                'sprite_thinking' => 'characters/Wild Zombie/Attack_1.gif',
                'sprite_success' => 'characters/Wild Zombie/Run.gif',
                'sprite_error' => 'characters/Wild Zombie/Hurt.gif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('costumes')->insert($costumes);
    }
}
