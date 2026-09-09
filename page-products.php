<?php
/**
 * Template Name: Products
 * Products catalogue page — grade families, filter bar, grade cards.
 */
get_header();

/**
 * Helper: render a grade card
 * $g = [ $code, $base, $form, $apps, $desc, $app_labels, $grade_slug, $family_slug ]
 */
function uci_grade_card( $g ) {
    list( $code, $base, $form, $apps, $desc, $app_labels, $grade_slug, $family_slug ) = $g;

    // Grade page URL
    $grade_page = get_page_by_path( 'products/' . $family_slug . '/' . $grade_slug );
    $grade_url  = $grade_page ? get_permalink( $grade_page ) : home_url( '/products/' . $family_slug . '/' . $grade_slug . '/' );

    // Request TDS / Sample base URLs — resolved once and cached across calls
    static $tds_base = null, $cont_base = null;
    if ( $tds_base === null ) {
        $tds_page  = get_page_by_path( 'request-tds' );
        $tds_base  = $tds_page ? get_permalink( $tds_page ) : home_url( '/request-tds/' );
    }
    if ( $cont_base === null ) {
        $cont_page = get_page_by_path( 'contact' );
        $cont_base = $cont_page ? get_permalink( $cont_page ) : home_url( '/contact/' );
    }

    // Request TDS URL → /request-tds/?grade=CODE
    $tds_link  = esc_url( add_query_arg( 'grade', $code, $tds_base ) );

    // Sample URL → /contact/?grade=CODE
    $sample_link = esc_url( add_query_arg( 'grade', $code, $cont_base ) );
    ?>
    <?php $card_links = get_option('uci_product_card_links', '0'); ?>
    <div class="grade-card" data-base="<?php echo esc_attr($base); ?>" data-form="<?php echo esc_attr($form); ?>" data-apps="<?php echo esc_attr($apps); ?>"
         <?php if ($card_links == '1'): ?>onclick="location.href='<?php echo esc_js($grade_url); ?>'" style="cursor:pointer"<?php endif; ?>>
        <div class="grade-code"><?php echo esc_html($code); ?></div>
        <div class="grade-desc"><?php echo esc_html($desc); ?></div>
        <div class="grade-tags-row">
            <span class="grade-tag gt-<?php echo esc_attr($base); ?>"><?php echo ucfirst($base); ?></span>
            <span class="grade-tag gt-<?php echo esc_attr($form); ?>"><?php echo ucfirst($form); ?></span>
        </div>
        <div class="grade-app-tags">
            <?php foreach ( $app_labels as $label ) : ?>
                <span class="grade-app-tag"><?php echo esc_html($label); ?></span>
            <?php endforeach; ?>
        </div>
        <div class="grade-ctarow">
            <a href="<?php echo $tds_link; ?>" class="grade-cta-tds" <?php if ($card_links == '1'): ?>onclick="event.stopPropagation()"<?php endif; ?>>Request TDS →</a>
            <a href="<?php echo $sample_link; ?>" class="grade-cta-sample" <?php if ($card_links == '1'): ?>onclick="event.stopPropagation()"<?php endif; ?>>Sample</a>
        </div>
    </div>
    <?php
}
?>

<main id="primary" class="site-main">

