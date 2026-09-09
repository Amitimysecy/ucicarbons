<?php
/**
 * Template Name: Product Family
 * Family landing page — Wood-Based Carbon, Coconut Shell Carbon, Specialty Grades.
 * Reads _fam_* meta fields set by uci-products-importer.
 */
get_header();

$id         = get_the_ID();
$fam_slug   = get_post_meta($id, '_fam_slug',   true) ?: 'wood';
$color      = get_post_meta($id, '_fam_color',  true) ?: '#0296D8';
$bg         = get_post_meta($id, '_fam_bg',     true) ?: '#EFF9FF';
$icon       = get_post_meta($id, '_fam_icon',   true) ?: '⚗️';
$tagline    = get_post_meta($id, '_fam_tagline',true) ?: '';
$body       = get_post_meta($id, '_fam_body',   true) ?: '';
$props      = json_decode(get_post_meta($id, '_fam_properties', true), true) ?: [];
$grade_slugs= json_decode(get_post_meta($id, '_fam_grades',     true), true) ?: [];

/* Colour helpers */
$hex = ltrim($color,'#');
$r = hexdec(substr($hex,0,2)); $g2 = hexdec(substr($hex,2,2)); $b = hexdec(substr($hex,4,2));
$rgba12 = "rgba($r,$g2,$b,0.12)";
$rgba06 = "rgba($r,$g2,$b,0.06)";

/* Fetch grade pages */
$grades_data = [];
foreach ( $grade_slugs as $gs ) {
    $gp = get_page_by_path('products/' . $gs);
    if ( !$gp ) continue;
    $gid = $gp->ID;
    $grades_data[] = [
        'id'       => $gid,
        'url'      => get_permalink($gp),
        'code'     => get_post_meta($gid,'_grade_code',      true) ?: $gp->post_title,
        'form'     => get_post_meta($gid,'_grade_form',      true) ?: '',
        'mesh'     => get_post_meta($gid,'_grade_mesh',      true) ?: '',
        'iodine'   => get_post_meta($gid,'_grade_iodine',    true) ?: '',
        'ctc'      => get_post_meta($gid,'_grade_ctc',       true) ?: '',
        'tagline'  => get_post_meta($gid,'_grade_tagline',   true) ?: '',
        'apps'     => json_decode(get_post_meta($gid,'_grade_apps',true), true) ?: [],
        'certs'    => json_decode(get_post_meta($gid,'_grade_certs',true), true) ?: [],
        'highlight'=> get_post_meta($gid,'_grade_highlight', true) ?: '',
    ];
}

/* Breadcrumb parent */
$products_url = get_permalink(get_page_by_path('products')) ?: home_url('/products/');
$tds_url      = get_permalink(get_page_by_path('request-tds')) ?: home_url('/request-tds/');
?>

<main id="primary" class="site-main">

