/* ═══════════════════════════════════════════════════════
   UCI CARBONS THEME 2 — main.js
   ═══════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function () {

  /* ─── MOBILE NAV OVERLAY ─── */
  var mobileBtn     = document.querySelector('.nav-mobile-btn');
  var mobileOverlay = document.querySelector('.mobile-overlay');
  var overlayClose  = document.querySelector('.mobile-overlay-close');

  function openMobileNav() {
    if (mobileOverlay) mobileOverlay.classList.add('open');
    if (mobileBtn)     mobileBtn.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeMobileNav() {
    if (mobileOverlay) mobileOverlay.classList.remove('open');
    if (mobileBtn)     mobileBtn.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (mobileBtn)    mobileBtn.addEventListener('click', openMobileNav);
  if (overlayClose) overlayClose.addEventListener('click', closeMobileNav);

  document.querySelectorAll('.mobile-nav-link').forEach(function (link) {
    link.addEventListener('click', closeMobileNav);
  });

  /* ─── MEGA MENUS (Products · Applications · Knowledge · ESG) ─── */
  var megaBackdrop   = document.getElementById('megaBackdrop');
  var megaItems      = document.querySelectorAll('.has-mega');
  var megaTimer      = null;
  var activeMegaItem = null;

  function openMega(item) {
    clearTimeout(megaTimer);
    /* Close any currently open mega first */
    if (activeMegaItem && activeMegaItem !== item) {
      activeMegaItem.classList.remove('mega-open');
      var prevBtn = activeMegaItem.querySelector('[aria-expanded]');
      if (prevBtn) prevBtn.setAttribute('aria-expanded', 'false');
    }
    item.classList.add('mega-open');
    if (megaBackdrop) megaBackdrop.classList.add('visible');
    var btn = item.querySelector('[aria-expanded]');
    if (btn) btn.setAttribute('aria-expanded', 'true');
    activeMegaItem = item;
  }

  function closeMega() {
    clearTimeout(megaTimer);
    megaItems.forEach(function(item) {
      item.classList.remove('mega-open');
      var btn = item.querySelector('[aria-expanded]');
      if (btn) btn.setAttribute('aria-expanded', 'false');
    });
    if (megaBackdrop) megaBackdrop.classList.remove('visible');
    activeMegaItem = null;
  }

  function closeMegaDelayed() {
    megaTimer = setTimeout(closeMega, 150);
  }

  megaItems.forEach(function(item) {
    if (!item) return;
    item.addEventListener('mouseenter', function() { openMega(item); });
    item.addEventListener('mouseleave', closeMegaDelayed);
    var panel = item.querySelector('.mega-panel');
    if (panel) {
      panel.addEventListener('mouseenter', function() { clearTimeout(megaTimer); });
      panel.addEventListener('mouseleave', closeMegaDelayed);
    }
    /* Click: first opens; second click follows the href */
    var triggerBtn = item.querySelector('[aria-haspopup]');
    if (triggerBtn) {
      triggerBtn.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) return;
        if (!item.classList.contains('mega-open')) {
          e.preventDefault();
          openMega(item);
        }
      });
    }
  });

  if (megaBackdrop) {
    megaBackdrop.addEventListener('click', closeMega);
  }
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMega();
  });

  /* ─── SMOOTH SCROLL ─── */
  document.querySelectorAll('[data-scroll]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetId = btn.getAttribute('data-scroll');
      var el = document.getElementById(targetId);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  /* ─── HERO PHOTO ROTATION ─── */
  var heroBgs = document.querySelectorAll('.hero2-photo-bg');
  if (heroBgs.length > 1) {
    var currentBg = 0;
    heroBgs[currentBg].classList.add('active');
    setInterval(function () {
      heroBgs[currentBg].classList.remove('active');
      currentBg = (currentBg + 1) % heroBgs.length;
      heroBgs[currentBg].classList.add('active');
    }, 5000);
  } else if (heroBgs.length === 1) {
    heroBgs[0].classList.add('active');
  }

  /* ─── TEAM-GEN ACCORDION ─── */
  document.querySelectorAll('.team-gen-header').forEach(function (header) {
    header.addEventListener('click', function () {
      header.closest('.team-gen').classList.toggle('expanded');
    });
  });


  /* ─── PRODUCT FILTER BAR ─── */
  document.querySelectorAll('.pf-filter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      filterProducts(btn.getAttribute('data-group'), btn.getAttribute('data-val'), btn);
    });
  });

  var pfAppSelect = document.getElementById('pfAppFilter');
  if (pfAppSelect) {
    pfAppSelect.addEventListener('change', function () { pfSetApp(pfAppSelect.value); });
  }

  /* ─── AUTO-FILTER from ?app= URL param (coming from homepage cards) ─── */
  var urlApp = new URLSearchParams(window.location.search).get('app');
  if (urlApp && document.querySelector('.grade-card')) {
    // activate the matching app filter button if it exists
    var appBtn = document.querySelector('.pf-filter-btn[data-group="app"][data-val="' + urlApp + '"]');
    if (appBtn) {
      filterProducts('app', urlApp, appBtn);
    } else {
      pfSetApp(urlApp);
      if (pfAppSelect) pfAppSelect.value = urlApp;
    }
    // scroll the grade grid into view after a short delay
    setTimeout(function () {
      var grid = document.getElementById('grade-grid') || document.querySelector('.grade-grid');
      if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 200);
  }

  /* ─── AUTO-TRIGGER AI from ?aiask= URL param (set by aiAskFromPage) ─── */
  var urlAiAsk = new URLSearchParams(window.location.search).get('aiask');
  if (urlAiAsk && document.getElementById('ai-input')) {
    var aiInput = document.getElementById('ai-input');
    aiInput.value = urlAiAsk;
    setTimeout(function() { _showAiResponse(urlAiAsk); }, 400);
  }

  /* ─── KNOWLEDGE FILTER ─── */
  function filterKB(cat, btn) {
    document.querySelectorAll('.kb-filter').forEach(function (b) { b.classList.remove('active'); });
    if (btn) btn.classList.add('active');
    document.querySelectorAll('.article-card').forEach(function (card) {
      var cats = (card.getAttribute('data-cat') || card.getAttribute('data-filter') || '').split(' ');
      var show = cat === 'all' || cats.indexOf(cat) !== -1;
      card.classList.toggle('hidden', !show);
    });
  }
  window.filterKB = filterKB;

  /* ─── OPEN ARTICLE ─── */
  /* Cards are now <a href> — this handler is kept for graceful fallback only.
     If the native href navigation should be prevented (e.g. future lightbox),
     call e.preventDefault() here. Currently we let the href navigate normally
     so the link works for SEO, keyboard users, and right-click → open in tab. */
  function openArticle(e, slug) {
    // Allow default href navigation — no e.preventDefault()
    // Future: add lightbox logic here if needed
  }
  window.openArticle = openArticle;

  /* ─── COUNTER ANIMATION ─── */
  if ('IntersectionObserver' in window) {
    var countObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          countObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    document.querySelectorAll('[data-count]').forEach(function (el) { countObserver.observe(el); });
  }

  function animateCounter(el) {
    var target   = parseInt(el.getAttribute('data-count'), 10);
    var duration = 1200;
    var start    = null;
    requestAnimationFrame(function step(ts) {
      if (!start) start = ts;
      var ease = 1 - Math.pow(1 - Math.min((ts - start) / duration, 1), 3);
      el.textContent = Math.floor(target * ease);
      if (ease < 1) requestAnimationFrame(step);
      else el.textContent = target;
    });
  }

  /* ─── HERO PARTICLES ─── */
  initParticles();

  /* ─── ACTIVE NAV LINK ─── */
  var path = window.location.pathname;
  document.querySelectorAll('.nav-menu a, .nav-link').forEach(function (link) {
    var href = link.getAttribute('href');
    if (href && href !== '/' && path.indexOf(href) !== -1) link.classList.add('active');
  });

}); // end DOMContentLoaded


