<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-4S1XC0Y4B7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-4S1XC0Y4B7');
</script>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php
/* ── URLs for mega menu — cached to avoid DB queries on every load ── */
$nav_products_url = get_transient( 'uci_url_products' );
if ( ! $nav_products_url ) {
    $p = get_page_by_path( 'products' );
    $nav_products_url = $p ? get_permalink( $p ) : home_url( '/products/' );
    set_transient( 'uci_url_products', $nav_products_url, DAY_IN_SECONDS );
}

$nav_apps_url = get_transient( 'uci_url_applications' );
if ( ! $nav_apps_url ) {
    $p = get_page_by_path( 'applications' );
    $nav_apps_url = $p ? get_permalink( $p ) : home_url( '/applications/' );
    set_transient( 'uci_url_applications', $nav_apps_url, DAY_IN_SECONDS );
}

$nav_knowledge_url = get_transient( 'uci_url_knowledge' );
if ( ! $nav_knowledge_url ) {
    $p = get_page_by_path( 'knowledge' );
    $nav_knowledge_url = $p ? get_permalink( $p ) : home_url( '/knowledge/' );
    set_transient( 'uci_url_knowledge', $nav_knowledge_url, DAY_IN_SECONDS );
}

$nav_esg_url = get_transient( 'uci_url_esg' );
if ( ! $nav_esg_url ) {
    $p = get_page_by_path( 'esg' );
    $nav_esg_url = $p ? get_permalink( $p ) : home_url( '/esg/' );
    set_transient( 'uci_url_esg', $nav_esg_url, DAY_IN_SECONDS );
}

$nav_tds_url = get_transient( 'uci_url_tds' );
if ( ! $nav_tds_url ) {
    $p = get_page_by_path( 'request-tds' );
    $nav_tds_url = $p ? get_permalink( $p ) : home_url( '/request-tds/' );
    set_transient( 'uci_url_tds', $nav_tds_url, DAY_IN_SECONDS );
}

/* Mega-menu family data: [slug, color, bg-gradient, icon, label, tagline, grades[]] */
$mega_families = [
    [
        'slug'    => 'wood-pac',
        'color'   => '#A0714F',
        'grad'    => 'linear-gradient(135deg,#5D3A1A 0%,#A0714F 100%)',
        'icon'    => '🌲',
        'label'   => 'Wood Powder',
        'sub'     => 'PAC · Pharma & Decolourisation',
        'grades'  => ['UCI UW-22','UCI UW-24','UCI UW-26','UCI UW-32'],
    ],
    [
        'slug'    => 'wood-gac',
        'color'   => '#2E7D32',
        'grad'    => 'linear-gradient(135deg,#1B4D1E 0%,#388E3C 100%)',
        'icon'    => '🪵',
        'label'   => 'Wood Granular',
        'sub'     => 'GAC · Water & Air Treatment',
        'grades'  => ['UCI RC 830 (3×6)','UCI RC 830 (8×16)','UCI 6×56'],
    ],
    [
        'slug'    => 'coconut-carbon',
        'color'   => '#0296D8',
        'grad'    => 'linear-gradient(135deg,#013D6B 0%,#0296D8 100%)',
        'icon'    => '🥥',
        'label'   => 'Coconut Shell',
        'sub'     => 'GAC · Gold Recovery & Water',
        'grades'  => ['ACGOLD 6SZ','ACGOLD 6SFY','UCI 4×8C','UCI 4×8 AWC'],
    ],
    [
        'slug'    => 'pellets',
        'color'   => '#0F6B8A',
        'grad'    => 'linear-gradient(135deg,#07384A 0%,#0F6B8A 100%)',
        'icon'    => '⚙️',
        'label'   => 'Pellets',
        'sub'     => 'Gas Phase · Low Pressure Drop',
        'grades'  => ['UCI P-3','UCI P-4','UCI P-6'],
    ],
    [
        'slug'    => 'specialty-impregnated',
        'color'   => '#7C3AED',
        'grad'    => 'linear-gradient(135deg,#3B0D8A 0%,#7C3AED 100%)',
        'icon'    => '🧬',
        'label'   => 'Specialty',
        'sub'     => 'CBRN · Silver · Ultra-Purity',
        'grades'  => ['UCI DL Premium','UCI Ag-PAC','UCI ABEK-P3','UCI 55NS'],
    ],
];
?>

