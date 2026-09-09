<?php
/**
 * Template Name: Applications
 * Nine sector applications page with expandable portals.
 */
get_header();
?>

<main id="primary" class="site-main">

<!-- ── HERO ── -->
<section style="padding:100px 0 56px;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:600px;height:600px;border-radius:50%;background:var(--blue);opacity:.06;top:-180px;right:-120px;pointer-events:none"></div>
    <div style="position:absolute;inset:0;opacity:.025;background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);background-size:48px 48px;pointer-events:none"></div>
    <div class="wrap" style="position:relative;z-index:2">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
            <div style="width:28px;height:2px;background:var(--blue)"></div>
            <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--blue);text-transform:uppercase">Where Carbon Works</span>
        </div>
        <h1 style="font-size:clamp(36px,4.5vw,60px);font-weight:800;letter-spacing:-2px;color:#fff;line-height:1;margin-bottom:16px">
            Nine sectors.<br><span style="color:var(--sky)">One source.</span>
        </h1>
        <p style="font-size:17px;color:rgba(255,255,255,0.5);max-width:540px;line-height:1.7;margin-bottom:36px">
            One grade may serve multiple applications. Select a sector to explore the process, key specifications, and recommended UCI grades.
        </p>
        <div style="display:flex;gap:1px;background:rgba(255,255,255,0.08);border-radius:14px;overflow:hidden;max-width:560px;border:1px solid rgba(255,255,255,0.07)">
            <?php
            $app_stats = [['9','Sectors'],['35+','Grades'],['55','Years'],['30+','Countries']];
            foreach ( $app_stats as $s ) {
                echo '<div style="flex:1;padding:16px 20px;background:rgba(255,255,255,0.04);text-align:center">';
                echo '<div style="font-size:24px;font-weight:800;color:var(--blue);letter-spacing:-1px;line-height:1">' . esc_html($s[0]) . '</div>';
                echo '<div style="font-family:var(--mono);font-size:9px;letter-spacing:1.5px;color:rgba(255,255,255,0.3);text-transform:uppercase;margin-top:5px">' . esc_html($s[1]) . '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- ── SECTOR GRID ── -->
<section class="section" style="background:var(--bg)">
    <div class="wrap">
        <div class="app-sector-grid" id="app-grid">

            <?php
            $sectors = [
                //  card-id      color       bg         border      icon   name                              desc                                                                                                              chips                              wp-slug
                ['water',    '#0296D8',  '#E8F5FD', '#BAE6FD',  '💧', 'Water Treatment',               'Municipal, industrial, and potable water. GAC bed filtration, post-treatment polishing, emergency PAC dosing.',  ['RC830','4×8C','12×40C'],         'water'],
                ['gold',     '#D97706',  '#FEF3C7', '#FDE68A',  '🥇', 'Gold Recovery',                 'CIL, CIP, and heap leach circuits. High K-value, fast kinetics, extreme hardness for elution cycling.',           ['ACGOLD 6SZ','ACGOLD 8SFY'],      'gold'],
                ['pharma',   '#7C3AED',  '#EDE9FE', '#DDD6FE',  '⚗️', 'Pharma &amp; API Purification', 'BP/USP/EU Pharmacopoeia grades. API decolorisation, injectables, medicinal charcoal tablets.',                   ['UW-22','55NS','DL Premium'],     'pharma'],
                ['food',     '#059669',  '#D1FAE5', '#A7F3D0',  '🍶', 'Food &amp; Beverage',           'Spirit decolorisation, juice purification, liquid sugar. Acid-washed, food-contact certified coconut grades.',    ['12×40B','12×30B','55N'],         'food'],
                ['edibleoil','#B45309',  '#FEF3C7', '#FDE68A',  '🌿', 'Edible Oil Refining',           'Rice bran, palm, sunflower, specialty oils. Water-washed powder — no acid carry-over to oil stream.',            ['AC 200E','AC 325E'],             'edibleoil'],
                ['air',      '#0E7490',  '#CFFAFE', '#A5F3FC',  '🌬️', 'Air &amp; VOC Control',         'Industrial air purification, solvent recovery, odour control. Granular and pellet grades for fixed beds.',        ['4×8C','PAC-950','PAC-1300'],     'air'],
                ['gasmask',  '#4B5563',  '#F3F4F6', '#E5E7EB',  '🛡️', 'Gas Masks / CBRN',             'ABEK impregnated grades for respirator cartridges. Tested to EN 14387 and NATO STANAG standards.',               ['20×60','30×60','35×80'],         'gasmask'],
                ['pellets',  '#2563EB',  '#DBEAFE', '#BFDBFE',  '⚙️', 'Pellets — Gas Phase',           '3mm, 4mm, 6mm extruded pellets. Lower pressure drop vs GAC. Continuous gas-phase and industrial air systems.',   ['PAC-950','PAC-1300'],            'pellets'],
                ['merox',    '#DC2626',  '#FEE2E2', '#FECACA',  '🏭', 'Oil Refining / Merox',          'Sweetening of LPG, kerosene, naphtha. Continuous liquid-phase duty. Ultra-high hardness essential.',             ['8×30 Premio','UW-22'],           'merox'],
            ];

            foreach ( $sectors as $s ) {
                list( $id, $color, $bg, $border, $icon, $name, $desc, $chips, $wp_slug ) = $s;
                $sector_page = get_page_by_path( 'applications/' . $wp_slug );
                $sector_url  = $sector_page ? get_permalink( $sector_page ) : home_url( '/applications/' . $wp_slug . '/' );
            ?>
            <div class="app-card" style="--app-color:<?php echo esc_attr($color); ?>;--app-bg:<?php echo esc_attr($bg); ?>;cursor:pointer" id="app-card-<?php echo esc_attr($id); ?>" onclick="expandAppPortal('<?php echo esc_attr($id); ?>', this)">
                <div class="app-card-inner">
                    <div class="app-card-icon-wrap" style="background:<?php echo esc_attr($bg); ?>"><?php echo $icon; ?></div>
                    <div class="app-card-h"><?php echo $name; ?></div>
                    <div class="app-card-body"><?php echo esc_html($desc); ?></div>
                    <div class="app-card-grades">
                        <?php foreach ( $chips as $chip ) : ?>
                            <span class="chip"><?php echo esc_html($chip); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="app-card-footer" style="border-top-color:<?php echo esc_attr($border); ?>;display:flex;align-items:center;justify-content:space-between;gap:8px">
                    <span class="app-card-cta" style="color:<?php echo esc_attr($color); ?>;font-weight:700;font-size:12px;letter-spacing:.3px">Explore sector</span>
                    <div style="display:flex;align-items:center;gap:8px">
                        <a href="<?php echo esc_url($sector_url); ?>" onclick="event.stopPropagation()" style="font-family:var(--mono);font-size:9px;font-weight:700;letter-spacing:1px;color:<?php echo esc_attr($color); ?>;text-decoration:none;opacity:.7;text-transform:uppercase;border:1px solid <?php echo esc_attr($color); ?>44;border-radius:4px;padding:3px 8px">Full page ↗</a>
                        <span class="app-card-arrow" style="color:<?php echo esc_attr($color); ?>">→</span>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

        <!-- Expandable portal -->
        <div class="app-portal" id="app-portal">
            <div class="app-portal-header">
                <div class="app-portal-meta">
                    <div class="app-portal-icon" id="portal-icon">💧</div>
                    <div>
                        <div class="app-portal-title" id="portal-title">Water Treatment</div>
                        <div class="app-portal-sector" id="portal-sector">SECTOR</div>
                    </div>
                </div>
                <button class="app-portal-close" onclick="closeAppPortal()">✕</button>
            </div>
            <div class="app-portal-body">
                <div class="app-portal-main" id="portal-main">
                    <!-- Populated by JS -->
                </div>
                <div class="app-portal-sidebar" id="portal-sidebar">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>

        <!-- Can't find your application? -->
        <div style="margin-top:32px;padding:28px 32px;background:linear-gradient(135deg,var(--dark),#0e4d6a);border-radius:18px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:20px">
            <div>
                <p style="font-size:17px;font-weight:700;color:#fff;margin-bottom:6px">Can't find your application?</p>
                <p style="font-size:14px;color:rgba(255,255,255,0.5);max-width:500px;line-height:1.6">Our technical team has worked across industrial, environmental, and specialty applications for 55 years. If your use case isn't listed, we'll match you to the right grade.</p>
            </div>
            <a href="<?php echo esc_url( get_permalink(get_page_by_path('contact')) ?: home_url('/contact/') ); ?>" class="btn-primary" style="flex-shrink:0;text-decoration:none">Talk to our team →</a>
        </div>

    </div>
</section>

<!-- ── CARBON AI STRIP ── -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Not sure which carbon suits your application?</div>
                <div class="pai-sub">Describe your process — our AI will recommend the right grade.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-apps" class="pai-input" placeholder="e.g. VOC removal in pharmaceutical exhaust air..." onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-apps')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-apps')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>

<?php get_footer(); ?>