<!-- ── PRODUCTS HERO ── -->
<div class="prod-page-hero">
    <div class="prod-page-hero-photo" style="position:absolute;inset:0;background:url('<?php echo get_template_directory_uri(); ?>/assets/images/warehouse-white-bags.jpeg') center/cover no-repeat;opacity:.18"></div>
    <div style="position:absolute;width:600px;height:600px;border-radius:50%;background:var(--blue);opacity:.06;top:-150px;right:-100px;pointer-events:none"></div>
    <div class="wrap" style="position:relative;z-index:2">
        <div style="font-family:var(--mono);font-size:11px;font-weight:700;letter-spacing:2.5px;color:var(--blue);text-transform:uppercase;margin-bottom:14px">Product range · UCI Carbons Group</div>
        <h1 style="font-size:clamp(40px,4.5vw,68px);font-weight:800;letter-spacing:-2.5px;color:#fff;margin-bottom:14px;line-height:1">Find your grade.</h1>
        <p style="font-size:17px;color:rgba(255,255,255,0.55);max-width:520px;line-height:1.7">Two base materials. Five families. Every purification application.</p>
        <div class="prod-hero-stats">
            <div class="prod-hero-stat"><strong>28+</strong> Grades</div>
            <div class="prod-hero-stat"><strong>Wood &amp; Coconut</strong></div>
            <div class="prod-hero-stat"><strong>9,000 MT</strong>/year</div>
            <div class="prod-hero-stat"><strong>TDS</strong> within 24hrs</div>
        </div>
    </div>
</div>

<!-- ── FILTER BAR ── -->
<div class="prod-filter-bar" id="prod-filter-bar">
    <div class="wrap">
        <div class="prod-filter-inner">
            <div class="pf-btn-group">
                <button class="pf-filter-btn active" data-group="base" data-val="all">All</button>
                <button class="pf-filter-btn" data-group="base" data-val="wood">Wood</button>
                <button class="pf-filter-btn" data-group="base" data-val="coconut">Coconut</button>
                <button class="pf-filter-btn" data-group="base" data-val="specialty">Speciality</button>
            </div>
            <div class="pf-divider"></div>
            <div class="pf-btn-group">
                <button class="pf-filter-btn active" data-group="form" data-val="all">All Forms</button>
                <button class="pf-filter-btn" data-group="form" data-val="powder">Powder</button>
                <button class="pf-filter-btn" data-group="form" data-val="granular">Granular</button>
                <button class="pf-filter-btn" data-group="form" data-val="pellet">Pellets</button>
                <button class="pf-filter-btn" data-group="form" data-val="impregnated">Impregnated</button>
            </div>
            <div class="pf-divider"></div>
            <select class="pf-app-select" id="pfAppFilter">
                <option value="all">All Applications</option>
                <option value="water">Water Treatment</option>
                <option value="gold">Gold Recovery</option>
                <option value="pharma">Pharmaceutical</option>
                <option value="food">Food &amp; Beverage</option>
                <option value="oil">Edible Oil</option>
                <option value="air">Air &amp; VOC</option>
                <option value="masks">Gas Masks · CBRN</option>
                <option value="pellets">Pellets Gas Phase</option>
                <option value="merox">Merox · Oil Refining</option>
            </select>
            <span class="pf-count" id="pfCount">28 grades</span>
        </div>
    </div>
</div>

<!-- ── GRADE CATALOGUE ── -->
<div class="grades-catalogue">
<div class="wrap">