<nav class="nav" id="site-nav">
    <div class="nav-inner">

        <a class="nav-logo" href="<?php echo esc_url( home_url('/') ); ?>">
            <div class="nav-logo-mark">
                <svg viewBox="0 0 52 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="navDropGrad" x1="50%" y1="0%" x2="50%" y2="100%">
                            <stop offset="0%" stop-color="#4DD8FF"/>
                            <stop offset="40%" stop-color="#0296D8"/>
                            <stop offset="100%" stop-color="#024A80"/>
                        </linearGradient>
                        <radialGradient id="navDropGlow" cx="50%" cy="40%">
                            <stop offset="0%" stop-color="#29B6F6" stop-opacity="0.5"/>
                            <stop offset="100%" stop-color="#0296D8" stop-opacity="0"/>
                        </radialGradient>
                        <filter id="navDropBlur">
                            <feGaussianBlur stdDeviation="1.5"/>
                        </filter>
                    </defs>
                    <path d="M26 4C26 4 4 26 4 40a22 22 0 0044 0C48 26 26 4 26 4z" fill="url(#navDropGlow)" filter="url(#navDropBlur)" opacity="0.6"/>
                    <path d="M26 4C26 4 5 26 5 40a21 21 0 0042 0C47 26 26 4 26 4z" fill="url(#navDropGrad)"/>
                    <path d="M26 10C26 10 14 24 12 34" stroke="rgba(255,255,255,0.35)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    <circle cx="26" cy="41" r="13" fill="none" stroke="rgba(255,255,255,0.18)" stroke-width="1"/>
                    <circle cx="26" cy="41" r="8"  fill="none" stroke="rgba(255,255,255,0.20)" stroke-width="1"/>
                    <circle cx="26" cy="41" r="3.5" fill="rgba(255,255,255,0.28)"/>
                    <circle cx="26" cy="29" r="1.2" fill="rgba(255,255,255,0.22)"/>
                    <circle cx="33" cy="33" r="1.2" fill="rgba(255,255,255,0.18)"/>
                    <circle cx="33" cy="41" r="1.2" fill="rgba(255,255,255,0.18)"/>
                    <circle cx="19" cy="33" r="1.2" fill="rgba(255,255,255,0.18)"/>
                    <circle cx="19" cy="41" r="1.2" fill="rgba(255,255,255,0.18)"/>
                    <ellipse cx="20" cy="18" rx="4" ry="6" fill="rgba(255,255,255,0.12)" transform="rotate(-20 20 18)"/>
                </svg>
            </div>
            <div class="nav-brand">
                <div class="nav-brand-name"><?php bloginfo('name'); ?></div>
                <div class="nav-brand-tagline">Purifying Since 1969</div>
                <div class="nav-brand-sub">UCI Carbons Group</div>
            </div>
        </a>

        <div class="nav-links">
            <?php $mega_enabled = get_option('uci_mega_menu_enabled', '1'); ?>
            <?php if ($mega_enabled == '1'): ?>
            <ul class="nav-menu">

                <li><a href="<?php echo esc_url( home_url('/') ); ?>" class="nav-link <?php echo (is_front_page()) ? 'active' : ''; ?>">Home</a></li>

                <!-- ── PRODUCTS MEGA MENU ── -->
                <li class="has-mega" id="navProductsItem">
                    <a href="<?php echo esc_url($nav_products_url); ?>" class="nav-link nav-link-mega <?php echo (is_page('products') || is_page_template('page-product-family.php') || is_page_template('page-product-grade.php')) ? 'active' : ''; ?>" id="navProductsBtn" aria-expanded="false" aria-haspopup="true">
                        Products
                        <svg class="nav-chevron" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>

                    <!-- MEGA PANEL -->
                    <div class="mega-panel" id="megaPanel" role="region" aria-label="Products menu">
                        <div class="mega-inner">

                            <!-- Left: featured image-like panel -->
                            <div class="mega-featured">
                                <div class="mega-feat-bg">
                                    <!-- animated carbon hexagon grid -->
                                    <svg class="mega-feat-svg" viewBox="0 0 280 320" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <!-- hex grid pattern -->
                                        <g opacity="0.18" stroke="#4DD8FF" stroke-width="0.8">
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
                                            <polygon points="25,195 50,210 50,240 25,255 0,240 0,210"/>
                                            <polygon points="75,195 100,210 100,240 75,255 50,240 50,210"/>
                                            <polygon points="125,195 150,210 150,240 125,255 100,240 100,210"/>
                                            <polygon points="175,195 200,210 200,240 175,255 150,240 150,210"/>
                                            <polygon points="225,195 250,210 250,240 225,255 200,240 200,210"/>
                                        </g>
                                        <!-- glowing dots at hex nodes -->
                                        <circle cx="50" cy="15"  r="2.5" fill="#0296D8" opacity="0.7"/>
                                        <circle cx="125" cy="30" r="2"   fill="#29B6F6" opacity="0.5"/>
                                        <circle cx="75" cy="135" r="2.5" fill="#0296D8" opacity="0.6"/>
                                        <circle cx="175" cy="90" r="3"   fill="#4DD8FF" opacity="0.8"/>
                                        <circle cx="100" cy="195" r="2"  fill="#0296D8" opacity="0.5"/>
                                        <circle cx="225" cy="150" r="2.5" fill="#29B6F6" opacity="0.6"/>
                                        <circle cx="150" cy="255" r="2"  fill="#4DD8FF" opacity="0.5"/>
                                    </svg>
                                </div>
                                <div class="mega-feat-content">
                                    <div class="mega-feat-eyebrow">UCI CARBONS GROUP</div>
                                    <div class="mega-feat-heading">28+ grades.<br>Two materials.<br>Every application.</div>
                                    <div class="mega-feat-stats">
                                        <div class="mega-feat-stat">
                                            <span class="mega-feat-stat-n">55+</span>
                                            <span class="mega-feat-stat-l">Years</span>
                                        </div>
                                        <div class="mega-feat-stat">
                                            <span class="mega-feat-stat-n">30+</span>
                                            <span class="mega-feat-stat-l">Countries</span>
                                        </div>
                                        <div class="mega-feat-stat">
                                            <span class="mega-feat-stat-n">24h</span>
                                            <span class="mega-feat-stat-l">TDS turnaround</span>
                                        </div>
                                    </div>
                                    <div class="mega-feat-actions">
                                        <a href="<?php echo esc_url($nav_tds_url); ?>" class="mega-feat-btn-primary">Request TDS →</a>
                                        <a href="<?php echo esc_url($nav_products_url); ?>" class="mega-feat-btn-ghost">All grades</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: 5 family columns -->
                            <div class="mega-families">
                                <?php foreach ( $mega_families as $mf ):
                                    $fam_page = get_page_by_path('products/' . $mf['slug']);
                                    $fam_url  = $fam_page ? get_permalink($fam_page) : home_url('/products/' . $mf['slug'] . '/');
                                ?>
                                <div class="mega-fam">
                                    <a href="<?php echo esc_url($fam_url); ?>" class="mega-fam-header" style="--fam-grad:<?php echo esc_attr($mf['grad']); ?>;--fam-color:<?php echo esc_attr($mf['color']); ?>">
                                        <div class="mega-fam-img">
                                            <span class="mega-fam-icon"><?php echo $mf['icon']; ?></span>
                                            <div class="mega-fam-img-bg" style="background:<?php echo esc_attr($mf['grad']); ?>"></div>
                                        </div>
                                        <div class="mega-fam-meta">
                                            <div class="mega-fam-name"><?php echo esc_html($mf['label']); ?></div>
                                            <div class="mega-fam-sub"><?php echo esc_html($mf['sub']); ?></div>
                                        </div>
                                    </a>
                                    <ul class="mega-fam-grades">
                                        <?php foreach ( array_slice($mf['grades'], 0, 3) as $grade_code ):
                                            /* Build slug: lowercase, spaces→hyphens, × → x, special chars strip */
                                            $g_slug_raw = strtolower($grade_code);
                                            $g_slug_raw = preg_replace('/[×x]/', 'x', $g_slug_raw);
                                            $g_slug_raw = preg_replace('/[^a-z0-9\-]/', '-', $g_slug_raw);
                                            $g_slug_raw = preg_replace('/-+/', '-', trim($g_slug_raw, '-'));
                                            $g_slug = 'uci-' . ltrim($g_slug_raw, 'uci-');
                                            /* Try to find the page */
                                            $gp  = get_page_by_path('products/' . $g_slug_raw)
                                                ?: get_page_by_path('products/' . $g_slug);
                                            $gurl = $gp ? get_permalink($gp) : home_url('/products/' . $g_slug_raw . '/');
                                        ?>
                                        <li><a href="<?php echo esc_url($gurl); ?>" class="mega-grade-link"><?php echo esc_html($grade_code); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <a href="<?php echo esc_url($fam_url); ?>" class="mega-fam-more" style="color:<?php echo esc_attr($mf['color']); ?>">View all →</a>
                                </div>
                                <?php endforeach; ?>
                            </div>

                        </div><!-- /.mega-inner -->
                    </div><!-- /.mega-panel -->
                </li>
                <!-- ── END PRODUCTS MEGA MENU ── -->

                <!-- ── APPLICATIONS MEGA MENU ── -->
                <li class="has-mega" id="navAppsItem">
                    <a href="<?php echo esc_url($nav_apps_url); ?>" class="nav-link nav-link-mega <?php echo (is_page('applications') || is_page_template('page-application-sector.php')) ? 'active' : ''; ?>" aria-expanded="false" aria-haspopup="true">
                        Applications
                        <svg class="nav-chevron" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php uci_render_mega_applications(); ?>
                </li>

                <!-- ── KNOWLEDGE MEGA MENU ── -->
                <li class="has-mega" id="navKnowledgeItem">
                    <a href="<?php echo esc_url($nav_knowledge_url); ?>" class="nav-link nav-link-mega <?php echo is_page('knowledge') ? 'active' : ''; ?>" aria-expanded="false" aria-haspopup="true">
                        Knowledge
                        <svg class="nav-chevron" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php uci_render_mega_knowledge(); ?>
                </li>

                <!-- ── ESG MEGA MENU ── -->
                <li class="has-mega" id="navEsgItem">
                    <a href="<?php echo esc_url($nav_esg_url); ?>" class="nav-link nav-link-mega <?php echo is_page('esg') ? 'active' : ''; ?>" aria-expanded="false" aria-haspopup="true">
                        ESG
                        <svg class="nav-chevron" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <?php uci_render_mega_esg(); ?>
                </li>

            </ul>
            <?php else: ?>
            <?php wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'walker'         => new UCI_Simple_Walker(),
                'depth'          => 0,
                'fallback_cb'    => false,
            ]); ?>
            <?php endif; ?>
        </div>

        <a href="<?php echo esc_url( home_url('/contact/') ); ?>" class="nav-cta">Get in touch</a>

        <button class="nav-mobile-btn" id="mobileMenuBtn" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>

    </div>