<!-- ══ HERO ══ -->
<section style="padding:100px 0 64px;background:var(--dark);position:relative;overflow:hidden">
    <div style="position:absolute;width:600px;height:600px;border-radius:50%;background:<?php echo esc_attr($color); ?>;opacity:.08;top:-180px;right:-100px;pointer-events:none"></div>
    <div style="position:absolute;width:300px;height:300px;border-radius:50%;background:<?php echo esc_attr($color); ?>;opacity:.05;bottom:-80px;left:-60px;pointer-events:none"></div>

    <div class="wrap" style="position:relative;z-index:2">

        <!-- Breadcrumb -->
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.35);text-transform:uppercase;margin-bottom:20px">
            <a href="<?php echo esc_url($products_url); ?>" style="color:rgba(255,255,255,0.35);text-decoration:none;transition:color .15s" onmouseover="this.style.color='<?php echo esc_attr($color); ?>'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">Products</a>
            <span style="margin:0 8px">›</span>
            <span style="color:<?php echo esc_attr($color); ?>"><?php the_title(); ?></span>
        </div>

        <div style="max-width:720px">
            <div style="display:inline-flex;align-items:center;gap:10px;background:<?php echo esc_attr($rgba12); ?>;border:1px solid <?php echo esc_attr($color); ?>44;border-radius:100px;padding:8px 18px;margin-bottom:22px">
                <span style="font-size:16px"><?php echo $icon; ?></span>
                <span style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2px;color:<?php echo esc_attr($color); ?>;text-transform:uppercase">Product Family</span>
            </div>

            <h1 style="font-size:clamp(40px,5vw,68px);font-weight:800;letter-spacing:-2.5px;color:#fff;line-height:1;margin-bottom:18px">
                <?php the_title(); ?>
            </h1>

            <?php if ($tagline): ?>
            <p style="font-size:20px;color:<?php echo esc_attr($color); ?>;font-weight:600;margin-bottom:16px;letter-spacing:-0.3px"><?php echo esc_html($tagline); ?></p>
            <?php endif; ?>

            <?php if ($body): ?>
            <p style="font-size:17px;color:rgba(255,255,255,0.5);line-height:1.8;max-width:600px;margin-bottom:32px"><?php echo esc_html($body); ?></p>
            <?php endif; ?>

            <div style="display:flex;flex-wrap:wrap;gap:12px">
                <a href="<?php echo esc_url($tds_url); ?>" class="btn-primary" style="background:<?php echo esc_attr($color); ?>;border-color:<?php echo esc_attr($color); ?>">Request TDS →</a>
                <a href="<?php echo esc_url($products_url); ?>" style="display:inline-flex;align-items:center;font-size:14px;font-weight:700;color:rgba(255,255,255,0.4);text-decoration:none;padding:12px 0;transition:color .15s" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">← All products</a>
            </div>
        </div>
    </div>
</section>

<!-- ══ MATERIAL PROPERTIES ══ -->
<?php if ( !empty($props) ): ?>
<section style="background:<?php echo esc_attr($bg); ?>;padding:56px 0">
    <div class="wrap">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:10px;text-align:center">Raw material properties</div>
        <h2 style="font-size:clamp(26px,2.5vw,36px);font-weight:800;letter-spacing:-1px;color:var(--dark);text-align:center;margin-bottom:40px">Why <?php the_title(); ?>?</h2>
        <div class="g3" style="gap:24px">
        <?php foreach ($props as $prop): ?>
            <div style="background:#fff;border:1px solid var(--rule);border-radius:16px;padding:28px;border-top:3px solid <?php echo esc_attr($color); ?>">
                <div style="font-size:26px;margin-bottom:14px"><?php echo esc_html($prop['icon']); ?></div>
                <div style="font-size:15px;font-weight:800;color:var(--dark);margin-bottom:8px"><?php echo esc_html($prop['title']); ?></div>
                <p style="font-size:14px;color:var(--soft);line-height:1.7;margin:0"><?php echo esc_html($prop['desc']); ?></p>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ══ GRADE CARDS ══ -->