<!-- FAMILY 1: Wood Powder — Pharmaceutical & Industrial -->
<div class="grades-family-section" id="family-wood-powder">
    <div class="grades-family-hdr">
        <div class="grades-family-accent" style="background:var(--blue)"></div>
        <div>
            <div class="grades-family-label">WOOD-BASED · POWDER (PAC) · Pine / Poplar / Babool / Eucalyptus</div>
            <div class="grades-family-title">Wood Powder — Pharmaceutical &amp; Industrial</div>
        </div>
    </div>
    <div class="carbon-appearance">
        <div class="carbon-appearance-photo" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/product-pac-fine.jpeg')">
            <div class="carbon-appearance-photo-label">Actual product · PAC Fine</div>
        </div>
        <div class="carbon-appearance-info">
            <div class="carbon-appearance-title">What wood powder looks like</div>
            <div class="carbon-appearance-props">
                <span class="cap-prop cap-key">Fine black powder</span>
                <span class="cap-prop cap-key">Macroporous structure</span>
                <span class="cap-prop">Particle: &lt;75 µm (200 mesh)</span>
                <span class="cap-prop">Bulk density: 200–320 kg/m³</span>
                <span class="cap-prop">Base: Pine · Poplar · Babool · Eucalyptus</span>
                <span class="cap-prop">Forms slurry when dosed to liquid</span>
                <span class="cap-prop">Removed by filtration after use</span>
            </div>
        </div>
    </div>
    <div class="grades-grid">
        <?php
        $grades = [
            ['UCI UW-22',          'wood','powder','pharma water food oil', 'Acid-washed powder. pH-neutral. Ultra-high purity for pharma API decolorisation. BP/USP/IP compliant.',                ['Pharma','Water','Food','Edible Oil'], 'uci-uw-22',      'wood-pac'],
            ['UCI UW-24',          'wood','powder','pharma food water',     'High-purity acid-washed grade. Pharmaceutical and food contact certified. Consistent batch-to-batch quality.',          ['Pharma','Food','Water'],             'uci-uw-24',      'wood-pac'],
            ['UCI UW-26',          'wood','powder','pharma food',           'Premium acid-washed powder for demanding API and injectable applications. Lowest ash content in the wood range.',       ['Pharma','Food'],                     'uci-uw-26',      'wood-pac'],
            ['UCI UW-32',          'wood','powder','pharma',                'Ultra-fine acid-washed grade for injectable-grade pharmaceutical applications. Meets strictest BP/USP requirements.',  ['Pharma'],                            'uci-uw-32',      'wood-pac'],
            ['UCI 55N',            'wood','powder','water food pharma',     'High surface area wood powder. Excellent adsorption capacity. Broad liquid-phase use across water, food and pharma.',  ['Water','Food','Pharma'],             'uci-55n',        'wood-pac'],
            ['UCI 55NS',           'wood','powder','pharma food water',     'Acid-washed variant of 55N. Low pH, low chloride — for food, beverage and pharma purification.',                      ['Pharma','Food','Water'],             'uci-55ns',       'wood-pac'],
            ['DL Premium',         'wood','powder','pharma food oil',       'Top-tier decolorisation grade. Highest purity, lowest ash. Food contact compliant and pharmacopoeial grade.',          ['Pharma','Food','Edible Oil'],        'dl-premium',     'wood-pac'],
            ['UCI 10 / UCI 10NS',  'wood','powder','water food',            'Standard wood powder and acid-washed variant. Reliable liquid-phase adsorbent for industrial decolorisation.',         ['Water','Food'],                      'uci-10',         'wood-pac'],
            ['UCI 15N / UCI 15NS', 'wood','powder','water food',            'Versatile powder grades — standard and acid-washed. Water treatment, food processing and chemical purification.',      ['Water','Food'],                      'uci-15n',        'wood-pac'],
            ['UCI 16N+ / UCI 16NS','wood','powder','water',                 'Standard and acid-washed industrial powder. Consistent performance in continuous dosing applications.',                ['Water'],                             'uci-16n',        'wood-pac'],
        ];
        foreach ( $grades as $g ) uci_grade_card( $g );
        ?>
    </div>
</div>

