/**
 * PxlKit Renderer — Vanilla JS
 * Merender pixel-art data dari pxlkit.xyz ke <canvas> elemen.
 * Support: static PxlKitData + animated AnimatedPxlKitData (multi-frame)
 */

/* ─── Tipe data ───────────────────────────────────────────────── */
const PXLKIT = {

  /**
   * Buat canvas dan render pixel art static satu frame.
   * @param {object} data   - PxlKitData ({ grid, palette, size })
   * @param {number} scale  - Ukuran setiap pixel (default 4)
   * @returns {HTMLCanvasElement}
   */
  renderStatic(data, scale = 4) {
    const { grid, palette, size } = data;
    const canvas = document.createElement('canvas');
    canvas.width  = size * scale;
    canvas.height = size * scale;
    canvas.style.imageRendering = 'pixelated';
    this._drawFrame(canvas.getContext('2d'), grid, palette, scale);
    return canvas;
  },

  /**
   * Buat canvas dan jalankan animasi multi-frame (loop).
   * @param {object} data   - AnimatedPxlKitData ({ frames, palette, size, frameDuration })
   * @param {number} scale
   * @returns {HTMLCanvasElement}
   */
  renderAnimated(data, scale = 4) {
    const { frames, palette, size, frameDuration = 150 } = data;
    if (!frames || frames.length === 0) return this.renderStatic(data, scale);

    const canvas = document.createElement('canvas');
    canvas.width  = size * scale;
    canvas.height = size * scale;
    canvas.style.imageRendering = 'pixelated';
    const ctx = canvas.getContext('2d');

    let currentFrame = 0;
    const interval = setInterval(() => {
      if (!canvas.isConnected) { clearInterval(interval); return; }
      this._drawFrame(ctx, frames[currentFrame].grid, palette, scale, size);
      currentFrame = (currentFrame + 1) % frames.length;
    }, frameDuration);

    // Render frame pertama segera
    this._drawFrame(ctx, frames[0].grid, palette, scale, size);

    // Simpan interval di canvas agar bisa di-stop
    canvas._pxlInterval = interval;
    return canvas;
  },

  /**
   * Otomatis pilih static/animated berdasarkan data.
   */
  render(data, scale = 4) {
    return data.frames ? this.renderAnimated(data, scale) : this.renderStatic(data, scale);
  },

  /**
   * Render ke dalam elemen container yang sudah ada.
   * Clears container lalu append canvas.
   * @param {HTMLElement} container
   * @param {object} data
   * @param {number} scale
   * @returns {HTMLCanvasElement}
   */
  renderInto(container, data, scale = 4) {
    // Stop animasi lama jika ada
    const old = container.querySelector('canvas');
    if (old && old._pxlInterval) clearInterval(old._pxlInterval);
    container.innerHTML = '';
    const canvas = this.render(data, scale);
    container.appendChild(canvas);
    return canvas;
  },

  /* ─── Internal ───────────────────────────────────────────────── */
  _drawFrame(ctx, grid, palette, scale, size) {
    size = size || grid.length;
    ctx.clearRect(0, 0, size * scale, size * scale);
    for (let row = 0; row < grid.length; row++) {
      for (let col = 0; col < grid[row].length; col++) {
        const ch = grid[row][col];
        if (ch === '.' || ch === ' ') continue;
        const color = palette[ch];
        if (!color) continue;
        ctx.fillStyle = color;
        ctx.fillRect(col * scale, row * scale, scale, scale);
      }
    }
  },
};

/* ─── Data pixel art (di-embed langsung dari file .ts) ─────────── */

/* == GAMIFICATION == */
const PXL_TROPHY = {
  name: 'trophy', size: 16, category: 'gamification',
  grid: [
    '................','..GGGGGGGGGGGG..', '.GG.YYYYYYYY.GG.',
    '.G..YYYYYYYY..G.','.G..YYYWYYYY..G.', '.GG.YYYYYYYY.GG.',
    '..GGGGGGGGGGGG..','....GGGGGGGG....', '.....GGGGGG.....',
    '......GGGG......','......GGGG......', '.....DDDDDD.....',
    '....DDDDDDDD....','....BBBBBBBB....', '...BBBBBBBBBB...',
    '................',
  ],
  palette: { G:'#FFD700', Y:'#FFF44F', D:'#B8860B', B:'#8B4513', W:'#FFFFFF' },
};