</nav>

<!-- ── MEGA MENU BACKDROP ── -->
<div class="mega-backdrop" id="megaBackdrop"></div>

<!-- Mobile overlay -->
<div class="mobile-overlay" id="mobileOverlay">

    <!-- Mobile overlay header: logo + close -->
    <div class="mobile-overlay-header">
        <a class="mobile-overlay-logo" href="<?php echo esc_url( home_url('/') ); ?>">
            <div class="mobile-overlay-logo-mark">
                <svg viewBox="0 0 52 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="mDropGrad" x1="50%" y1="0%" x2="50%" y2="100%">
                            <stop offset="0%" stop-color="#4DD8FF"/>
                            <stop offset="40%" stop-color="#0296D8"/>
                            <stop offset="100%" stop-color="#024A80"/>
                        </linearGradient>
                    </defs>
                    <path d="M26 4C26 4 5 26 5 40a21 21 0 0042 0C47 26 26 4 26 4z" fill="url(#mDropGrad)"/>
                    <circle cx="26" cy="41" r="13" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
                    <circle cx="26" cy="41" r="3.5" fill="rgba(255,255,255,0.3)"/>
                    <path d="M26 10C26 10 14 24 12 34" stroke="rgba(255,255,255,0.3)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
            <div class="mobile-overlay-brand">
                <div class="mobile-overlay-brand-name"><?php bloginfo('name'); ?></div>
                <div class="mobile-overlay-brand-sub">Purifying Since 1969</div>
            </div>
        </a>
        <button class="mobile-overlay-close" id="mobileOverlayClose" aria-label="Close menu">✕</button>
    </div>

    <?php if ( has_nav_menu('mobile') ): ?>

    <?php wp_nav_menu([
        'theme_location' => 'mobile',
        'container'      => false,
        'items_wrap'     => '%3$s',
        'walker'         => new UCI_Mobile_Walker(),
        'depth'          => 0,
        'fallback_cb'    => false,
    ]); ?>

    <?php else: ?>

    <!-- Fallback: hardcoded mobile links (used when no menu assigned to Mobile Menu location) -->
    <a class="mobile-nav-link" href="<?php echo esc_url( home_url('/') ); ?>">Home</a>
    <a class="mobile-nav-link" href="<?php echo esc_url($nav_products_url); ?>">Products</a>
    <div class="mobile-sub-links">
        <?php foreach ( $mega_families as $mf ):
            $fam_page = get_page_by_path('products/' . $mf['slug']);
            $fam_url  = $fam_page ? get_permalink($fam_page) : home_url('/products/' . $mf['slug'] . '/');
        ?>
        <a class="mobile-sub-link" href="<?php echo esc_url($fam_url); ?>" style="border-left-color:<?php echo esc_attr($mf['color']); ?>"><?php echo $mf['icon']; ?> <?php echo esc_html($mf['label']); ?></a>
        <?php endforeach; ?>
    </div>
    <a class="mobile-nav-link" href="<?php echo esc_url($nav_apps_url); ?>">Applications</a>
    <a class="mobile-nav-link" href="<?php echo esc_url($nav_knowledge_url); ?>">Knowledge</a>
    <a class="mobile-nav-link" href="<?php echo esc_url($nav_esg_url); ?>">ESG</a>
    <div class="mobile-sub-links">
        <a class="mobile-sub-link" href="<?php echo esc_url($nav_esg_url . '#environmental'); ?>" style="border-left-color:#2E7D32">🌿 Environmental</a>
        <a class="mobile-sub-link" href="<?php echo esc_url($nav_esg_url . '#social'); ?>" style="border-left-color:#0296D8">👥 Social</a>
        <a class="mobile-sub-link" href="<?php echo esc_url($nav_esg_url . '#governance'); ?>" style="border-left-color:#7C3AED">🏛️ Governance</a>
    </div>

    <?php endif; ?>

    <div class="mobile-overlay-cta">
        <a href="<?php echo esc_url( home_url('/contact/') ); ?>" class="btn-primary">Get in touch →</a>
    </div>
</div>