/* ═══════════════════════════════════════════════════════
   VIDEO LIGHTBOX
   ═══════════════════════════════════════════════════════ */

function openVideoLightbox(src) {
  var lb     = document.getElementById('vid-lightbox');
  var player = document.getElementById('vid-lightbox-player');
  var srcEl  = document.getElementById('vid-lightbox-src');
  if (!lb || !player || !srcEl) return;
  srcEl.src = src;
  player.load();
  player.play();
  lb.classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeVideoLightbox(e) {
  if (e && e.target !== document.getElementById('vid-lightbox')) return;
  var lb     = document.getElementById('vid-lightbox');
  var player = document.getElementById('vid-lightbox-player');
  if (!lb) return;
  if (player) player.pause();
  lb.classList.remove('open');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') closeVideoLightbox();
});


/* ═══════════════════════════════════════════════════════
   HERO PARTICLES
   ═══════════════════════════════════════════════════════ */

function initParticles() {
  var container = document.getElementById('hero-particles');
  if (!container) return;
  for (var i = 0; i < 15; i++) {
    var dot = document.createElement('div');
    dot.className = 'hero-particle';
    dot.style.left              = Math.random() * 100 + '%';
    dot.style.bottom            = Math.random() * 20 + '%';
    dot.style.animationDuration = (8 + Math.random() * 12) + 's';
    dot.style.animationDelay    = (Math.random() * 10) + 's';
    dot.style.width = dot.style.height = (2 + Math.random() * 4) + 'px';
    dot.style.opacity           = String(0.3 + Math.random() * 0.4);
    container.appendChild(dot);
  }
}


/* ═══════════════════════════════════════════════════════
   GRADE SELECTOR
   ═══════════════════════════════════════════════════════ */

/* ══════════════════════════════════════════════
   CARBON AI — calls WP AJAX → Anthropic Claude
   ══════════════════════════════════════════════ */

function aiSend() {
  var input = document.getElementById('ai-input');
  var q = (input.value || '').trim();
  if (!q) return;
  input.value = '';
  _showAiResponse(q);
}

function aiAsk(q) {
  var input = document.getElementById('ai-input');
  if (input) input.value = q;
  _showAiResponse(q);
}

/* ── CONTACT FORM SUBMIT ── */
function uciSubmitForm(e, formId, successId) {
  e.preventDefault();
  var form = document.getElementById(formId);
  if (!form) return;

  // Basic client-side required check
  var required = form.querySelectorAll('[required]');
  var valid = true;
  required.forEach(function(f) {
    if (!f.value.trim()) { f.style.borderColor = '#ef4444'; valid = false; }
    else f.style.borderColor = '';
  });
  if (!valid) return;

  var btn = form.querySelector('button[type="submit"]');
  if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }

  var data = new FormData(form);
  data.append('action', 'uci_contact_form');

  fetch((typeof uciVars !== 'undefined' ? uciVars.ajaxUrl : '/wp-admin/admin-ajax.php'), {
    method: 'POST',
    body: data,
    credentials: 'same-origin',
  })
  .then(function(res) { return res.json(); })
  .then(function(json) {
    if (json && json.data && json.data.redirect) {
      window.location.href = json.data.redirect;
    } else {
      // Fallback: show inline success if no redirect
      form.style.display = 'none';
      var success = document.getElementById(successId);
      if (success) success.style.display = 'block';
    }
  })
  .catch(function() {
    // Network error fallback
    form.style.display = 'none';
    var success = document.getElementById(successId);
    if (success) success.style.display = 'block';
  });
}
window.uciSubmitForm = uciSubmitForm;