const PXL_CROWN = {
  name: 'crown', size: 16, category: 'gamification',
  grid: [
    '................','................', 'G............G..',
    'GG..G.....G..GG.','GGG.GG...GG.GGG.', 'GGGGGG...GGGGGG.',
    '.GGGGGGGGGGGGGG.','GGGGGGGGGGGGGGGG', 'GGGGGGGGGGGGGGGG',
    '.GGGGGGGGGGGGGG.','..GGGGGGGGGGGG..', '...GGGGGGGGGG...',
    '................','................', '................',
    '................',
  ],
  palette: { G:'#FFD700', Y:'#FFF44F', D:'#B8860B' },
};

const PXL_COIN = {
  name: 'coin', size: 16, category: 'gamification',
  grid: [
    '................','.....GGGGG......', '...GGWWGGGGG....',
    '..GGWGGGGGGGG...','..GGGGGGGGGGG...', '.GGGGGSSSSGGGG..',
    '.GGGGSGGGGGGG...','.GGGGSSSSGGGGG..', '.GGGGGGGGSGGGG..',
    '.GGGGSSSSGGGGG..','.GGGGGGGGGGGGG..', '..GGGGGGGGGGG...',
    '..GGGGGGGGGGD...','...GGGGGGGDD....', '.....DDDDD......',
    '................',
  ],
  palette: { G:'#FFD700', D:'#DAA520', B:'#8B6914', W:'#FFF8DC', S:'#C0A000' },
};

const PXL_FLOATING_GEM = {
  name: 'floating-gem', size: 16, category: 'gamification',
  frames: [
    { grid: ['................','........W.......', '.......BBB......','.....BBLLBBB....','....BLLLLLLLB...','....BLLWLLLBB...','....BBBBBBBBB...', '.....DBBBBBD....', '.....DDDBBDD....','......DDDDD.....', '.......DDD......','........D.......','................','................','................','................'] },
    { grid: ['........W.......', '.......BBB......','.....BBLLBBB....','....BLLLLLLLB...','....BLLWLLLBB...','....BBBBBBBBB...', '.....DBBBBBD....', '.....DDDBBDD....','......DDDDD.....', '.......DDD......','........D.......','................','................','..........S.....','................','................'] },
    { grid: ['................','........W.......', '.......BBB......','....SBBLLBBBS...','....BLLLLLLLB...','....BLLWLLLBB...','....BBBBBBBBB...', '.....DBBBBBD....','....SDDDBBDDS...','......DDDDD.....', '.......DDD......','........D.......','................','................','................','................'] },
    { grid: ['................','................', '........W.......', '.......BBB......','.....BBLLBBB....','....BLLLLLLLB...','....BLLWLLLBB...','....BBBBBBBBB...', '.....DBBBBBD....', '.....DDDBBDD....','......DDDDD.....', '.......DDD......','........D.......','................','................','................'] },
  ],
  frameDuration: 200, loop: true,
  palette: { B:'#5DADE2', L:'#AED6F1', D:'#1A5276', W:'#FFFFFF', S:'#E8F8FF' },
};

