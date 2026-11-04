/**
 * CodeTide — Floating Icon Background v5
 * - Berbagai jenis icon dari berbagai kategori
 * - Ukuran dan transparansi sangat bervariasi untuk depth 3D
 * - Spring repel dari cursor
 * - Natural parallax effect
 */
(function () {
  'use strict';

  /* ─── Canvas ────────────────────────────────────────────────── */
  const canvas = document.createElement('canvas');
  canvas.id = 'bg-canvas';
  canvas.style.cssText = 'position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:0;';
  document.body.prepend(canvas);
  const ctx = canvas.getContext('2d');
  let W = canvas.width  = window.innerWidth;
  let H = canvas.height = window.innerHeight;
  window.addEventListener('resize', () => {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
    placeIcons();
  });

  /* ─── Mouse ─────────────────────────────────────────────────── */
  const mouse = { x: -9999, y: -9999 };
  window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
  window.addEventListener('mouseleave', () => { mouse.x = -9999; mouse.y = -9999; });

  /* ─── Render frame ke offscreen canvas ──────────────────────── */
  function renderFrames(data, pixelSize) {
    const grids = data.frames ? data.frames.map(f => f.grid) : [data.grid];
    return grids.map(grid => {
      const sz   = (data.size || 16) * pixelSize;
      const oc   = document.createElement('canvas');
      oc.width   = sz; oc.height = sz;
      const octx = oc.getContext('2d');
      
      for (let r = 0; r < grid.length; r++) {
        for (let c = 0; c < grid[r].length; c++) {
          const ch = grid[r][c];
          if (ch === '.' || ch === ' ') continue;
          const col = data.palette[ch];
          if (!col) continue;
          octx.fillStyle = col;
          octx.fillRect(c * pixelSize, r * pixelSize, pixelSize, pixelSize);
        }
      }
      return oc;
    });
  }

  /* ─── Partikel kecil bergerak dengan variasi warna ─────────── */
  const PCOLS = ['#53EAFD','#7BF1A8','#ED6AFF','#FFDF20','#A2F4FD','#F4A8FF','#E8F8FF'];
  const particles = Array.from({ length: 60 }, () => ({
    x: Math.random() * window.innerWidth,
    y: Math.random() * window.innerHeight,
    sz: Math.random() * 2.5 + 0.5,
    vx: (Math.random() - 0.5) * 0.4,
    vy: (Math.random() - 0.5) * 0.4,
    col: PCOLS[Math.floor(Math.random() * PCOLS.length)],
    alpha: Math.random() * 0.25 + 0.08,
  }));

  /* ─── Icon class dengan spring repel ──────────────────────── */
  class BgIcon {
    constructor(frames, frameDur, x, y, spinSpeed, alpha, depth) {
      this.frames    = frames;
      this.frameDur  = frameDur;
      this.frameIdx  = Math.floor(Math.random() * frames.length);
      this.frameTimer = 0;
      this.w = frames[0].width;
      this.h = frames[0].height;

      // Posisi tetap (center)
      this.cx = x;
      this.cy = y;

      // Offset dari kursor (spring)
      this.ox = 0; this.oy = 0;
      this.ovx = 0; this.ovy = 0;

      // Rotasi lambat
      this.angle     = Math.random() * Math.PI * 2;
      this.spinSpeed = spinSpeed;

      this.alpha = alpha;
      this.depth = depth; // 1-3: 1=far, 3=near
    }

    update() {
      /* Spring repel dari mouse dengan depth adjustment */
      const rx = this.cx + this.ox - mouse.x;
      const ry = this.cy + this.oy - mouse.y;
      const dist = Math.sqrt(rx * rx + ry * ry) || 1;
      
      // Depth affects repel force: far objects repel less
      const RADIUS = 150 + (this.depth * 30);
      const FORCE = 15000 + (this.depth * 5000);
      
      if (dist < RADIUS) {
        const f = FORCE / (dist * dist);
        this.ovx += (rx / dist) * f * 0.016;
        this.ovy += (ry / dist) * f * 0.016;
      }
      
      // Spring back + damping (far objects more sluggish)
      const spring = 0.06 + (this.depth * 0.02);
      const damp = 0.82 + (this.depth * 0.03);
      
      this.ovx += -this.ox * spring;
      this.ovy += -this.oy * spring;
      this.ovx *= damp;
      this.ovy *= damp;
      this.ox  += this.ovx;
      this.oy  += this.ovy;

      // Rotasi lambat
      this.angle += this.spinSpeed;

      // Animasi frame
      this.frameTimer += 16;
      if (this.frameTimer >= this.frameDur) {
        this.frameTimer = 0;
        this.frameIdx = (this.frameIdx + 1) % this.frames.length;
      }
    }

    draw() {
      const x = this.cx + this.ox - this.w / 2;
      const y = this.cy + this.oy - this.h / 2;
      ctx.save();
      ctx.globalAlpha = this.alpha;
      ctx.translate(x + this.w / 2, y + this.h / 2);
      ctx.rotate(this.angle);
      ctx.drawImage(this.frames[this.frameIdx], -this.w / 2, -this.h / 2);
      ctx.restore();
    }
  }

  /* ─── Pool icon data dengan VARIETY SIZE & ALPHA ─────────── */
  function getPool() {
    if (typeof PXL_FLOATING_GEM === 'undefined') return null;
    
    return [
      // === DEPTH LAYER 1 (FAR) - Large, Very Transparent ===
      { data: PXL_MAGIC_ORB,        scale: 5, spin: 0.001,  alpha: 0.04, depth: 1 },
      { data: PXL_GHOST_FRIEND,     scale: 5, spin: -0.001, alpha: 0.05, depth: 1 },
      { data: PXL_RETRO_TV,         scale: 6, spin: 0.0005, alpha: 0.03, depth: 1 },
      { data: PXL_PORTAL_SPIN,      scale: 5, spin: 0.002,  alpha: 0.04, depth: 1 },
      { data: PXL_FLOATING_GEM,     scale: 6, spin: -0.001, alpha: 0.05, depth: 1 },
      
      // === DEPTH LAYER 2 (MID) - Medium Size, Medium Alpha ===
      { data: PXL_FLOATING_GEM,     scale: 3, spin: 0.003,  alpha: 0.12, depth: 2 },
      { data: PXL_SPARKLE_STAR,     scale: 3, spin: -0.004, alpha: 0.14, depth: 2 },
      { data: PXL_GLOW_PULSE,       scale: 3, spin: 0.002,  alpha: 0.11, depth: 2 },
      { data: PXL_MAGIC_ORB,        scale: 3, spin: -0.003, alpha: 0.13, depth: 2 },
      { data: PXL_TWINKLE,          scale: 3, spin: 0.004,  alpha: 0.10, depth: 2 },
      { data: PXL_CROWN,            scale: 3, spin: -0.002, alpha: 0.12, depth: 2 },
      { data: PXL_TROPHY,           scale: 3, spin: 0.003,  alpha: 0.11, depth: 2 },
      { data: PXL_PIXEL_ROCKET,     scale: 3, spin: -0.003, alpha: 0.13, depth: 2 },
      { data: PXL_SHIELD_SMALL,     scale: 3, spin: 0.004,  alpha: 0.14, depth: 2 },
      { data: PXL_PULSING_DOT,      scale: 3, spin: -0.002, alpha: 0.12, depth: 2 },
      
      // === DEPTH LAYER 3 (NEAR) - Small Size, More Visible ===
      { data: PXL_SPARKLE_SMALL,    scale: 2, spin: 0.005,  alpha: 0.18, depth: 3 },
      { data: PXL_GEM_SMALL,        scale: 2, spin: -0.005, alpha: 0.20, depth: 3 },
      { data: PXL_STAR_SMALL,       scale: 2, spin: 0.006,  alpha: 0.19, depth: 3 },
      { data: PXL_HEART_SMALL,      scale: 2, spin: -0.004, alpha: 0.17, depth: 3 },
      { data: PXL_KEY_SMALL,        scale: 2, spin: 0.005,  alpha: 0.18, depth: 3 },
      { data: PXL_LIGHTNING,        scale: 2, spin: -0.006, alpha: 0.16, depth: 3 },
      { data: PXL_CHECK,            scale: 2, spin: 0.005,  alpha: 0.19, depth: 3 },
      { data: PXL_MEDAL,            scale: 2, spin: -0.005, alpha: 0.17, depth: 3 },
      { data: PXL_COIN,             scale: 2, spin: 0.006,  alpha: 0.18, depth: 3 },
      { data: PXL_SPARK_SMALL,      scale: 2, spin: -0.005, alpha: 0.15, depth: 3 },
      
      // === DEPTH LAYER 2.5 (MID-NEAR) - Mixed Sizes ===
      { data: PXL_FLOATING_GEM,     scale: 4, spin: 0.002,  alpha: 0.09, depth: 2 },
      { data: PXL_GLOW_PULSE,       scale: 4, spin: -0.002, alpha: 0.08, depth: 2 },
      { data: PXL_SPARKLE_STAR,     scale: 2, spin: 0.005,  alpha: 0.16, depth: 3 },
      { data: PXL_TWINKLE,          scale: 2, spin: -0.004, alpha: 0.14, depth: 3 },
      { data: PXL_PULSING_DOT,      scale: 4, spin: 0.002,  alpha: 0.07, depth: 2 },
      
      // === TINY ACCENTS (Very Small, High Alpha) ===
      { data: PXL_SPARKLE_SMALL,    scale: 1, spin: 0.007,  alpha: 0.25, depth: 3 },
      { data: PXL_GEM_SMALL,        scale: 1, spin: -0.007, alpha: 0.24, depth: 3 },
      { data: PXL_STAR_SMALL,       scale: 1, spin: 0.008,  alpha: 0.26, depth: 3 },
      { data: PXL_CHECK,            scale: 1, spin: -0.006, alpha: 0.22, depth: 3 },
      { data: PXL_HEART_SMALL,      scale: 1, spin: 0.007,  alpha: 0.23, depth: 3 },
    ];
  }

  /* ─── Tempatkan icon di posisi tersebar merata ─────────────── */
  let icons = [];
  function placeIcons() {
    if (!icons.length) return;
    icons.forEach(ic => {
      ic.cx = Math.random() * W;
      ic.cy = Math.random() * H;
    });
  }

  function initIcons() {
    const pool = getPool();
    if (!pool) { setTimeout(initIcons, 200); return; }

    // Pre-build semua frame
    const prebuilt = pool.map(({ data, scale, spin, alpha, depth }) => ({
      frames: renderFrames(data, scale),
      frameDur: data.frameDuration || 200,
      spin, alpha, depth,
    }));

    // Jumlah icon: 40 untuk background yang rich
    const COUNT = 40;
    const cols = Math.ceil(Math.sqrt(COUNT * (W / H)));
    const rows = Math.ceil(COUNT / cols);
    const cellW = W / cols;
    const cellH = H / rows;

    icons = Array.from({ length: COUNT }, (_, i) => {
      const pb   = prebuilt[i % prebuilt.length];
      const col  = i % cols;
      const row  = Math.floor(i / cols);
      
      // Random position within cell untuk organic distribution
      const jitterX = (Math.random() - 0.5) * 0.8;
      const jitterY = (Math.random() - 0.5) * 0.8;
      const cx   = cellW * col + cellW * (0.5 + jitterX);
      const cy   = cellH * row + cellH * (0.5 + jitterY);
      
      return new BgIcon(pb.frames, pb.frameDur, cx, cy, pb.spin, pb.alpha, pb.depth);
    });
    
    // Sort by depth: far icons drawn first
    icons.sort((a, b) => a.depth - b.depth);
  }

  /* ─── Main loop ──────────────────────────────────────────────── */
  function loop() {
    requestAnimationFrame(loop);
    ctx.clearRect(0, 0, W, H);

    /* Dot-grid sangat samar untuk texture */
    ctx.fillStyle = 'rgba(83,234,253,0.015)';
    for (let gx = 0; gx < W; gx += 60) {
      for (let gy = 0; gy < H; gy += 60) {
        ctx.fillRect(gx, gy, 1, 1);
      }
    }

    /* Partikel bergerak */
    particles.forEach(p => {
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0) p.x = W; if (p.x > W) p.x = 0;
      if (p.y < 0) p.y = H; if (p.y > H) p.y = 0;
      ctx.save();
      ctx.globalAlpha = p.alpha;
      ctx.fillStyle   = p.col;
      ctx.fillRect(Math.floor(p.x), Math.floor(p.y), Math.ceil(p.sz), Math.ceil(p.sz));
      ctx.restore();
    });

    /* Icon dengan depth sorting + spring repel */
    icons.forEach(ic => { ic.update(); ic.draw(); });
  }

  initIcons();
  loop();
  console.log('[BG] Icon background v5 aktif ✓ (depth-based parallax)');
})();