/* Inline AI strip on sub-pages (products page bottom) */
function aiAskFromPage(inputId) {
  var input = document.getElementById(inputId);
  if (!input) return;
  var q = (input.value || '').trim();
  if (!q) return;
  // Send to the homepage AI if on same domain, otherwise open homepage with query
  var homeUrl = (typeof uciVars !== 'undefined' && uciVars.homeUrl) ? uciVars.homeUrl : '/';
  window.location.href = homeUrl + '?aiask=' + encodeURIComponent(q) + '#carbon-ai';
}

function _showAiResponse(q) {
  var wrap   = document.getElementById('aiResponseWrap');
  var body   = document.getElementById('aiResponseBody');
  var footer = document.getElementById('aiResponseFooter');
  var status = document.getElementById('aiStatus');
  if (!wrap) return;

  wrap.classList.add('visible');
  body.innerHTML = '<div class="ai-typing"><span></span><span></span><span></span></div>';
  if (footer) footer.style.display = 'none';
  if (status) status.textContent = 'Thinking…';
  wrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

  // Highlight active quick chip
  document.querySelectorAll('.ai-chip').forEach(function(c) { c.classList.remove('active'); });
  document.querySelectorAll('.ai-chip').forEach(function(c) {
    if (c.textContent.trim().toLowerCase() === q.toLowerCase()) c.classList.add('active');
  });

  var vars = (typeof uciVars !== 'undefined') ? uciVars : {};
  var ajaxUrl = vars.ajaxUrl || '/wp-admin/admin-ajax.php';
  var nonce   = vars.nonce  || '';

  var fd = new FormData();
  fd.append('action',   'uci_carbon_ai');
  fd.append('nonce',    nonce);
  fd.append('question', q);

  fetch(ajaxUrl, { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.success && data.data.answer) {
        if (status) status.textContent = 'Answer ready';
        _typeText(body, data.data.answer, function() {
          if (footer) footer.style.display = 'flex';
        });
      } else {
        var msg = (data.data && data.data.message) ? data.data.message : 'Something went wrong. Please email kartik.gupta@ucicarbons.com.';
        body.innerHTML = '<p style="color:rgba(255,255,255,0.6)">' + _escHtml(msg) + '</p>';
        if (status) status.textContent = 'Error';
        if (footer) footer.style.display = 'flex';
      }
    })
    .catch(function() {
      body.innerHTML = '<p style="color:rgba(255,255,255,0.6)">Connection error. Please email kartik.gupta@ucicarbons.com.</p>';
      if (status) status.textContent = 'Error';
      if (footer) footer.style.display = 'flex';
    });
}

function _typeText(el, text, onDone) {
  el.innerHTML = '';
  var i = 0;
  var speed = Math.max(8, Math.min(20, Math.floor(4000 / text.length)));
  function tick() {
    if (i >= text.length) { if (onDone) onDone(); return; }
    el.innerHTML = text.slice(0, ++i);
    setTimeout(tick, speed);
  }
  tick();
}

function _escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Wire up send button and Enter key on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
  var btn = document.getElementById('ai-send-btn');
  if (btn) btn.addEventListener('click', aiSend);
  var inp = document.getElementById('ai-input');
  if (inp) inp.addEventListener('keydown', function(e) { if (e.key === 'Enter') aiSend(); });
});

/* ══════════════════════════════════════════════
   STICKY BAR — shows after scrolling 600px
   ══════════════════════════════════════════════ */
(function() {
  var _sbDismissed = false;
  function updateStickyBar() {
    if (_sbDismissed) return;
    var bar = document.getElementById('sticky-bar');
    if (!bar) return;
    if (window.scrollY > 600) {
      bar.classList.add('visible');
    } else {
      bar.classList.remove('visible');
    }
  }
  window.addEventListener('scroll', updateStickyBar, { passive: true });
  document.addEventListener('DOMContentLoaded', updateStickyBar);
})();

function closeStickyBar() {
  var bar = document.getElementById('sticky-bar');
  if (bar) bar.classList.remove('visible');
  // suppress for this session
  try { sessionStorage.setItem('uci_sb_dismissed', '1'); } catch(e) {}
}


