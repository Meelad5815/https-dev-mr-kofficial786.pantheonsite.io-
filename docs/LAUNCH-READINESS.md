# Phase 4 launch-readiness checklist

## What this repository verifies

The theme includes all referenced template files, a valid WordPress theme header, protected content-draft tools, secure quote submission handling, draft-only content creation, and core WordPress sitemap/robots compatibility. It does not include a database or live WordPress runtime, so activation, delivery, indexing and browser behavior require verification in Pantheon Dev/Test.

## Safe activation sequence

1. Back up through Pantheon and inspect existing pages, posts, media and menus. Do not delete or overwrite them.
2. Deploy the theme to Pantheon Dev, activate it, and save **Settings → Permalinks** once.
3. Configure **Appearance → Customize → MRK contact details**: email, WhatsApp international digits, service-area wording, and optional public address. Empty contact settings intentionally hide their corresponding public CTA/detail.
4. Use **Tools → MRK Content Setup** one action at a time. The Service button creates only missing service drafts. Optional Project, Article and Legal buttons are separate and all create drafts only.
5. Review each item marked **OWNER VERIFICATION REQUIRED**. Confirm availability, technical claims, delivery/support terms, images/alt text, SEO wording and public status before publishing.
6. Add actual navigation only to published pages and services. Do not link to draft content.

## Service-catalogue note

The repository preserves the prior 36-item catalogue: **Custom Software** is under App & Software, while **Software Solutions** is under IT Services. The Phase 4 requested list calls these **Custom Software Development** and places Software Solutions under App & Software; no unverified renaming or reclassification was applied.

## Article and legal-page drafts

The optional article action creates the first five requested educational drafts only: What Is PLC Programming?, PLC vs Arduino: What Is the Difference?, What Is Arduino and How Does It Work?, Automatic Water Tank Controller Using Arduino, and How to Build a Professional Business Website. The legal action creates absent About, Contact, Privacy Policy, Terms & Conditions, Disclaimer and Affiliate Disclosure pages as drafts. Owner/legal review is required before publishing.

## Technical SEO and discovery verification in Pantheon

After publication, check one representative page from each public type for one H1, title, description, canonical URL, Open Graph/Twitter tags, breadcrumb, JSON-LD and meaningful image alt text. Confirm WordPress core `/wp-sitemap.xml` and `/robots.txt` responses, confirm drafts are not publicly reachable, then inspect Google Search Console coverage after verified ownership. Do not install a second sitemap system unless there is a real requirement.

## Analytics configuration

No measurement ID is stored or loaded by this theme. When the owner has a verified Google Analytics measurement ID and consent approach, add the analytics snippet through the approved Pantheon/WordPress configuration or a small, reviewed site integration—not by committing the ID to this repository. The existing JavaScript automatically emits `quote_submit` and `whatsapp_click` only when a global `gtag` function is already present.

## Monetization gate

Do not add AdSense code or affiliate links at launch. First publish owner-reviewed About, Contact, Privacy Policy, Terms, Disclaimer and Affiliate Disclosure pages, plus original useful content. Add only clearly labelled, policy-reviewed advertisements and genuinely relevant disclosed affiliate recommendations later.

## Runtime QA pending

Test mobile, tablet and desktop views; keyboard navigation; menu; hero; cards; quote form; WhatsApp and email visibility; footer; FAQ; 404; search; form delivery; browser console; overflow; and actual image rendering in Pantheon Dev/Test. This has not been executed from the current repository environment.
