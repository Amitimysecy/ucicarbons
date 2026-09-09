<?php
/**
 * Template Name: Application Sector
 * Individual application sector page for SEO.
 */
get_header();

$id     = get_the_ID();
$color  = get_post_meta( $id, '_app_color',  true ) ?: '#0296D8';
$bg     = get_post_meta( $id, '_app_bg',     true ) ?: '#E8F5FD';
$icon   = get_post_meta( $id, '_app_icon',   true ) ?: '⚙️';
$body   = get_post_meta( $id, '_app_body',   true );
$chips  = json_decode( get_post_meta( $id, '_app_chips',  true ), true ) ?: [];
$steps  = json_decode( get_post_meta( $id, '_app_steps',  true ), true ) ?: [];
$specs  = json_decode( get_post_meta( $id, '_app_specs',  true ), true ) ?: [];
$grades = json_decode( get_post_meta( $id, '_app_grades', true ), true ) ?: [];

$apps_url    = get_permalink( get_page_by_path('applications') ) ?: home_url('/applications/');
$contact_url = get_permalink( get_page_by_path('contact') )      ?: home_url('/contact/');
$tds_url     = get_permalink( get_page_by_path('request-tds') )  ?: home_url('/request-tds/');
$products_url= get_permalink( get_page_by_path('products') )     ?: home_url('/products/');

/* Colour helpers */
$hex  = ltrim( $color, '#' );
$r    = hexdec( substr($hex,0,2) );
$g    = hexdec( substr($hex,2,2) );
$b    = hexdec( substr($hex,4,2) );
$rgba = "rgba({$r},{$g},{$b},0.12)";
$rgba_mid = "rgba({$r},{$g},{$b},0.08)";
?>

<main id="primary" class="site-main">

<!-- ── HERO ── -->
<section style="padding:72px 0 60px;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:600px;height:600px;border-radius:50%;background:<?php echo esc_attr($color); ?>;opacity:.1;top:-180px;right:-120px;pointer-events:none"></div>
    <div style="position:absolute;inset:0;opacity:.02;background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);background-size:48px 48px;pointer-events:none"></div>

    <div class="wrap" style="position:relative;z-index:2">

        <a href="<?php echo esc_url($apps_url); ?>" style="display:inline-flex;align-items:center;gap:6px;font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.4);text-transform:uppercase;text-decoration:none;margin-bottom:24px">← ALL SECTORS</a>

        <div style="display:flex;align-items:flex-start;gap:24px;flex-wrap:wrap">
            <div style="width:72px;height:72px;border-radius:20px;background:<?php echo esc_attr($rgba); ?>;border:1px solid <?php echo esc_attr($color); ?>33;display:flex;align-items:center;justify-content:center;font-size:34px;flex-shrink:0">
                <?php echo $icon; ?>
            </div>
            <div style="flex:1;min-width:280px">
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:<?php echo esc_attr($color); ?>;text-transform:uppercase;margin-bottom:10px">Application Sector</div>
                <h1 style="font-size:clamp(32px,4vw,56px);font-weight:800;letter-spacing:-2px;color:#fff;line-height:1;margin-bottom:16px"><?php the_title(); ?></h1>
                <?php if ( $body ) : ?>
                <p style="font-size:18px;color:rgba(255,255,255,0.55);max-width:620px;line-height:1.7;margin-bottom:24px"><?php echo esc_html($body); ?></p>
                <?php endif; ?>
                <?php if ( $chips ) : ?>
                <div style="display:flex;flex-wrap:wrap;gap:8px">
                    <?php foreach ( $chips as $chip ) : ?>
                    <span style="font-family:var(--mono);font-size:11px;font-weight:700;color:<?php echo esc_attr($color); ?>;background:<?php echo esc_attr($rgba); ?>;border:1px solid <?php echo esc_attr($color); ?>44;border-radius:6px;padding:4px 12px"><?php echo esc_html($chip); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ── PROCESS STEPS ── -->