/* ── PRODUCT FINDER (homepage app-card grid) ── */
function selectApp(el, app) {
  document.querySelectorAll('.pf-app-card').forEach(function(c) { c.classList.remove('selected'); });
  el.classList.add('selected');

  var grades = {
    water:   [{code:'UCI RC830',   base:'Wood',    desc:'Primary water treatment GAC. Consistent IV 800–900, excellent hardness, macroporous pine.'},{code:'UCI RC1240',  base:'Wood',    desc:'12×40 mesh water grade. Widely used in municipal plants and industrial filtration.'},{code:'UCI 4×8C',   base:'Coconut', desc:'Coconut GAC for potable water. NSF-certified facility. High micropore density.'}],
    gold:    [{code:'ACGOLD 6SZ',  base:'Coconut', desc:'K value up to 65. CTC 55–65%. 6×12 mesh. Primary CIL/CIP circuit grade.'},{code:'ACGOLD 8SZ',  base:'Coconut', desc:'8×16 mesh. Higher surface area. Suited for fine-gold recovery circuits.'},{code:'ACGOLD 6SFY', base:'Coconut', desc:'Enhanced fine-gold recovery variant. SFY designation. K value 60+.'}],
    pharma:  [{code:'UCI UW-22',   base:'Wood',    desc:'Standard API decolorisation grade. Low ash, neutral pH variant available.'},{code:'UCI UW-24',   base:'Wood',    desc:'Higher decolorisation capacity. Pharmaceutical processing and fine chemical purification.'},{code:'DL Premium',  base:'Wood',    desc:'Highest purity wood carbon. Lowest ash. Food contact and pharmaceutical certified.'}],
    food:    [{code:'AC 12×40B',   base:'Coconut', desc:'Acid-washed beverage grade. Spirit decolorisation, juice purification. Food contact certified.'},{code:'UCI 55N',    base:'Wood',    desc:'High decolorisation capacity powder. Liquid sugar, food processing streams.'}],
    oil:     [{code:'AC 200E',     base:'Coconut', desc:'200 mesh powder for edible oil refining. Water-washed, controlled ash content.'},{code:'AC 325E',     base:'Coconut', desc:'325 mesh — finer particle for tighter filtration in rice bran and palm oil.'},{code:'UCI UW-22',   base:'Wood',    desc:'Wood-based option for oil refining. Higher macroporosity for large oil molecules.'}],
    air:     [{code:'PAC-950',     base:'Wood',    desc:'3mm extruded pellet. 950 m²/g BET surface area. Gas phase VOC applications.'},{code:'PAC-1300',    base:'Wood',    desc:'4mm pellet, 1300 m²/g. High-activity gas phase grade. Industrial ventilation.'},{code:'UCI 4×8',    base:'Wood',    desc:'Granular GAC for air filtration beds. Good hardness, low pressure drop.'}],
    masks:   [{code:'ABEK Grade',  base:'Coconut', desc:'Impregnated grade for gas masks and CBRN protection. Contact us for full specification.'}],
    pellets: [{code:'PAC-950',     base:'Wood',    desc:'3mm pellet. Low pressure drop. 950 m²/g BET.'},{code:'PAC-1300',    base:'Wood',    desc:'4mm pellet. 1300 m²/g BET. Premium gas-phase grade.'}],
    other:   []
  };

  var contactUrl  = (typeof uciVars !== 'undefined' && uciVars.contactUrl)  ? uciVars.contactUrl  : '/contact/';
  var tdsUrl      = (typeof uciVars !== 'undefined' && uciVars.tdsUrl)      ? uciVars.tdsUrl      : '/request-tds/';
  var brochureUrl = (typeof uciVars !== 'undefined' && uciVars.brochureUrl) ? uciVars.brochureUrl : '/request-brochure/';
  var results = grades[app] || [];
  var wrap = document.getElementById('pfResults');

  if (results.length === 0) {
    wrap.innerHTML = '<div style="text-align:center;padding:32px;grid-column:1/-1"><p style="color:var(--soft);margin-bottom:16px">Tell us your application and we\'ll match you to the right grade.</p><a class="btn-primary btn-sm" href="' + contactUrl + '">Contact our technical team →</a></div>';
  } else {
    wrap.innerHTML = results.map(function(g) {
      return '<div class="pf-grade-card">'
        + '<div class="pf-grade-code">' + g.code + '</div>'
        + '<div class="pf-grade-base">' + g.base + '-based</div>'
        + '<p class="pf-grade-desc">' + g.desc + '</p>'
        + '<div class="pf-grade-actions">'
        + '<a class="btn-primary btn-sm" href="' + tdsUrl + '?grade=' + encodeURIComponent(g.code) + '">Request TDS →</a>'
        + '<a class="btn-outline-sm" href="' + contactUrl + '?grade=' + encodeURIComponent(g.code) + '">Request Sample</a>'
        + '</div></div>';
    }).join('');
  }

  var pfWrap = document.getElementById('pfResultsWrap');
  pfWrap.style.display = 'block';
  pfWrap.scrollIntoView({behavior:'smooth', block:'nearest'});
}


/* ═══════════════════════════════════════════════════════
   PRODUCT FILTER (page-products.php)
   ═══════════════════════════════════════════════════════ */

var _pfState = { base: 'all', form: 'all', app: 'all' };

function filterProducts(group, val, btn) {
  _pfState[group] = val;
  document.querySelectorAll('.pf-filter-btn[data-group="' + group + '"]').forEach(function (b) { b.classList.remove('active'); });
  if (btn) btn.classList.add('active');
  applyProductFilters();
}

function pfSetApp(val) {
  _pfState.app = val;
  applyProductFilters();
}

function applyProductFilters() {
  var visible = 0;
  document.querySelectorAll('.grade-card').forEach(function (card) {
    var okRaw  = _pfState.base === 'all' || card.getAttribute('data-base') === _pfState.base;
    var okForm = _pfState.form === 'all' || card.getAttribute('data-form') === _pfState.form;
    var okApp  = _pfState.app  === 'all' || (card.getAttribute('data-apps') || '').split(' ').indexOf(_pfState.app) !== -1;
    var show   = okRaw && okForm && okApp;
    card.classList.toggle('hidden', !show);
    if (show) visible++;
  });

  var countEl = document.getElementById('pfCount');
  if (countEl) countEl.textContent = visible + ' grade' + (visible !== 1 ? 's' : '');

  document.querySelectorAll('.grades-family-section').forEach(function (sec) {
    var any = Array.from(sec.querySelectorAll('.grade-card')).some(function (c) { return !c.classList.contains('hidden'); });
    sec.style.display = any ? '' : 'none';
  });
}