<section class="section" style="background:#fff">
    <div class="wrap">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:40px">
            <div>
                <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:8px">Available grades</div>
                <h2 style="font-size:clamp(26px,2.5vw,36px);font-weight:800;letter-spacing:-1px;color:var(--dark);margin:0"><?php echo count($grades_data); ?> grades in this family</h2>
            </div>
            <a href="<?php echo esc_url($tds_url); ?>" style="font-size:13px;font-weight:700;color:<?php echo esc_attr($color); ?>;text-decoration:none;font-family:var(--mono);letter-spacing:0.5px">Request any TDS →</a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px">
        <?php foreach ($grades_data as $gr): ?>
            <a href="<?php echo esc_url($gr['url']); ?>" style="display:block;background:var(--bg);border:1px solid var(--rule);border-radius:16px;padding:24px;text-decoration:none;position:relative;overflow:hidden;transition:transform .15s,box-shadow .15s,border-color .15s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.08)';this.style.borderColor='<?php echo esc_attr($color); ?>'" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--rule)'">

                <!-- Colour accent bar -->
                <div style="position:absolute;top:0;left:0;width:100%;height:3px;background:<?php echo esc_attr($color); ?>"></div>

                <div style="display:flex;align-items:start;justify-content:space-between;margin-bottom:10px">
                    <div style="font-family:var(--mono);font-size:16px;font-weight:800;color:var(--dark)"><?php echo esc_html($gr['code']); ?></div>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end">
                        <?php if ($gr['form']): ?>
                        <span style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1px;background:<?php echo esc_attr($rgba12); ?>;color:<?php echo esc_attr($color); ?>;padding:3px 9px;border-radius:4px"><?php echo esc_html($gr['form']); ?></span>
                        <?php endif; ?>
                        <?php if ($gr['mesh']): ?>
                        <span style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1px;background:var(--bg);border:1px solid var(--rule);color:var(--soft);padding:3px 9px;border-radius:4px"><?php echo esc_html($gr['mesh']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($gr['tagline']): ?>
                <p style="font-size:13px;color:var(--soft);line-height:1.6;margin:0 0 14px"><?php echo esc_html($gr['tagline']); ?></p>
                <?php endif; ?>

                <!-- Key specs inline -->
                <div style="display:flex;gap:16px;flex-wrap:wrap;padding:12px 0;border-top:1px solid var(--rule);border-bottom:1px solid var(--rule);margin-bottom:14px">
                    <?php if ($gr['iodine']): ?>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:var(--soft);letter-spacing:1px;font-family:var(--mono);text-transform:uppercase">Iodine</div>
                        <div style="font-size:13px;font-weight:800;color:var(--dark)"><?php echo esc_html($gr['iodine']); ?> mg/g</div>
                    </div>
                    <?php endif; ?>
                    <?php if ($gr['ctc']): ?>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:var(--soft);letter-spacing:1px;font-family:var(--mono);text-transform:uppercase">CTC</div>
                        <div style="font-size:13px;font-weight:800;color:var(--dark)"><?php echo esc_html($gr['ctc']); ?>%</div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($gr['apps'])): ?>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:var(--soft);letter-spacing:1px;font-family:var(--mono);text-transform:uppercase">Primary use</div>
                        <div style="font-size:13px;font-weight:800;color:var(--dark)"><?php echo esc_html($gr['apps'][0]); ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div style="display:flex;gap:4px;flex-wrap:wrap">
                        <?php foreach(array_slice($gr['certs'],0,3) as $cert): ?>
                        <span style="font-size:10px;font-weight:700;color:var(--soft);background:#fff;border:1px solid var(--rule);padding:2px 7px;border-radius:3px;font-family:var(--mono)"><?php echo esc_html($cert); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <span style="font-size:18px;font-weight:700;color:<?php echo esc_attr($color); ?>">→</span>
                </div>
            </a>
        <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ GRADE COMPARISON TABLE ══ -->
<?php if ( count($grades_data) > 1 ): ?>
<section style="background:var(--bg);padding:64px 0">
    <div class="wrap">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:8px;text-align:center">Quick compare</div>
        <h2 style="font-size:clamp(24px,2.5vw,34px);font-weight:800;letter-spacing:-1px;color:var(--dark);text-align:center;margin-bottom:32px">Grade specifications at a glance</h2>
        <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.05)">
            <thead>
                <tr style="background:var(--dark)">
                    <th style="padding:14px 20px;text-align:left;font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.5);text-transform:uppercase">Grade</th>
                    <th style="padding:14px 16px;text-align:left;font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.5);text-transform:uppercase">Form</th>
                    <th style="padding:14px 16px;text-align:left;font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.5);text-transform:uppercase">Iodine (mg/g)</th>
                    <th style="padding:14px 16px;text-align:left;font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.5);text-transform:uppercase">CTC (%)</th>
                    <th style="padding:14px 16px;text-align:left;font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.5);text-transform:uppercase">Primary Application</th>
                    <th style="padding:14px 16px;text-align:center;font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,0.5);text-transform:uppercase">TDS</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($grades_data as $i => $gr): ?>
                <tr style="border-bottom:1px solid var(--rule);<?php echo ($i % 2 === 0) ? '' : 'background:var(--bg)'; ?>">
                    <td style="padding:14px 20px">
                        <a href="<?php echo esc_url($gr['url']); ?>" style="font-family:var(--mono);font-size:14px;font-weight:800;color:<?php echo esc_attr($color); ?>;text-decoration:none"><?php echo esc_html($gr['code']); ?></a>
                    </td>
                    <td style="padding:14px 16px;font-size:13px;color:var(--soft)"><?php echo esc_html($gr['form']); ?></td>
                    <td style="padding:14px 16px;font-size:13px;font-weight:700;color:var(--dark)"><?php echo esc_html($gr['iodine']); ?></td>
                    <td style="padding:14px 16px;font-size:13px;font-weight:700;color:var(--dark)"><?php echo esc_html($gr['ctc']); ?></td>
                    <td style="padding:14px 16px;font-size:13px;color:var(--soft)"><?php echo esc_html(!empty($gr['apps']) ? $gr['apps'][0] : '—'); ?></td>
                    <td style="padding:14px 16px;text-align:center">
                        <a href="<?php echo esc_url(add_query_arg('grade', $gr['code'], $tds_url)); ?>" style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:1px;color:<?php echo esc_attr($color); ?>;text-decoration:none;border:1px solid <?php echo esc_attr($color); ?>44;padding:4px 10px;border-radius:4px">Request</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ══ OTHER FAMILIES ══ -->
