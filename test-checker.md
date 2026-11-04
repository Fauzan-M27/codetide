# 🧪 Checker Logic Manual Testing Guide

## Quick Start

1. **Login sebagai user test**: FAU2026
2. **Buka soal yang ingin ditest**
3. **Submit kode sesuai test case**
4. **Verify response message**

---

## ✅ Test Cases per Material

### Material 1: HTML Struktur (ID 1-5)

#### Soal 1: Kerangka HTML
- ❌ **Wrong**: `<html><body></body></html>`
  - Expected: "Tag <head> belum ada atau belum ditutup."
- ✅ **Correct**: 
  ```html
  <!DOCTYPE html>
  <html>
  <head><title>Test</title></head>
  <body></body>
  </html>
  ```

#### Soal 2: Heading
- ❌ **Wrong**: `<body><p>Text</p></body>`
  - Expected: "Tag <h1> belum ada."
- ✅ **Correct**: `<body><h1>Heading</h1></body>`

#### Soal 3: Paragraf
- ❌ **Wrong**: `<p>Only one</p>`
  - Expected: "Belum ada 2 paragraf."
- ✅ **Correct**: `<p>First</p><p>Second</p>`

#### Soal 4: Komentar
- ❌ **Wrong**: `<!-- Only one -->`
  - Expected: "Belum ada cukup komentar."
- ✅ **Correct**: `<!-- Comment 1 --><!-- Comment 2 -->`

#### Soal 5: Halaman Lengkap
- ❌ **Wrong**: Missing meta charset
- ✅ **Correct**: Complete HTML with meta, title, h1, 2 paragraphs

---

### Material 2: HTML Lanjut (ID 6-10)

#### Soal 1: Link Internal
- ❌ **Wrong**: `<a href="http://google.com">Link</a>`
  - Expected: "Link internal dengan href="#..." belum ada."
- ✅ **Correct**: `<a href="#section">Link</a>`

#### Soal 2: Link Eksternal
- ❌ **Wrong**: `<a href="http://google.com">Link</a>` (no target)
  - Expected: "Atribut target=\"_blank\" belum ditambahkan."
- ✅ **Correct**: `<a href="http://google.com" target="_blank">Link</a>`

#### Soal 4: Iframe
- ❌ **Wrong**: `<iframe></iframe>`
  - Expected: "Tag dengan attribut src belum ada."
- ✅ **Correct**: `<iframe src="https://www.google.com"></iframe>`

---

### Material 3: HTML Interaktif (ID 11-15)

#### Soal 1: Form Sederhana
- ❌ **Wrong**: `<form><input type="text"></form>`
  - Expected: "Button submit belum ditambahkan."
- ✅ **Correct**: `<form><input type="text"><button>Submit</button></form>`

#### Soal 2: Select Dropdown
- ❌ **Wrong**: `<select><option>A</option><option>B</option></select>`
  - Expected: "Select harus memiliki minimal 3 <option>."
- ✅ **Correct**: `<select><option>A</option><option>B</option><option>C</option></select>`

#### Soal 3: Textarea dengan Label
- ❌ **Wrong**: Missing for/id connection
- ✅ **Correct**: `<label for="msg">Message:</label><textarea id="msg"></textarea>`

---

### Material 4: CSS Dasar (ID 16-20)

#### Soal 1: Warna Teks
- ❌ **Wrong**: `h1 { color: red; }`
  - Expected: "Warna biru belum diterapkan."
- ✅ **Correct**: `h1 { color: blue; }`

#### Soal 2: Ukuran Font
- ❌ **Wrong**: `.besar { font-size: 16px; }`
  - Expected: "Ukuran font harus 18px."
- ✅ **Correct**: `.besar { font-size: 18px; }`

#### Soal 4: Padding & Margin
- ❌ **Wrong**: `.kartu { padding: 20px; }`
  - Expected: "Property margin belum ditambahkan."
- ✅ **Correct**: `.kartu { padding: 20px; margin: 10px; }`

---

### Material 5: CSS Layout (ID 21-25)

#### Soal 1: Flexbox
- ❌ **Wrong**: `.container { width: 100%; }`
  - Expected: "Property display: flex belum ditambahkan."
- ✅ **Correct**: `.container { display: flex; }`

#### Soal 2: Gap
- ❌ **Wrong**: `.container { display: flex; }`
  - Expected: "Property gap belum ditambahkan."
- ✅ **Correct**: `.container { display: flex; gap: 20px; }`

#### Soal 3: Hover Effect
- ❌ **Wrong**: Missing `:hover`
- ✅ **Correct**: `.btn { border-radius: 5px; transition: all 0.3s; } .btn:hover { background: blue; }`

---

### Material 6: JavaScript Dasar (ID 26-30)

#### Soal 1: Variabel Output
- ❌ **Wrong**: `let nama = "John";`
  - Expected: "Belum menampilkan ke elemen."
- ✅ **Correct**: `let nama = "John"; document.getElementById("output").innerText = nama;`

#### Soal 2: Concatenation
- ❌ **Wrong**: Only 1 variable
- ✅ **Correct**: `let nama = "John"; let semester = 3; document.getElementById("output").innerText = nama + " semester " + semester;`

#### Soal 4: Function
- ❌ **Wrong**: `function sapa() { return "Hi"; }`
  - Expected: "Function belum dipanggil."
- ✅ **Correct**: `function sapa() { return "Hi"; } sapa();`

---

### Material 7: JavaScript Events (ID 31-35)

#### Soal 1: Click Event
- ❌ **Wrong**: `function klik() { alert("Hi"); }`
  - Expected: "Event listener belum ditambahkan."
- ✅ **Correct**: 
  ```javascript
  document.getElementById("btn").addEventListener("click", function() {
    document.getElementById("output").innerText = "Clicked";
  });
  ```

