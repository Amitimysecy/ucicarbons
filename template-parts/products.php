<?php
$products_url = get_permalink( get_page_by_path('products') );
if ( ! $products_url ) $products_url = home_url('/products/');
?>
<!-- ── PRODUCT FINDER (Homepage) ── -->
<section class="sec-home" style="background:var(--bg)" id="product-finder">
    <div class="wrap">
        <div class="sec-eyebrow">Find your grade</div>
        <h2 class="sec-h">Tell us your application. We'll match you to a grade.</h2>
        <p class="sec-sub" style="margin-bottom:32px">Select your end-use below. Results show recommended grades with a one-click TDS or sample request.</p>

        <div class="pf-app-grid">
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=water' ); ?>">
                <div class="pf-app-icon">💧</div>
                <div class="pf-app-name">Water Treatment</div>
                <div class="pf-app-hint">Municipal &amp; industrial filtration</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=gold' ); ?>">
                <div class="pf-app-icon">🥇</div>
                <div class="pf-app-name">Gold Recovery</div>
                <div class="pf-app-hint">CIL/CIP circuits, heap leach</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=pharma' ); ?>">
                <div class="pf-app-icon">⚗️</div>
                <div class="pf-app-name">Pharmaceutical</div>
                <div class="pf-app-hint">API decolorisation, BP/USP grades</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=food' ); ?>">
                <div class="pf-app-icon">🍶</div>
                <div class="pf-app-name">Food &amp; Beverage</div>
                <div class="pf-app-hint">Spirit, juice, liquid sugar purification</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=oil' ); ?>">
                <div class="pf-app-icon">🌿</div>
                <div class="pf-app-name">Edible Oil</div>
                <div class="pf-app-hint">Rice bran, palm oil refining</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=air' ); ?>">
                <div class="pf-app-icon">🌬️</div>
                <div class="pf-app-name">Air &amp; VOC</div>
                <div class="pf-app-hint">Industrial ventilation, solvent recovery</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=masks' ); ?>">
                <div class="pf-app-icon">🛡️</div>
                <div class="pf-app-name">Gas Masks / CBRN</div>
                <div class="pf-app-hint">Impregnated protection grades</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=pellets' ); ?>">
                <div class="pf-app-icon">⚙️</div>
                <div class="pf-app-name">Pellets</div>
                <div class="pf-app-hint">Gas phase, low pressure drop</div>
            </a>
            <a class="pf-app-card" href="<?php echo esc_url( $products_url . '?app=other' ); ?>">
                <div class="pf-app-icon">💬</div>
                <div class="pf-app-name">Other / Not sure</div>
                <div class="pf-app-hint">Contact us — we'll advise</div>
            </a>
        </div>
    </div>
</section>