<!-- FAMILY 2: Wood Granular — Water & Industrial -->
<div class="grades-family-section" id="family-wood-granular">
    <div class="grades-family-hdr">
        <div class="grades-family-accent" style="background:#29B6F6"></div>
        <div>
            <div class="grades-family-label">WOOD-BASED · GRANULAR (GAC) · Fixed-Bed · Column · Industrial</div>
            <div class="grades-family-title">Wood Granular — Water &amp; Industrial</div>
        </div>
    </div>
    <div class="carbon-appearance">
        <div class="carbon-appearance-photo" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/product-gac-8x16.jpeg')">
            <div class="carbon-appearance-photo-label">Actual product · 8×16 GAC</div>
        </div>
        <div class="carbon-appearance-info">
            <div class="carbon-appearance-title">What wood granular looks like</div>
            <div class="carbon-appearance-props">
                <span class="cap-prop cap-key">Irregular dark granules</span>
                <span class="cap-prop cap-key">Macroporous/mesoporous</span>
                <span class="cap-prop">Particle: 0.6–2.4 mm (8×30 mesh)</span>
                <span class="cap-prop">Bulk density: 270–380 kg/m³</span>
                <span class="cap-prop">Base: Pine · Poplar · Babool</span>
                <span class="cap-prop">Packed in fixed-bed contactors</span>
                <span class="cap-prop">Reactivatable after use</span>
            </div>
        </div>
    </div>
    <div class="grades-grid">
        <?php
        $grades = [
            ['UCI RC830',       'wood','granular','water air',      '8×30 mesh water treatment grade. High hardness, consistent adsorption capacity. Municipal and industrial fixed-bed filtration.', ['Water','Air & VOC'],          'uci-rc830',       'wood-gac'],
            ['UCI RC1240',      'wood','granular','water',          '12×40 mesh water treatment grade. Finer particle for enhanced contact time. Municipal and industrial use.',                      ['Water'],                      'uci-rc1240',      'wood-gac'],
            ['UCI 4×8',         'wood','granular','water air',      'Standard 4×8 mesh granular. Versatile liquid and gas-phase adsorbent for industrial column systems.',                           ['Water','Air & VOC'],          'uci-4x8',         'wood-gac'],
            ['UCI 6×18',        'wood','granular','water air',      'Coarse granular for low-pressure-drop applications. VOC control, industrial ventilation and gas-phase processing.',              ['Water','Air & VOC'],          'uci-6x18',        'wood-gac'],
            ['UCI 8×30 Premio', 'wood','granular','merox oil water','Premium 8×30 granular for Merox oil refining units and demanding industrial process streams.',                                   ['Merox','Edible Oil','Water'],  'uci-8x30-premio', 'wood-gac'],
            ['UCI 5-15',        'wood','granular','water',          'Coarse crushed granular for industrial bulk applications. Reliable performance in large fixed-bed systems.',                     ['Water'],                      'uci-5-15',        'wood-gac'],
        ];
        foreach ( $grades as $g ) uci_grade_card( $g );
        ?>
    </div>
</div>

<!-- FAMILY 3: Coconut Granular — Water & Industrial -->
<div class="grades-family-section" id="family-coconut-water">
    <div class="grades-family-hdr">
        <div class="grades-family-accent" style="background:var(--blue)"></div>
        <div>
            <div class="grades-family-label">COCONUT SHELL · GRANULAR · Own Kerala Facility · NSF Certified</div>
            <div class="grades-family-title">Coconut Granular — Water &amp; Industrial</div>
        </div>
    </div>
    <div class="carbon-appearance">
        <div class="carbon-appearance-photo" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/product-gac-3x6.jpeg')">
            <div class="carbon-appearance-photo-label">Actual product · 3×6 Coconut GAC</div>
        </div>
        <div class="carbon-appearance-info">
            <div class="carbon-appearance-title">What coconut shell granular looks like</div>
            <div class="carbon-appearance-props">
                <span class="cap-prop cap-key">Uniform brown-black granules</span>
                <span class="cap-prop cap-key">Microporous structure</span>
                <span class="cap-prop">Particle: 0.42–1.7 mm (12×40 mesh)</span>
                <span class="cap-prop">Hardness: 98–99% (ASTM)</span>
                <span class="cap-prop">Bulk density: 450–520 kg/m³</span>
                <span class="cap-prop">Own Kerala carbonisation plant</span>
                <span class="cap-prop">NSF / Halal / Kosher certified</span>
            </div>
        </div>
    </div>
    <div class="grades-grid">
        <?php
        $grades = [
            ['UCI 3×6C',    'coconut','granular','water air',    'Coarse coconut granular. Four activity tiers (Standard / H / HH / HHH). Designed for water treatment column systems.',         ['Water','Air & VOC'],    'uci-3x6c',    'coconut-water'],
            ['UCI 4×8C',    'coconut','granular','water air',    'Standard coconut 4×8 mesh. Consistent micropore structure. High butane activity. Municipal water treatment.',                  ['Water','Air & VOC'],    'uci-4x8c',    'coconut-water'],
            ['UCI 12×40C',  'coconut','granular','water air',    'Fine 12×40 coconut granular for drinking water and VOC filtration. Four activity tiers available.',                            ['Water','Air & VOC'],    'uci-12x40c',  'coconut-water'],
            ['UCI 4×8 AWC', 'coconut','granular','water pharma', 'Acid-washed coconut granular. Meets USP/BP pharmaceutical requirements. High-purity water applications.',                    ['Water','Pharma'],       'uci-4x8-awc', 'coconut-water'],
            ['AC 12×40B',   'coconut','granular','food',         'Acid-washed beverage grade. Spirits purification, juice and liquid sugar decolorisation. Halal and Kosher certified.',       ['Food & Beverage'],      'ac-12x40b',   'coconut-water'],
            ['AC 12×30B',   'coconut','granular','food',         'Acid-washed beverage coconut. Coarser mesh for faster filtration in distillery and beverage plants.',                        ['Food & Beverage'],      'ac-12x30b',   'coconut-water'],
        ];
        foreach ( $grades as $g ) uci_grade_card( $g );
        ?>
    </div>