#### Soal 2: Input Event
- ❌ **Wrong**: Missing `.value`
- ✅ **Correct**:
  ```javascript
  document.getElementById("input").addEventListener("keyup", function() {
    let value = this.value;
    document.getElementById("output").innerText = value;
  });
  ```

#### Soal 3: preventDefault
- ❌ **Wrong**: Missing `e.preventDefault()`
- ✅ **Correct**:
  ```javascript
  document.getElementById("form").addEventListener("submit", function(e) {
    e.preventDefault();
    let value = document.getElementById("input").value;
  });
  ```

#### Soal 4: Calculator
- ❌ **Wrong**: Only 1 input accessed
- ✅ **Correct**:
  ```javascript
  document.getElementById("btn").addEventListener("click", function() {
    let a = parseFloat(document.getElementById("num1").value);
    let b = parseFloat(document.getElementById("num2").value);
    document.getElementById("result").innerText = a + b;
  });
  ```

---

### Material 8: JavaScript DOM (ID 36-40)

#### Soal 1: innerHTML
- ❌ **Wrong**: `let elem = document.getElementById("output");`
  - Expected: "Konten belum diubah."
- ✅ **Correct**: `document.getElementById("output").innerHTML = "New Content";`

#### Soal 2: Toggle Class
- ❌ **Wrong**: Missing `classList.toggle`
- ✅ **Correct**:
  ```javascript
  document.getElementById("btn").addEventListener("click", function() {
    document.getElementById("box").classList.toggle("active");
  });
  ```

#### Soal 3: Show/Hide
- ❌ **Wrong**: Not using `style.display`
- ✅ **Correct**:
  ```javascript
  document.getElementById("btn").addEventListener("click", function() {
    let box = document.getElementById("box");
    box.style.display = box.style.display === "none" ? "block" : "none";
  });
  ```

#### Soal 4: Form Validation
- ❌ **Wrong**: No validation logic
- ✅ **Correct**:
  ```javascript
  document.getElementById("btn").addEventListener("click", function() {
    let value = document.getElementById("input").value;
    if (value.length < 3) {
      document.getElementById("error").innerText = "Minimal 3 karakter";
    }
  });
  ```

#### Soal 5: Todo List
- ❌ **Wrong**: Missing `createElement` or `appendChild`
- ✅ **Correct**:
  ```javascript
  document.getElementById("btn").addEventListener("click", function() {
    let value = document.getElementById("input").value;
    let li = document.createElement("li");
    li.textContent = value;
    document.getElementById("list").appendChild(li);
    li.addEventListener("click", function() {
      li.remove();
    });
  });
  ```

---

## 🔒 Security Test Cases

### Test 1: Starter Code Rejection
1. Open any question
2. Submit the starter code exactly
3. Expected: "Silakan tulis kode terlebih dahulu."

### Test 2: Empty Code Rejection
1. Open any question
2. Clear all code and submit
3. Expected: "Silakan tulis kode terlebih dahulu."

### Test 3: XP Only Once
1. Complete a question correctly
2. Note the XP earned
3. Submit correct answer again
4. Expected: Status correct but NO xp_earned in response

### Test 4: Random Code Rejection
1. Open any question
2. Submit random gibberish: `asdfghjkl`
3. Expected: Specific error based on question type

---

## 📊 Testing Checklist

### Phase 1: Basic Validation (1 day)
- [ ] Test all 5 Material 1 questions
- [ ] Test all 5 Material 2 questions
- [ ] Test all 5 Material 3 questions
- [ ] Test all 5 Material 4 questions
- [ ] Test all 5 Material 5 questions
- [ ] Test all 5 Material 6 questions
- [ ] Test all 5 Material 7 questions
- [ ] Test all 5 Material 8 questions

### Phase 2: Edge Cases (1 day)
- [ ] Test starter code rejection (10 random questions)
- [ ] Test empty code rejection (10 random questions)
- [ ] Test XP single reward (5 questions)
- [ ] Test random code rejection (10 random questions)
- [ ] Test extremely long code (1KB+)
- [ ] Test special characters in code

### Phase 3: Security (1 day)
- [ ] Test SQL injection attempts in code field
- [ ] Test XSS attempts in code field
- [ ] Test rapid submission (10 submits in 1 second)
- [ ] Test concurrent submissions from same user
- [ ] Test submission after logout

### Phase 4: Integration (1 day)
- [ ] Verify XP correctly added to user total
- [ ] Verify costume unlock triggered correctly
- [ ] Verify badge unlock triggered correctly
- [ ] Verify leaderboard updated correctly
- [ ] Verify next question unlocked correctly
- [ ] Verify activity log recorded correctly

---

## 🐛 Bug Report Template

When you find a bug, report using this format:

```
**Soal**: Material X - Soal Y (Order Z)
**Test Case**: [Correct/Wrong/Edge]
**Code Submitted**:
```
[paste code here]
```
**Expected Result**: [describe]
**Actual Result**: [describe]
**Screenshot**: [if applicable]
**Priority**: [Critical/High/Medium/Low]
```

---

## ✅ Sign-off Checklist

Before launch, confirm:
- [ ] All 40 questions tested with correct answers
- [ ] All 40 questions tested with wrong answers
- [ ] All security test cases passed
- [ ] All integration test cases passed
- [ ] No false positives (wrong answer accepted)
- [ ] No false negatives (correct answer rejected)
- [ ] Error messages are clear and helpful
- [ ] XP rewards working correctly
- [ ] Costume/badge unlocks working correctly

---

**Testing by**: _____________  
**Date**: _____________  
**Status**: [ ] PASS / [ ] FAIL  
**Notes**: 