<section class="section" style="background:var(--bg)">
    <div class="wrap">

        <div style="display:grid;grid-template-columns:1fr 340px;gap:64px;align-items:start">

            <!-- Left: how it works + steps -->
            <div>
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:<?php echo esc_attr($color); ?>;text-transform:uppercase;margin-bottom:8px">How It Works</div>
                <h2 style="font-size:clamp(24px,2.8vw,36px);font-weight:800;letter-spacing:-1px;color:var(--dark);margin-bottom:14px">The process, step by step</h2>
                <p style="font-size:16px;color:var(--soft);line-height:1.7;max-width:560px;margin-bottom:40px"><?php echo esc_html($body); ?></p>

                <?php if ( $steps ) : ?>
                <div style="display:flex;flex-direction:column;gap:24px">
                    <?php foreach ( $steps as $step ) : ?>
                    <div style="display:flex;gap:20px;align-items:flex-start">
                        <div style="width:44px;height:44px;border-radius:12px;background:<?php echo esc_attr($color); ?>;color:#fff;font-family:var(--mono);font-size:13px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;letter-spacing:.5px">
                            <?php echo esc_html($step['n']); ?>
                        </div>
                        <div>
                            <div style="font-size:16px;font-weight:700;color:var(--dark);margin-bottom:4px"><?php echo esc_html($step['label']); ?></div>
                            <div style="font-size:14px;color:var(--soft);line-height:1.65"><?php echo esc_html($step['desc']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right: specs + grades -->
            <div style="position:sticky;top:100px;display:flex;flex-direction:column;gap:20px">

                <!-- Key specs -->
                <?php if ( $specs ) : ?>
                <div style="background:var(--white);border:1px solid var(--rule);border-radius:20px;padding:28px;overflow:hidden">
                    <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:2px;color:var(--soft);text-transform:uppercase;margin-bottom:18px">Key Specifications</div>
                    <div style="display:flex;flex-direction:column;gap:14px">
                        <?php foreach ( $specs as $spec ) : ?>
                        <div style="border-left:3px solid <?php echo esc_attr($color); ?>;padding-left:14px">
                            <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1px;color:var(--soft);text-transform:uppercase;margin-bottom:3px"><?php echo esc_html($spec['name']); ?></div>
                            <div style="font-size:15px;font-weight:800;color:<?php echo esc_attr($color); ?>;letter-spacing:-.3px"><?php echo esc_html($spec['value']); ?></div>
                            <div style="font-size:12px;color:var(--soft);margin-top:3px;line-height:1.5"><?php echo esc_html($spec['desc']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- CTA card -->
                <div style="background:linear-gradient(135deg,var(--dark),#0e4d6a);border-radius:20px;padding:24px">
                    <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:6px">Need a TDS or sample?</div>
                    <p style="font-size:13px;color:rgba(255,255,255,0.5);line-height:1.6;margin-bottom:16px">We respond within 24 hours.</p>
                    <a href="<?php echo esc_url( add_query_arg('sector', get_post_field('post_name',$id), $tds_url) ); ?>" class="btn-primary" style="display:block;text-align:center;text-decoration:none;font-size:13px;padding:11px 16px;margin-bottom:8px">Request TDS →</a>
                    <a href="<?php echo esc_url($contact_url); ?>" style="display:block;text-align:center;text-decoration:none;font-size:13px;padding:11px 16px;border:1px solid rgba(255,255,255,0.2);border-radius:10px;color:rgba(255,255,255,0.7)">Contact team →</a>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- ── RECOMMENDED GRADES ── -->
<?php if ( $grades ) : ?>
<section style="background:var(--white);padding:72px 0">
    <div class="wrap">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:<?php echo esc_attr($color); ?>;text-transform:uppercase;margin-bottom:8px">Grade Selection</div>
        <h2 style="font-size:clamp(24px,2.8vw,36px);font-weight:800;letter-spacing:-1px;color:var(--dark);margin-bottom:10px">Recommended grades for <?php the_title(); ?></h2>
        <p style="font-size:16px;color:var(--soft);max-width:560px;margin-bottom:40px">These grades are specifically optimised for this application. Full technical data sheets available on request.</p>

        <style>
        .grade-card{display:block;text-decoration:none;background:var(--bg);border:1px solid var(--rule);border-radius:16px;padding:24px;position:relative;overflow:hidden;transition:transform .15s,box-shadow .15s,border-color .15s}
        .grade-card:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.08);border-color:var(--grade-color)}
        .grade-card:hover .grade-arrow{opacity:1;transform:translateX(0)}
        .grade-arrow{opacity:0;transform:translateX(-4px);transition:opacity .15s,transform .15s;font-size:14px;font-weight:700}
        </style>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;margin-bottom:36px">
            <?php foreach ( $grades as $g ) :
                $grade_url = esc_url( $products_url . '?grade=' . urlencode( $g['code'] ) );
            ?>
            <a href="<?php echo $grade_url; ?>" class="grade-card" style="--grade-color:<?php echo esc_attr($color); ?>">
                <div style="position:absolute;top:0;left:0;width:4px;height:100%;background:<?php echo esc_attr($color); ?>;border-radius:4px 0 0 4px"></div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                    <div style="font-family:var(--mono);font-size:13px;font-weight:800;color:var(--dark);letter-spacing:-.2px"><?php echo esc_html($g['code']); ?></div>
                    <span class="grade-arrow" style="color:<?php echo esc_attr($color); ?>">→</span>
                </div>
                <span style="font-family:var(--mono);font-size:10px;font-weight:700;color:<?php echo esc_attr($color); ?>;background:<?php echo esc_attr($rgba); ?>;border-radius:4px;padding:2px 8px;letter-spacing:.5px"><?php echo esc_html($g['base']); ?></span>
                <p style="font-size:14px;color:var(--soft);line-height:1.6;margin-top:10px;margin-bottom:0"><?php echo esc_html($g['desc']); ?></p>
            </a>
            <?php endforeach; ?>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <?php
            $first_grade = ! empty( $grades[0]['code'] ) ? $grades[0]['code'] : '';
            ?>
            <a href="<?php echo esc_url( add_query_arg(['grade'=>$first_grade,'sector'=>get_post_field('post_name',$id)], $tds_url) ); ?>" class="btn-primary" style="text-decoration:none">Request TDS for these grades →</a>
            <a href="<?php echo esc_url($products_url); ?>" class="btn-outline" style="text-decoration:none">View full product catalogue →</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ── OTHER SECTORS ── -->