const PXL_SPARKLE_STAR = {
  name: 'sparkle-star', size: 16, category: 'gamification',
  frames: [
    { grid: ['.......YY.......','..W....YY.......', '.WWW..YYYY......', '..W...YYYY......', 'YYYYYYYYYYYYYYYY', '.YYYYYYYYYYYYYY.', '..YYYYYYYYYYYY..', '...YYYYYYYYYY...', '...YYYYYYYYYY...', '..YYYYY..YYYYY..', '..YYY......YYY..', '.DYY........YYD.', '................', '................', '................', '................'] },
    { grid: ['.......YY.......', '.......YY....W..', '......YYYY..WWW.', '......YYYY...W..', 'YYYYYYYYYYYYYYYY', '.YYYYYYYYYYYYYY.', '..YYYYYYYYYYYY..', '...YYYYYYYYYY...', '...YYYYYYYYYY...', '..YYYYY..YYYYY..', '..YYY......YYY..', '.DYY........YYD.', '................', '................', '................', '................'] },
    { grid: ['.......YY.......', '.......YY.......', '......YYYY......', '......YYYY......', 'YYYYYYYYYYYYYYYY', '.YYYYYYYYYYYYYY.', '..YYYYYYYYYYYY..', '...YYYYYYYYYY...', '...YYYYYYYYYY...', '..YYYYY..YYYYY..', '..YYY......YYY..', '.DYY........YYD.', '.............W..', '............WWW.', '.............W..', '................'] },
    { grid: ['.......YY.......', '.......YY.......', '......YYYY......', '......YYYY......', 'YYYYYYYYYYYYYYYY', '.YYYYYYYYYYYYYY.', '..YYYYYYYYYYYY..', '...YYYYYYYYYY...', '...YYYYYYYYYY...', '..YYYYY..YYYYY..', '..YYY......YYY..', '.DYY........YYD.', '..W.............', '.WWW............', '..W.............', '................'] },
  ],
  frameDuration: 200, loop: true,
  palette: { Y:'#FFD700', D:'#DAA520', W:'#FFFFFF' },
};

const PXL_LIGHTNING = {
  name: 'lightning', size: 16, category: 'gamification',
  grid: [
    '................','........YYYY....', '.......YYYY.....', '......YYYY......',
    '.....YYYY.......','....YYYYYY......', '....YYYYYYY.....', '....YYYYYYYYY...',
    '.......YYYYYYY..','......YYYYYY....', '.....YYYY.......', '....YYYY........',
    '...YYYY.........','................', '................', '................',
  ],
  palette: { Y:'#FFDF20', W:'#FFFFFF' },
};

const PXL_MEDAL = {
  name: 'medal', size: 16, category: 'gamification',
  grid: [
    '....AAAA........','....AAAA........', '....AAAA........', '....AAAA........',
    '....AAAA........','...AGGGGGA......', '..AGGGGGGGGA....', '..AGGGWGGGGA....',
    '..AGGGGGGGGA....','..AGGGGGGGGA....', '...AGGGGGGGA....', '....AGGGGGGA....',
    '.....AGGGGA.....','......AGGA......', '................', '................',
  ],
  palette: { A:'#FFD700', G:'#FFA500', W:'#FFFFFF' },
};

/* == UI == */
const PXL_LOCK = {
  name: 'lock', size: 16, category: 'ui',
  grid: [
    '................','....OOOOO.......', '...OOGGGOO......', '...OGGGGGO......',
    '...OGGGGGO......','...OGGGGGO......', 'OOOOOGGGOOOOOO..', 'OGGGGGGGGGGGGO..',
    'OGGGGKGGGGGGGO..','OGGGKKKGGGGGGO..', 'OGGGGKGGGGGGGO..', 'OGGGGGGGGGGGGO..',
    'OOOOOOOOOOOOOO..','................', '................', '................',
  ],
  palette: { G:'#FFB400', D:'#CC8800', O:'#53EAFD', K:'#091326' },
};

const PXL_CHECK = {
  name: 'check', size: 16, category: 'ui',
  grid: [
    '................','................', '................', '................',
    '..............G.','............GGG.', '...........GGG..', '..........GGG...',
    '...G.....GGG....','...GGG..GGG.....', '....GGGGGG......', '.....GGGG.......',
    '......GG........','................', '................', '................',
  ],
  palette: { G:'#7BF1A8' },
};

const PXL_PLAY = {
  name: 'play', size: 16, category: 'ui',
  grid: [
    '................','...G............', '...GGG..........', '...GGGGG........',
    '...GGGGGGG......','...GGGGGGGG.....', '...GGGGGGGGG....', '...GGGGGGGGGG...',
    '...GGGGGGGGG....','...GGGGGGGG.....', '...GGGGGGG......', '...GGGGG........',
    '...GGG..........','...G............', '................', '................',
  ],
  palette: { G:'#FFDF20' },
};

