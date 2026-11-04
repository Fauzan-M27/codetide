<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Karakter Kustom — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --cyan: #53EAFD;
            --purple: #ED6AFF;
            --yellow: #FFDF20;
            --green: #7BF1A8;
            --bg: #040D1A;
            --bg2: #091326;
            --border: rgba(83, 234, 253, 0.15);
            --text: #E8F4FF;
            --muted: rgba(232, 244, 255, 0.55);
            --ff-pixel: 'Press Start 2P', monospace;
            --ff-code: 'JetBrains Mono', monospace;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--ff-code);
            font-size: 14px;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Canvas background */
        #bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .page-content {
            position: relative;
            z-index: 1;
        }

        /* Navbar */
        .lp-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(4, 13, 26, 0.85);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .lp-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 64px;
            max-width: 1280px;
            margin: 0 auto;
        }

        .lp-logo {
            font-family: var(--ff-pixel);
            font-size: 0.75rem;
            color: var(--purple);
            text-decoration: none;
            letter-spacing: 0.05em;
        }

        .lp-logo .bracket {
            color: var(--cyan);
        }

        .lp-nav-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .lp-nav-links a {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 8px 16px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            transition: all 0.15s;
        }

        .lp-nav-links a:hover {
            color: var(--cyan);
            border-color: var(--cyan);
        }

        /* Container */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 80px 32px 60px;
        }

        /* Header */
        .page-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .page-header h1 {
            font-family: var(--ff-pixel);
            font-size: 1.8rem;
            color: var(--purple);
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .page-header p {
            color: var(--muted);
            font-size: 0.9rem;
        }

        /* Steps Panel */
        .steps-panel {
            background: rgba(9, 19, 38, 0.6);
            border: 1px solid var(--border);
            padding: 32px;
            margin-bottom: 32px;
        }

        .steps-panel h2 {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--cyan);
            margin-bottom: 24px;
        }

        .step {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
            padding: 20px;
            background: rgba(83, 234, 253, 0.05);
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .step:hover {
            border-color: var(--cyan);
            background: rgba(83, 234, 253, 0.1);
        }

        .step-number {
            font-family: var(--ff-pixel);
            font-size: 1.5rem;
            color: var(--yellow);
            flex-shrink: 0;
            width: 40px;
        }

        .step-content h3 {
            font-family: var(--ff-pixel);
            font-size: 0.75rem;
            color: var(--text);
            margin-bottom: 8px;
        }

        .step-content p {
            color: var(--muted);
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .step-content a {
            color: var(--cyan);
            text-decoration: none;
            font-weight: 700;
        }

        .step-content a:hover {
            text-decoration: underline;
        }

        /* CTA Button */
        .cta-button {
            display: inline-block;
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            padding: 16px 32px;
            background: var(--purple);
            color: #000;
            text-decoration: none;
            border: 2px solid var(--purple);
            transition: all 0.3s;
            margin-top: 12px;
        }

        .cta-button:hover {
            box-shadow: 4px 4px 0 rgba(237, 106, 255, 0.5);
            transform: translate(-2px, -2px);
        }

        /* Upload Form */
        .upload-panel {
            background: rgba(9, 19, 38, 0.6);
            border: 1px solid var(--border);
            padding: 32px;
        }

        .upload-panel h2 {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--green);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            color: var(--cyan);
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(9, 19, 38, 0.8);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--ff-code);
            font-size: 0.9rem;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--cyan);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(83, 234, 253, 0.1);
            border: 1px dashed var(--cyan);
            color: var(--cyan);
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .file-upload:hover .file-upload-label {
            background: rgba(83, 234, 253, 0.2);
            border-style: solid;
        }

        .file-info {
            display: inline-block;
            margin-left: 12px;
            color: var(--green);
            font-size: 0.8rem;
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--muted);
            margin-top: 6px;
        }

        .required {
            color: #FF6B6B;
        }

        .submit-button {
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            padding: 14px 32px;
            background: var(--green);
            color: #000;
            border: 2px solid var(--green);
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-button:hover:not(:disabled) {
            box-shadow: 4px 4px 0 rgba(123, 241, 168, 0.5);
            transform: translate(-2px, -2px);
        }

        .submit-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Success message */
        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border: 1px solid;
            font-size: 0.85rem;
        }

        .alert-success {
            background: rgba(123, 241, 168, 0.1);
            border-color: var(--green);
            color: var(--green);
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.1);
            border-color: #FF6B6B;
            color: #FF6B6B;
        }

        /* Preview */
        .preview-container {
            margin-top: 16px;
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .preview-item {
            text-align: center;
        }

        .preview-item img {
            max-width: 100px;
            max-height: 100px;
            image-rendering: pixelated;
            border: 1px solid var(--border);
            background: rgba(9, 19, 38, 0.8);
            padding: 8px;
        }

        .preview-label {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <!-- Canvas Background -->
    <canvas id="bg-canvas"></canvas>

    <div class="page-content">
        
        <!-- Navbar -->
        <nav class="lp-nav">
            <div class="lp-nav-inner">
                <a href="{{ route('dashboard') }}" class="lp-logo">
                    <span class="bracket">&lt;</span>Code<span style="color:var(--cyan)">/</span>Tide<span class="bracket">&gt;</span>
                </a>
                <div class="lp-nav-links">
                    <a href="{{ route('costumes.index') }}">← Kembali</a>
                </div>
            </div>
        </nav>

        <!-- Container -->
        <main class="container">
            
            <!-- Header -->
            <div class="page-header">
                <h1>Buat Karakter Kustom</h1>
                <p>Gunakan Memao Sprite Sheet Creator untuk membuat karakter unik, lalu upload hasilnya!</p>
            </div>

            <!-- Steps -->
            <div class="steps-panel">
                <h2>📝 Panduan Pembuatan</h2>
                
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Buka Memao Creator</h3>
                        <p>Klik tombol di bawah untuk membuka Memao Sprite Sheet Creator di tab baru. Tool ini gratis dan berjalan di browser!</p>
                        <a href="https://sleeping-robot-games.itch.io/sprite-sheet-creator" target="_blank" class="cta-button">
                            🚀 Buka Memao Creator
                        </a>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Desain Karaktermu</h3>
                        <p>Pilih body parts, warna, clothing, dan accessories. Mix & match sesuka hati sampai kamu puas dengan hasilnya!</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Export Animasi</h3>
                        <p>
                            Export animasi berikut (format PNG atau GIF):
                            <br>• <strong>Idle</strong> (wajib) - karakter diam
                            <br>• <strong>Walk/Run</strong> (opsional) - untuk animasi typing/thinking
                            <br>• <strong>Jump/Attack</strong> (opsional) - untuk animasi success
                        </p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Upload di Bawah</h3>
                        <p>Isi form dan upload file sprite yang sudah kamu export. Minimal upload <strong>Idle animation</strong>!</p>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="upload-panel">
                <h2>📤 Upload Karaktermu</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <strong>Terjadi kesalahan:</strong>
                        <ul style="margin-top: 8px; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('costumes.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <div class="form-group">
                        <label for="character_name">Nama Karakter <span class="required">*</span></label>
                        <input type="text" id="character_name" name="character_name" required 
                               placeholder="Contoh: RoboCodex, ByteBuddy, etc" value="{{ old('character_name') }}">
                        <p class="form-hint">Nama unik untuk karakter kustom kamu</p>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea id="description" name="description" 
                                  placeholder="Ceritakan tentang karaktermu...">{{ old('description') }}</textarea>
                        <p class="form-hint">Opsional: personality, backstory, dll</p>
                    </div>

                    <div class="form-group">
                        <label for="sprite_idle">Sprite Idle <span class="required">*</span></label>
                        <div class="file-upload">
                            <input type="file" id="sprite_idle" name="sprite_idle" accept="image/png,image/gif" required onchange="previewFile('sprite_idle')">
                            <div class="file-upload-label">📁 Pilih File</div>
                        </div>
                        <span class="file-info" id="info-sprite_idle"></span>
                        <p class="form-hint">Wajib | Format: PNG atau GIF | Max: 2MB</p>
                    </div>

                    <div class="form-group">
                        <label for="sprite_typing">Sprite Typing/Walking</label>
                        <div class="file-upload">
                            <input type="file" id="sprite_typing" name="sprite_typing" accept="image/png,image/gif" onchange="previewFile('sprite_typing')">
                            <div class="file-upload-label">📁 Pilih File</div>
                        </div>
                        <span class="file-info" id="info-sprite_typing"></span>
                        <p class="form-hint">Opsional | Akan pakai Idle jika tidak diupload</p>
                    </div>

                    <div class="form-group">
                        <label for="sprite_thinking">Sprite Thinking</label>
                        <div class="file-upload">
                            <input type="file" id="sprite_thinking" name="sprite_thinking" accept="image/png,image/gif" onchange="previewFile('sprite_thinking')">
                            <div class="file-upload-label">📁 Pilih File</div>
                        </div>
                        <span class="file-info" id="info-sprite_thinking"></span>
                        <p class="form-hint">Opsional | Animasi saat user diam/mikir</p>
                    </div>

                    <div class="form-group">
                        <label for="sprite_success">Sprite Success/Jump</label>
                        <div class="file-upload">
                            <input type="file" id="sprite_success" name="sprite_success" accept="image/png,image/gif" onchange="previewFile('sprite_success')">
                            <div class="file-upload-label">📁 Pilih File</div>
                        </div>
                        <span class="file-info" id="info-sprite_success"></span>
                        <p class="form-hint">Opsional | Animasi saat jawaban benar</p>
                    </div>

                    <div class="form-group">
                        <label for="sprite_error">Sprite Error/Damage</label>
                        <div class="file-upload">
                            <input type="file" id="sprite_error" name="sprite_error" accept="image/png,image/gif" onchange="previewFile('sprite_error')">
                            <div class="file-upload-label">📁 Pilih File</div>
                        </div>
                        <span class="file-info" id="info-sprite_error"></span>
                        <p class="form-hint">Opsional | Animasi saat jawaban salah</p>
                    </div>

                    <!-- Preview -->
                    <div class="preview-container" id="previewContainer" style="display: none;">
                        <!-- Previews akan muncul di sini -->
                    </div>

                    <div class="form-group" style="margin-top: 32px;">
                        <button type="submit" class="submit-button">
                            ✨ Buat Karakter
                        </button>
                    </div>
                </form>
            </div>

        </main>

    </div>

    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script>
        function previewFile(inputId) {
            const input = document.getElementById(inputId);
            const file = input.files[0];
            const infoSpan = document.getElementById('info-' + inputId);
            const previewContainer = document.getElementById('previewContainer');
            
            if (file) {
                // Show filename
                infoSpan.textContent = `✓ ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                
                // Show preview container
                previewContainer.style.display = 'flex';
                
                // Create or update preview
                let previewItem = document.getElementById('preview-' + inputId);
                if (!previewItem) {
                    previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.id = 'preview-' + inputId;
                    previewContainer.appendChild(previewItem);
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const labelText = inputId.replace('sprite_', '').toUpperCase();
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="${labelText}">
                        <div class="preview-label">${labelText}</div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                infoSpan.textContent = '';
                const previewItem = document.getElementById('preview-' + inputId);
                if (previewItem) {
                    previewItem.remove();
                }
                
                // Hide container if no previews
                if (previewContainer.children.length === 0) {
                    previewContainer.style.display = 'none';
                }
            }
        }

        // Form validation
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const idleInput = document.getElementById('sprite_idle');
            if (!idleInput.files || idleInput.files.length === 0) {
                e.preventDefault();
                alert('Sprite Idle wajib diupload!');
                return false;
            }
        });
    </script>
</body>
</html>
