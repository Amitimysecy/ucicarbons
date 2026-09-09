<?php
/**
 * Plugin Name: UCI Application Sector Pages Importer
 * Plugin URI:  https://ucicarbons.com
 * Description: One-time setup — creates 9 application sector pages as children of the Applications page, with full process steps, specs, and grade data. Deactivate and delete after use.
 * Version:     1.0
 * Author:      UCI Carbons
 */

if ( ! defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, 'uci_app_create_pages' );

function uci_app_create_pages() {

    $parent    = get_page_by_path( 'applications' );
    $parent_id = $parent ? $parent->ID : 0;

    $sectors = uci_app_sectors();
    $created = 0;
    $skipped = 0;

    foreach ( $sectors as $s ) {
        $existing = get_page_by_path( 'applications/' . $s['slug'] );
        if ( $existing ) { $skipped++; continue; }

        $post_id = wp_insert_post( [
            'post_title'   => $s['title'],
            'post_name'    => $s['slug'],
            'post_excerpt' => $s['body'],
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_parent'  => $parent_id,
            'meta_input'   => [
                '_wp_page_template' => 'page-application-sector.php',
                '_app_color'        => $s['color'],
                '_app_bg'           => $s['bg'],
                '_app_icon'         => $s['icon'],
                '_app_body'         => $s['body'],
                '_app_chips'        => json_encode( $s['chips'],  JSON_UNESCAPED_UNICODE ),
                '_app_steps'        => json_encode( $s['steps'],  JSON_UNESCAPED_UNICODE ),
                '_app_specs'        => json_encode( $s['specs'],  JSON_UNESCAPED_UNICODE ),
                '_app_grades'       => json_encode( $s['grades'], JSON_UNESCAPED_UNICODE ),
            ],
        ], true );

        if ( ! is_wp_error( $post_id ) ) $created++;
    }

    update_option( 'uci_app_import_result', "UCI Application Importer: created {$created} sector pages, skipped {$skipped}. Deactivate and delete this plugin." );
}

add_action( 'admin_notices', function () {
    $msg = get_option( 'uci_app_import_result' );
    if ( ! $msg ) return;
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
    delete_option( 'uci_app_import_result' );
} );

/* ════════════════════════════════════════════════════════
   SECTOR DATA
   ════════════════════════════════════════════════════════ */
