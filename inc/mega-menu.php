<?php
/**
 * UCI Carbons — Mega Menu system
 *
 * HOW TO MANAGE FROM WP ADMIN (Appearance → Menus):
 * ─────────────────────────────────────────────────
 * 1. Go to Appearance → Menus → Screen Options (top-right) → tick "Description"
 * 2. Four menu locations are registered:
 *      Mega Menu — Applications
 *      Mega Menu — Knowledge
 *      Mega Menu — ESG
 *    (Products mega is auto-built from the importer — no menu needed)
 *
 * APPLICATIONS MENU:
 *   • Each top-level item = one sector card
 *   • Title   = sector name (start with an emoji for the icon, e.g. "💧 Water Treatment")
 *   • URL     = sector page URL
 *   • Description = one-line tagline shown under the sector name
 *
 * KNOWLEDGE MENU:
 *   • Each top-level item = one article card
 *   • Title   = article title (start with emoji icon if desired)
 *   • URL     = article page URL
 *   • Description = short excerpt (1–2 lines)
 *
 * ESG MENU:
 *   • Top-level items = pillar headers (e.g. "🌿 Environmental")
 *   • Drag sub-items under each pillar → they render as bullet points
 *   • Top-level item URL can be left as # if the pillar has no dedicated page
 *
 * If a menu location is NOT assigned, the panel falls back to auto-generated
 * content from the site's pages (no manual setup required).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ═══════════════════════════════════════════════════════════
   REGISTER MENU LOCATIONS
   ═══════════════════════════════════════════════════════════ */

add_action( 'after_setup_theme', function() {
    register_nav_menus([
        'mega_applications' => __( 'Mega Menu — Applications', 'uci' ),
        'mega_knowledge'    => __( 'Mega Menu — Knowledge',    'uci' ),
        'mega_esg'          => __( 'Mega Menu — ESG',          'uci' ),
    ]);
});

/* ═══════════════════════════════════════════════════════════
   HELPER: READ WP NAV MENU INTO A TREE
   Returns array of top-level items, each with 'children' key.
   Returns empty array if the location has no assigned menu.
   ═══════════════════════════════════════════════════════════ */

function uci_mega_get_tree( $location ) {
    $locations = get_nav_menu_locations();
    if ( empty( $locations[ $location ] ) ) return [];

    $menu = wp_get_nav_menu_object( $locations[ $location ] );
    if ( ! $menu ) return [];

    $raw = wp_get_nav_menu_items( $menu->term_id );
    if ( ! $raw || is_wp_error( $raw ) ) return [];

    /* Index items and strip emoji from front of title for separate storage */
    $indexed = [];
    foreach ( $raw as $item ) {
        $title = $item->title;
        $icon  = '';
        /* Extract leading emoji (up to 2 Unicode code points + optional variation selector + ZWJ) */
        if ( preg_match( '/^(\X{1,3})\s+(.+)$/u', $title, $m ) ) {
            $icon  = trim( $m[1] );
            $title = trim( $m[2] );
        }
        $indexed[ $item->ID ] = [
            'id'       => $item->ID,
            'title'    => $title,
            'icon'     => $icon,
            'url'      => $item->url,
            'desc'     => $item->description,
            'classes'  => implode( ' ', (array) $item->classes ),
            'parent'   => (int) $item->menu_item_parent,
            'children' => [],
        ];
    }

    /* Build tree */
    $tree = [];
    foreach ( $indexed as $id => &$node ) {
        if ( $node['parent'] && isset( $indexed[ $node['parent'] ] ) ) {
            $indexed[ $node['parent'] ]['children'][] = &$node;
        } else {
            $tree[] = &$node;
        }
    }
    unset( $node );

    return $tree;
}

/* ═══════════════════════════════════════════════════════════
   SHARED: RENDER MEGA FEATURED PANEL (left column)
   ═══════════════════════════════════════════════════════════ */

