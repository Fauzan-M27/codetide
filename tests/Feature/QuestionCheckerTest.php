<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use App\Models\Module;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionCheckerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run seeders
        $this->seed();
        
        // Create test user
        $this->user = User::factory()->create([
            'participant_code' => 'TEST123',
            'nickname' => 'TestUser',
        ]);
        
        $this->actingAs($this->user);
    }

    /**
     * Test Material 1: HTML Struktur Dasar
     */
    public function test_material_1_soal_1_kerangka_html()
    {
        $question = Question::where('material_id', 1)->where('order', 1)->first();
        
        // Test wrong answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<html><body></body></html>' // Missing head
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<!DOCTYPE html><html><head><title>Test</title></head><body></body></html>'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_1_soal_2_heading()
    {
        $question = Question::where('material_id', 1)->where('order', 2)->first();
        
        // Test wrong answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<body><p>Text</p></body>' // Missing h1
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<html><body><h1>Heading</h1></body></html>'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_1_soal_3_paragraf()
    {
        $question = Question::where('material_id', 1)->where('order', 3)->first();
        
        // Test with only 1 paragraph
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<body><p>Paragraph 1</p></body>'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer with 2 paragraphs
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<body><p>Paragraph 1</p><p>Paragraph 2</p></body>'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test Material 2: HTML Lanjut
     */
    public function test_material_2_soal_1_link_internal()
    {
        $question = Question::where('material_id', 2)->where('order', 1)->first();
        
        // Test external link (wrong)
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<a href="https://google.com">Link</a>'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct internal link
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<a href="#section">Link</a>'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_2_soal_4_iframe()
    {
        $question = Question::where('material_id', 2)->where('order', 4)->first();
        
        // Test iframe without src
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<iframe></iframe>'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct iframe with src
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<iframe src="https://www.google.com"></iframe>'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test Material 3: HTML Interaktif
     */
    public function test_material_3_soal_1_form_sederhana()
    {
        $question = Question::where('material_id', 3)->where('order', 1)->first();
        
        // Test incomplete form
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<form><input type="text"></form>' // Missing button
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test complete form
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '<form><input type="text" name="name"><button>Submit</button></form>'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test Material 4: CSS Dasar
     */
    public function test_material_4_soal_1_warna_teks()
    {
        $question = Question::where('material_id', 4)->where('order', 1)->first();
        
        // Test without h1 selector
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'p { color: blue; }'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test with h1 but wrong color
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'h1 { color: red; }'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'h1 { color: blue; }'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_4_soal_4_padding_margin()
    {
        $question = Question::where('material_id', 4)->where('order', 4)->first();
        
        // Test without margin
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '.kartu { padding: 20px; }'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '.kartu { padding: 20px; margin: 10px; }'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test Material 5: CSS Layout
     */
    public function test_material_5_soal_1_flexbox()
    {
        $question = Question::where('material_id', 5)->where('order', 1)->first();
        
        // Test without display flex
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '.container { width: 100%; }'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '.container { display: flex; }'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_5_soal_3_hover_effect()
    {
        $question = Question::where('material_id', 5)->where('order', 3)->first();
        
        // Test without hover
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '.btn { border-radius: 5px; transition: all 0.3s; }'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => '.btn { border-radius: 5px; transition: all 0.3s; } .btn:hover { background: blue; }'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test Material 6: JavaScript Dasar
     */
    public function test_material_6_soal_1_variabel_output()
    {
        $question = Question::where('material_id', 6)->where('order', 1)->first();
        
        // Test without DOM manipulation
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'let nama = "John";'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'let nama = "John"; document.getElementById("output").innerText = nama;'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_6_soal_4_function()
    {
        $question = Question::where('material_id', 6)->where('order', 4)->first();
        
        // Test function without call
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'function sapa() { return "Hello"; }'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'function sapa() { return "Hello"; } sapa();'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test Material 7: JavaScript Events
     */
    public function test_material_7_soal_1_click_event()
    {
        $question = Question::where('material_id', 7)->where('order', 1)->first();
        
        // Test without addEventListener
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'function klik() { alert("Hi"); }'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'document.getElementById("btn").addEventListener("click", function() { document.getElementById("output").innerText = "Clicked"; });'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_7_soal_3_prevent_default()
    {
        $question = Question::where('material_id', 7)->where('order', 3)->first();
        
        // Test without preventDefault
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'document.getElementById("form").addEventListener("submit", function(e) { console.log("submitted"); });'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'document.getElementById("form").addEventListener("submit", function(e) { e.preventDefault(); let value = document.getElementById("input").value; });'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test Material 8: JavaScript DOM
     */
    public function test_material_8_soal_1_inner_html()
    {
        $question = Question::where('material_id', 8)->where('order', 1)->first();
        
        // Test without innerHTML
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'let elem = document.getElementById("output");'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'document.getElementById("output").innerHTML = "New Content";'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_8_soal_2_toggle_class()
    {
        $question = Question::where('material_id', 8)->where('order', 2)->first();
        
        // Test without toggle
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'document.getElementById("btn").addEventListener("click", function() {});'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'document.getElementById("btn").addEventListener("click", function() { document.getElementById("box").classList.toggle("active"); });'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    public function test_material_8_soal_5_todo_list()
    {
        $question = Question::where('material_id', 8)->where('order', 5)->first();
        
        // Test without createElement
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'let value = document.getElementById("input").value;'
        ]);
        $response->assertJson(['status' => 'incorrect']);
        
        // Test correct answer
        $response = $this->postJson(route('questions.check', $question), [
            'code' => 'let li = document.createElement("li"); li.textContent = document.getElementById("input").value; document.getElementById("list").appendChild(li); li.addEventListener("click", function() { li.remove(); });'
        ]);
        $response->assertJson(['status' => 'correct']);
    }

    /**
     * Test XP Reward
     */
    public function test_xp_reward_only_once()
    {
        $question = Question::where('material_id', 1)->where('order', 1)->first();
        $correctCode = '<!DOCTYPE html><html><head><title>Test</title></head><body></body></html>';
        
        // First submission
        $response = $this->postJson(route('questions.check', $question), [
            'code' => $correctCode
        ]);
        $response->assertJson(['status' => 'correct']);
        $response->assertJsonStructure(['xp_earned']);
        
        // Second submission (should not give XP again)
        $response = $this->postJson(route('questions.check', $question), [
            'code' => $correctCode
        ]);
        $response->assertJson(['status' => 'correct']);
        $response->assertJsonMissing(['xp_earned']);
    }

    /**
     * Test Starter Code Rejection
     */
    public function test_starter_code_rejected()
    {
        $question = Question::where('material_id', 1)->where('order', 1)->first();
        
        // Submit starter code
        $response = $this->postJson(route('questions.check', $question), [
            'code' => $question->starter_code
        ]);
        $response->assertJson(['status' => 'incomplete']);
    }

    /**
     * Test Empty Code Rejection
     */
    public function test_empty_code_rejected()
    {
        $question = Question::where('material_id', 1)->where('order', 1)->first();
        
        // Submit empty code
        $response = $this->postJson(route('questions.check', $question), [
            'code' => ''
        ]);
        $response->assertJson(['status' => 'incomplete']);
    }
}