function uci_app_sectors() {
    return [

    [
        'slug'  => 'water',
        'title' => 'Water Treatment',
        'color' => '#0296D8',
        'bg'    => '#E8F5FD',
        'icon'  => '💧',
        'chips' => ['RC830','4×8C','12×40C'],
        'body'  => 'Granular activated carbon is the workhorse of municipal and industrial water treatment. It removes chlorine, chloramines, taste, odour, and a wide range of organic micropollutants through adsorption in fixed-bed contactors. Powder grades handle emergency dosing and variable-load treatment plants.',
        'steps' => [
            ['n'=>'01','label'=>'Load the contactors','desc'=>'GAC fills fixed-bed vessels to 1–3 m depth. Water flows downward through the carbon bed.'],
            ['n'=>'02','label'=>'Adsorption in bed','desc'=>'Contaminants bind to carbon surface as water passes through. Treated water exits from the base.'],
            ['n'=>'03','label'=>'Monitor breakthrough','desc'=>'Effluent quality is tested continuously. When capacity degrades, carbon is replaced or reactivated.'],
        ],
        'specs' => [
            ['name'=>'Iodine Number','value'=>'900–1100 mg/g','desc'=>'Core capacity indicator — higher IV means more adsorption capacity per gram'],
            ['name'=>'Mesh Size','value'=>'8×30 / 12×40','desc'=>'Consistent mesh ensures uniform bed flow distribution and controlled pressure drop'],
            ['name'=>'Hardness','value'=>'>95% (ASTM)','desc'=>'Must withstand backwash cycles without generating excessive fines'],
        ],
        'grades' => [
            ['code'=>'UCI RC830 / RC1240','base'=>'Wood','desc'=>'Primary water treatment GAC. Consistent IV, excellent hardness, macroporous pine structure.'],
            ['code'=>'UCI 4×8C / 12×40C','base'=>'Coconut','desc'=>'Higher micropore density for small-molecule removal. Suitable for potable water duty.'],
            ['code'=>'UCI 4×8 AWC','base'=>'Coconut','desc'=>'Acid-washed coconut GAC — low iron, neutral pH, NSF-quality facility.'],
        ],
    ],

    [
        'slug'  => 'gold',
        'title' => 'Gold Recovery',
        'color' => '#D97706',
        'bg'    => '#FEF3C7',
        'icon'  => '🥇',
        'chips' => ['ACGOLD 6SZ','ACGOLD 8SFY'],
        'body'  => 'In CIL (carbon-in-leach) and CIP (carbon-in-pulp) gold recovery, activated carbon adsorbs the gold-cyanide complex from the leach slurry. The process demands extreme mechanical durability — carbon circulates through pumps and screens repeatedly, and fines mean lost gold.',
        'steps' => [
            ['n'=>'01','label'=>'Leach & load','desc'=>'Crushed ore is leached with cyanide solution. Gold-cyanide complex forms and is adsorbed onto carbon in agitated tanks.'],
            ['n'=>'02','label'=>'Elution','desc'=>'Gold-loaded carbon is stripped using hot caustic solution. Gold transfers to eluate for electrowinning.'],
            ['n'=>'03','label'=>'Reactivation','desc'=>'Stripped carbon is thermally reactivated at 650°C+ to restore capacity. Durability determines how many cycles are viable.'],
        ],
        'specs' => [
            ['name'=>'K Value (Freundlich)','value'=>'≥ 45 (target 60–65)','desc'=>'The best predictor of gold adsorption performance — higher is better'],
            ['name'=>'CTC Activity','value'=>'45–65%','desc'=>'Correlates with micropore volume available for gold-cyanide complex adsorption'],
            ['name'=>'Hardness','value'=>'≥ 98% (ASTM)','desc'=>'Critical — low hardness generates fines that carry gold-loaded particles to tailings'],
        ],
        'grades' => [
            ['code'=>'ACGOLD 6SZ','base'=>'Coconut','desc'=>'6×12 mesh, K value up to 65, CTC 60%. Standard CIL/CIP workhorse grade.'],
            ['code'=>'ACGOLD 8SFY','base'=>'Coconut','desc'=>'8×16 mesh, enhanced fines yield. CTC 55–60%, hardness 99%.'],
            ['code'=>'ACGOLD 6SFY','base'=>'Coconut','desc'=>'Fast kinetics variant — for high-throughput CIL circuits with short contact time.'],
        ],
    ],

    [
        'slug'  => 'pharma',
        'title' => 'Pharma & API Purification',
        'color' => '#7C3AED',
        'bg'    => '#EDE9FE',
        'icon'  => '⚗️',
        'chips' => ['UW-22','55NS','DL Premium'],
        'body'  => 'Activated carbon in pharmaceutical manufacturing decolorises API solutions, removes trace organics from injectable preparations, and is the active ingredient in medicinal charcoal products. BP, USP, and EU pharmacopoeial standards apply — and acid-washing is mandatory for injectable-grade applications.',
        'steps' => [
            ['n'=>'01','label'=>'Slurry & contact','desc'=>'Carbon is slurried with the API solution in a mixing vessel. Contact time allows decolorisation and impurity adsorption.'],
            ['n'=>'02','label'=>'Filtration','desc'=>'Carbon is filtered out — typically through filter press or sparkler filter. The clarified API solution is collected.'],
            ['n'=>'03','label'=>'Pharmacopoeial QC','desc'=>'Batch-level CoA against BP/USP specifications. Heavy metals, pH, arsenic, iron all tested and documented.'],
        ],
        'specs' => [
            ['name'=>'pH (Acid-washed)','value'=>'6.5–7.5','desc'=>'Mandatory for injectables — standard carbon will lower pH of sensitive API solutions'],
            ['name'=>'Iron Content','value'=>'< 200 ppm','desc'=>'Low iron prevents discolouration of white APIs and contamination of light-coloured products'],
            ['name'=>'Ash Content','value'=>'< 5%','desc'=>'Lower ash means higher purity carbon with fewer inorganic residuals'],
        ],
        'grades' => [
            ['code'=>'UCI UW-22 / 24 / 26 / 32','base'=>'Wood','desc'=>'Acid-washed, pH-neutral. Primary pharma API decolorisation grades. BP/USP CoA available.'],
            ['code'=>'UCI 55NS / DL Premium','base'=>'Wood','desc'=>'Ultra-high purity. Lowest ash and iron. For injectables and most demanding API streams.'],
            ['code'=>'UCI 4×8 AWC','base'=>'Coconut','desc'=>'Acid-washed coconut GAC for pharmaceutical water systems and BP Purified Water carbon filtration.'],
        ],
    ],

    [
        'slug'  => 'food',
        'title' => 'Food & Beverage',
        'color' => '#059669',
        'bg'    => '#D1FAE5',
        'icon'  => '🍶',
        'chips' => ['12×40B','12×30B','55N'],
        'body'  => 'Coconut shell activated carbon is the beverage industry standard — its tight micropore structure removes colour and odour compounds without stripping the flavour precursors that define the product. Applications range from spirit decolorisation to liquid sugar purification.',
        'steps' => [
            ['n'=>'01','label'=>'Carbon dosing','desc'=>'Carbon is dosed into the liquid stream — typically in a mixing tank or inline dosing system.'],
            ['n'=>'02','label'=>'Contact & adsorption','desc'=>'Colour bodies and off-flavour compounds adsorb onto carbon surface. Contact time is controlled to preserve flavour.'],
            ['n'=>'03','label'=>'Filtration & recovery','desc'=>'Spent carbon is filtered from the treated liquid. Product meets colour specification (ICUMSA or Hazen units).'],
        ],
        'specs' => [
            ['name'=>'Food Contact','value'=>'FC certified','desc'=>'Required for all carbon contacting food or beverage streams — documented for each batch'],
            ['name'=>'Iron Content','value'=>'< 100 ppm','desc'=>'Low iron prevents metallic taste carry-over into the final beverage product'],
            ['name'=>'Decolorisation','value'=>'>80% efficiency','desc'=>'Standard test at defined contact time and dosage against standard caramel colour solution'],
        ],
        'grades' => [
            ['code'=>'AC 12×40B','base'=>'Coconut','desc'=>'Primary beverage grade. Acid-washed, FC certified. Spirit decolorisation and juice purification.'],
            ['code'=>'AC 12×30B','base'=>'Coconut','desc'=>'Slightly coarser mesh — lower pressure drop. Same purity and certification as 12×40B.'],
            ['code'=>'UCI 55N / 55NS','base'=>'Wood','desc'=>'Wood-based option for liquid sugar and food processing with high decolorisation demand.'],
        ],
    ],

    [
        'slug'  => 'edibleoil',
        'title' => 'Edible Oil Refining',
        'color' => '#B45309',
        'bg'    => '#FEF3C7',
        'icon'  => '🫒',
        'chips' => ['AC 200E','AC 325E','UCI UW-22'],
        'body'  => 'Powder activated carbon is used in refining rice bran, palm, sunflower, and specialty oils — removing colour, odour compounds, and trace contaminants. Water-washed grades are mandatory: acid-washed carbon introduces free acid that can damage the oil.',
        'steps' => [
            ['n'=>'01','label'=>'Bleaching step','desc'=>'Carbon is added to oil at elevated temperature (80–110°C) along with bleaching earth, in a bleaching vessel under vacuum.'],
            ['n'=>'02','label'=>'Contact & mixing','desc'=>'Carbon contacts the oil for 20–30 minutes. Colour bodies and odour compounds adsorb onto the carbon surface.'],
            ['n'=>'03','label'=>'Filtration','desc'=>'Bleached oil passes through filter press. Carbon and bleaching earth are removed. Oil meets colour specification.'],
        ],
        'specs' => [
            ['name'=>'Wash type','value'=>'Water-washed only','desc'=>'Acid-washed carbon introduces free fatty acid into oil — water-washed is the mandatory specification'],
            ['name'=>'Particle Size','value'=>'200 or 325 mesh','desc'=>'Finer particle size gives more surface area per gram and improves contact efficiency in oil'],
            ['name'=>'Decolorisation','value'=>'>85% (Lovibond)','desc'=>'Lovibond colour reduction test in standard oil at defined dose and temperature'],
        ],
        'grades' => [
            ['code'=>'AC 200E','base'=>'Coconut','desc'=>'Water-washed coconut powder, 200 mesh. Standard edible oil grade. High decolorisation activity.'],
            ['code'=>'AC 325E','base'=>'Coconut','desc'=>'325 mesh (finer). Better contact efficiency in applications with shorter contact time.'],
            ['code'=>'UCI UW-22','base'=>'Wood','desc'=>'Available water-washed for oil applications. Higher total capacity per batch dosing.'],
        ],
    ],

    [
        'slug'  => 'air',
        'title' => 'Air & VOC Control',
        'color' => '#0284C7',
        'bg'    => '#E0F2FE',
        'icon'  => '🌬️',
        'chips' => ['AC 3×6C','PAC-950','PAC-1300'],
        'body'  => 'Activated carbon in air purification adsorbs volatile organic compounds, solvent vapours, odour molecules, and low-concentration toxic gases. Granular beds are the standard for fixed systems; pellets offer lower pressure drop for continuous high-throughput applications.',
        'steps' => [
            ['n'=>'01','label'=>'Gas stream entry','desc'=>'Contaminated air enters the carbon bed. Flow rate and bed depth determine contact time and removal efficiency.'],
            ['n'=>'02','label'=>'Adsorption in micropores','desc'=>'VOC molecules are captured in the micropore network of the carbon. Coconut shell carbon is preferred for its micropore density.'],
            ['n'=>'03','label'=>'Bed saturation & replacement','desc'=>'Breakthrough is detected by downstream monitoring. Bed is either replaced (once-through) or regenerated with steam.'],
        ],
        'specs' => [
            ['name'=>'CTC Activity','value'=>'45–80%','desc'=>'Carbon tetrachloride activity is the key indicator for gas-phase micropore capacity'],
            ['name'=>'Pressure Drop','value'=>'Pellets < GAC','desc'=>'3mm pellets offer significantly lower pressure drop than equivalent 4×8 GAC — important for energy cost'],
            ['name'=>'Hardness','value'=>'>95% (GAC)','desc'=>'Bed carbon must survive packing and airflow without generating fine particles that bypass the system'],
        ],
        'grades' => [
            ['code'=>'AC 3×6C / 4×8C','base'=>'Coconut','desc'=>'Granular coconut GAC. High CTC activity — standard, H, HH, HHH tiers. Primary VOC control grade.'],
            ['code'=>'PAC-950 / PAC-1300','base'=>'Wood','desc'=>'3mm, 4mm, 6mm extruded pellets. Lower pressure drop than GAC. For continuous regeneration systems.'],
            ['code'=>'UCI 4×8 / 6×18','base'=>'Wood','desc'=>'Wood GAC for lower-cost industrial air purification where coconut micropore density is not required.'],
        ],
    ],

    [
        'slug'  => 'gasmask',
        'title' => 'Gas Masks & CBRN',
        'color' => '#DC2626',
        'bg'    => '#FEE2E2',
        'icon'  => '🛡️',
        'chips' => ['AC 20×60','AC 35×70','ABEK'],
        'body'  => 'CBRN-grade activated carbon for respirator cartridges and collective protection systems must meet stringent penetration tests for chemical warfare agents and toxic industrial chemicals. ABEK impregnation adds chemical reactivity against acid gases, organic vapours, and ammonia.',
        'steps' => [
            ['n'=>'01','label'=>'ABEK impregnation','desc'=>'Carbon is impregnated with specific reagents: triethylenediamine (TEDA), KI, and other agents that chemically react with toxic gases.'],
            ['n'=>'02','label'=>'Cartridge filling','desc'=>'Impregnated carbon is filled into cartridge bodies to precise mesh-controlled tolerances. Packing density affects cartridge service life.'],
            ['n'=>'03','label'=>'Penetration testing','desc'=>'Finished cartridges are tested for breakthrough time against standard challenge agents per EN 14387 and NATO STANAG protocols.'],
        ],
        'specs' => [
            ['name'=>'Mesh Size','value'=>'20×60 to 35×80','desc'=>'Extremely tight tolerances — fine mesh maximises packing density and contact time per cartridge volume'],
            ['name'=>'Hardness','value'=>'>99% (ASTM)','desc'=>'Cartridges vibrate in field use. Any fines generation creates voids and shortens service life'],
            ['name'=>'ABEK Impregnation','value'=>'Full ABEK spectrum','desc'=>'A=organic vapours, B=acid gases, E=SO₂/HF, K=ammonia — broad-spectrum protection'],
        ],
        'grades' => [
            ['code'=>'AC 20×60 / 30×60','base'=>'Coconut','desc'=>'Standard CBRN mesh sizes. ABEK impregnated. For standard respirator filter cartridges.'],
            ['code'=>'AC 35×70 / 35×80','base'=>'Coconut','desc'=>'Fine mesh for collective protection units. Highest packing density per cartridge volume.'],
            ['code'=>'ABEK-P3 Pellet','base'=>'Coconut','desc'=>'Pre-impregnated ABEK pellet for OEM cartridge manufacturers requiring ready-to-fill material.'],
        ],
    ],

    [
        'slug'  => 'merox',
        'title' => 'Oil Refining / Merox',
        'color' => '#0F766E',
        'bg'    => '#CCFBF1',
        'icon'  => '🏭',
        'chips' => ['UCI 8×30 Premio','UCI UW-22'],
        'body'  => 'Merox (Mercaptan Oxidation) is a licensed refinery process that sweetens LPG, kerosene, and jet fuel by converting mercaptans to disulfides using activated carbon as a catalyst support. The carbon bed must withstand continuous liquid-phase contact, elevated temperatures, and regular caustic wash cycles.',
        'steps' => [
            ['n'=>'01','label'=>'Merox catalyst loading','desc'=>'Carbon impregnated with cobalt phthalocyanine catalyst is loaded into fixed-bed sweetening reactors.'],
            ['n'=>'02','label'=>'Sweetening reaction','desc'=>'Petroleum fraction passes through the carbon bed with caustic and air. Mercaptans oxidise to disulfides at the carbon surface.'],
            ['n'=>'03','label'=>'Caustic wash & regeneration','desc'=>'Carbon bed is periodically washed with caustic to remove deposits and restore catalyst activity. Ultra-high hardness prevents attrition.'],
        ],
        'specs' => [
            ['name'=>'Hardness','value'=>'>98% (ASTM)','desc'=>'Caustic wash cycles create mechanical stress. Low hardness means attrition, fines, and bed channelling.'],
            ['name'=>'Mesh Size','value'=>'8×30 (standard)','desc'=>'Uniform mesh ensures consistent bed void fraction and predictable pressure drop in sweetening units'],
            ['name'=>'Iodine Number','value'=>'>900 mg/g','desc'=>'High surface area provides maximum catalyst support surface and sustained sweetening activity'],
        ],
        'grades' => [
            ['code'=>'UCI 8×30 Premio','base'=>'Wood','desc'=>'Primary Merox grade. High hardness, consistent 8×30 mesh, proven in LPG and kerosene sweetening.'],
            ['code'=>'UCI UW-22','base'=>'Wood','desc'=>'High-specification Merox duty — ultra-pure, low ash, for refinery applications with strict contamination limits.'],
        ],
    ],

    [
        'slug'  => 'sugar',
        'title' => 'Sugar Refining',
        'color' => '#92400E',
        'bg'    => '#FEF3C7',
        'icon'  => '🍬',
        'chips' => ['NC 850','NC 830','55N'],
        'body'  => 'Activated carbon is used in raw and refined sugar decolorisation — removing colour compounds (melanoidins, caramels, and polyphenols) from sugar liquors as a modern bone-char replacement. High decolorisation efficiency and consistent mesh sizing are critical for column operations.',
        'steps' => [
            ['n'=>'01','label'=>'Liquor preparation','desc'=>'Raw sugar is dissolved to form a liquor. The liquor is pre-treated to remove suspended solids before carbon contact.'],
            ['n'=>'02','label'=>'Carbon contact','desc'=>'Sugar liquor passes through GAC columns or is mixed with PAC. Colour compounds adsorb onto the carbon surface.'],
            ['n'=>'03','label'=>'Filtration & polishing','desc'=>'Decolorised liquor is filtered, then evaporated and crystallised. Colour (ICUMSA units) is measured at each stage.'],
        ],
        'specs' => [
            ['name'=>'Decolorisation Index','value'=>'>90% efficiency','desc'=>'Molasses decolorisation index — the primary performance measure for sugar refinery carbon'],
            ['name'=>'Iodine Number','value'=>'900–1050 mg/g','desc'=>'Surface area indicator — sufficient capacity for efficient decolorisation per column pass'],
            ['name'=>'Mesh Size','value'=>'8×30 / 12×40','desc'=>'Consistent sizing ensures uniform bed flow and avoids channelling in column decolorisation'],
        ],
        'grades' => [
            ['code'=>'UCI NC 850','base'=>'Coconut','desc'=>'Primary sugar GAC grade. High decolorisation efficiency, consistent 8×30 mesh, excellent hardness.'],
            ['code'=>'UCI NC 830','base'=>'Coconut','desc'=>'12×40 mesh variant. For applications requiring finer particle size and higher bed contact area.'],
            ['code'=>'UCI 55N','base'=>'Wood','desc'=>'Powdered wood grade for batch decolorisation of liquid sugar and glucose syrups.'],
        ],
    ],

    ];
}
