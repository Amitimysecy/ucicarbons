<?php
/**
 * Shared contact sidebar — Primary Contact, Facilities, Export, Stats.
 * Used by page-contact.php, page-tds.php, page-brochure.php.
 */
?>
<div style="display:flex;flex-direction:column;gap:20px">

    <!-- Primary contact -->
    <div class="card" style="background:var(--dark);border-color:var(--dark)">
        <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:2px;color:var(--sky);text-transform:uppercase;margin-bottom:12px">Primary contact</div>
        <div style="font-size:20px;font-weight:800;color:#fff;margin-bottom:4px">Kartik Gupta</div>
        <div style="font-size:14px;color:rgba(255,255,255,0.45);margin-bottom:20px">Vice President, UCI Carbons Group</div>
        <div style="display:flex;flex-direction:column;gap:12px">
            <a href="mailto:kartik.gupta@ucicarbons.com" style="font-size:14px;color:var(--sky);text-decoration:none;display:flex;align-items:center;gap:8px">✉ kartik.gupta@ucicarbons.com</a>
            <a href="tel:+919501005395" style="font-size:14px;color:var(--sky);text-decoration:none;display:flex;align-items:center;gap:8px">📱 +91 9501005395</a>
        </div>
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid rgba(255,255,255,0.08);font-size:13px;color:rgba(255,255,255,0.3)">Response time: within 24 hours</div>
    </div>

    <!-- Facilities -->
    <div class="card">
        <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:2px;color:var(--blue);text-transform:uppercase;margin-bottom:14px">Our facilities</div>
        <div style="display:flex;flex-direction:column;gap:14px">
            <div style="padding:12px 14px;background:var(--bg);border-radius:10px;border:1px solid var(--rule)">
                <div style="font-weight:700;color:var(--dark);font-size:13px;margin-bottom:2px">Rajindra Carbons</div>
                <div style="font-size:12px;color:var(--soft)">Bhagowal, Hoshiarpur, Punjab · Main facility</div>
            </div>
            <div style="padding:12px 14px;background:var(--bg);border-radius:10px;border:1px solid var(--rule)">
                <div style="font-weight:700;color:var(--dark);font-size:13px;margin-bottom:2px">Universal Carbons (India)</div>
                <div style="font-size:12px;color:var(--soft)">Tanda Road, Hoshiarpur, Punjab · Pharma &amp; specialty</div>
            </div>
            <div style="padding:12px 14px;background:var(--bg);border-radius:10px;border:1px solid var(--rule)">
                <div style="font-weight:700;color:var(--dark);font-size:13px;margin-bottom:2px">Rajindra Carbon</div>
                <div style="font-size:12px;color:var(--soft)">Village Mallapuram, Kerala · Coconut shell carbon</div>
            </div>
        </div>
    </div>

    <!-- Export reach -->
    <div class="card" style="background:var(--bg)">
        <div style="font-family:var(--mono);font-size:10px;font-weight:700;letter-spacing:2px;color:var(--blue);text-transform:uppercase;margin-bottom:10px">Export reach</div>
        <p style="font-size:14px;color:var(--mid);line-height:1.75;margin:0">USA · UK · France · Germany · Netherlands · Serbia · Gulf region · Australia · Argentina · Russia · Sri Lanka and 20+ more markets.</p>
    </div>

    <!-- Client stat -->
    <div class="card" style="background:var(--dark);border-color:var(--dark);text-align:center">
        <div style="font-size:28px;font-weight:800;color:var(--blue);letter-spacing:-1px;line-height:1;margin-bottom:4px">500+</div>
        <div style="font-size:12px;color:rgba(255,255,255,0.35);font-family:var(--mono);letter-spacing:1px;text-transform:uppercase">Clients who trust us</div>
    </div>

</div>