/* ══════════════════════════════════════════════════════
   APPLICATIONS PORTAL (page-applications.php)
   ══════════════════════════════════════════════════════ */

var appData = {
  water:{
    title:'Water Treatment', color:'#0296D8', icon:'💧',
    body:'Granular activated carbon is the workhorse of municipal and industrial water treatment. It removes chlorine, chloramines, taste, odour, and a wide range of organic micropollutants through adsorption in fixed-bed contactors. Powder grades handle emergency dosing and variable-load treatment plants.',
    steps:[
      {n:'01',label:'Load the contactors',desc:'GAC fills fixed-bed vessels to 1–3 m depth. Water flows downward through the carbon bed.'},
      {n:'02',label:'Adsorption in bed',desc:'Contaminants bind to carbon surface as water passes through. Treated water exits from the base.'},
      {n:'03',label:'Monitor breakthrough',desc:'Effluent quality is tested continuously. When capacity degrades, carbon is replaced or reactivated.'}
    ],
    specs:[
      {name:'Iodine Number',value:'900–1100 mg/g',desc:'Core capacity indicator — higher IV means more adsorption capacity per gram'},
      {name:'Mesh Size',value:'8×30 / 12×40',desc:'Consistent mesh ensures uniform bed flow distribution and controlled pressure drop'},
      {name:'Hardness',value:'>95% (ASTM)',desc:'Must withstand backwash cycles without generating excessive fines'}
    ],
    grades:[
      {code:'UCI RC830 / RC1240',base:'Wood',desc:'Primary water treatment GAC. Consistent IV, excellent hardness, macroporous pine structure.'},
      {code:'UCI 4×8C / 12×40C',base:'Coconut',desc:'Higher micropore density for small-molecule removal. Suitable for potable water duty.'},
      {code:'UCI 4×8 AWC',base:'Coconut',desc:'Acid-washed coconut GAC — low iron, neutral pH, NSF-quality facility.'}
    ]
  },
  gold:{
    title:'Gold Recovery', color:'#D97706', icon:'🥇',
    body:'In CIL (carbon-in-leach) and CIP (carbon-in-pulp) gold recovery, activated carbon adsorbs the gold-cyanide complex from the leach slurry. The process demands extreme mechanical durability — carbon circulates through pumps and screens repeatedly, and fines mean lost gold.',
    steps:[
      {n:'01',label:'Leach & load',desc:'Crushed ore is leached with cyanide solution. Gold-cyanide complex forms and is adsorbed onto carbon in agitated tanks.'},
      {n:'02',label:'Elution',desc:'Gold-loaded carbon is stripped using hot caustic solution. Gold transfers to eluate for electrowinning.'},
      {n:'03',label:'Reactivation',desc:'Stripped carbon is thermally reactivated at 650°C+ to restore capacity. Durability determines how many cycles are viable.'}
    ],
    specs:[
      {name:'K Value (Freundlich)',value:'≥ 45 (target 60–65)',desc:'The best predictor of gold adsorption performance — higher is better'},
      {name:'CTC Activity',value:'45–65%',desc:'Correlates with micropore volume available for gold-cyanide complex adsorption'},
      {name:'Hardness',value:'≥ 98% (ASTM)',desc:'Critical — low hardness generates fines that carry gold-loaded particles to tailings'}
    ],
    grades:[
      {code:'ACGOLD 6SZ',base:'Coconut',desc:'6×12 mesh, K value up to 65, CTC 60%. Standard CIL/CIP workhorse grade.'},
      {code:'ACGOLD 8SFY',base:'Coconut',desc:'8×16 mesh, enhanced fines yield. CTC 55–60%, hardness 99%.'},
      {code:'ACGOLD 6SFY',base:'Coconut',desc:'Fast kinetics variant — for high-throughput CIL circuits with short contact time.'}
    ]
  },
  pharma:{
    title:'Pharma & API Purification', color:'#7C3AED', icon:'⚗️',
    body:'Activated carbon in pharmaceutical manufacturing decolorises API solutions, removes trace organics from injectable preparations, and is the active ingredient in medicinal charcoal products. BP, USP, and EU pharmacopoeial standards apply — and acid-washing is mandatory for injectable-grade applications.',
    steps:[
      {n:'01',label:'Slurry & contact',desc:'Carbon is slurried with the API solution in a mixing vessel. Contact time allows decolorisation and impurity adsorption.'},
      {n:'02',label:'Filtration',desc:'Carbon is filtered out — typically through filter press or sparkler filter. The clarified API solution is collected.'},
      {n:'03',label:'Pharmacopoeial QC',desc:'Batch-level CoA against BP/USP specifications. Heavy metals, pH, arsenic, iron all tested and documented.'}
    ],
    specs:[
      {name:'pH (Acid-washed)',value:'6.5–7.5',desc:'Mandatory for injectables — standard carbon will lower pH of sensitive API solutions'},
      {name:'Iron Content',value:'< 200 ppm',desc:'Low iron prevents discolouration of white APIs and contamination of light-coloured products'},
      {name:'Ash Content',value:'< 5%',desc:'Lower ash means higher purity carbon with fewer inorganic residuals'}
    ],
    grades:[
      {code:'UCI UW-22 / 24 / 26 / 32',base:'Wood',desc:'Acid-washed, pH-neutral. Primary pharma API decolorisation grades. BP/USP CoA available.'},
      {code:'UCI 55NS / DL Premium',base:'Wood',desc:'Ultra-high purity. Lowest ash and iron. For injectables and most demanding API streams.'},
      {code:'UCI 4×8 AWC',base:'Coconut',desc:'Acid-washed coconut GAC for pharmaceutical water systems and BP Purified Water carbon filtration.'}
    ]
  },
  food:{
    title:'Food & Beverage', color:'#059669', icon:'🍶',
    body:'Coconut shell activated carbon is the beverage industry standard — its tight micropore structure removes colour and odour compounds without stripping the flavour precursors that define the product. Applications range from spirit decolorisation to liquid sugar purification.',
    steps:[
      {n:'01',label:'Carbon dosing',desc:'Carbon is dosed into the liquid stream — typically in a mixing tank or inline dosing system.'},
      {n:'02',label:'Contact & adsorption',desc:'Colour bodies and off-flavour compounds adsorb onto carbon surface. Contact time is controlled to preserve flavour.'},
      {n:'03',label:'Filtration & recovery',desc:'Spent carbon is filtered from the treated liquid. Product meets colour specification (ICUMSA or Hazen units).'}
    ],
    specs:[
      {name:'Food Contact',value:'FC certified',desc:'Required for all carbon contacting food or beverage streams — documented for each batch'},
      {name:'Iron Content',value:'< 100 ppm',desc:'Low iron prevents metallic taste carry-over into the final beverage product'},
      {name:'Decolorisation',value:'>80% efficiency',desc:'Standard test at defined contact time and dosage against standard caramel colour solution'}
    ],
    grades:[
      {code:'AC 12×40B',base:'Coconut',desc:'Primary beverage grade. Acid-washed, FC certified. Spirit decolorisation and juice purification.'},
      {code:'AC 12×30B',base:'Coconut',desc:'Slightly coarser mesh — lower pressure drop. Same purity and certification as 12×40B.'},
      {code:'UCI 55N / 55NS',base:'Wood',desc:'Wood-based option for liquid sugar and food processing with high decolorisation demand.'}
    ]
  },
  edibleoil:{
    title:'Edible Oil Refining', color:'#B45309', icon:'🌿',
    body:'Powder activated carbon is used in refining rice bran, palm, sunflower, and specialty oils — removing colour, odour compounds, and trace contaminants. Water-washed grades are mandatory: acid-washed carbon introduces free acid that can damage the oil.',
    steps:[
      {n:'01',label:'Bleaching step',desc:'Carbon is added to oil at elevated temperature (80–110°C) along with bleaching earth, in a bleaching vessel under vacuum.'},
      {n:'02',label:'Contact & mixing',desc:'Carbon contacts the oil for 20–30 minutes. Colour bodies and odour compounds adsorb onto the carbon surface.'},
      {n:'03',label:'Filtration',desc:'Bleached oil passes through filter press. Carbon and bleaching earth are removed. Oil meets colour specification.'}
    ],
    specs:[
      {name:'Wash type',value:'Water-washed only',desc:'Acid-washed carbon introduces free fatty acid into oil — water-washed is the mandatory specification'},
      {name:'Particle Size',value:'200 or 325 mesh',desc:'Finer particle size gives more surface area per gram and improves contact efficiency in oil'},
      {name:'Decolorisation',value:'>85% (Lovibond)',desc:'Lovibond colour reduction test in standard oil at defined dose and temperature'}
    ],
    grades:[
      {code:'AC 200E',base:'Coconut',desc:'Water-washed coconut powder, 200 mesh. Standard edible oil grade. High decolorisation activity.'},
      {code:'AC 325E',base:'Coconut',desc:'325 mesh (finer). Better contact efficiency in applications with shorter contact time.'},
      {code:'UCI UW-22',base:'Wood',desc:'Available water-washed for oil applications. Higher total capacity per batch dosing.'}
    ]
  },
  air:{
    title:'Air & VOC Control', color:'#0E7490', icon:'🌬️',
    body:'Activated carbon in air purification adsorbs volatile organic compounds, solvent vapours, odour molecules, and low-concentration toxic gases. Granular beds are the standard for fixed systems; pellets offer lower pressure drop for continuous high-throughput applications.',
    steps:[
      {n:'01',label:'Gas stream entry',desc:'Contaminated air enters the carbon bed. Flow rate and bed depth determine contact time and removal efficiency.'},
      {n:'02',label:'Adsorption in micropores',desc:'VOC molecules are captured in the micropore network of the carbon. Coconut shell carbon is preferred for its micropore density.'},
      {n:'03',label:'Bed saturation & replacement',desc:'Breakthrough is detected by downstream monitoring. Bed is either replaced (once-through) or regenerated with steam.'}
    ],
    specs:[
      {name:'CTC Activity',value:'45–80%',desc:'Carbon tetrachloride activity is the key indicator for gas-phase micropore capacity'},
      {name:'Pressure Drop',value:'Pellets < GAC',desc:'3mm pellets offer significantly lower pressure drop than equivalent 4×8 GAC — important for energy cost'},
      {name:'Hardness',value:'>95% (GAC), >1.5 MPa crush',desc:'Bed carbon must survive packing and airflow without generating fine particles that bypass the system'}
    ],
    grades:[
      {code:'AC 3×6C / 4×8C',base:'Coconut',desc:'Granular coconut GAC. High CTC activity — standard, H, HH, HHH tiers. Primary VOC control grade.'},
      {code:'PAC-950 / PAC-1300',base:'Wood',desc:'3mm, 4mm, 6mm extruded pellets. Lower pressure drop than GAC. For continuous regeneration systems.'},
      {code:'UCI 4×8 / 6×18',base:'Wood',desc:'Wood GAC for lower-cost industrial air purification where coconut micropore density is not required.'}
    ]
  },
  gasmask:{
    title:'Gas Masks / CBRN', color:'#4B5563', icon:'🛡️',
    body:'CBRN-grade activated carbon for respirator cartridges and collective protection systems must meet stringent penetration tests for chemical warfare agents and toxic industrial chemicals. ABEK impregnation adds chemical reactivity against acid gases, organic vapours, and ammonia.',
    steps:[
      {n:'01',label:'ABEK impregnation',desc:'Carbon is impregnated with specific reagents: triethylenediamine (TEDA), KI, and other agents that chemically react with toxic gases.'},
      {n:'02',label:'Cartridge filling',desc:'Impregnated carbon is filled into cartridge bodies to precise mesh-controlled tolerances. Packing density affects cartridge service life.'},
      {n:'03',label:'Penetration testing',desc:'Finished cartridges are tested for breakthrough time against standard challenge agents per EN 14387 and NATO STANAG protocols.'}
    ],
    specs:[
      {name:'Mesh Size',value:'20×60 to 35×80',desc:'Extremely tight tolerances — fine mesh maximises packing density and contact time per cartridge volume'},
      {name:'Hardness',value:'>99% (ASTM)',desc:'Cartridges vibrate in field use. Any fines generation creates voids and shortens service life'},
      {name:'ABEK Impregnation',value:'Full ABEK spectrum',desc:'A=organic vapours, B=acid gases, E=SO₂/HF, K=ammonia — broad-spectrum protection'}
    ],
    grades:[
      {code:'AC 20×60 / 30×60',base:'Coconut',desc:'Standard CBRN mesh sizes. ABEK impregnated. For standard respirator filter cartridges.'},
      {code:'AC 35×70 / 35×80',base:'Coconut',desc:'Fine mesh for collective protection units. Highest packing density per cartridge volume.'}
    ]
  },
  merox:{
    title:'Oil Refining / Merox', color:'#DC2626', icon:'🏭',
    body:'Merox (Mercaptan Oxidation) is a licensed refinery process that sweetens LPG, kerosene, and jet fuel by converting mercaptans to disulfides using activated carbon as a catalyst support. The carbon bed must withstand continuous liquid-phase contact, elevated temperatures, and regular caustic wash cycles.',
    steps:[
      {n:'01',label:'Merox catalyst loading',desc:'Carbon impregnated with cobalt phthalocyanine catalyst is loaded into fixed-bed sweetening reactors.'},
      {n:'02',label:'Sweetening reaction',desc:'Petroleum fraction passes through the carbon bed with caustic and air. Mercaptans oxidise to disulfides at the carbon surface.'},
      {n:'03',label:'Caustic wash & regeneration',desc:'Carbon bed is periodically washed with caustic to remove deposits and restore catalyst activity. Ultra-high hardness prevents attrition.'}
    ],
    specs:[
      {name:'Hardness',value:'>98% (ASTM)',desc:'Caustic wash cycles create mechanical stress. Low hardness means attrition, fines, and bed channelling.'},
      {name:'Mesh Size',value:'8×30 (standard)',desc:'Uniform mesh ensures consistent bed void fraction and predictable pressure drop in sweetening units'},
      {name:'Iodine Number',value:'>900 mg/g',desc:'High surface area provides maximum catalyst support surface and sustained sweetening activity'}
    ],
    grades:[
      {code:'UCI 8×30 Premio',base:'Wood',desc:'Primary Merox grade. High hardness, consistent 8×30 mesh, proven in LPG and kerosene sweetening.'},
      {code:'UCI UW-22',base:'Wood',desc:'High-specification Merox duty — ultra-pure, low ash, for refinery applications with strict contamination limits.'}
    ]
  },
  sugar:{
    title:'Sugar Refining', color:'#92400E', icon:'🍬',
    body:'Activated carbon is used in raw and refined sugar decolorisation — removing colour compounds (melanoidins, caramels, and polyphenols) from sugar liquors as a modern bone-char replacement. High decolorisation efficiency and consistent mesh sizing are critical for column operations.',
    steps:[
      {n:'01',label:'Liquor preparation',desc:'Raw sugar is dissolved to form a liquor. The liquor is pre-treated to remove suspended solids before carbon contact.'},
      {n:'02',label:'Carbon contact',desc:'Sugar liquor passes through GAC columns or is mixed with PAC. Colour compounds adsorb onto the carbon surface.'},
      {n:'03',label:'Filtration & polishing',desc:'Decolorised liquor is filtered, then evaporated and crystallised. Colour (ICUMSA units) is measured at each stage.'}
    ],
    specs:[
      {name:'Decolorisation Index',value:'>90% efficiency',desc:'Molasses decolorisation index — the primary performance measure for sugar refinery carbon'},
      {name:'Iodine Number',value:'900–1050 mg/g',desc:'Surface area indicator — sufficient capacity for efficient decolorisation per column pass'},
      {name:'Mesh Size',value:'8×30 / 12×40',desc:'Consistent sizing ensures uniform bed flow and avoids channelling in column decolorisation'}
    ],
    grades:[
      {code:'UCI NC 850',base:'Coconut',desc:'Primary sugar GAC grade. High decolorisation efficiency, consistent 8×30 mesh, excellent hardness.'},
      {code:'UCI NC 830',base:'Coconut',desc:'12×40 mesh variant. For applications requiring finer particle size and higher bed contact area.'},
      {code:'UCI 55N',base:'Wood',desc:'Powdered wood grade for batch decolorisation of liquid sugar and glucose syrups.'}
    ]
  }
};

