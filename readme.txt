=== Almost Ready Temporary Page ===
Contributors: josvelasco
Tags: temporary page, coming soon, maintenance mode, blocks, under construction
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple plugin that displays a customizable temporary page to visitors while you work on your site. Fully editable using native WordPress blocks.

== Description ==

Almost Ready Temporary Page is a simple plugin that shows a friendly temporary page to visitors while you work on your WordPress site. Unlike other plugins, it uses **native WordPress core blocks** — no custom builders or proprietary templates required.

**What it does:**

* Creates a simple temporary page automatically when activated
* Shows this page to all website visitors
* Lets you and other logged-in users access the site normally to keep working
* Uses your theme's styling so it looks consistent with your brand
* Completely customizable using WordPress's native block editor

**Key Features:**

* **Native Blocks** - Uses WordPress core blocks, no proprietary builders
* **Automatic Setup** - No complicated configuration needed
* **Smart Detection** - Visitors see the temporary page, logged-in users see the normal site
* **Fully Customizable** - Change text, colors, layout, add images using blocks
* **Settings Page** - Activate/deactivate and assign any page directly from Settings > Almost Ready
* **Rename Freely** - Rename the page title and slug without breaking anything — the plugin tracks it by ID
* **SEO Friendly** - Tells search engines not to index the temporary page
* **Theme Compatible** - Works with any WordPress theme
* **Clean Removal** - When deactivated, everything returns to normal
* **Clean Uninstall** - Optionally delete all plugin data when uninstalling (the page itself is kept)

**Perfect for:**

* Website updates and maintenance
* Launching new websites while still working on them
* Making design changes without visitors seeing work-in-progress
* Temporary downtime periods
* Any time you need privacy while working on your site

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/almost-ready-temporary-page` or install through WordPress admin
2. Activate the plugin through the 'Plugins' screen
3. That's it! The temporary page is now live for visitors
4. To customize the page, go to Pages > All Pages and edit "Almost Ready"
5. To manage settings, go to Settings > Almost Ready

== Frequently Asked Questions ==

= How do I customize the temporary page? =

Go to Pages > All Pages in your WordPress admin and click "Edit" on the "Almost Ready" page. You can change the text, add images, modify colors, and rearrange the layout using the block editor - just like editing any other page.

= Can I preview what visitors see? =

Yes! Either log out of WordPress or open a private/incognito browser window to see exactly what your visitors see.

= Will this affect my website permanently? =

Not at all. When you deactivate the plugin, your website returns to normal immediately. The temporary page becomes hidden (saved as a draft) but isn't deleted, so you can reactivate the plugin anytime.

= Can I still access my admin area? =

Absolutely. You and anyone else who is logged in can access everything normally - the admin area, your regular pages, everything. Only visitors who aren't logged in see the temporary page.

= What about search engines? =

The plugin tells search engines not to index the temporary page, so it won't hurt your SEO. When you deactivate it, your normal site pages will be crawled again.

= Do I need to know any code? =

Nope! Everything works automatically. If you want to customize the page, you can do it through the familiar WordPress page editor without touching any code.

= Can I rename the temporary page? =

Yes! You can rename the page title and slug to anything you like. The plugin tracks the page by its ID, not its slug, so renaming it won't break anything.

= Can I use an existing page instead of the one the plugin created? =

Yes. Go to Settings > Almost Ready and select any page from the dropdown.

= Will the plugin delete my page if I uninstall it? =

No. The temporary page is always kept. If you want the plugin to also remove its data from the database on uninstall, enable "Delete plugin data when uninstalling" in Settings > Almost Ready before deleting the plugin.

= What makes this different from other maintenance plugins? =

Almost Ready Temporary Page is the only plugin that uses native WordPress core blocks for the temporary page. Other plugins use custom builders or proprietary templates. This means your page is lightweight, future-proof, and works exactly like any other WordPress page.

== Screenshots ==

1. The friendly temporary page that visitors see
2. Easy customization through WordPress's native block editor
3. Simple plugin activation - no settings required

== Changelog ==

= 1.1.1 =
* Added "Delete plugin data when uninstalling" option to the Settings page
* Plugin data is removed from the database on uninstall when opted in — the temporary page itself is always kept

= 1.1.0 =
* Added Settings page under Settings > Almost Ready
* Activate/deactivate the temporary page from the Settings page
* Assign any existing page as the temporary page via a dropdown
* Pages are now tracked by ID instead of slug — renaming the page title or slug is fully supported
* Plugin row link switches to Settings when the temporary page is inactive
* Existing installs are migrated automatically

= 1.0.0 =
* Initial release
* Automatic temporary page creation
* Native block editor customization
* SEO-friendly noindex tags
* Smart user detection

== Upgrade Notice ==

= 1.1.1 =
Adds an optional "Delete plugin data when uninstalling" setting. No action needed for existing installs.

= 1.1.0 =
Adds a Settings page with an activate/deactivate toggle and page selector. Pages are now tracked by ID, so you can freely rename the temporary page. Existing installs are migrated automatically — no action needed.

= 1.0.0 =
Initial release of Almost Ready Temporary Page.