const PXL_LOADING_SPINNER = {
  name: 'loading-spinner', size: 16, category: 'ui',
  frames: [
    { grid: ['................','.....CCCC.......','....CWWWWC......','...CW....WC.....','...CW....WC.....','...CW....WC.....','...CW....WC.....','....CWWWWC......','.....CCCC.......','................','...........C....','...........CC...','...........CCC..','...........CCCC.','...........CCCCC','................'] },
    { grid: ['................','...........CCCC.','...........CWWWC','...........CW..C','...........CW..C','...........CW..C','...........CWWWC','...........CCCC.','................','...C............','...CC...........','...CCC..........','...CCCC.........','...CCCCC........','................','................'] },
    { grid: ['................','.....CCCC.......','....CWWWWC......','...CW....WC.....','...CW....WC.....','...CW....WC.....','...CW....WC.....','....CWWWWC......','.....CCCC.......','................','...........C....','...........CC...','...........CCC..','...........CCCC.','...........CCCCC','................'] },
    { grid: ['................','.CCCC...........','CWWWC...........','CW..C...........','CW..C...........','CW..C...........','CWWWC...........','.CCCC...........','................','...........C....','...........CC...','...........CCC..','...........CCCC.','...........CCCCC','................','................'] },
  ],
  frameDuration: 120, loop: true,
  palette: { C:'#53EAFD', W:'rgba(83,234,253,0.3)' },
};

/* == EFFECTS == */
const PXL_GLOW_PULSE = {
  name: 'glow-pulse', size: 16, category: 'effects',
  frames: [
    { grid: ['................','................','................','................','................','......BBBB......','.....BBCCBB.....', '.....BCWWCB.....', '.....BCWWCB.....', '.....BBCCBB.....', '......BBBB......','................','................','................','................','................'] },
    { grid: ['................','................','................','.....DDDD.......','....DBBBBBD.....','...DBBCCBBD.....','...DBCWWCBD.....','...DBCWWCBD.....','...DBCWWCBD.....','...DBBCCBBD.....','....DBBBBBD.....', '.....DDDD.......','................','................','................','................'] },
    { grid: ['................','................','.....LLLLLL.....','....LLDDDDLL....','...LLDBBBBBDLL..','..LLDBCCCCBDLL..','..LDBBCWWCBBDL..','..LDBCCWWCCBDL..','..LDBCCWWCCBDL..','..LDBBCWWCBBDL..','..LLDBCCCCBDLL..','...LLDBBBBBDLL..','....LLDDDDLL....', '.....LLLLLL.....','................','................'] },
    { grid: ['................','................','................','....DDDDDDD.....','...DDBBBBBDD....','...DBBCCCCBD....','...DBCCWWCBD....','...DBCWWWCBD....','...DBCWWWCBD....','...DBCCWWCBD....','...DBBCCCCBD....','...DDBBBBBDD....','....DDDDDDD.....','................','................','................'] },
  ],
  frameDuration: 200, loop: true,
  palette: { B:'#53EAFD', C:'#A2F4FD', L:'rgba(83,234,253,0.4)', D:'rgba(83,234,253,0.15)', W:'#FFFFFF' },
};

const PXL_SPARK_BURST = {
  name: 'spark-burst', size: 16, category: 'effects',
  frames: [
    { grid: ['................','................','................','................','................','................','........C.......','........C.......','................','................','................','................','................','................','................','................'] },
    { grid: ['................','................','....C...........','..........C.....','................','......C.........','........C.......','........C.......','........C.......','..C.............','................','............C...','................','................','................','................'] },
    { grid: ['..C.............','................','....C...........','..........C.....','..............C.','......C.........','........W.......','...C....W....C..','........W.......','..C.............','................','............C...','..C.............','..............C.','................','................'] },
    { grid: ['..C.............','..C.......C.....','....C...........','..........C.....','..............C.','......C.........','..C.....W....C..','...C....W....C..','..C.....W....C..','..C.............','...C............','............C...','..C.............','..............C.','........C.......','................'] },
  ],
  frameDuration: 80, loop: false,
  palette: { C:'#FFDF20', W:'#FFFFFF' },
};

