# Tristate Healthcare System — WordPress Theme

Custom WordPress theme for [tristatehs.com](https://tristatehs.com), hand-built from the Figma design with GSAP scroll animations. It replaces the Elementor-built homepage while keeping every existing Elementor page working untouched.

![Theme screenshot](tristate-theme/screenshot.jpg)

## Features

- **Custom homepage:** hero slider, services grid, mission, stats, testimonials, news and an appointment form, all built to the Figma design.
- **Animations:** GSAP and ScrollTrigger reveals, parallax, counters and scrubbed text. Visitors who have turned on the reduced-motion setting see no animation.
- **Appointment booking:** requests are saved privately under **Appointments** in WP Admin, and each one also sends an email notification. The form is protected against spam and forged submissions (nonce and honeypot).
- **Brochures and flyers:** documents managed under **Downloads** (with file, cover image and type) appear in the site footer.
- **Blog:** post archive, single posts, categories, search, comments and a 404 page, all in the same design.
- **Elementor compatibility:** pages built with Elementor render inside the new header and footer without changes, so the site can move over one page at a time.
- **One-click updates:** new versions published as GitHub Releases show up as **Update now** in Appearance → Themes.

## Requirements

| | Minimum |
|---|---|
| WordPress | 6.4 |
| PHP | 7.4 |
| Node.js (development only) | 20 |
| GitHub CLI (releases only) | 2.x, authenticated with `gh auth login` |

## Local development

The development site runs on [WordPress Playground](https://wordpress.github.io/wordpress-playground/), so you don't need PHP, MySQL or a local server.

```bash
npm install
npm run dev
```

Open http://127.0.0.1:9400. You are logged in as an administrator, the theme is active, Elementor is installed and demo content is seeded. The theme folder is mounted live, so file changes appear on refresh.

## Project structure

```
tristate-theme/            The theme (this folder is what gets zipped and installed)
├── assets/css/            One stylesheet per section; tokens.css holds colours, type and spacing
├── assets/js/             Animations, header, hero slider, testimonials, appointment form
├── assets/images/
├── inc/
│   ├── appointments.php   Appointment post type, form handler, email notification
│   ├── downloads.php      Brochure/flyer post type and media picker
│   └── updater.php        GitHub Releases update checker
├── template-parts/        Homepage sections, page hero, post card, footer downloads
├── functions.php          Setup, asset loading, menus, contact details
└── style.css              Theme header, including the Version used for updates
dev/
├── make-zip.js            Builds tristate-theme.zip
├── release.js             Publishes a GitHub release
└── seed.php               Demo content for the local site
blueprint.json             Playground setup for the local site
```

## Configuration

| What | Where |
|---|---|
| Phone numbers, email, emergency line | `tristate_contact()` in `tristate-theme/functions.php` |
| Primary and footer menus | Appearance → Menus (the **Primary Menu** and **Footer Quick Links** locations) |
| Brochures and flyers | WP Admin → Downloads → Add New |
| Appointment requests | WP Admin → Appointments |
| Update source repository | `TRISTATE_GITHUB_REPO` in `tristate-theme/inc/updater.php` |

Appointment emails are sent through WordPress's `wp_mail()`. Configure an SMTP plugin on the live site so that they reach the inbox reliably.

## Releasing an update

1. Bump `Version:` in `tristate-theme/style.css`, following [semantic versioning](https://semver.org/) (e.g. `1.0.0` → `1.0.1`).
2. Commit the change.
3. Run:

   ```bash
   npm run release
   ```

   The script checks that there are no uncommitted changes and that the version hasn't been released before. It then pushes `main`, builds `tristate-theme.zip` and publishes GitHub release `v<version>` with the zip attached.
4. In WP Admin, go to **Dashboard → Updates → Check again**, then click **Update now** on Tristate Healthcare.

The live site checks for new releases every six hours; **Check again** skips that wait.

**Automated alternative:** `.github/workflows/release.yml` does the same build whenever a release is published on GitHub. It checks that the release tag matches the version in `style.css`, then attaches the zip.

## First-time installation

Sites without the updater (anything older than v1.0.0) need one manual install:

1. Download `tristate-theme.zip` from the [latest release](https://github.com/Fagbayibo/tristate-wp-theme/releases/latest). Don't use GitHub's "Source code" download, because its folder name is wrong for WordPress.
2. In WP Admin, go to **Appearance → Themes → Add Theme → Upload Theme**. Choose the zip, then **Replace current with uploaded**.
3. Clear any page or CDN cache.

Existing pages, posts, menus and media are not affected by installing or updating the theme.

## License

Proprietary. © Tristate Healthcare System. All rights reserved.
