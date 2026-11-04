<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\UserProgress;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display question editor (soal page)
     */
    public function show(Request $request, Question $question)
    {
        $user = $request->user();

        // Check if question is unlocked
        if (!$question->isUnlockedFor($user)) {
            return redirect()
                ->route('materials.show', $question->material_id)
                ->with('error', 'Soal ini masih terkunci. Selesaikan soal sebelumnya terlebih dahulu.');
        }

        // Log activity
        $user->logActivity('open_question', $question->id);

        // Get or create user progress
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            [
                'code_submitted' => $question->starter_code,
                'attempts' => 0,
            ]
        );

        // Load relationships
        $question->load(['material.module']);

        // Get saved code
        $savedCode = $progress->code_submitted ?? $question->starter_code;

        // Get all questions in this material for progress dots
        $allQuestions = Question::where('material_id', $question->material_id)
            ->orderBy('order')
            ->get();

        $progressDots = $allQuestions->map(function ($q) use ($user, $question) {
            $qProgress = $q->userProgress($user);
            $isCompleted = $qProgress && $qProgress->completed_at !== null;
            $isCurrent = $q->id === $question->id;
            $isUnlocked = $q->isUnlockedFor($user);

            return [
                'order' => $q->order,
                'status' => $isCurrent ? 'current' : ($isCompleted ? 'done' : ''),
                'url' => $isUnlocked ? route('questions.show', $q->id) : null,
            ];
        });

        // Count total questions in material
        $totalQuestions = $allQuestions->count();

        return view('questions.show', compact(
            'question',
            'savedCode',
            'progressDots',
            'totalQuestions'
        ));
    }

    /**
     * Autosave user's code
     */
    public function autosave(Request $request, Question $question)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();

        // Update or create progress
        $progress = UserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            [
                'code_submitted' => $request->code,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Code autosaved',
        ]);
    }

    /**
     * Check user's answer
     */
    public function check(Request $request, Question $question)
    {
        $request->validate([
            'code' => 'nullable|string', // Allow empty to be handled by checker
        ]);

        $user = $request->user();
        $code = $request->code ?? '';

        // Get or create progress
        $progress = UserProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            [
                'code_submitted' => $question->starter_code,
                'attempts' => 0,
            ]
        );

        // Increment attempts
        $progress->increment('attempts');

        // Save submitted code
        $progress->code_submitted = $code;
        $progress->save();

        // Check answer using checker logic
        $result = $this->runChecker($question, $code);

        // If correct and not yet completed
        if ($result['status'] === 'correct' && !$progress->completed_at) {
            DB::transaction(function () use ($user, $question, $progress) {
                // Mark as completed
                $progress->completed_at = now();
                $progress->save();

                // Log activity
                $user->logActivity('complete_question', $question->id, [
                    'xp_earned' => $question->xp_reward,
                    'attempts' => $progress->attempts,
                ]);

                // Check and award badges
                $this->checkAndAwardBadges($user);

                // Check and unlock costumes based on XP/badges
                $user->checkAndUnlockCostumes();
            });

            $result['xp_earned'] = $question->xp_reward;
            
            // Get next question info
            $nextQuestion = $question->nextQuestion();
            if ($nextQuestion && $nextQuestion->isUnlockedFor($user)) {
                $result['next_question'] = [
                    'id' => $nextQuestion->id,
                    'title' => $nextQuestion->title,
                    'url' => route('questions.show', $nextQuestion->id),
                ];
            } else {
                // No next question, return to material page
                $result['back_to_material'] = [
                    'url' => route('materials.show', $question->material_id),
                    'message' => 'Selamat! Kamu telah menyelesaikan semua soal di materi ini.',
                ];
            }
        }

        return response()->json($result);
    }

    /**
     * Run checker logic for the question
     */
    private function runChecker(Question $question, string $code): array
    {
        // Basic validation: check if code is not empty or same as starter
        $code = trim($code);
        $starterCode = trim($question->starter_code);

        if (empty($code) || $code === $starterCode) {
            return [
                'status' => 'incomplete',
                'message' => 'Silakan tulis kode terlebih dahulu.',
            ];
        }

        // Type-specific checks with material routing
        switch ($question->question_type) {
            case 'html':
                return $this->checkHTML($question, $code);
            
            case 'css':
                return $this->checkCSS($question, $code);
            
            case 'javascript':
                return $this->checkJavaScript($question, $code);
            
            default:
                return [
                    'status' => 'incorrect',
                    'message' => 'Tipe soal tidak dikenali.',
                ];
        }
    }

    /**
     * Check HTML code
     */
    private function checkHTML(Question $question, string $code): array
    {
        // Remove comments and extra whitespace for checking
        $cleanCode = preg_replace('/<!--.*?-->/s', '', $code);
        $codeLower = strtolower($code);
        
        // Material 1: Struktur Dasar HTML
        if ($question->material_id === 1) {
            return $this->checkMaterial1($question, $code, $codeLower);
        }
        
        // Material 2: HTML Lanjut
        if ($question->material_id === 2) {
            return $this->checkMaterial2($question, $code, $codeLower);
        }
        
        // Material 3: HTML Interaktif
        if ($question->material_id === 3) {
            return $this->checkMaterial3($question, $code, $codeLower);
        }
        
        // Default check
        return $this->checkHTMLDefault($code);
    }
    
    /**
     * Check Material 1: Struktur Dasar HTML
     */
    private function checkMaterial1(Question $question, string $code, string $codeLower): array
    {
        // Soal 1: Kerangka HTML Dasar
        if ($question->order === 1) {
            if (!$this->hasTag($code, 'html')) {
                return ['status' => 'incorrect', 'message' => 'Tag <html> belum ada atau belum ditutup.'];
            }
            if (!$this->hasTag($code, 'head')) {
                return ['status' => 'incorrect', 'message' => 'Tag <head> belum ada atau belum ditutup.'];
            }
            if (!$this->hasTag($code, 'title')) {
                return ['status' => 'incorrect', 'message' => 'Tag <title> belum ada di dalam <head>.'];
            }
            if (!$this->hasTag($code, 'body')) {
                return ['status' => 'incorrect', 'message' => 'Tag <body> belum ada atau belum ditutup.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Struktur HTML dasar sudah lengkap.'];
        }
        
        // Soal 2: Judul Halaman
        if ($question->order === 2) {
            if (!$this->hasTag($code, 'h1')) {
                return ['status' => 'incorrect', 'message' => 'Tag <h1> belum ada. Tambahkan heading di dalam body.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Heading <h1> sudah ditambahkan.'];
        }
        
        // Soal 3: Paragraf Konten
        if ($question->order === 3) {
            $pCount = substr_count($codeLower, '<p>') + substr_count($codeLower, '<p ');
            if ($pCount < 2) {
                return ['status' => 'incorrect', 'message' => 'Belum ada 2 paragraf. Tambahkan 2 tag <p>.'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Dua paragraf sudah ditambahkan.'];
        }
        
        // Soal 4: Komentar HTML
        if ($question->order === 4) {
            $commentCount = substr_count($code, '<!--');
            if ($commentCount < 2) {
                return ['status' => 'incorrect', 'message' => 'Belum ada cukup komentar. Tambahkan minimal 2 komentar.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Komentar HTML sudah ditambahkan.'];
        }
        
        // Soal 5: Halaman Lengkap
        if ($question->order === 5) {
            if (!$this->hasTag($code, 'html') || !$this->hasTag($code, 'head') || !$this->hasTag($code, 'body')) {
                return ['status' => 'incorrect', 'message' => 'Struktur HTML belum lengkap.'];
            }
            if (!$this->hasTag($code, 'title')) {
                return ['status' => 'incorrect', 'message' => 'Tag <title> belum ada di dalam <head>.'];
            }
            if (stripos($code, '<meta') === false || stripos($code, 'charset') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <meta charset="UTF-8"> belum ada.'];
            }
            if (!$this->hasTag($code, 'h1')) {
                return ['status' => 'incorrect', 'message' => 'Tag <h1> belum ada di dalam body.'];
            }
            $pCount = substr_count($codeLower, '<p>') + substr_count($codeLower, '<p ');
            if ($pCount < 2) {
                return ['status' => 'incorrect', 'message' => 'Belum ada 2 paragraf di dalam body.'];
            }
            return ['status' => 'correct', 'message' => 'Luar biasa! Halaman HTML lengkap sudah sempurna.'];
        }
        
        return $this->checkHTMLDefault($code);
    }
    
    /**
     * Check Material 2: HTML Lanjut
     */
    private function checkMaterial2(Question $question, string $code, string $codeLower): array
    {
        // Soal 6: Link Internal
        if ($question->order === 1) {
            if (!preg_match('/<a[^>]+href=["\']#[^"\']+["\'][^>]*>/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Link internal dengan href="#..." belum ada.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Link internal sudah dibuat dengan benar.'];
        }
        
        // Soal 7: Link Eksternal
        if ($question->order === 2) {
            if (!preg_match('/<a[^>]+href=["\']https?:\/\/[^"\']+["\'][^>]*>/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Link eksternal dengan URL lengkap belum ada.'];
            }
            if (stripos($code, 'target="_blank"') === false) {
                return ['status' => 'incorrect', 'message' => 'Atribut target="_blank" belum ditambahkan pada link.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Link eksternal dengan target="_blank" sudah benar.'];
        }
        
        // Soal 8: Tabel Data
        if ($question->order === 3) {
            if (!$this->hasTag($code, 'table')) {
                return ['status' => 'incorrect', 'message' => 'Tag <table> belum ada.'];
            }
            if (!$this->hasTag($code, 'thead') || !$this->hasTag($code, 'th')) {
                return ['status' => 'incorrect', 'message' => 'Tag <thead> dengan <th> untuk header tabel belum ada.'];
            }
            if (!$this->hasTag($code, 'tbody') || !$this->hasTag($code, 'tr')) {
                return ['status' => 'incorrect', 'message' => 'Tag <tbody> dengan <tr> untuk data tabel belum ada.'];
            }
            $thCount = substr_count($codeLower, '<th');
            if ($thCount < 3) {
                return ['status' => 'incorrect', 'message' => 'Header tabel harus memiliki minimal 3 kolom (<th>).'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Tabel dengan struktur lengkap sudah dibuat.'];
        }
        
        // Soal 9: Iframe
        if ($question->order === 4) {
            if (stripos($code, '<iframe') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <iframe> belum ada.'];
            }
            if (!preg_match('/<iframe[^>]+src=["\'][^"\']+["\'][^>]*>/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Tag dengan attribut src belum ada. Contoh: <iframe src="https://www.google.com"></iframe>'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! <iframe> sudah dibuat dengan src yang valid. Iframe digunakan untuk meng-embed halaman web lain.'];
        }
        
        // Soal 10: Halaman Navigasi Lengkap
        if ($question->order === 5) {
            $linkCount = substr_count($codeLower, '<a ') + substr_count($codeLower, '<a>');
            if ($linkCount < 3) {
                return ['status' => 'incorrect', 'message' => 'Navigasi harus memiliki minimal 3 link (<a>).'];
            }
            if (stripos($code, '<table') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <table> belum ditambahkan.'];
            }
            if (stripos($code, '<iframe') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <iframe> belum ditambahkan.'];
            }
            return ['status' => 'correct', 'message' => 'Luar biasa! Halaman lengkap dengan navigasi, tabel, dan iframe sudah sempurna.'];
        }
        
        return $this->checkHTMLDefault($code);
    }
    
    /**
     * Check Material 3: HTML Interaktif
     */
    private function checkMaterial3(Question $question, string $code, string $codeLower): array
    {
        // Soal 11: Form Sederhana
        if ($question->order === 1) {
            if (stripos($code, '<form') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <form> belum ada.'];
            }
            if (!preg_match('/<input[^>]+type=["\']text["\'][^>]*>/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Input type="text" belum ditambahkan.'];
            }
            if (stripos($code, '<button') === false) {
                return ['status' => 'incorrect', 'message' => 'Button submit belum ditambahkan.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Form sederhana sudah dibuat dengan benar.'];
        }
        
        // Soal 12: Select Dropdown
        if ($question->order === 2) {
            if (stripos($code, '<select') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <select> belum ada.'];
            }
            $optionCount = substr_count($codeLower, '<option');
            if ($optionCount < 3) {
                return ['status' => 'incorrect', 'message' => 'Select harus memiliki minimal 3 <option>.'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Select dropdown dengan 3 option sudah dibuat.'];
        }
        
        // Soal 13: Textarea dengan Label
        if ($question->order === 3) {
            if (stripos($code, '<label') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <label> belum ada.'];
            }
            if (stripos($code, '<textarea') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <textarea> belum ada.'];
            }
            // Check if label has for attribute
            if (!preg_match('/<label[^>]+for=["\'][^"\']+["\'][^>]*>/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Atribut for pada <label> belum ada.'];
            }
            // Check if textarea has id attribute
            if (!preg_match('/<textarea[^>]+id=["\'][^"\']+["\'][^>]*>/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Atribut id pada <textarea> belum ada untuk menghubungkan dengan label.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Label dan textarea sudah terhubung dengan benar.'];
        }
        
        // Soal 14: Checkbox dengan Label
        if ($question->order === 4) {
            if (!preg_match('/<input[^>]+type=["\']checkbox["\'][^>]*>/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Input type="checkbox" belum ada.'];
            }
            if (stripos($code, '<label') === false) {
                return ['status' => 'incorrect', 'message' => 'Tag <label> untuk checkbox belum ada.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Checkbox dengan label sudah dibuat.'];
        }
        
        // Soal 15: Bug Hunt Form
        if ($question->order === 5) {
            // Check if bugs are fixed
            $hasBugs = false;
            $bugMessages = [];
            
            // Bug 1: Label for nama tidak terhubung
            if (!preg_match('/<label[^>]+for=["\']nama["\'][^>]*>/i', $code)) {
                $bugMessages[] = 'Label pertama harus memiliki for="nama"';
                $hasBugs = true;
            }
            
            // Bug 2: Input email id tidak match dengan label
            if (preg_match('/<label[^>]+for=["\']email["\'][^>]*>/i', $code) && 
                !preg_match('/<input[^>]+id=["\']email["\'][^>]*>/i', $code)) {
                $bugMessages[] = 'ID pada input email harus "email" bukan "emailField"';
                $hasBugs = true;
            }
            
            // Bug 3: Input type harus email bukan text
            if (preg_match('/<input[^>]+type=["\']text["\'][^>]+email/i', $code)) {
                $bugMessages[] = 'Input email harus menggunakan type="email"';
                $hasBugs = true;
            }
            
            // Bug 4: Checkbox tidak terhubung dengan label
            if (!preg_match('/<input[^>]+type=["\']checkbox["\'][^>]+id=["\'][^"\']+["\'][^>]*>/i', $code) ||
                !preg_match('/<label[^>]+for=["\'][^"\']+["\'][^>]*>.*?[Ss]etuju/i', $code)) {
                $bugMessages[] = 'Checkbox dan labelnya harus terhubung dengan id dan for';
                $hasBugs = true;
            }
            
            // Bug 5: Button harus punya type="submit"
            if (!preg_match('/<button[^>]+type=["\']submit["\'][^>]*>/i', $code)) {
                $bugMessages[] = 'Button harus memiliki type="submit"';
                $hasBugs = true;
            }
            
            if ($hasBugs) {
                return ['status' => 'incorrect', 'message' => 'Masih ada bug: ' . implode(', ', $bugMessages)];
            }
            
            return ['status' => 'correct', 'message' => 'Sempurna! Semua bug sudah diperbaiki.'];
        }
        
        return $this->checkHTMLDefault($code);
    }
    
    /**
     * Helper: Check if tag exists with opening and closing
     */
    private function hasTag(string $code, string $tag): bool
    {
        $opening = stripos($code, "<$tag") !== false || stripos($code, "<$tag>") !== false;
        $closing = stripos($code, "</$tag>") !== false;
        return $opening && $closing;
    }
    
    /**
     * Default HTML check
     */
    private function checkHTMLDefault(string $code): array
    {
        $codeLower = strtolower($code);
        $hasBasicTags = stripos($code, '<html') !== false || 
                        stripos($code, '<body') !== false || 
                        stripos($code, '<div') !== false ||
                        stripos($code, '<p') !== false;
        
        if (!$hasBasicTags) {
            return ['status' => 'incorrect', 'message' => 'Kode HTML belum sesuai.'];
        }
        
        return ['status' => 'correct', 'message' => 'Bagus! Kode HTML sudah benar.'];
    }

    /**
     * Check CSS code
     */
    private function checkCSS(Question $question, string $code): array
    {
        $codeLower = strtolower($code);
        
        // Material 4: Dasar CSS
        if ($question->material_id === 4) {
            return $this->checkMaterial4($question, $code, $codeLower);
        }
        
        // Material 5: Layout & Interaksi CSS
        if ($question->material_id === 5) {
            return $this->checkMaterial5($question, $code, $codeLower);
        }
        
        // Default check
        return $this->checkCSSDefault($code);
    }
    
    /**
     * Check Material 4: Dasar CSS
     */
    private function checkMaterial4(Question $question, string $code, string $codeLower): array
    {
        // Soal 1: Warna Teks
        if ($question->order === 1) {
            // Check for h1 selector
            if (!preg_match('/h1\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Selector h1 belum ada. Gunakan h1 { ... }'];
            }
            // Check for color property
            if (stripos($code, 'color') === false) {
                return ['status' => 'incorrect', 'message' => 'Property color belum ditambahkan pada h1.'];
            }
            // Check for blue color value
            if (stripos($code, 'blue') === false && stripos($code, '#0000ff') === false && stripos($code, 'rgb(0,0,255)') === false && stripos($code, 'rgb(0, 0, 255)') === false) {
                return ['status' => 'incorrect', 'message' => 'Warna biru belum diterapkan. Gunakan blue, #0000ff, atau rgb(0,0,255).'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Warna teks h1 sudah diubah menjadi biru.'];
        }
        
        // Soal 2: Ukuran Font
        if ($question->order === 2) {
            // Check for class selector
            if (!preg_match('/\.besar\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Class selector .besar belum ada. Gunakan .besar { ... }'];
            }
            // Check for font-size property
            if (stripos($code, 'font-size') === false) {
                return ['status' => 'incorrect', 'message' => 'Property font-size belum ditambahkan.'];
            }
            // Check for 18px value
            if (stripos($code, '18px') === false) {
                return ['status' => 'incorrect', 'message' => 'Ukuran font harus 18px.'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Ukuran font sudah diatur 18px.'];
        }
        
        // Soal 3: Background Color
        if ($question->order === 3) {
            // Check for id selector
            if (!preg_match('/#kotak\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'ID selector #kotak belum ada. Gunakan #kotak { ... }'];
            }
            // Check for background-color or background property
            if (stripos($code, 'background-color') === false && stripos($code, 'background:') === false) {
                return ['status' => 'incorrect', 'message' => 'Property background-color belum ditambahkan pada #kotak.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Background color sudah diterapkan.'];
        }
        
        // Soal 4: Padding dan Margin
        if ($question->order === 4) {
            // Check for class selector
            if (!preg_match('/\.kartu\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Class selector .kartu belum ada.'];
            }
            // Check for padding
            if (stripos($code, 'padding') === false) {
                return ['status' => 'incorrect', 'message' => 'Property padding belum ditambahkan.'];
            }
            // Check for 20px padding
            if (!preg_match('/padding\s*:\s*20px/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Padding harus 20px.'];
            }
            // Check for margin
            if (stripos($code, 'margin') === false) {
                return ['status' => 'incorrect', 'message' => 'Property margin belum ditambahkan.'];
            }
            // Check for 10px margin
            if (!preg_match('/margin\s*:\s*10px/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Margin harus 10px.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Padding dan margin sudah diatur dengan benar.'];
        }
        
        // Soal 5: Stylesheet Terorganisir
        if ($question->order === 5) {
            // Check for tag selector (h1)
            if (!preg_match('/h1\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Tag selector h1 belum ada.'];
            }
            // Check for class selector (.intro)
            if (!preg_match('/\.intro\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Class selector .intro belum ada.'];
            }
            // Check for id selector (#footer)
            if (!preg_match('/#footer\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'ID selector #footer belum ada.'];
            }
            return ['status' => 'correct', 'message' => 'Luar biasa! Stylesheet terorganisir dengan baik menggunakan tag, class, dan id selector.'];
        }
        
        return $this->checkCSSDefault($code);
    }
    
    /**
     * Check Material 5: Layout & Interaksi CSS
     */
    private function checkMaterial5(Question $question, string $code, string $codeLower): array
    {
        // Soal 1: Flexbox Horizontal
        if ($question->order === 1) {
            // Check for display: flex
            if (!preg_match('/display\s*:\s*flex/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Property display: flex belum ditambahkan.'];
            }
            // Check for .container selector
            if (!preg_match('/\.container\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Selector .container belum ada.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Flexbox horizontal sudah diterapkan.'];
        }
        
        // Soal 2: Gap pada Flexbox
        if ($question->order === 2) {
            // Check for gap property
            if (stripos($code, 'gap') === false) {
                return ['status' => 'incorrect', 'message' => 'Property gap belum ditambahkan.'];
            }
            // Check for 20px value
            if (!preg_match('/gap\s*:\s*20px/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Gap harus 20px.'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Gap 20px sudah diterapkan pada flexbox.'];
        }
        
        // Soal 3: Tombol dengan Hover
        if ($question->order === 3) {
            // Check for .btn selector
            if (!preg_match('/\.btn\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Selector .btn belum ada untuk style tombol.'];
            }
            // Check for border-radius
            if (stripos($code, 'border-radius') === false) {
                return ['status' => 'incorrect', 'message' => 'Property border-radius belum ditambahkan.'];
            }
            // Check for :hover pseudo-class
            if (!preg_match('/\.btn\s*:\s*hover\s*{/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Pseudo-class :hover belum ditambahkan pada .btn'];
            }
            // Check for transition (optional but good practice)
            if (stripos($code, 'transition') === false) {
                return ['status' => 'incorrect', 'message' => 'Property transition belum ditambahkan untuk animasi hover yang smooth.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Tombol dengan hover effect sudah dibuat.'];
        }
        
        // Soal 4: Layout Dua Kolom
        if ($question->order === 4) {
            // Check for display: flex
            if (!preg_match('/display\s*:\s*flex/', $codeLower)) {
                return ['status' => 'incorrect', 'message' => 'Layout flexbox belum diterapkan.'];
            }
            // Check for .layout or .sidebar or .content selectors
            $hasLayoutSelectors = preg_match('/\.layout\s*{/', $codeLower) || 
                                 preg_match('/\.sidebar\s*{/', $codeLower) || 
                                 preg_match('/\.content\s*{/', $codeLower);
            if (!$hasLayoutSelectors) {
                return ['status' => 'incorrect', 'message' => 'Selector untuk layout (.layout, .sidebar, .content) belum ada.'];
            }
            return ['status' => 'correct', 'message' => 'Luar biasa! Layout 2 kolom dengan flexbox sudah dibuat.'];
        }
        
        // Soal 5: Bug Hunt Kartu Interaktif
        if ($question->order === 5) {
            $bugs = [];
            
            // Bug 1: display harus flex bukan block
            if (preg_match('/\.card-container\s*{[^}]*display\s*:\s*block/', $codeLower)) {
                $bugs[] = 'display pada .card-container harus flex bukan block';
            }
            
            // Bug 2: harus ada transition untuk smooth hover
            if (!preg_match('/\.card\s*{[^}]*transition/', $codeLower)) {
                $bugs[] = 'transition belum ditambahkan pada .card untuk hover yang smooth';
            }
            
            // Bug 3: box-shadow harus ada di default state juga
            if (!preg_match('/\.card\s*{[^}]*box-shadow/', $codeLower)) {
                $bugs[] = 'box-shadow harus ditambahkan juga pada .card (bukan hanya di hover)';
            }
            
            if (count($bugs) > 0) {
                return ['status' => 'incorrect', 'message' => 'Masih ada bug: ' . implode(', ', $bugs)];
            }
            
            return ['status' => 'correct', 'message' => 'Sempurna! Semua bug sudah diperbaiki.'];
        }
        
        return $this->checkCSSDefault($code);
    }
    
    /**
     * Default CSS check
     */
    private function checkCSSDefault(string $code): array
    {
        if (stripos($code, '{') === false || stripos($code, '}') === false) {
            return ['status' => 'incorrect', 'message' => 'Syntax CSS belum lengkap.'];
        }
        return ['status' => 'correct', 'message' => 'Bagus! Kode CSS sudah benar.'];
    }

    /**
     * Check JavaScript code
     */
    private function checkJavaScript(Question $question, string $code): array
    {
        $codeLower = strtolower($code);
        
        // Material 6: Dasar JavaScript
        if ($question->material_id === 6) {
            return $this->checkMaterial6($question, $code, $codeLower);
        }
        
        // Material 7: Event & Interaksi JavaScript
        if ($question->material_id === 7) {
            return $this->checkMaterial7($question, $code, $codeLower);
        }
        
        // Material 8: DOM Manipulation JavaScript
        if ($question->material_id === 8) {
            return $this->checkMaterial8($question, $code, $codeLower);
        }
        
        // Default check for other materials
        return $this->checkJavaScriptDefault($code);
    }
    
    /**
     * Check Material 6: Dasar JavaScript
     */
    private function checkMaterial6(Question $question, string $code, string $codeLower): array
    {
        // Soal 1: Variabel dan Output
        if ($question->order === 1) {
            // Check if has variable declaration
            if (stripos($code, 'let') === false && stripos($code, 'const') === false && stripos($code, 'var') === false) {
                return ['status' => 'incorrect', 'message' => 'Deklarasi variabel (let/const/var) belum ada.'];
            }
            // Check if has getElementById or querySelector
            if (stripos($code, 'getElementById') === false && stripos($code, 'querySelector') === false) {
                return ['status' => 'incorrect', 'message' => 'Belum mengakses elemen dengan getElementById atau querySelector.'];
            }
            // Check if has innerText or textContent or innerHTML
            if (stripos($code, 'innerText') === false && stripos($code, 'textContent') === false && stripos($code, 'innerHTML') === false) {
                return ['status' => 'incorrect', 'message' => 'Belum menampilkan ke elemen dengan innerText/textContent/innerHTML.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Variabel dan output sudah benar.'];
        }
        
        // Soal 2: Menggabungkan Variabel
        if ($question->order === 2) {
            // Check for at least 2 variables
            $letCount = substr_count($codeLower, 'let ');
            $constCount = substr_count($codeLower, 'const ');
            $varCount = substr_count($codeLower, 'var ');
            $totalVars = $letCount + $constCount + $varCount;
            
            if ($totalVars < 2) {
                return ['status' => 'incorrect', 'message' => 'Belum ada 2 variabel. Deklarasikan minimal 2 variabel (nama dan semester).'];
            }
            // Check if variables are concatenated (has + operator or template literal)
            if (stripos($code, '+') === false && stripos($code, '`') === false) {
                return ['status' => 'incorrect', 'message' => 'Variabel belum digabungkan. Gunakan operator + atau template literal.'];
            }
            // Check if has output
            if (stripos($code, 'getElementById') === false && stripos($code, 'querySelector') === false) {
                return ['status' => 'incorrect', 'message' => 'Belum menampilkan hasil ke elemen output.'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Penggabungan variabel sudah benar.'];
        }
        
        // Soal 3: Operator Aritmatika
        if ($question->order === 3) {
            // Check for arithmetic operators
            $hasArithmetic = (stripos($code, '+') !== false && 
                             (preg_match('/\d+\s*\+\s*\d+/', $code) || preg_match('/\w+\s*\+\s*\w+/', $code))) ||
                            stripos($code, '-') !== false ||
                            stripos($code, '*') !== false ||
                            stripos($code, '/') !== false;
            
            if (!$hasArithmetic) {
                return ['status' => 'incorrect', 'message' => 'Belum ada operasi aritmatika (+, -, *, /).'];
            }
            // Check if result is displayed
            if (stripos($code, 'getElementById') === false && stripos($code, 'querySelector') === false) {
                return ['status' => 'incorrect', 'message' => 'Hasil operasi belum ditampilkan ke elemen output.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Operasi aritmatika sudah benar.'];
        }
        
        // Soal 4: Function
        if ($question->order === 4) {
            // Check if has function declaration
            if (stripos($code, 'function') === false && !preg_match('/=\s*\(.*?\)\s*=>/', $code)) {
                return ['status' => 'incorrect', 'message' => 'Deklarasi function belum ada. Buat function dengan keyword function atau arrow function.'];
            }
            // Check if function is called - must have () that's NOT part of function declaration
            // Look for pattern: identifier followed by () but exclude "function identifier()"
            $hasCall = preg_match('/(?<!function\s)(\w+)\s*\([^)]*\)\s*;/', $code) ||
                      preg_match('/(\w+)\s*\([^)]*\)(?!\s*{)/', $code);
            
            if (!$hasCall) {
                return ['status' => 'incorrect', 'message' => 'Function belum dipanggil. Panggil function yang sudah dibuat.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Function sudah dibuat dan dipanggil.'];
        }
        
        // Soal 5: Event Listener
        if ($question->order === 5) {
            // Check if has addEventListener
            if (stripos($code, 'addEventListener') === false && stripos($code, 'onclick') === false) {
                return ['status' => 'incorrect', 'message' => 'Event listener belum ditambahkan. Gunakan addEventListener atau onclick.'];
            }
            // Check if has function or handler
            if (stripos($code, 'function') === false && !preg_match('/=\s*\(.*?\)\s*=>/', $code)) {
                return ['status' => 'incorrect', 'message' => 'Event handler function belum ada.'];
            }
            return ['status' => 'correct', 'message' => 'Luar biasa! Event listener sudah bekerja dengan baik.'];
        }
        
        return $this->checkJavaScriptDefault($code);
    }
    
    /**
     * Check Material 7: Event & Interaksi JavaScript
     */
    private function checkMaterial7(Question $question, string $code, string $codeLower): array
    {
        // Soal 1: Click Event dengan Function
        if ($question->order === 1) {
            // Check if has addEventListener with 'click'
            if (!preg_match('/addEventListener\s*\(\s*["\']click["\']/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'addEventListener untuk event "click" belum ditambahkan.'];
            }
            // Check if has function handler
            if (stripos($code, 'function') === false && !preg_match('/=\s*\(.*?\)\s*=>/', $code)) {
                return ['status' => 'incorrect', 'message' => 'Event handler function belum dibuat.'];
            }
            // Check if modifies DOM (innerText, textContent, innerHTML, or classList)
            if (stripos($code, 'innerText') === false && 
                stripos($code, 'textContent') === false && 
                stripos($code, 'innerHTML') === false &&
                stripos($code, 'classList') === false) {
                return ['status' => 'incorrect', 'message' => 'Belum memodifikasi elemen (innerText/textContent/innerHTML/classList).'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Click event sudah bekerja dengan benar.'];
        }
        
        // Soal 2: Input Event (keyup)
        if ($question->order === 2) {
            // Check if has addEventListener with 'keyup' or 'input'
            if (!preg_match('/addEventListener\s*\(\s*["\'](?:keyup|input)["\']/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'addEventListener untuk event "keyup" atau "input" belum ditambahkan.'];
            }
            // Check if accesses input value
            if (stripos($code, '.value') === false) {
                return ['status' => 'incorrect', 'message' => 'Nilai input belum diakses dengan .value'];
            }
            // Check if displays result
            if (stripos($code, 'innerText') === false && 
                stripos($code, 'textContent') === false && 
                stripos($code, 'innerHTML') === false) {
                return ['status' => 'incorrect', 'message' => 'Hasil belum ditampilkan ke elemen output.'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Input event sudah capture dengan baik.'];
        }
        
        // Soal 3: Form Submission dengan preventDefault
        if ($question->order === 3) {
            // Check if has addEventListener with 'submit'
            if (!preg_match('/addEventListener\s*\(\s*["\']submit["\']/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'addEventListener untuk event "submit" belum ditambahkan pada form.'];
            }
            // Check if has preventDefault
            if (stripos($code, 'preventDefault') === false) {
                return ['status' => 'incorrect', 'message' => 'event.preventDefault() belum dipanggil untuk mencegah reload halaman.'];
            }
            // Check if accesses form input value
            if (stripos($code, '.value') === false) {
                return ['status' => 'incorrect', 'message' => 'Nilai input dari form belum diakses.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Form submission dengan preventDefault sudah benar.'];
        }
        
        // Soal 4: Calculator Sederhana
        if ($question->order === 4) {
            // Check if has at least 2 input elements accessed
            $valueCount = substr_count($codeLower, '.value');
            if ($valueCount < 2) {
                return ['status' => 'incorrect', 'message' => 'Belum mengakses 2 input (angka1 dan angka2). Gunakan .value'];
            }
            // Check if has arithmetic operation
            $hasArithmetic = preg_match('/[\+\-\*\/]/', $code);
            if (!$hasArithmetic) {
                return ['status' => 'incorrect', 'message' => 'Operasi aritmatika (+, -, *, /) belum ditambahkan.'];
            }
            // Check if has addEventListener
            if (stripos($code, 'addEventListener') === false) {
                return ['status' => 'incorrect', 'message' => 'addEventListener belum ditambahkan untuk tombol hitung.'];
            }
            // Check if displays result
            if (stripos($code, 'innerText') === false && 
                stripos($code, 'textContent') === false && 
                stripos($code, 'innerHTML') === false) {
                return ['status' => 'incorrect', 'message' => 'Hasil perhitungan belum ditampilkan.'];
            }
            return ['status' => 'correct', 'message' => 'Luar biasa! Calculator sederhana sudah berfungsi.'];
        }
        
        // Soal 5: Multiple Event Types
        if ($question->order === 5) {
            // Count different event types
            $hasClick = preg_match('/addEventListener\s*\(\s*["\']click["\']/i', $code);
            $hasMouseOver = preg_match('/addEventListener\s*\(\s*["\'](?:mouseover|mouseenter)["\']/i', $code);
            $hasMouseOut = preg_match('/addEventListener\s*\(\s*["\'](?:mouseout|mouseleave)["\']/i', $code);
            
            $eventCount = ($hasClick ? 1 : 0) + ($hasMouseOver ? 1 : 0) + ($hasMouseOut ? 1 : 0);
            
            if ($eventCount < 2) {
                return ['status' => 'incorrect', 'message' => 'Belum ada minimal 2 jenis event. Gunakan click, mouseover, mouseout, dll.'];
            }
            // Check if modifies styles or classes
            if (stripos($code, 'style') === false && 
                stripos($code, 'classList') === false &&
                stripos($code, 'className') === false) {
                return ['status' => 'incorrect', 'message' => 'Belum memodifikasi style atau class elemen.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Multiple event types sudah diterapkan dengan baik.'];
        }
        
        return $this->checkJavaScriptDefault($code);
    }
    
    /**
     * Check Material 8: DOM Manipulation JavaScript
     */
    private function checkMaterial8(Question $question, string $code, string $codeLower): array
    {
        // Soal 1: Ubah Konten dengan innerHTML
        if ($question->order === 1) {
            // Check if has innerHTML or innerText or textContent
            if (stripos($code, 'innerHTML') === false && 
                stripos($code, 'innerText') === false && 
                stripos($code, 'textContent') === false) {
                return ['status' => 'incorrect', 'message' => 'Properti innerHTML/innerText/textContent belum digunakan untuk mengubah konten.'];
            }
            // Check if gets element
            if (stripos($code, 'getElementById') === false && 
                stripos($code, 'querySelector') === false) {
                return ['status' => 'incorrect', 'message' => 'Belum mengakses elemen dengan getElementById atau querySelector.'];
            }
            // Check if has assignment (=)
            if (!preg_match('/(?:innerHTML|innerText|textContent)\s*=/', $code)) {
                return ['status' => 'incorrect', 'message' => 'Konten belum diubah. Gunakan assignment (=) untuk mengubah konten.'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Konten elemen sudah diubah dengan benar.'];
        }
        
        // Soal 2: Toggle Class
        if ($question->order === 2) {
            // Check if has classList
            if (stripos($code, 'classList') === false) {
                return ['status' => 'incorrect', 'message' => 'classList belum digunakan.'];
            }
            // Check if has toggle, add, or remove
            if (stripos($code, 'toggle') === false && 
                stripos($code, 'add') === false && 
                stripos($code, 'remove') === false) {
                return ['status' => 'incorrect', 'message' => 'Method toggle(), add(), atau remove() belum dipanggil pada classList.'];
            }
            // Check if has addEventListener
            if (stripos($code, 'addEventListener') === false) {
                return ['status' => 'incorrect', 'message' => 'addEventListener belum ditambahkan untuk trigger toggle.'];
            }
            return ['status' => 'correct', 'message' => 'Mantap! Toggle class sudah bekerja dengan sempurna.'];
        }
        
        // Soal 3: Show/Hide Element
        if ($question->order === 3) {
            // Check if modifies style.display
            if (!preg_match('/\.style\.display\s*=/i', $code)) {
                return ['status' => 'incorrect', 'message' => 'Property style.display belum diubah untuk show/hide.'];
            }
            // Check if has 'none' and ('block' or 'flex' or '')
            $hasNone = stripos($code, "'none'") !== false || stripos($code, '"none"') !== false;
            $hasVisible = stripos($code, "'block'") !== false || 
                         stripos($code, '"block"') !== false ||
                         stripos($code, "'flex'") !== false ||
                         stripos($code, '"flex"') !== false ||
                         stripos($code, "''") !== false ||
                         stripos($code, '""') !== false;
            
            if (!$hasNone || !$hasVisible) {
                return ['status' => 'incorrect', 'message' => 'Nilai display harus diubah antara "none" dan "block"/"flex"/"".'];
            }
            return ['status' => 'correct', 'message' => 'Bagus! Show/Hide element sudah berfungsi.'];
        }
        
        // Soal 4: Form Validation
        if ($question->order === 4) {
            // Check if accesses input value
            if (stripos($code, '.value') === false) {
                return ['status' => 'incorrect', 'message' => 'Nilai input belum diakses dengan .value'];
            }
            // Check if has validation (length check, empty check, or comparison)
            $hasValidation = preg_match('/\.value\s*(?:===|!==|==|!=|>|<|\.length)/', $code) ||
                            preg_match('/if\s*\(.*\.value.*\)/', $code);
            
            if (!$hasValidation) {
                return ['status' => 'incorrect', 'message' => 'Validasi input belum ditambahkan (cek panjang, kosong, atau kondisi).'];
            }
            // Check if displays error/success message
            if (stripos($code, 'innerText') === false && 
                stripos($code, 'textContent') === false && 
                stripos($code, 'innerHTML') === false) {
                return ['status' => 'incorrect', 'message' => 'Pesan validasi belum ditampilkan ke elemen.'];
            }
            return ['status' => 'correct', 'message' => 'Sempurna! Form validation sudah bekerja dengan baik.'];
        }
        
        // Soal 5: Todo List (Create & Delete)
        if ($question->order === 5) {
            // Check if creates new element
            if (stripos($code, 'createElement') === false) {
                return ['status' => 'incorrect', 'message' => 'document.createElement() belum digunakan untuk membuat elemen baru.'];
            }
            // Check if appends element
            if (stripos($code, 'appendChild') === false && 
                stripos($code, 'append') === false) {
                return ['status' => 'incorrect', 'message' => 'appendChild() atau append() belum digunakan untuk menambahkan elemen.'];
            }
            // Check if has delete functionality (remove, removeChild, atau innerHTML = '')
            if (stripos($code, 'remove') === false && 
                stripos($code, 'removeChild') === false &&
                !preg_match('/innerHTML\s*=\s*["\']?["\']?/', $code)) {
                return ['status' => 'incorrect', 'message' => 'Fungsi delete belum ditambahkan (remove/removeChild).'];
            }
            // Check if accesses input value
            if (stripos($code, '.value') === false) {
                return ['status' => 'incorrect', 'message' => 'Input value belum diambil untuk todo item baru.'];
            }
            return ['status' => 'correct', 'message' => 'Luar biasa! Todo list dengan create & delete sudah sempurna.'];
        }
        
        return $this->checkJavaScriptDefault($code);
    }
    
    /**
     * Default JavaScript check
     */
    private function checkJavaScriptDefault(string $code): array
    {
        if (stripos($code, 'function') === false && 
            stripos($code, 'const') === false && 
            stripos($code, 'let') === false &&
            stripos($code, 'var') === false) {
            return ['status' => 'incorrect', 'message' => 'Kode JavaScript belum lengkap.'];
        }
        return ['status' => 'correct', 'message' => 'Bagus! Kode JavaScript sudah benar.'];
    }

    /**
     * Check and award badges to user based on progress
     */
    private function checkAndAwardBadges($user): void
    {
        $badges = Badge::all();

        foreach ($badges as $badge) {
            // Check if user already has this badge
            if ($user->badges()->where('badge_id', $badge->id)->exists()) {
                continue;
            }

            // Check badge requirement
            $shouldAward = false;

            switch ($badge->requirement_type) {
                case 'complete_module':
                    // Check if user completed all questions in a specific module
                    $moduleId = $badge->requirement_value;
                    
                    if (!$moduleId) {
                        break;
                    }
                    
                    $totalQuestions = Question::whereHas('material', function ($query) use ($moduleId) {
                        $query->where('module_id', $moduleId);
                    })->count();

                    $completedInModule = UserProgress::where('user_id', $user->id)
                        ->whereNotNull('completed_at')
                        ->whereHas('question.material', function ($query) use ($moduleId) {
                            $query->where('module_id', $moduleId);
                        })
                        ->count();

                    $shouldAward = $completedInModule >= $totalQuestions && $totalQuestions > 0;
                    break;

                case 'complete_all':
                    // Check if user completed all modules
                    $totalModules = 3; // HTML, CSS, JavaScript
                    $completedModules = 0;

                    for ($moduleId = 1; $moduleId <= $totalModules; $moduleId++) {
                        $totalQuestions = Question::whereHas('material', function ($query) use ($moduleId) {
                            $query->where('module_id', $moduleId);
                        })->count();

                        $completedInModule = UserProgress::where('user_id', $user->id)
                            ->whereNotNull('completed_at')
                            ->whereHas('question.material', function ($query) use ($moduleId) {
                                $query->where('module_id', $moduleId);
                            })
                            ->count();

                        if ($completedInModule >= $totalQuestions && $totalQuestions > 0) {
                            $completedModules++;
                        }
                    }

                    $shouldAward = $completedModules >= $totalModules;
                    break;
            }

            // Award badge if criteria met
            if ($shouldAward) {
                $user->badges()->attach($badge->id, [
                    'earned_at' => now(),
                ]);

                // Log activity
                $user->logActivity('earn_badge', $badge->id);
            }
        }
    }
}