const PXL_NEON_STROBE = {
  name: 'neon-strobe', size: 16, category: 'effects',
  frames: [
    { grid: ['PPPPPPPPPPPPPPPP','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','P..............P','PPPPPPPPPPPPPPPP'] },
    { grid: ['CCCCCCCCCCCCCCCC','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','C..............C','CCCCCCCCCCCCCCCC'] },
    { grid: ['................','................','................','................','................','................','................','................','................','................','................','................','................','................','................','................'] },
  ],
  frameDuration: 100, loop: true,
  palette: { P:'#ED6AFF', C:'#53EAFD' },
};

/* == FEEDBACK == */
const PXL_TARGET_HIT = {
  name: 'target-hit', size: 16, category: 'feedback',
  grid: [
    '................','....RRRRRR......', '...RRGGGGRR.....', '..RRGGGGGGRR....',
    '..RGGGWWGGGRR...','..RGGWGGWGGR....', '..RGGGWWGGGR....', '..RGGGGGGGGR....',
    '..RGGGGGGGGR....','..RRGGGGGGRR....', '...RRGGGGRR.....', '....RRRRRR......',
    '................','................', '................', '................',
  ],
  palette: { R:'#FF3366', G:'#7BF1A8', W:'#FFFFFF' },
};

const PXL_BUG = {
  name: 'bug', size: 16, category: 'feedback',
  grid: [
    '................','..R..RRRRR..R...', '.RR..RRRRR..RR..', '.R.RRRRRRRRR.R..',
    '..RRRRRRRRRRRR..','..RRRRRRRRRRRR..', '...RRRRRRRRRR...', '...RRRRRRRRRR...',
    '..RRRRRRRRRRRR..','..RRRRRRRRRRRR..', '.R.RRRRRRRRR.R..', '.RR..RRRRR..RR..',
    '..R..RRRRR..R...','................', '................', '................',
  ],
  palette: { R:'#FF3366' },
};

/* ─── Helper: buat elemen pixel art langsung ────────────────────── */
PXLKIT.createElement = function(data, scale = 4, cssClass = '') {
  const wrapper = document.createElement('div');
  wrapper.className = `pxl-icon ${cssClass}`;
  wrapper.style.display = 'inline-block';
  wrapper.style.lineHeight = '0';
  const canvas = this.render(data, scale);
  wrapper.appendChild(canvas);
  return wrapper;
};


/* == PARALLAX ICONS == */
const PXL_MAGIC_ORB = {
  name: 'magic-orb', size: 16, category: 'parallax',
  frames: [
    { grid: ['......PPPP......','.....PPCCPP.....','....PCCLLCCP....','...PCLLLLLLLCP..','...PCLLWWLLCP...','..PCLLLLLLLCP...','..PCCCCCCCCP....','...PPPPPPPP.....','................','................','................','................','................','................','................','................'] },
    { grid: ['......PPPP......','.....PPCCPP.....','....PCCLLCCP....','...PCLLLLLLLCP..','...PCLWWWLLCP...','..PCLL WWW LCP..','..PCCCCCCCCP....','...PPPPPPPP.....','................','................','................','................','................','................','................','................'] },
  ],
  frameDuration: 300, loop: true,
  palette: { P:'#9B59B6', C:'#BB8FCE', L:'#D7BDE2', W:'#FFFFFF' },
};

const PXL_PIXEL_ROCKET = {
  name: 'pixel-rocket', size: 16, category: 'parallax',
  grid: [
    '.......RR.......','......RRRR......','.....RRRRRR.....','....RRRWWRRR....','....RRWWWWRR....','....RWWWWWWR....','....RWWWWWWR....','....RRRRRRRR....','....RBBRRBBR....','....BYYRRYYB....','....BYYRRYYB....','....BYYRRYYB....','....BBYYBBYY....','....BBYYBBYY....','....BB....BB....','................'],
  palette: { R:'#E74C3C', W:'#ECF0F1', B:'#3498DB', Y:'#F39C12' },
};