<section style="background:var(--bg);padding:64px 0">
    <div class="wrap">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:var(--soft);text-transform:uppercase;margin-bottom:8px">Other Sectors</div>
        <h2 style="font-size:clamp(22px,2.5vw,32px);font-weight:800;letter-spacing:-1px;color:var(--dark);margin-bottom:28px">Explore all nine application sectors</h2>
        <?php
        /* Fetch sibling pages */
        $siblings = get_pages([
            'parent'      => wp_get_post_parent_id( $id ),
            'exclude'     => [ $id ],
            'post_status' => 'publish',
            'number'      => 8,
        ]);
        if ( $siblings ) : ?>
        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:28px">
            <?php foreach ( $siblings as $sib ) :
                $sib_icon  = get_post_meta( $sib->ID, '_app_icon',  true ) ?: '⚙️';
                $sib_color = get_post_meta( $sib->ID, '_app_color', true ) ?: '#0296D8';
            ?>
            <a href="<?php echo esc_url( get_permalink($sib->ID) ); ?>" style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:var(--white);border:1px solid var(--rule);border-radius:10px;text-decoration:none;font-size:14px;font-weight:600;color:var(--dark);transition:all .15s">
                <span><?php echo $sib_icon; ?></span>
                <?php echo esc_html($sib->post_title); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <a href="<?php echo esc_url($apps_url); ?>" style="font-size:14px;color:var(--blue);font-weight:600;text-decoration:none">← Back to all applications</a>
    </div>
</section>

<!-- ── AI STRIP ── -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Not sure which grade fits your process?</div>
                <div class="pai-sub">Describe your application — our AI will recommend the right carbon.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-sector" class="pai-input" placeholder="e.g. 500 m³/day potable water, chlorine removal..." onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-sector')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-sector')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>

<?php get_footer(); ?>