</div>

<!-- FAMILY 4: ACGOLD Series — Mining & Gold Recovery -->
<div class="grades-family-section" id="family-gold">
    <div class="grades-family-hdr">
        <div class="grades-family-accent" style="background:var(--gold)"></div>
        <div>
            <div class="grades-family-label">COCONUT SHELL · GOLD RECOVERY · CIL / CIP / Heap Leach</div>
            <div class="grades-family-title">ACGOLD Series — Mining &amp; Gold Recovery</div>
        </div>
    </div>
    <div class="carbon-appearance">
        <div class="carbon-appearance-photo" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/product-3x6.jpeg')">
            <div class="carbon-appearance-photo-label">Actual product · 3×6 Gold Grade</div>
        </div>
        <div class="carbon-appearance-info">
            <div class="carbon-appearance-title">What ACGOLD grades look like</div>
            <div class="carbon-appearance-props">
                <span class="cap-prop cap-key">Coarse hard coconut granules</span>
                <span class="cap-prop cap-key">K value up to 65</span>
                <span class="cap-prop">Mesh: 6×12, 8×16 (coarser than water grades)</span>
                <span class="cap-prop">Hardness: 99% (ASTM)</span>
                <span class="cap-prop">CTC: 45–65%</span>
                <span class="cap-prop">Reactivated and reused in circuit</span>
                <span class="cap-prop">Full batch traceability from Kerala plant</span>
            </div>
        </div>
    </div>
    <div class="grades-grid grades-grid-4">
        <?php
        $grades = [
            ['ACGOLD 6SZ',  'coconut','granular','gold', '6-mesh coconut gold carbon. High K value, excellent CTC activity. Designed for CIL and CIP gold circuits. Own-facility traceability.',       ['Gold Recovery'], 'acgold-6sz',  'gold-recovery'],
            ['ACGOLD 6SFY', 'coconut','granular','gold', '6-mesh floatable coconut grade. Premium surface chemistry for optimal gold adsorption in agitated leach tanks.',                              ['Gold Recovery'], 'acgold-6sfy', 'gold-recovery'],
            ['ACGOLD 8SZ',  'coconut','granular','gold', '8-mesh coconut gold carbon. K values up to 65. CTC 45–65%. Hardness 99%. For high-throughput CIL circuits.',                                 ['Gold Recovery'], 'acgold-8sz',  'gold-recovery'],
            ['ACGOLD 8SFY', 'coconut','granular','gold', '8-mesh floatable premium grade. Highest K value in the ACGOLD series. Preferred for large-scale heap leach operations.',                     ['Gold Recovery'], 'acgold-8sfy', 'gold-recovery'],
        ];
        foreach ( $grades as $g ) uci_grade_card( $g );
        ?>
    </div>
</div>