const PXL_RETRO_TV = {
  name: 'retro-tv', size: 16, category: 'parallax',
  grid: [
    '...TTTTTTTT.....','..TCCCCCCCCT....','..TCCCCCCCCT....','..TCCCCCCCCT....','..TCGGGGGCCT....','..TCGGGGGCCT....','..TCGGGGGCCT....','..TCGGGGGCCT....','..TCCCCCCCCT....','..TCCCCCCCCT....','...TTTTTTTTT....','....RR..RR......','....RR..RR......','................','................','................'],
  palette: { T:'#95A5A6', C:'#34495E', G:'#2ECC71', R:'#E74C3C' },
};

const PXL_GHOST_FRIEND = {
  name: 'ghost-friend', size: 16, category: 'parallax',
  frames: [
    { grid: ['................','....GGGGGG......','...GGGGGGGG.....','..GGGGGGGGGG....','..GGBBGGBBGG....','..GGBBGGBBGG....','..GGGGGGGGGG....','..GGPPPPPPGG....','..GGPPPPPPGG....','..GGGGGGGGGG....','...GGGGGGGG.....','..GG.GG.GG.GG...','................','................','................','................'] },
    { grid: ['................','....GGGGGG......','...GGGGGGGG.....','..GGGGGGGGGG....','..GGBBGGBBGG....','..GGBBGGBBGG....','..GGGGGGGGGG....','..GGPPPPPPGG....','..GGPPPPPPGG....','..GGGGGGGGGG....','...GGGGGGGG.....','...GG.GG.GG.G...','................','................','................','................'] },
  ],
  frameDuration: 400, loop: true,
  palette: { G:'#FFFFFF', B:'#000000', P:'#FF69B4' },
};

/* == EFFECTS == */
const PXL_TWINKLE = {
  name: 'twinkle', size: 16, category: 'effects',
  frames: [
    { grid: ['................','................','........W.......','......W.W.......','........W.......','................','................','................','................','................','................','................','................','................','................','................'] },
    { grid: ['................','........W.......','......WWWWW.....','....WWWWWWWWW...','......WWWWW.....','........W.......','................','................','................','................','................','................','................','................','................','................'] },
    { grid: ['........W.......','......WWWWW.....','....WWWWWWWWW...','..WWWWWWWWWWWWW.','....WWWWWWWWW...','......WWWWW.....','........W.......','................','................','................','................','................','................','................','................','................'] },
    { grid: ['................','........W.......','......WWWWW.....','....WWWWWWWWW...','......WWWWW.....','........W.......','................','................','................','................','................','................','................','................','................','................'] },
  ],
  frameDuration: 100, loop: true,
  palette: { W:'#FFFFFF' },
};

const PXL_SPARK_SMALL = {
  name: 'spark-small', size: 16, category: 'effects',
  frames: [
    { grid: ['................','................','................','................','................','................','........Y.......','........Y.......','................','................','................','................','................','................','................','................'] },
    { grid: ['................','................','....Y...........','..........Y.....','................','......Y.........','........Y.......','........Y.......','........Y.......','..Y.............','................','............Y...','................','................','................','................'] },
    { grid: ['..Y.............','................','....Y...........','..........Y.....','..............Y.','......Y.........','........W.......','...Y....W....Y..','........W.......','..Y.............','................','............Y...','..Y.............','..............Y.','................','................'] },
  ],
  frameDuration: 80, loop: false,
  palette: { Y:'#FFDF20', W:'#FFFFFF' },
};

const PXL_PORTAL_SPIN = {
  name: 'portal-spin', size: 16, category: 'effects',
  frames: [
    { grid: ['................','....PPPPPP......','...PCCCCCCP.....','..PCCWWWWCCP....','..PCWW..WWCP....','..PCW....WCP....','..PCW....WCP....','..PCW....WCP....','..PCW....WCP....','..PCWW..WWCP....','..PCCWWWWCCP....','...PCCCCCCP.....','....PPPPPP......','................','................','................'] },
    { grid: ['................','....PPPPPP......','...PCCCCCCP.....','..PCCWWWWCCP....','..PCWWWWWWCP....','..PCWWWWWWCP....','..PCWWWWWWCP....','..PCWWWWWWCP....','..PCWWWWWWCP....','..PCWWWWWWCP....','..PCCWWWWCCP....','...PCCCCCCP.....','....PPPPPP......','................','................','................'] },
  ],
  frameDuration: 200, loop: true,
  palette: { P:'#9B59B6', C:'#8E44AD', W:'#ECF0F1' },
};

