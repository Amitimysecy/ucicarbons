<!-- ── APPLICATIONS OVERVIEW (Homepage) ── -->
<section class="section" id="applications" style="background:var(--white)">
    <div class="wrap">

        <div class="g2" style="align-items:start;margin-bottom:52px">
            <div>
                <div class="sec-eyebrow">Where carbon works</div>
                <h2 class="sec-h">Nine sectors.<br><span>One source.</span></h2>
                <p class="sec-sub">From water treatment to gold recovery — UCI carbons serve every major purification sector.</p>
                <a href="<?php echo esc_url( home_url('/applications/') ); ?>" class="btn-primary" style="text-decoration:none;display:inline-block;font-size:14px">
                    Explore all applications →
                </a>
            </div>
            <div class="cert-bar" style="flex-wrap:wrap;gap:12px;align-self:center">
                <?php
                $certs = [
                    ['🏅', 'ISO 9001',  'Quality Management'],
                    ['🌿', 'ISO 14001', 'Environmental'],
                    ['☪️',  'Halal',     'Certified'],
                    ['✡️',  'Kosher',    'Certified'],
                    ['💧', 'NSF',       'Drinking Water'],
                ];
                foreach ( $certs as $c ) {
                    echo '<div class="cert-badge">';
                    echo '<div class="cert-badge-icon">' . $c[0] . '</div>';
                    echo '<div><div class="cert-badge-text">' . esc_html($c[1]) . '</div>';
                    echo '<div class="cert-badge-sub">' . esc_html($c[2]) . '</div></div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Sector grid -->
        <?php
        $hp_sectors = [
            ['slug'=>'water',     'color'=>'#0296D8','bg'=>'#E8F5FD','rule'=>'','icon'=>'💧',  'h'=>'Water Treatment',    'body'=>'Municipal, industrial, and potable water. GAC bed filtration, post-treatment polishing, emergency PAC dosing.','chips'=>'RC830·4×8C·12×40C'],
            ['slug'=>'gold',      'color'=>'#D97706','bg'=>'#FEF3C7','rule'=>'#FDE68A','icon'=>'🥇','h'=>'Gold Recovery',  'body'=>'CIL, CIP, and heap leach circuits. High K-value, fast kinetics, extreme hardness for elution cycling.','chips'=>'ACGOLD 6SZ·ACGOLD 8SFY'],
            ['slug'=>'pharma',    'color'=>'#7C3AED','bg'=>'#EDE9FE','rule'=>'#DDD6FE','icon'=>'⚗️','h'=>'Pharma &amp; API','body'=>'BP/USP/EU Pharmacopoeia grades. API decolorisation, injectables, medicinal charcoal tablets.','chips'=>'UW-22·55NS·DL Premium'],
            ['slug'=>'food',      'color'=>'#059669','bg'=>'#D1FAE5','rule'=>'#A7F3D0','icon'=>'🍶','h'=>'Food &amp; Beverage','body'=>'Spirit decolorisation, juice purification, liquid sugar. Acid-washed, food-contact certified coconut grades.','chips'=>'12×40B·12×30B·55N'],
            ['slug'=>'air',       'color'=>'#0284C7','bg'=>'#E0F2FE','rule'=>'#BAE6FD','icon'=>'💨','h'=>'Air &amp; VOC Removal','body'=>'Solvent recovery, odour control, industrial air purification. High-capacity GAC and pellet grades.','chips'=>'P-3·P-4·GX50'],
            ['slug'=>'edibleoil', 'color'=>'#B45309','bg'=>'#FEF3C7','rule'=>'#FDE68A','icon'=>'🫒','h'=>'Edible Oil Refining','body'=>'Decolorisation of vegetable, palm, soya and sunflower oils. High iodine value wood powder grades.','chips'=>'UW-22·UW-24·NC830'],
        ];
        ?>
        <div class="app-sector-grid">
        <?php foreach ( $hp_sectors as $s ) :
            $s_page = get_page_by_path( 'applications/' . $s['slug'] );
            $s_url  = $s_page ? get_permalink($s_page) : home_url('/applications/' . $s['slug'] . '/');
            $rule   = $s['rule'] ?: 'var(--rule)';
            $chips  = array_map('trim', explode('·', $s['chips']));
        ?>
            <div class="app-card" style="--app-color:<?php echo esc_attr($s['color']); ?>;--app-bg:<?php echo esc_attr($s['bg']); ?>">
                <div class="app-card-inner">
                    <div class="app-card-icon-wrap" style="background:<?php echo esc_attr($s['bg']); ?>"><?php echo $s['icon']; ?></div>
                    <div class="app-card-h"><?php echo $s['h']; ?></div>
                    <div class="app-card-body"><?php echo esc_html($s['body']); ?></div>
                    <div class="app-card-grades">
                        <?php foreach($chips as $chip): ?>
                        <span class="chip"><?php echo esc_html($chip); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="app-card-footer" style="border-top-color:<?php echo esc_attr($rule); ?>">
                    <a href="<?php echo esc_url($s_url); ?>" class="app-card-cta" style="color:<?php echo esc_attr($s['color']); ?>;text-decoration:none">Explore sector</a>
                    <span class="app-card-arrow" style="color:<?php echo esc_attr($s['color']); ?>">→</span>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

    </div>
</section>