<!-- FAMILY 5: Coconut Powder — Edible Oil Refining -->
<div class="grades-family-section" id="family-coconut-oil">
    <div class="grades-family-hdr">
        <div class="grades-family-accent" style="background:#B45309"></div>
        <div>
            <div class="grades-family-label">COCONUT SHELL · POWDER · Edible Oil · Water-Washed</div>
            <div class="grades-family-title">Coconut Powder — Edible Oil Refining</div>
        </div>
    </div>
    <div class="carbon-appearance">
        <div class="carbon-appearance-photo" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/product-pac-fine.jpeg')">
            <div class="carbon-appearance-photo-label">Actual product · Coconut PAC</div>
        </div>
        <div class="carbon-appearance-info">
            <div class="carbon-appearance-title">What coconut powder looks like</div>
            <div class="carbon-appearance-props">
                <span class="cap-prop cap-key">Fine black powder</span>
                <span class="cap-prop cap-key">Microporous — tight pore distribution</span>
                <span class="cap-prop">Particle: 200 mesh or 325 mesh</span>
                <span class="cap-prop">Water-washed — no acid residues</span>
                <span class="cap-prop">pH neutral — does not affect oil acid value</span>
                <span class="cap-prop">Low heavy metals — food safe</span>
                <span class="cap-prop">Dosed by weight into oil bleaching vessel</span>
            </div>
        </div>
    </div>
    <div class="grades-grid grades-grid-4">
        <?php
        $grades = [
            ['AC 200E', 'coconut','powder','oil', 'Water-washed coconut powder for edible oil purification. Rice bran, palm oil and specialty oil refining.',                               ['Edible Oil'], 'ac-200e', 'coconut-oil'],
            ['AC 325E', 'coconut','powder','oil', 'Fine 325-mesh coconut powder for edible oil. Maximum surface contact in filter press and bleacher applications.',                       ['Edible Oil'], 'ac-325e', 'coconut-oil'],
        ];
        foreach ( $grades as $g ) uci_grade_card( $g );
        ?>
    </div>
</div>

<!-- FAMILY 6: Pellets — Gas Phase & Industrial -->
<div class="grades-family-section" id="family-pellets">
    <div class="grades-family-hdr">
        <div class="grades-family-accent" style="background:#555"></div>
        <div>
            <div class="grades-family-label">EXTRUDED PELLETS · Wood &amp; Coconut · Low Pressure Drop</div>
            <div class="grades-family-title">Pellets — Gas Phase &amp; Industrial</div>
        </div>
    </div>
    <div class="carbon-appearance">
        <div class="carbon-appearance-photo" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/product-4mm-pellet.jpeg')">
            <div class="carbon-appearance-photo-label">Actual product · 4mm Pellets</div>
        </div>
        <div class="carbon-appearance-info">
            <div class="carbon-appearance-title">What pellets look like</div>
            <div class="carbon-appearance-props">
                <span class="cap-prop cap-key">Uniform cylindrical rods</span>
                <span class="cap-prop cap-key">Low pressure drop</span>
                <span class="cap-prop">3mm or 4mm diameter</span>
                <span class="cap-prop">Length: 5–15mm (variable)</span>
                <span class="cap-prop">Low dust — extruded not crushed</span>
                <span class="cap-prop">Available plain or impregnated</span>
                <span class="cap-prop">Activity: 950 or 1300 mg/g</span>
            </div>
        </div>
    </div>
    <div class="grades-grid grades-grid-4">
        <?php
        $grades = [
            ['PAC-950 · 3mm',  'wood','pellet','pellets air', 'Extruded 3mm pellet. Activity 950mg/g. Low pressure drop for continuous gas-phase systems and odour control.',        ['Gas Phase','Air & VOC'], 'pac-950-3mm',  'pellets'],
            ['PAC-950 · 4mm',  'wood','pellet','pellets air', 'Standard 4mm extruded pellet. Activity 950mg/g. Automotive, industrial and environmental gas treatment.',             ['Gas Phase','Air & VOC'], 'pac-950-4mm',  'pellets'],
            ['PAC-1300 · 4mm', 'wood','pellet','pellets air', 'High-activity 4mm pellet. Activity 1300mg/g. For demanding gas purification requiring maximum capacity.',             ['Gas Phase','Air & VOC'], 'pac-1300-4mm', 'pellets'],
            ['PAC-1300 · 6mm', 'wood','pellet','pellets air', 'Large 6mm extruded pellet. Activity 1300mg/g. Low pressure drop in high gas-volume industrial systems.',             ['Gas Phase','Air & VOC'], 'pac-1300-6mm', 'pellets'],
        ];
        foreach ( $grades as $g ) uci_grade_card( $g );
        ?>
    </div>