<section class="section" style="background:#fff">
    <div class="wrap">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--soft);text-transform:uppercase;margin-bottom:8px">Also in our range</div>
        <h2 style="font-size:clamp(22px,2vw,30px);font-weight:800;letter-spacing:-0.5px;color:var(--dark);margin-bottom:28px">Other product families</h2>
        <div class="g3" style="gap:20px">
        <?php
        $other_families = [
            ['wood-pac',              '🌲', 'Wood Powder (PAC)',       '#8B5E3C', '#FDF4EE', 'Pharmaceutical, food & industrial decolourisation — BP/USP grades'],
            ['wood-gac',              '🪵', 'Wood Granular (GAC)',     '#2E7D32', '#F1F8F2', 'Water treatment, air purification, and VOC control'],
            ['coconut-carbon',        '🥥', 'Coconut Shell Carbon',    '#0296D8', '#EFF9FF', 'Gold recovery, potable water, beverage — ultra-hard, microporous'],
            ['pellets',               '⚙️', 'Pellets — Gas Phase',     '#0F6B8A', '#EBF6FB', 'Cylindrical extruded 3 mm / 4 mm / 6 mm for low-pressure-drop beds'],
            ['specialty-impregnated', '🧬', 'Specialty & Impregnated', '#7C3AED', '#F5F3FF', 'CBRN, silver-impregnated, and ultra-purity pharmaceutical grades'],
        ];
        foreach ( $other_families as $fam ):
            if ( $fam[0] === $fam_slug ) continue; // skip current
            $fam_page = get_page_by_path('products/' . $fam[0]);
            $fam_url  = $fam_page ? get_permalink($fam_page) : home_url('/products/' . $fam[0] . '/');
        ?>
        <a href="<?php echo esc_url($fam_url); ?>" style="display:block;background:<?php echo esc_attr($fam[4]); ?>;border:1px solid <?php echo esc_attr($fam[3]); ?>22;border-radius:16px;padding:28px;text-decoration:none;transition:transform .15s,box-shadow .15s" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.07)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="font-size:24px;margin-bottom:12px"><?php echo $fam[1]; ?></div>
            <div style="font-size:15px;font-weight:800;color:var(--dark);margin-bottom:6px"><?php echo esc_html($fam[2]); ?></div>
            <div style="font-size:13px;color:var(--soft);line-height:1.6"><?php echo esc_html($fam[5]); ?></div>
        </a>
        <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══ AI STRIP ══ -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Not sure which grade fits your process?</div>
                <div class="pai-sub">Describe your application and our AI will recommend the right grade from this family.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-fam" class="pai-input" placeholder="e.g. Decolourising pharmaceutical API at pH 7..." onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-fam')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-fam')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

</main>
<?php get_footer(); ?>
