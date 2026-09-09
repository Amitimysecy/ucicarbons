<!-- ── KNOWLEDGE BANK (Homepage teaser) ── -->
<section class="section" id="knowledge" style="background:var(--bg)">
    <div class="wrap">

        <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:20px;margin-bottom:40px">
            <div>
                <div class="sec-eyebrow">Knowledge Bank</div>
                <h2 class="sec-h" style="margin-bottom:8px">Technical resources &amp; expertise.</h2>
                <p class="sec-sub" style="margin-bottom:0">Deep-dives on activated carbon science, industry applications, and specifications.</p>
            </div>
            <a href="<?php echo esc_url( home_url('/knowledge/') ); ?>" class="btn-outline" style="color:var(--blue);border-color:var(--blue);text-decoration:none;white-space:nowrap">
                View all articles →
            </a>
        </div>

        <?php
        $kb_teasers = [
            [
                'slug'    => 'what-is-ac',
                'emoji'   => '🔬',
                'cat'     => 'Fundamentals',
                'cat_bg'  => 'rgba(2,150,216,0.85)',
                'time'    => '6 min read',
                'level'   => 'Beginner',
                'title'   => 'How Activated Carbon Works — The Science of Adsorption',
                'excerpt' => 'Pore networks, surface area, and why 1 gram of activated carbon can hold a football pitch of surface area.',
            ],
            [
                'slug'    => 'wood-coconut-bamboo',
                'emoji'   => '🌲',
                'cat'     => 'Materials',
                'cat_bg'  => 'rgba(2,150,216,0.85)',
                'time'    => '6 min read',
                'level'   => 'Beginner',
                'title'   => 'Wood vs Coconut Shell Carbon — Which Should You Choose?',
                'excerpt' => 'Macroporous vs microporous. PAC vs GAC. A practical guide to choosing the right base material for your application.',
            ],
            [
                'slug'    => 'gac-vs-pac',
                'emoji'   => '📊',
                'cat'     => 'Specifications',
                'cat_bg'  => 'rgba(245,158,11,0.85)',
                'time'    => '5 min read',
                'level'   => 'Intermediate',
                'title'   => 'Reading a Carbon TDS — Iodine Value, CTC, Hardness Explained',
                'excerpt' => 'What every specification parameter means and why it matters for your process. A buyer\'s guide to technical data sheets.',
            ],
        ];
        foreach ( $kb_teasers as $t ) :
            $art_page = get_page_by_path( 'knowledge/' . $t['slug'] );
            $art_url  = $art_page ? get_permalink($art_page) : home_url('/knowledge/' . $t['slug'] . '/');
        ?>
        <a class="article-card" href="<?php echo esc_url($art_url); ?>" style="text-decoration:none">
            <div class="article-img">
                <div class="article-img-placeholder"><?php echo $t['emoji']; ?></div>
                <div class="article-cat" style="background:<?php echo esc_attr($t['cat_bg']); ?>;color:#fff"><?php echo esc_html($t['cat']); ?></div>
            </div>
            <div class="article-body">
                <div class="article-meta">
                    <span><?php echo esc_html($t['time']); ?></span>
                    <span><?php echo esc_html($t['level']); ?></span>
                </div>
                <div class="article-h"><?php echo esc_html($t['title']); ?></div>
                <div class="article-excerpt"><?php echo esc_html($t['excerpt']); ?></div>
                <div class="article-read">Read article →</div>
            </div>
        </a>
        <?php endforeach; ?>

        </div>

    </div>
</section>