/* == UI ICONS (Small) == */
const PXL_SPARKLE_SMALL = {
  name: 'sparkle-small', size: 16, category: 'ui',
  grid: [
    '................','................','.......G........','.......G........','......GGG.......','.....GGGGG......','....GGGGGGG.....','...GGGGGGGGG....','....GGGGGGG.....','.....GGGGG......','......GGG.......','.......G........','.......G........','................','................','................'],
  palette: { G:'#FFD700' },
};

const PXL_PULSING_DOT = {
  name: 'pulsing-dot', size: 16, category: 'ui',
  frames: [
    { grid: ['................','................','................','................','................','................','........CC......','........CC......','................','................','................','................','................','................','................','................'] },
    { grid: ['................','................','................','................','................','.......CCCC.....','......CCCCCC....','......CCCCCC....','......CCCCCC....','......CCCC......','................','................','................','................','................','................'] },
    { grid: ['................','................','................','................','......CCCCCC....','......CCCCCC....','....CCCCCCCCCC..','....CCCCCCCCCC..','....CCCCCCCCCC..','......CCCCCC....','......CCCCCC....','................','................','................','................','................'] },
  ],
  frameDuration: 200, loop: true,
  palette: { C:'#53EAFD' },
};

/* == GAMIFICATION EXTRA == */
const PXL_GEM_SMALL = {
  name: 'gem-small', size: 16, category: 'gamification',
  grid: [
    '................','........G.......','........G.......','.......GGG......','......GGGGG.....','......GLLLG.....','......GGGGG.....','.......GGG......','........G.......','................','................','................','................','................','................','................'],
  palette: { G:'#E74C3C', L:'#EC7063' },
};

const PXL_STAR_SMALL = {
  name: 'star-small', size: 16, category: 'gamification',
  grid: [
    '................','................','.......Y........','.......Y........','......YYY.......','.....YYYYY......','....YYYYYYY.....','...YY.....YY....','...Y.......Y....','................','................','................','................','................','................','................'],
  palette: { Y:'#F39C12' },
};

const PXL_HEART_SMALL = {
  name: 'heart-small', size: 16, category: 'gamification',
  grid: [
    '................','................','...RR...RR......','..RRRR.RRRR.....','..RRRRRRRRR.....','...RRRRRRR......','....RRRRR.......','.....RRR........','......R.........','................','................','................','................','................','................','................'],
  palette: { R:'#E74C3C' },
};

const PXL_KEY_SMALL = {
  name: 'key-small', size: 16, category: 'gamification',
  grid: [
    '................','....GGGG........','...GGGGGG.......','...GGKKGG.......','...GGGGGG.......','....GGGG........','......GG........','......GG........','......GG........','......GGGG......','......GG........','................','................','................','................','................'],
  palette: { G:'#FFD700', K:'#000000' },
};

const PXL_SHIELD_SMALL = {
  name: 'shield-small', size: 16, category: 'gamification',
  grid: [
    '................','......BB........','.....BBBB.......','....BBSSBB......','....BSSSSB......','....BSSSSB......','....BSSSSB......','.....BSSB.......','.....BSSB.......','.....BSSB.......','......BB........','......BB........','................','................','................','................'],
  palette: { B:'#3498DB', S:'#5DADE2' },
};

/* ─── Helper: buat elemen pixel art langsung ────────────────────── */
PXLKIT.createElement = function(data, scale = 4, cssClass = '') {
  const wrapper = document.createElement('div');
  wrapper.className = `pxl-icon ${cssClass}`;
  wrapper.style.display = 'inline-block';
  wrapper.style.lineHeight = '0';
  const canvas = this.render(data, scale);
  wrapper.appendChild(canvas);
  return wrapper;
};
