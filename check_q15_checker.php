<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simulasi kode yang "benar" (semua bug sudah diperbaiki dari starter code)
$fixedCode = '<!DOCTYPE html>
<html>
<body>
    <form>
        <label for="nama">Nama</label>
        <input type="text" id="nama">
        
        <label for="email">Email</label>
        <input type="email" id="email">
        
        <label for="pesan">Pesan</label>
        <textarea id="pesan"></textarea>
        
        <input type="checkbox" id="setuju">
        <label for="setuju">Setuju dengan syarat</label>
        
        <button type="submit">Kirim</button>
    </form>
</body>
</html>';

echo "=== Testing Q15 Checker Manually ===\n\n";

// Bug 1: Label for nama
$r1 = preg_match('/<label[^>]+for=["\']nama["\'][^>]*>/i', $fixedCode);
echo "Bug1 (label for=nama): " . ($r1 ? "PASSED ✅" : "FAILED ❌") . "\n";

// Bug 2: Input email id
$hasLabelEmail = preg_match('/<label[^>]+for=["\']email["\'][^>]*>/i', $fixedCode);
$hasInputEmail = preg_match('/<input[^>]+id=["\']email["\'][^>]*>/i', $fixedCode);
echo "Bug2 (input id=email): hasLabelEmail=" . ($hasLabelEmail ? "yes" : "no") . " hasInputId=" . ($hasInputEmail ? "yes" : "no") . "\n";
// Condition: has label for=email AND NOT has input id=email -> bug
$bug2 = $hasLabelEmail && !$hasInputEmail;
echo "Bug2 detected: " . ($bug2 ? "FAILED ❌" : "PASSED ✅") . "\n";

// Bug 3: Input type email bukan text
$r3 = preg_match('/<input[^>]+type=["\']text["\'][^>]+email/i', $fixedCode);
echo "Bug3 (type=text with email): " . ($r3 ? "FAILED ❌ bug masih ada" : "PASSED ✅") . "\n";

// Bug 4: Checkbox dengan id & label for
$r4a = preg_match('/<input[^>]+type=["\']checkbox["\'][^>]+id=["\'][^"\']+["\'][^>]*>/i', $fixedCode);
$r4b = preg_match('/<label[^>]+for=["\'][^"\']+["\'][^>]*>.*?[Ss]etuju/i', $fixedCode);
echo "Bug4 (checkbox id & label for setuju): " . ($r4a && $r4b ? "PASSED ✅" : "FAILED ❌ (r4a=$r4a, r4b=$r4b)") . "\n";

// Bug 5: Button type=submit
$r5 = preg_match('/<button[^>]+type=["\']submit["\'][^>]*>/i', $fixedCode);
echo "Bug5 (button type=submit): " . ($r5 ? "PASSED ✅" : "FAILED ❌") . "\n";

echo "\n=== Hasil akhir ===\n";
$hasBugs = !$r1 || $bug2 || $r3 || !($r4a && $r4b) || !$r5;
echo $hasBugs ? "MASIH ADA BUG ❌\n" : "SEMUA BUG SUDAH DIPERBAIKI ✅\n";

// Test starter code (harus ada bugs)
echo "\n=== Testing STARTER CODE (harus gagal) ===\n";
$starterCode = DB::table('questions')->find(15)->starter_code;
$s1 = preg_match('/<label[^>]+for=["\']nama["\'][^>]*>/i', $starterCode);
$sL = preg_match('/<label[^>]+for=["\']email["\'][^>]*>/i', $starterCode);
$sI = preg_match('/<input[^>]+id=["\']email["\'][^>]*>/i', $starterCode);
$s3 = preg_match('/<input[^>]+type=["\']text["\'][^>]+email/i', $starterCode);
$s4a = preg_match('/<input[^>]+type=["\']checkbox["\'][^>]+id=["\'][^"\']+["\'][^>]*>/i', $starterCode);
$s4b = preg_match('/<label[^>]+for=["\'][^"\']+["\'][^>]*>.*?[Ss]etuju/i', $starterCode);
$s5 = preg_match('/<button[^>]+type=["\']submit["\'][^>]*>/i', $starterCode);
echo "Bug1 detected: " . (!$s1 ? "YES ✅" : "NO (mestinya ada)") . "\n";
echo "Bug2 detected: " . ($sL && !$sI ? "YES ✅" : "NO (sL=$sL, sI=$sI)") . "\n";
echo "Bug3 detected: " . ($s3 ? "YES ✅" : "NO (mestinya ada bug)") . "\n";
echo "Bug4 detected: " . (!($s4a && $s4b) ? "YES ✅ (s4a=$s4a, s4b=$s4b)" : "NO") . "\n";
echo "Bug5 detected: " . (!$s5 ? "YES ✅" : "NO (mestinya ada bug)") . "\n";