function uci_mega_featured( $args ) {
    $color     = $args['color']     ?? '#0296D8';
    $icon      = $args['icon']      ?? '⚗️';
    $eyebrow   = $args['eyebrow']   ?? 'UCI CARBONS GROUP';
    $heading   = $args['heading']   ?? 'Explore our range';
    $stats     = $args['stats']     ?? [];
    $btn_text  = $args['btn_text']  ?? 'Explore →';
    $btn_url   = $args['btn_url']   ?? home_url('/');
    $ghost_text= $args['ghost_text']?? '';
    $ghost_url = $args['ghost_url'] ?? '';

    // Convert hex to rgb for the glow
    $hex = ltrim( $color, '#' );
    $r = hexdec( substr($hex,0,2) );
    $g = hexdec( substr($hex,2,2) );
    $b = hexdec( substr($hex,4,2) );
    $glow = "rgba($r,$g,$b,0.18)";
    ?>
    <div class="mega-featured" style="--feat-color:<?php echo esc_attr($color); ?>;--feat-glow:<?php echo esc_attr($glow); ?>">
        <div class="mega-feat-bg">
            <svg class="mega-feat-svg" viewBox="0 0 280 320" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <g opacity="0.15" stroke="<?php echo esc_attr($color); ?>" stroke-width="0.8">
                    <polygon points="50,15 75,30 75,60 50,75 25,60 25,30"/>
                    <polygon points="100,15 125,30 125,60 100,75 75,60 75,30"/>
                    <polygon points="150,15 175,30 175,60 150,75 125,60 125,30"/>
                    <polygon points="200,15 225,30 225,60 200,75 175,60 175,30"/>
                    <polygon points="25,75 50,90 50,120 25,135 0,120 0,90"/>
                    <polygon points="75,75 100,90 100,120 75,135 50,120 50,90"/>
                    <polygon points="125,75 150,90 150,120 125,135 100,120 100,90"/>
                    <polygon points="175,75 200,90 200,120 175,135 150,120 150,90"/>
                    <polygon points="225,75 250,90 250,120 225,135 200,120 200,90"/>
                    <polygon points="50,135 75,150 75,180 50,195 25,180 25,150"/>
                    <polygon points="100,135 125,150 125,180 100,195 75,180 75,150"/>
                    <polygon points="150,135 175,150 175,180 150,195 125,180 125,150"/>
                    <polygon points="200,135 225,150 225,180 200,195 175,180 175,150"/>
                </g>
                <circle cx="175" cy="90" r="3" fill="<?php echo esc_attr($color); ?>" opacity="0.8"/>
                <circle cx="75"  cy="135" r="2.5" fill="<?php echo esc_attr($color); ?>" opacity="0.6"/>
                <circle cx="125" cy="30"  r="2" fill="<?php echo esc_attr($color); ?>" opacity="0.5"/>
                <circle cx="50"  cy="195" r="2.5" fill="<?php echo esc_attr($color); ?>" opacity="0.5"/>
            </svg>
            <div class="mega-feat-icon-bg" style="color:<?php echo esc_attr($color); ?>"><?php echo $icon; ?></div>
        </div>
        <div class="mega-feat-content">
            <div class="mega-feat-eyebrow"><?php echo esc_html($eyebrow); ?></div>
            <div class="mega-feat-heading"><?php echo wp_kses_post($heading); ?></div>
            <?php if ( !empty($stats) ): ?>
            <div class="mega-feat-stats">
                <?php foreach ($stats as $s): ?>
                <div class="mega-feat-stat">
                    <span class="mega-feat-stat-n" style="color:<?php echo esc_attr($color); ?>"><?php echo esc_html($s[0]); ?></span>
                    <span class="mega-feat-stat-l"><?php echo esc_html($s[1]); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="mega-feat-actions">
                <a href="<?php echo esc_url($btn_url); ?>" class="mega-feat-btn-primary" style="background:<?php echo esc_attr($color); ?>"><?php echo esc_html($btn_text); ?></a>
                <?php if ($ghost_text): ?>
                <a href="<?php echo esc_url($ghost_url); ?>" class="mega-feat-btn-ghost"><?php echo esc_html($ghost_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/* ═══════════════════════════════════════════════════════════
   APPLICATIONS MEGA PANEL
   ═══════════════════════════════════════════════════════════ */

function uci_render_mega_applications() {
    $apps_url = get_permalink( get_page_by_path('applications') ) ?: home_url('/applications/');
    $tds_url  = get_permalink( get_page_by_path('request-tds') )  ?: home_url('/request-tds/');

    /* ── Fallback sector data ── */
    $fallback_sectors = [
        ['slug'=>'water',     'icon'=>'💧', 'color'=>'#0296D8', 'bg'=>'#EFF9FF', 'label'=>'Water Treatment',  'desc'=>'Municipal drinking water, GAC pressure vessels, gravity filters'],
        ['slug'=>'gold',      'icon'=>'🥇', 'color'=>'#F59E0B', 'bg'=>'#FFFBEB', 'label'=>'Gold Recovery',    'desc'=>'CIC · CIL · CIP · Heap leach — ACGOLD series'],
        ['slug'=>'pharma',    'icon'=>'💊', 'color'=>'#7C3AED', 'bg'=>'#F5F3FF', 'label'=>'Pharma & API',     'desc'=>'BP/USP, injectable, parenteral, API decolourisation'],
        ['slug'=>'food',      'icon'=>'🍃', 'color'=>'#2E7D32', 'bg'=>'#F1F8F2', 'label'=>'Food & Beverage',  'desc'=>'Sugar refining, beverage decolourisation, wine & beer'],
        ['slug'=>'air',       'icon'=>'🌬️', 'color'=>'#0F6B8A', 'bg'=>'#EBF6FB', 'label'=>'Air & VOC',        'desc'=>'VOC control, solvent recovery, industrial air treatment'],
        ['slug'=>'edibleoil', 'icon'=>'🫒', 'color'=>'#8B5E3C', 'bg'=>'#FDF4EE', 'label'=>'Edible Oil',        'desc'=>'Palm, soybean, sunflower bleaching and refining'],
    ];

    /* ── Try WP nav menu first ── */
    $menu_items = uci_mega_get_tree('mega_applications');

    /* ── Convert menu items to same shape as fallback ── */
    $sectors = [];
    if ( ! empty($menu_items) ) {
        foreach ( $menu_items as $item ) {
            $sectors[] = [
                'url'   => $item['url'],
                'icon'  => $item['icon'] ?: '⚗️',
                'color' => '#0296D8',
                'bg'    => '#EFF9FF',
                'label' => $item['title'],
                'desc'  => $item['desc'],
            ];
        }
    } else {
        /* Fallback: auto-build from pages */
        foreach ( $fallback_sectors as $s ) {
            $pg  = get_page_by_path('applications/' . $s['slug']);
            $url = $pg ? get_permalink($pg) : home_url('/applications/' . $s['slug'] . '/');
            $sectors[] = array_merge($s, ['url' => $url]);
        }
    }
    ?>
    <div class="mega-panel mega-panel--apps" id="megaPanelApps" role="region" aria-label="Applications menu">
        <div class="mega-inner">

            <?php uci_mega_featured([
                'color'      => '#0296D8',
                'icon'       => '💧',
                'eyebrow'    => 'APPLICATIONS',
                'heading'    => "Six sectors.<br>One carbon partner.",
                'stats'      => [['6','Industry sectors'], ['30+','Countries served'], ['55+','Years of expertise']],
                'btn_text'   => 'All applications →',
                'btn_url'    => $apps_url,
                'ghost_text' => 'Request TDS',
                'ghost_url'  => $tds_url,
            ]); ?>

            <div class="mega-families mega-apps-grid">
                <?php foreach ( $sectors as $s ):
                    $color = $s['color'] ?? '#0296D8';
                    $hex   = ltrim($color,'#');
                    $r = hexdec(substr($hex,0,2)); $g2 = hexdec(substr($hex,2,2)); $b = hexdec(substr($hex,4,2));
                    $rgba12 = "rgba($r,$g2,$b,0.12)";
                ?>
                <a href="<?php echo esc_url($s['url']); ?>" class="mega-fam mega-app-card">
                    <div class="mega-app-icon" style="background:<?php echo esc_attr($rgba12); ?>;color:<?php echo esc_attr($color); ?>"><?php echo $s['icon']; ?></div>
                    <div class="mega-app-body">
                        <div class="mega-fam-name" style="--fam-color:<?php echo esc_attr($color); ?>"><?php echo esc_html($s['label']); ?></div>
                        <?php if ($s['desc']): ?>
                        <div class="mega-fam-sub"><?php echo esc_html($s['desc']); ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="mega-app-arrow" style="color:<?php echo esc_attr($color); ?>">→</span>
                </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
    <?php
}

/* ═══════════════════════════════════════════════════════════
   KNOWLEDGE MEGA PANEL
   ═══════════════════════════════════════════════════════════ */

function uci_render_mega_knowledge() {
    $know_url = get_permalink( get_page_by_path('knowledge') ) ?: home_url('/knowledge/');
    $tds_url  = get_permalink( get_page_by_path('request-tds') ) ?: home_url('/request-tds/');

    /* ── Try WP nav menu first ── */
    $menu_items = uci_mega_get_tree('mega_knowledge');

    $articles = [];
    if ( ! empty($menu_items) ) {
        foreach ( $menu_items as $item ) {
            $articles[] = [
                'url'   => $item['url'],
                'icon'  => $item['icon'] ?: '📖',
                'title' => $item['title'],
                'desc'  => $item['desc'],
            ];
        }
    } else {
        /* Fallback: auto-read child pages of /knowledge/ */
        $know_page = get_page_by_path('knowledge');
        if ( $know_page ) {
            $child_pages = get_pages([
                'parent'      => $know_page->ID,
                'number'      => 6,
                'sort_column' => 'menu_order',
                'post_status' => 'publish',
            ]);
            foreach ( $child_pages as $cp ) {
                $excerpt = get_the_excerpt($cp->ID) ?: wp_trim_words($cp->post_content, 12);
                $articles[] = [
                    'url'   => get_permalink($cp->ID),
                    'icon'  => '📖',
                    'title' => $cp->post_title,
                    'desc'  => $excerpt,
                ];
            }
        }
        /* If still empty, use hardcoded starters */
        if ( empty($articles) ) {
            $hardcoded = [
                ['slug'=>'what-is-ac',          'icon'=>'🔬', 'title'=>'What is Activated Carbon?',     'desc'=>'How activated carbon is made, how it works, and which form to use'],
                ['slug'=>'wood-coconut-bamboo',  'icon'=>'🌳', 'title'=>'Wood vs Coconut Carbon',        'desc'=>'When to choose wood-based PAC/GAC over coconut-shell carbon'],
                ['slug'=>'gac-vs-pac',           'icon'=>'⚗️', 'title'=>'GAC vs PAC — Which to use?',   'desc'=>'Granular vs powdered carbon — process fit, cost, and regeneration'],
                ['slug'=>'water-treatment-guide','icon'=>'💧', 'title'=>'Water Treatment Carbon Guide',  'desc'=>'GAC sizing, bed design, and replacement cycles for drinking water'],
                ['slug'=>'gold-recovery',        'icon'=>'🥇', 'title'=>'Gold Recovery with Carbon',    'desc'=>'CIC, CIL, and CIP explained — K-value, elution, and grade selection'],
                ['slug'=>'pharma-grade-carbon',  'icon'=>'💊', 'title'=>'Pharma Grade Carbon Explained','desc'=>'BP/USP compliance, acid washing, and particle size requirements'],
            ];
            foreach ( $hardcoded as $a ) {
                $pg  = get_page_by_path('knowledge/' . $a['slug']);
                $url = $pg ? get_permalink($pg) : home_url('/knowledge/' . $a['slug'] . '/');
                $articles[] = ['url'=>$url, 'icon'=>$a['icon'], 'title'=>$a['title'], 'desc'=>$a['desc']];
            }
        }
    }
    ?>
    <div class="mega-panel mega-panel--knowledge" id="megaPanelKnowledge" role="region" aria-label="Knowledge menu">
        <div class="mega-inner">

            <?php uci_mega_featured([
                'color'      => '#F59E0B',
                'icon'       => '📚',
                'eyebrow'    => 'KNOWLEDGE BANK',
                'heading'    => "Carbon science.<br>In plain language.",
                'stats'      => [['15+','Technical articles'], ['Free','No registration'], ['AI','Carbon expert chat']],
                'btn_text'   => 'Browse all articles →',
                'btn_url'    => $know_url,
                'ghost_text' => 'Ask Carbon AI',
                'ghost_url'  => $know_url . '#ai-section-home',
            ]); ?>

            <div class="mega-families mega-knowledge-grid">
                <?php foreach ( array_slice($articles, 0, 6) as $art ): ?>
                <a href="<?php echo esc_url($art['url']); ?>" class="mega-fam mega-article-card">
                    <div class="mega-article-icon"><?php echo $art['icon']; ?></div>
                    <div class="mega-article-body">
                        <div class="mega-article-title"><?php echo esc_html($art['title']); ?></div>
                        <?php if ($art['desc']): ?>
                        <div class="mega-fam-sub"><?php echo esc_html(wp_trim_words($art['desc'], 10)); ?></div>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
    <?php
}

/* ═══════════════════════════════════════════════════════════
   ESG MEGA PANEL
   ═══════════════════════════════════════════════════════════ */

function uci_render_mega_esg() {
    $esg_url = get_permalink( get_page_by_path('esg') ) ?: home_url('/esg/');

    /* ── Fallback pillar data ── */
    $fallback_pillars = [
        [
            'icon'  => '🌿',
            'color' => '#2E7D32',
            'bg'    => '#F1F8F2',
            'label' => 'Environmental',
            'items' => [
                'Zero-liquid discharge production',
                'Responsibly sourced wood precursors',
                'Carbon footprint tracking & reduction',
                'ISO 14001 environmental management',
            ],
            'url'   => $esg_url . '#environmental',
        ],
        [
            'icon'  => '👥',
            'color' => '#0296D8',
            'bg'    => '#EFF9FF',
            'label' => 'Social',
            'items' => [
                '400+ direct employees, Hoshiarpur, Punjab',
                'Safe workplace — ISO 45001 certified',
                'Community development & skills programmes',
                'Gender-inclusive manufacturing workforce',
            ],
            'url'   => $esg_url . '#social',
        ],
        [
            'icon'  => '🏛️',
            'color' => '#7C3AED',
            'bg'    => '#F5F3FF',
            'label' => 'Governance',
            'items' => [
                'ISO 9001 quality management system',
                'Three-generation family ownership',
                'Full batch traceability, raw material → dispatch',
                'Halal, Kosher, NSF, and BP/USP compliance',
            ],
            'url'   => $esg_url . '#governance',
        ],
    ];

    /* ── Try WP nav menu first ── */
    $menu_tree = uci_mega_get_tree('mega_esg');

    $pillars = [];
    if ( ! empty($menu_tree) ) {
        foreach ( $menu_tree as $top ) {
            $items = [];
            foreach ( $top['children'] as $child ) {
                $items[] = $child['title'];
            }
            $pillars[] = [
                'icon'  => $top['icon'] ?: '⚗️',
                'color' => '#0296D8',
                'bg'    => '#EFF9FF',
                'label' => $top['title'],
                'items' => $items,
                'url'   => $top['url'],
            ];
        }
    } else {
        $pillars = $fallback_pillars;
    }
    ?>
    <div class="mega-panel mega-panel--esg" id="megaPanelEsg" role="region" aria-label="ESG menu">
        <div class="mega-inner">

            <?php uci_mega_featured([
                'color'      => '#2E7D32',
                'icon'       => '🌿',
                'eyebrow'    => 'ESG COMMITMENT',
                'heading'    => "Purifying responsibly<br>since 1969.",
                'stats'      => [['ISO','9001 · 14001 · 45001'], ['55+','Years of stewardship'], ['400+','Employees, Hoshiarpur']],
                'btn_text'   => 'Read our ESG report →',
                'btn_url'    => $esg_url,
                'ghost_text' => '',
                'ghost_url'  => '',
            ]); ?>

            <div class="mega-families mega-esg-grid">
                <?php foreach ( $pillars as $pillar ):
                    $color = $pillar['color'];
                    $hex   = ltrim($color,'#');
                    $r = hexdec(substr($hex,0,2)); $g2 = hexdec(substr($hex,2,2)); $b = hexdec(substr($hex,4,2));
                    $rgba12 = "rgba($r,$g2,$b,0.12)";
                ?>
                <div class="mega-fam mega-esg-pillar">
                    <a href="<?php echo esc_url($pillar['url']); ?>" class="mega-esg-header" style="color:<?php echo esc_attr($color); ?>">
                        <span class="mega-esg-icon" style="background:<?php echo esc_attr($rgba12); ?>"><?php echo $pillar['icon']; ?></span>
                        <span class="mega-fam-name" style="--fam-color:<?php echo esc_attr($color); ?>"><?php echo esc_html($pillar['label']); ?></span>
                    </a>
                    <?php if (!empty($pillar['items'])): ?>
                    <ul class="mega-esg-items">
                        <?php foreach ($pillar['items'] as $bullet): ?>
                        <li><?php echo esc_html($bullet); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
    <?php
}
