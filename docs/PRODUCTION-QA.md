# Production QA and migration plan

## Safety and migration

This repository contains only the theme source; it does not contain a WordPress database, media library, credentials or a live export. Do not delete or replace any production posts, pages, menus or media during deployment. Activate the theme in a Pantheon Dev/Test environment first, inspect the existing content and map it to the Services, Projects and WordPress Posts structures. Replace demo content through the dashboard only after owner approval and retain backups in Pantheon before any editorial cleanup.

## Runtime QA performed from this environment

On 2026-09-25, an HTTPS header request to the production target returned `403 Forbidden` from the network proxy. Follow-up page probes could not connect (`000`), and there is no local WordPress install or `wp` CLI in this repository. Therefore no runtime assertions were made for pages, the browser console, form delivery, responsive breakpoints, menus, sticky CTA, CSS, JavaScript, images, XML sitemap, robots.txt or PHP warnings.

## Required post-deployment checklist

1. Activate the theme in Pantheon Dev and resave permalinks.
2. Configure contact email, WhatsApp number and service-area wording in **Appearance → Customize → MRK contact details**.
3. Create/verify Home, About, Contact, Privacy Policy, Terms & Conditions, Disclaimer and Affiliate Disclosure pages; add owner-reviewed legal content before linking them publicly.
4. Configure the primary menu and add confirmed nested service pages; do not link unpublished service slugs.
5. Test all named views plus search and 404 at 320, 360, 375, 390, 414, 1366, 1440 and 1920 pixels, including keyboard navigation and reduced-motion behavior.
6. Submit a controlled quote request and verify nonce failure behavior, validation messages and delivered email. Do not test against a real client address.
7. Check `/wp-sitemap.xml`, `/robots.txt`, canonicals, HTTPS URLs, indexability and Search Console after deployment.
8. Validate rendered JSON-LD in Google Rich Results Test / Schema Markup Validator after real content exists.

## Future monetization controls

The theme deliberately contains no ad network code or affiliate links. Add advertisements only through owner-controlled, clearly labelled ad placements after policy review. Add affiliate links only in reviewed, genuinely useful content and publish the Affiliate Disclosure page before they go live.
