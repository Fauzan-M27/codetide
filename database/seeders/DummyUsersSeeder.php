<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProgress;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyUsers = [
            ['nickname' => 'CodeMaster', 'questions' => 40, 'avg_xp' => 50],
            ['nickname' => 'WebWizard', 'questions' => 38, 'avg_xp' => 48],
            ['nickname' => 'ByteBuster', 'questions' => 35, 'avg_xp' => 47],
            ['nickname' => 'PixelPro', 'questions' => 32, 'avg_xp' => 45],
            ['nickname' => 'DebugDiva', 'questions' => 30, 'avg_xp' => 46],
            ['nickname' => 'SyntaxSage', 'questions' => 28, 'avg_xp' => 44],
            ['nickname' => 'LogicLion', 'questions' => 25, 'avg_xp' => 43],
            ['nickname' => 'DataDragon', 'questions' => 22, 'avg_xp' => 42],
            ['nickname' => 'FuncFox', 'questions' => 20, 'avg_xp' => 41],
            ['nickname' => 'LoopLegend', 'questions' => 18, 'avg_xp' => 40],
            ['nickname' => 'ArrayAce', 'questions' => 15, 'avg_xp' => 39],
            ['nickname' => 'ClassChamp', 'questions' => 12, 'avg_xp' => 38],
            ['nickname' => 'QueryQueen', 'questions' => 10, 'avg_xp' => 37],
            ['nickname' => 'GitGuru', 'questions' => 8, 'avg_xp' => 36],
            ['nickname' => 'ApiNinja', 'questions' => 5, 'avg_xp' => 35],
        ];

        $questions = Question::all();

        foreach ($dummyUsers as $index => $userData) {
            // Check if user already exists
            $existingUser = User::where('nickname', $userData['nickname'])->first();
            if ($existingUser) {
                $this->command->warn("User {$userData['nickname']} already exists, skipping...");
                continue;
            }

            // Generate participant code
            $date = now()->format('ymd');
            $sequence = str_pad($index + 200, 4, '0', STR_PAD_LEFT); // Start from 200
            $participantCode = "CP-{$date}-{$sequence}";

            // Create user
            $user = User::create([
                'nickname' => $userData['nickname'],
                'participant_code' => $participantCode,
                'password' => Hash::make('password'),
                'consent_at' => now(),
            ]);

            // Create progress for random questions
            $questionsToComplete = $questions->random(min($userData['questions'], 40));
            
            foreach ($questionsToComplete as $question) {
                UserProgress::create([
                    'user_id' => $user->id,
                    'question_id' => $question->id,
                    'code_submitted' => '// Completed code',
                    'completed_at' => now()->subDays(rand(1, 30)),
                    'attempts' => rand(1, 3),
                ]);
            }

            // Award badges based on progress
            if ($userData['questions'] >= 40) {
                $user->badges()->attach([1, 2, 3], ['earned_at' => now()]); // All module badges
            } elseif ($userData['questions'] >= 14) {
                $user->badges()->attach([1], ['earned_at' => now()]); // HTML Master
            }
        }

        $this->command->info('Dummy users created successfully!');
    }
}