/* Aliases: PHP template uses oil/masks — map to full appData keys */
appData.oil   = appData.edibleoil;
appData.masks = appData.gasmask;

function expandAppPortal(sectorId, cardEl) {
  var data   = appData[sectorId];
  var portal = document.getElementById('app-portal');
  if (!data || !portal) return;

  var homeUrl     = (typeof uciVars !== 'undefined' && uciVars.homeUrl)     ? uciVars.homeUrl.replace(/\/$/, '')  : '';
  var contactUrl  = (typeof uciVars !== 'undefined' && uciVars.contactUrl)  ? uciVars.contactUrl                 : '/contact/';
  var tdsUrl      = (typeof uciVars !== 'undefined' && uciVars.tdsUrl)      ? uciVars.tdsUrl                     : '/request-tds/';
  var brochureUrl = (typeof uciVars !== 'undefined' && uciVars.brochureUrl) ? uciVars.brochureUrl                : '/request-brochure/';

  /* ── Build steps HTML ── */
  var stepsHtml = (data.steps || []).map(function (s) {
    return '<div class="app-step">'
      + '<div class="app-step-num" style="background:' + data.color + '">' + s.n + '</div>'
      + '<div><div class="app-step-label">' + escHtml(s.label) + '</div>'
      + '<div class="app-step-desc">'  + escHtml(s.desc)  + '</div></div>'
      + '</div>';
  }).join('');

  /* ── Build specs HTML ── */
  var specsHtml = (data.specs || []).map(function (s) {
    return '<div class="app-spec-card" style="border-left-color:' + data.color + '">'
      + '<div class="app-spec-name">'  + escHtml(s.name)  + '</div>'
      + '<div class="app-spec-value">' + escHtml(s.value) + '</div>'
      + '<div class="app-spec-desc">'  + escHtml(s.desc)  + '</div>'
      + '</div>';
  }).join('');

  /* ── Build grade recs HTML ── */
  var gradesHtml = (data.grades || []).map(function (g) {
    return '<div class="app-grade-rec">'
      + '<div class="app-grade-rec-code">' + escHtml(g.code) + '</div>'
      + '<span class="app-grade-rec-base">' + escHtml(g.base) + '</span>'
      + '<div class="app-grade-rec-desc">' + escHtml(g.desc) + '</div>'
      + '</div>';
  }).join('');

  /* ── Populate portal header ── */
  var iconEl   = document.getElementById('portal-icon');
  var titleEl  = document.getElementById('portal-title');
  var sectorEl = document.getElementById('portal-sector');
  if (iconEl)   iconEl.textContent   = data.icon;
  if (titleEl)  titleEl.textContent  = data.title;
  if (sectorEl) sectorEl.textContent = 'Application Sector';

  /* ── Populate portal main ── */
  var mainEl = document.getElementById('portal-main');
  if (mainEl) {
    mainEl.innerHTML =
      '<div class="app-section-h">How it works</div>'
      + '<p class="app-desc">' + escHtml(data.body) + '</p>'
      + '<div class="app-section-h">Process</div>'
      + '<div class="app-steps">' + stepsHtml + '</div>';
  }

  /* ── Populate portal sidebar ── */
  var sidebarEl = document.getElementById('portal-sidebar');
  if (sidebarEl) {
    var firstGradeCode = (data.grades && data.grades[0]) ? data.grades[0].code : '';
    sidebarEl.innerHTML =
      '<div class="app-section-h">Key specifications</div>'
      + '<div class="app-specs">' + specsHtml + '</div>'
      + '<div class="app-section-h" style="margin-top:4px">Recommended grades</div>'
      + gradesHtml
      + '<div class="app-portal-ctas">'
      + '<a href="' + tdsUrl + '?grade=' + encodeURIComponent(firstGradeCode) + '" class="btn-primary" style="font-size:13px;padding:11px 18px;display:block;text-align:center;text-decoration:none">Request TDS for these grades →</a>'
      + '<a href="' + homeUrl + '/products/" class="btn-outline" style="font-size:13px;display:block;text-align:center;text-decoration:none;margin-top:8px">View full product catalogue →</a>'
      + '</div>';
  }

  document.querySelectorAll('.app-card').forEach(function (c) { c.classList.remove('active-card'); });
  if (cardEl) cardEl.classList.add('active-card');

  portal.classList.add('open');
  setTimeout(function () {
    var rect = portal.getBoundingClientRect();
    var scrollTop = window.pageYOffset + rect.top - 80;
    window.scrollTo({ top: scrollTop, behavior: 'smooth' });
  }, 50);
}

function closeAppPortal() {
  var portal = document.getElementById('app-portal');
  if (portal) portal.classList.remove('open');
  document.querySelectorAll('.app-card').forEach(function (c) { c.classList.remove('active-card'); });
}


/* ═══════════════════════════════════════════════════════
   UTILITY
   ═══════════════════════════════════════════════════════ */

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
