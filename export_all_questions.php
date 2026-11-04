<?php
/**
 * Export all questions to markdown format
 */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=codetide', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get all modules with materials and questions
$modules = $pdo->query('
    SELECT 
        m.id as module_id,
        m.title as module_title,
        m.description as module_description,
        mat.id as material_id,
        mat.title as material_title,
        mat.order as material_order,
        q.id as question_id,
        q.title as question_title,
        q.instruction as question_instruction,
        q.type as question_type,
        q.question_type as question_category,
        q.xp_reward as question_xp,
        q.starter_code as question_code,
        q.checker_file as question_checker,
        q.order as question_order
    FROM modules m
    LEFT JOIN materials mat ON mat.module_id = m.id
    LEFT JOIN questions q ON q.material_id = mat.id
    ORDER BY m.id, mat.order, q.order
')->fetchAll(PDO::FETCH_ASSOC);

$output = "# CodeTide - Daftar Lengkap 40 Soal\n\n";
$output .= "> **Platform Pembelajaran Web Development Berbasis Gamifikasi**  \n";
$output .= "> **Total:** 3 Modules | 8 Materials | 40 Questions  \n";
$output .= "> **Last Updated:** " . date('F d, Y') . "\n\n";
$output .= "---\n\n";

// Table of contents
$output .= "## 📚 Daftar Isi\n\n";
$output .= "- [Module 1: HTML](#module-1-html)\n";
$output .= "- [Module 2: CSS](#module-2-css)\n";
$output .= "- [Module 3: JavaScript](#module-3-javascript)\n";
$output .= "- [Ringkasan](#ringkasan)\n\n";
$output .= "---\n\n";

$currentModule = null;
$currentMaterial = null;
$moduleCount = 0;
$materialCount = 0;
$questionCount = 0;
$totalXP = 0;
$typeCount = ['reguler' => 0, 'bug_hunt' => 0, 'clone' => 0, 'time_mode' => 0];

foreach ($modules as $row) {
    // New module
    if ($currentModule !== $row['module_id']) {
        $currentModule = $row['module_id'];
        $moduleCount++;
        
        $output .= "\n## Module {$moduleCount}: {$row['module_title']}\n\n";
        $output .= "> {$row['module_description']}\n\n";
    }
    
    // New material
    if ($currentMaterial !== $row['material_id'] && $row['material_id']) {
        $currentMaterial = $row['material_id'];
        $materialCount++;
        
        $output .= "### 📖 {$row['material_title']}\n\n";
    }
    
    // Question
    if ($row['question_id']) {
        $questionCount++;
        $totalXP += $row['question_xp'];
        $typeCount[$row['question_type']]++;
        
        // Type badge
        $typeBadge = match($row['question_type']) {
            'reguler' => '📝',
            'bug_hunt' => '🐛',
            'clone' => '🎨',
            'time_mode' => '⏱️',
            default => '❓'
        };
        
        $output .= "#### {$typeBadge} Soal #{$questionCount}: {$row['question_title']}\n\n";
        $output .= "**Reward:** {$row['question_xp']} XP | **Type:** " . ucfirst($row['question_type']) . "\n\n";
        
        // Instruction
        $output .= "**Instruksi:**  \n";
        $output .= "{$row['question_instruction']}\n\n";
        
        // Starter code
        if ($row['question_code'] && trim($row['question_code'])) {
            $lang = $row['question_category'] ?: 'html';
            
            $output .= "**Starter Code:**\n";
            $output .= "```{$lang}\n";
            $output .= trim($row['question_code']);
            $output .= "\n```\n\n";
        }
        
        $output .= "---\n\n";
    }
}

// Summary
$output .= "\n## 📊 Ringkasan\n\n";
$output .= "### Total Content\n";
$output .= "- **Modules:** {$moduleCount}\n";
$output .= "- **Materials:** {$materialCount}\n";
$output .= "- **Questions:** {$questionCount}\n";
$output .= "- **Total XP:** {$totalXP} XP\n";
$output .= "- **Total Coins:** " . ($totalXP * 2) . " coins (1 XP = 2 coins)\n\n";

// Type breakdown
$output .= "### Question Types\n";
foreach ($typeCount as $type => $count) {
    if ($count > 0) {
        $badge = match($type) {
            'reguler' => '📝',
            'bug_hunt' => '🐛',
            'clone' => '🎨',
            'time_mode' => '⏱️',
            default => '❓'
        };
        $output .= "- {$badge} " . ucfirst(str_replace('_', ' ', $type)) . ": {$count}\n";
    }
}
$output .= "\n";

// XP per module
$moduleXP = $pdo->query('
    SELECT 
        m.title as module_name,
        SUM(q.xp_reward) as total_xp,
        COUNT(q.id) as question_count
    FROM modules m
    LEFT JOIN materials mat ON mat.module_id = m.id
    LEFT JOIN questions q ON q.material_id = mat.id
    GROUP BY m.id
    ORDER BY m.id
')->fetchAll(PDO::FETCH_ASSOC);

$output .= "### Per Module\n";
foreach ($moduleXP as $mod) {
    $output .= "- **{$mod['module_name']}:** {$mod['question_count']} questions, {$mod['total_xp']} XP\n";
}
$output .= "\n";

// Badges
$badges = $pdo->query('SELECT * FROM badges ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
$badgeCount = count($badges);
$output .= "### 🏆 Badges\n";
foreach ($badges as $badge) {
    $output .= "- **{$badge['name']}:** {$badge['description']}\n";
}
$output .= "\n";

// Costumes
$costumeCount = $pdo->query('SELECT COUNT(*) FROM costumes')->fetchColumn();
$output .= "### 👕 Costumes\n";
$output .= "- Total available: {$costumeCount} unlockable costumes\n";
$output .= "- See full costume list in project documentation\n\n";

$output .= "---\n\n";
$output .= "*Generated from CodeTide database on " . date('F d, Y H:i:s') . "*\n";

// Save to file
file_put_contents(__DIR__ . '/CODETIDE_QUESTIONS.md', $output);

echo "✅ Questions exported successfully!\n";
echo "📄 File: CODETIDE_QUESTIONS.md\n";
echo "📊 Stats:\n";
echo "   - Modules: {$moduleCount}\n";
echo "   - Materials: {$materialCount}\n";
echo "   - Questions: {$questionCount}\n";
echo "   - Total XP: {$totalXP}\n";
echo "   - File size: " . round(strlen($output) / 1024, 1) . " KB\n";