</div>

<!-- FAMILY 7: Impregnated & Specialty Grades -->
<div class="grades-family-section" id="family-impregnated">
    <div class="grades-family-hdr">
        <div class="grades-family-accent" style="background:#555"></div>
        <div>
            <div class="grades-family-label">SPECIALTY · Impregnated · Custom Applications</div>
            <div class="grades-family-title">Impregnated &amp; Specialty Grades</div>
        </div>
    </div>
    <div class="carbon-appearance">
        <div class="carbon-appearance-photo" style="background-image:url('<?php echo get_template_directory_uri(); ?>/assets/images/product-8x16.jpeg')">
            <div class="carbon-appearance-photo-label">Actual product · 8×16 Impregnated</div>
        </div>
        <div class="carbon-appearance-info">
            <div class="carbon-appearance-title">What specialty grades look like</div>
            <div class="carbon-appearance-props">
                <span class="cap-prop cap-key">Same visual as base carbon</span>
                <span class="cap-prop cap-key">Reagent bound to pore surface</span>
                <span class="cap-prop">Silver: slight grey tint</span>
                <span class="cap-prop">ABEK: very fine granule (20×60 mesh)</span>
                <span class="cap-prop">Custom impregnation to specification</span>
                <span class="cap-prop">Tested for reagent loading per batch</span>
                <span class="cap-prop">MOD / CBRN / NSF / food cert available</span>
            </div>
        </div>
    </div>
    <div class="grades-grid">
        <?php
        $grades = [
            ['Silver-Impregnated',  'specialty','impregnated','water',      'Bacteriostatic carbon for drinking water POE/POU filters. Prevents microbial regrowth. NSF/ANSI 42 compatible.',              ['Water'],             'silver-impregnated', 'specialty'],
            ['ABEK · 20×60–35×80', 'specialty','impregnated','masks',      'ABEK-impregnated grades for gas mask and CBRN cartridges. AC 20×60, 30×60, 35×70, 35×80 mesh sizes available.',               ['Gas Masks','CBRN'],  'abek',               'specialty'],
            ['Wood:Coconut Blend',  'specialty','impregnated','air water',  'Custom blend ratios combining macroporous wood structure with micropore density of coconut. For bespoke applications.',        ['Air & VOC','Water'], 'wood-coconut-blend', 'specialty'],
        ];
        foreach ( $grades as $g ) uci_grade_card( $g );
        ?>
    </div>
</div>

</div><!-- .wrap -->
</div><!-- .grades-catalogue -->

<!-- ── CARBON AI STRIP ── -->
<section class="page-ai-strip">
    <div class="wrap">
        <div class="pai-inner">
            <div class="pai-avatar">
                <svg viewBox="0 0 24 24" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M9 9h.01M15 9h.01"/><path d="M9.5 15a3.5 3.5 0 005 0" stroke-linecap="round"/></svg>
            </div>
            <div class="pai-text">
                <div class="pai-eyebrow">Carbon Expert AI</div>
                <div class="pai-headline">Can't find the exact grade?</div>
                <div class="pai-sub">Describe your application and our AI will match you to the right product.</div>
            </div>
            <div class="pai-input-row">
                <input type="text" id="paiInput-products" class="pai-input" placeholder="e.g. GAC for drinking water, 1000 iodine..." onkeydown="if(event.key==='Enter')aiAskFromPage('paiInput-products')">
                <button class="pai-btn" onclick="aiAskFromPage('paiInput-products')">Ask AI →</button>
            </div>
        </div>
    </div>
