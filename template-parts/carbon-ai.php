<?php
$contact_url = get_permalink(get_page_by_path('contact'))        ?: home_url('/contact/');
$tds_url     = get_permalink(get_page_by_path('request-tds'))    ?: home_url('/request-tds/');
?>
<!-- ── CARBON AI SECTION ── -->
<section class="ai-section" id="ai-section-home">
    <div class="wrap ai-inner">

        <div class="ai-badge">
            <div class="ai-badge-dot"></div>
            <span class="ai-badge-label">Powered by Claude AI · Live 24/7</span>
        </div>

        <h2 class="ai-heading">Ask our <span>Carbon AI</span> anything.</h2>
        <p class="ai-subhead">Specs, grades, shipping, certifications, applications — instant answers from our full product database.</p>

        <div class="ai-prompts-label">Try asking —</div>

        <div class="ai-ticker-wrap">
            <div class="ai-ticker-track" id="aiTicker">
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you ship to Africa?">Do you ship to Africa?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the hardness of your gold grade carbon?">What is the hardness of your gold grade carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you do acid washing?">Do you do acid washing?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the iodine value of UCI RC830?">What is the iodine value of UCI RC830?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Can I get halal certified activated carbon?">Can I get halal certified activated carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the K value of your ACGOLD series?">What is the K value of your ACGOLD series?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you supply pharma BP/USP grade carbon?">Do you supply pharma BP/USP grade carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the difference between wood and coconut carbon?">What is the difference between wood and coconut carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is your minimum order quantity?">What is your minimum order quantity?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you have NSF certified water treatment grades?">Do you have NSF certified water treatment grades?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Which grade for edible oil refining?">Which grade for edible oil refining?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Can you supply activated carbon for gas masks?">Can you supply activated carbon for gas masks?</div>
                <!-- duplicated for seamless loop -->
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you ship to Africa?">Do you ship to Africa?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the hardness of your gold grade carbon?">What is the hardness of your gold grade carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you do acid washing?">Do you do acid washing?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the iodine value of UCI RC830?">What is the iodine value of UCI RC830?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Can I get halal certified activated carbon?">Can I get halal certified activated carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the K value of your ACGOLD series?">What is the K value of your ACGOLD series?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you supply pharma BP/USP grade carbon?">Do you supply pharma BP/USP grade carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is the difference between wood and coconut carbon?">What is the difference between wood and coconut carbon?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="What is your minimum order quantity?">What is your minimum order quantity?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Do you have NSF certified water treatment grades?">Do you have NSF certified water treatment grades?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Which grade for edible oil refining?">Which grade for edible oil refining?</div>
                <div class="ai-ticker-chip" onclick="aiAsk(this.dataset.q)" data-q="Can you supply activated carbon for gas masks?">Can you supply activated carbon for gas masks?</div>
            </div>
        </div>

        <div class="ai-chips" id="aiQuickChips">
            <button class="ai-chip" onclick="aiAsk('Do you ship to Africa?')">Do you ship to Africa?</button>
            <button class="ai-chip" onclick="aiAsk('Do you do acid washing?')">Acid washing available?</button>
            <button class="ai-chip" onclick="aiAsk('What is the hardness of your gold grade carbon?')">Hardness of gold grade?</button>
            <button class="ai-chip" onclick="aiAsk('What certifications do you hold?')">Your certifications?</button>
            <button class="ai-chip" onclick="aiAsk('What is your minimum order quantity?')">Minimum order?</button>
        </div>

        <div class="ai-input-wrap">
            <div class="ai-input-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </div>
            <input type="text" id="ai-input" placeholder="Ask about grades, shipping, certifications, applications…" autocomplete="off" />
            <button class="ai-send-btn" id="ai-send-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                Ask →
            </button>
        </div>

        <div class="ai-response-wrap" id="aiResponseWrap">
            <div class="ai-response-header">
                <div class="ai-response-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <div>
                    <div class="ai-response-name">Carbon AI — Rajindra Carbons</div>
                    <div class="ai-response-status" id="aiStatus">Thinking…</div>
                </div>
            </div>
            <div class="ai-response-body" id="aiResponseBody">
                <div class="ai-typing"><span></span><span></span><span></span></div>
            </div>
            <div class="ai-response-footer" id="aiResponseFooter" style="display:none">
                <div class="ai-response-cta-row">
                    <a class="ai-response-cta ai-response-cta-primary" href="<?php echo esc_url($tds_url); ?>">Request TDS →</a>
                    <a class="ai-response-cta ai-response-cta-outline" href="<?php echo esc_url($contact_url); ?>">Contact us</a>
                </div>
                <span class="ai-disclaimer">AI-generated · Verify specs with official TDS</span>
            </div>
        </div>

    </div>
</section>
