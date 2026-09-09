<footer class="site-footer">

    <div class="container footer-top">

        <!-- Column 1 -->
        <div class="footer-col footer-company">

            <a href="<?php echo esc_url( home_url('/') ); ?>" class="nav-logo" style="border-right:none;padding:0;margin-bottom:8px">
                <div class="nav-logo-mark" style="animation:none">
                    <svg viewBox="0 0 52 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="ftDropGrad" x1="50%" y1="0%" x2="50%" y2="100%">
                                <stop offset="0%" stop-color="#4DD8FF"/>
                                <stop offset="40%" stop-color="#0296D8"/>
                                <stop offset="100%" stop-color="#024A80"/>
                            </linearGradient>
                        </defs>
                        <path d="M26 4C26 4 5 26 5 40a21 21 0 0042 0C47 26 26 4 26 4z" fill="url(#ftDropGrad)"/>
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

            <p>
                Purifying Since 1969. Three generations of activated carbon —
                from our forests to your filter.
            </p>

            <div class="footer-contact">
                <p>Bhagowal, Hoshiarpur, Punjab, India</p>
                <p>
                    <a href="mailto:kartik.gupta@ucicarbons.com">
                        kartik.gupta@ucicarbons.com
                    </a>
                </p>
                <p>
                    <a href="tel:+919501005395">
                        +91 9501005395
                    </a>
                </p>
            </div>

        </div>

        <!-- Column 2: Products -->
        <div class="footer-col">
            <h4>Products</h4>
            <?php
            /* Always use hardcoded links so they work before WP menus are configured */
            $products_url_ft = get_permalink(get_page_by_path('products')) ?: home_url('/products/');
            $footer_products = [
                ['Wood Powder (PAC)',       get_permalink(get_page_by_path('products/wood-pac'))              ?: home_url('/products/wood-pac/')],
                ['Wood Granular (GAC)',     get_permalink(get_page_by_path('products/wood-gac'))              ?: home_url('/products/wood-gac/')],
                ['Coconut Shell Carbon',    get_permalink(get_page_by_path('products/coconut-carbon'))        ?: home_url('/products/coconut-carbon/')],
                ['Pellets — Gas Phase',     get_permalink(get_page_by_path('products/pellets'))               ?: home_url('/products/pellets/')],
                ['Specialty & Impregnated', get_permalink(get_page_by_path('products/specialty-impregnated')) ?: home_url('/products/specialty-impregnated/')],
                ['All Grades',              $products_url_ft],
            ];
            echo '<ul class="footer-menu">';
            foreach ( $footer_products as $item ) {
                echo '<li><a href="' . esc_url($item[1]) . '">' . esc_html($item[0]) . '</a></li>';
            }
            echo '</ul>';
            ?>
        </div>

        <!-- Column 3: Applications -->
        <div class="footer-col">
            <h4>Applications</h4>
            <?php
            $has_apps_menu = has_nav_menu('footer_applications') && wp_nav_menu(['theme_location'=>'footer_applications','container'=>false,'menu_class'=>'footer-menu','echo'=>false]);
            if ( ! $has_apps_menu ) :
                $footer_apps = [
                    ['Water Treatment', 'applications/water'],
                    ['Gold Recovery',   'applications/gold'],
                    ['Pharma & API',    'applications/pharma'],
                    ['Food & Beverage', 'applications/food'],
                    ['Air & VOC',       'applications/air'],
                    ['Edible Oil',      'applications/edibleoil'],
                ];
                echo '<ul class="footer-menu">';
                foreach ( $footer_apps as $item ) {
                    $pg  = get_page_by_path($item[1]);
                    $url = $pg ? get_permalink($pg) : home_url('/' . $item[1] . '/');
                    echo '<li><a href="' . esc_url($url) . '">' . esc_html($item[0]) . '</a></li>';
                }
                echo '</ul>';
            else :
                wp_nav_menu(['theme_location'=>'footer_applications','container'=>false,'menu_class'=>'footer-menu']);
            endif;
            ?>
        </div>

        <!-- Column 4: Company -->
        <div class="footer-col">
            <h4>Company</h4>
            <?php
            $has_company_menu = has_nav_menu('footer_company') && wp_nav_menu(['theme_location'=>'footer_company','container'=>false,'menu_class'=>'footer-menu','echo'=>false]);
            if ( ! $has_company_menu ) :
                $footer_company = [
                    ['Knowledge Bank',   'knowledge'],
                    ['ESG',              'esg'],
                    ['Contact Us',       'contact'],
                    ['Request TDS',      'request-tds'],
                    ['Request Brochure', 'request-brochure'],
                ];
                echo '<ul class="footer-menu">';
                foreach ( $footer_company as $item ) {
                    $pg  = get_page_by_path($item[1]);
                    $url = $pg ? get_permalink($pg) : home_url('/' . $item[1] . '/');
                    echo '<li><a href="' . esc_url($url) . '">' . esc_html($item[0]) . '</a></li>';
                }
                echo '</ul>';
            else :
                wp_nav_menu(['theme_location'=>'footer_company','container'=>false,'menu_class'=>'footer-menu']);
            endif;
            ?>
        </div>

    </div>

    <div class="footer-bottom">

        <div class="container footer-bottom-inner">

            <div class="copyright">
                © <?php echo date('Y'); ?>
                Rajindra Carbons · UCT Carbons Group · Hoshiarpur, Punjab, India
            </div>

            <div class="footer-badges">

                <span>ISO 9001</span>
                <span>ISO 14001</span>
                <span>HALAL</span>
                <span>KOSHER</span>
                <span>NSF</span>

            </div>

        </div>

    </div>

</footer>

<!-- ── VIDEO LIGHTBOX ── -->
<div class="vid-lightbox" id="vid-lightbox" onclick="closeVideoLightbox(event)">
    <div class="vid-lightbox-inner">
        <button class="vid-lightbox-close" onclick="closeVideoLightbox()" aria-label="Close video">✕</button>
        <video id="vid-lightbox-player" controls playsinline>
            <source id="vid-lightbox-src" src="" type="video/mp4">
        </video>
    </div>
</div>

<!-- ── STICKY BAR ── -->
<div class="sticky-bar" id="sticky-bar">
    <div class="sticky-bar-text"><strong>Need a TDS or sample?</strong> We respond within 24 hours.</div>
    <?php
    $sb_contact_url = get_permalink( get_page_by_path('contact') ) ?: home_url('/contact/');
    $sb_tds_url     = get_permalink( get_page_by_path('request-tds') ) ?: home_url('/request-tds/');
    ?>
    <div class="sticky-bar-actions">
        <a class="sb-primary" href="<?php echo esc_url( $sb_tds_url ); ?>">Request TDS</a>
        <a class="sb-secondary" href="<?php echo esc_url( $sb_contact_url ); ?>">Contact Us</a>
    </div>
    <button class="sticky-bar-close" onclick="closeStickyBar()" title="Close">×</button>
</div>

<?php wp_footer(); ?>
</body>
</html>