</section>

<!-- ── BOTTOM CTA STRIP ── -->
<div class="prod-cta-strip">
    <div class="wrap">
        <div style="font-size:clamp(22px,2.5vw,34px);font-weight:800;color:#fff;letter-spacing:-0.5px;margin-bottom:10px">Need a TDS, sample or custom grade?</div>
        <p style="font-size:16px;color:rgba(255,255,255,0.5);margin-bottom:28px;max-width:520px;margin-left:auto;margin-right:auto">Technical data sheets dispatched within 24 hours. Samples shipped in 5–7 working days.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
            <?php
            $tds_pg  = get_page_by_path( 'request-tds' );
            $tds_url = $tds_pg ? get_permalink( $tds_pg ) : home_url( '/request-tds/' );
            ?>
            <a href="<?php echo esc_url( $tds_url ); ?>" class="btn-primary" style="text-decoration:none;font-size:15px;padding:13px 28px">Request TDS →</a>
            <a href="<?php echo esc_url( home_url('/contact/') ); ?>" class="btn-outline" style="text-decoration:none;font-size:15px;padding:13px 28px">Request Sample →</a>
        </div>
    </div>
</div>

</main>

<script>
/* ── Upgrade grade-app-tags to icon badges (matches demo HTML) ── */
(function() {
    var appIconDefs = {
        water:   { label:'Water Treatment',  svg:'<polyline points="23 7 13 17 8 12 2 18"/><polyline points="16 7 23 7 23 14"/>' },
        pharma:  { label:'Pharmaceutical',   svg:'<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><circle cx="17.5" cy="17.5" r="3.5"/><line x1="17.5" y1="14" x2="17.5" y2="21"/><line x1="14" y1="17.5" x2="21" y2="17.5"/>' },
        food:    { label:'Food &amp; Bev',   svg:'<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>' },
        oil:     { label:'Edible Oil',       svg:'<path d="M12 22C6.5 22 2 17.5 2 12S6.5 2 12 2s10 4.5 10 10"/><path d="M12 6v6l4 2"/><path d="M18 14l4 2-4 2"/>' },
        air:     { label:'Air &amp; VOC',    svg:'<path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/>' },
        gold:    { label:'Gold Recovery',    svg:'<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>' },
        masks:   { label:'Gas Masks/CBRN',   svg:'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' },
        pellets: { label:'Gas Phase',        svg:'<line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11L2 12v6a2 2 0 002 2h16a2 2 0 002-2v-6l-3.45-6.89A2 2 0 0016.76 4H7.24a2 2 0 00-1.79 1.11z"/><line x1="6" y1="16" x2="6.01" y2="16"/><line x1="10" y1="16" x2="10.01" y2="16"/>' },
        merox:   { label:'Merox/Oil Ref.',   svg:'<circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 010 14.14"/><path d="M4.93 4.93a10 10 0 000 14.14"/>' }
    };

    document.querySelectorAll('.grade-card[data-apps]').forEach(function(card) {
        var appTagsEl = card.querySelector('.grade-app-tags');
        if (!appTagsEl) return;
        var apps = (card.dataset.apps || '').trim().split(/\s+/);
        var html = apps.map(function(app) {
            var def = appIconDefs[app];
            if (!def) return '';
            return '<span class="gai"><svg viewBox="0 0 24 24"><g>' + def.svg + '</g></svg>' + def.label + '</span>';
        }).join('');
        if (!html) return;
        var wrapper = document.createElement('div');
        wrapper.className = 'grade-app-icons';
        wrapper.innerHTML = html;
        appTagsEl.replaceWith(wrapper);
    });
})();
</script>

<?php get_footer(); ?>
