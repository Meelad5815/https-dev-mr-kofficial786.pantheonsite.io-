# MRK Digital & Online Services Center

A lightweight custom WordPress theme for a service-led, content-ready business website. The theme preserves WordPress as the content management system and uses custom post types for **Services** and **Projects**.

## Requirements

- WordPress 6.0+ and PHP 7.4+ (PHP 8.1+ recommended)
- No required third-party plugins
- A working WordPress mail configuration for quote notifications

## Installation

1. Copy `wp-content/themes/mrk-digital` into the target WordPress installation's `wp-content/themes/` directory.
2. Activate **MRK Digital** in **Appearance → Themes**.
3. Visit **Settings → Permalinks** and save once to register the Services and Projects URLs.
4. Create Pages for Home, About, Contact, Privacy Policy, Terms & Conditions and Disclaimer. Set Home as the static front page under **Settings → Reading**.
5. Create a menu in **Appearance → Menus**, assign it to **Primary navigation**, and add Home, Services, Projects, Blog, About and Contact. Add nested Service items when service pages are published.
6. Set the contact email, WhatsApp number (international digits only) and service-area text in **Appearance → Customize → MRK contact details**. No contact information is invented by the theme.

## Content management

- Add only services that MRK can actually deliver under **Services**. Each service can use normal WordPress title, excerpt, featured image and editor fields.
- Add verified portfolio context under **Projects**. Record the problem, solution, technologies and known outcomes; do not imply unverified client results.
- Publish practical articles through **Posts**, using categories, internal links, useful headings and genuine FAQs.
- The quote form uses a nonce, sanitizes submitted values, and sends enquiries to the configured contact email or the WordPress administrator email.

## SEO and analytics

The theme includes semantic templates, title tags, description/Open Graph basics, canonical URLs through WordPress, WordPress sitemap/robots compatibility, responsive images, Article-compatible posts, Organization/WebSite schema, search and 404 templates. Use an SEO plugin only if editorial controls beyond these foundations are needed.

No analytics credentials are included. Add Google Analytics/Search Console only after the owner provides the relevant verified IDs and consent requirements are addressed. The JavaScript includes a small optional `gtag` event hook for WhatsApp clicks; it does nothing until analytics is intentionally installed.

## Development and deployment

Use a local WordPress environment, activate the theme, and test changed templates on mobile and desktop. Validate PHP with `php -l`, then commit only theme source files. Deploy the theme through Pantheon's normal Git workflow; do not overwrite the database or production media to deploy code. Configure mail and contact details in the target environment after deployment.

## Publishing checklist

Before publishing a service, project or article: check the permalink, title, excerpt/meta description, one visible H1, heading hierarchy, featured-image alt text, internal links, CTA destination, mobile layout and factual accuracy. Create the legal pages from WordPress Pages and add owner-reviewed content before adding them to a live footer menu.

## Phase 2 production QA and content planning

See [`docs/PRODUCTION-QA.md`](docs/PRODUCTION-QA.md) for the non-destructive migration plan, runtime verification limits and Pantheon checklist. See [`docs/CONTENT-ROADMAP.md`](docs/CONTENT-ROADMAP.md) for the service catalogue, verified-project standard and staged article clusters. These documents are deliberately editorial plans: no unverified services, project results, contacts, articles or legal claims are seeded automatically.
