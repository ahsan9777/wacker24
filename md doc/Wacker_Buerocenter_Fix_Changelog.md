# Wacker Bürocenter (`wacker-buerocenter.de`) — Mobile & Security Audit Fix Change Log

**Project:** Wacker Bürocenter (`https://www.wacker-buerocenter.de/`)  
**Testing Tool:** [HubSpot Website Grader](https://website.grader.com/tests/wacker-buerocenter.de) (powered by Google Lighthouse)  
**Date:** 2026-11-05  

---

## 1. Summary of Audit Status

| Category | Check | Initial Status | Current / Verified Status |
| :--- | :--- | :--- | :--- |
| **Mobile** | Legible Font Size (`usesLegibleFontSizes` / `font-size`) | PASS | **PASS** (`10/10`) |
| **Mobile** | Tap Targets (`areTapTargetsProperlySized` / `tap-targets`) | **FAIL** | **PASS** (`0` undersized/overlapping tap targets; `100%` `>= 48x48px`) |
| **Mobile** | Responsive (`isSizedForViewport` / `content-width`) | PASS | **PASS** (`scrollWidth === clientWidth` across `320px`, `360px`, `375px`, `390px`, `412px`, `430px`) |
| **Security** | HTTPS (`usesHttps`) | PASS | **PASS** (`10/10`) |
| **Security** | Secure JS Libraries (`hasNoVulnerableLibraries` / `no-vulnerable-libraries`) | **FAIL** | **PASS** (Upgraded jQuery `2.2.0 -> 3.7.1` & jQuery UI `1.10.3 / 1.12.0 -> 1.13.3`) |

---

## 2. Backup Inventory

Before any project files were modified, full backups of all original files were saved to `md doc/backups_05112026/`:
- `md doc/backups_05112026/includes/html_header.php`
- `md doc/backups_05112026/includes/bottom_js.php`
- `md doc/backups_05112026/css/responsive.css`
- `md doc/backups_05112026/css/responsive.min.css`
- `md doc/backups_05112026/css/styles.css`
- `md doc/backups_05112026/css/styles.min.css`
- `md doc/backups_05112026/login.php`
- `md doc/backups_05112026/forgot_password.php`
- `md doc/backups_05112026/registration.php`
- `md doc/backups_05112026/registration_as_gast.php`
- `md doc/backups_05112026/return_request.php`

---

## 3. Root Cause Analysis

### Issue 1: Security — Secure JavaScript Libraries (`FAIL`)
1. **jQuery `v2.2.0`**:
   - Loaded in `includes/html_header.php` (`https://cdn.jsdelivr.net/npm/jquery@2.2.0/dist/jquery.min.js`) and locally (`js/jquery-2.2.0.min.js`) in standalone pages (`login.php`, `forgot_password.php`, `registration.php`, `registration_as_gast.php`, `return_request.php`).
   - Flagged by Lighthouse / Snyk / Website Grader for known vulnerabilities:
     - `CVE-2015-9251` (XSS in cross-domain Ajax requests in jQuery `< 3.0.0`)
     - `CVE-2019-11358` (Prototype pollution in `jQuery.extend` in jQuery `< 3.4.0`)
     - `CVE-2020-11022` & `CVE-2020-11023` (XSS in `jQuery.htmlPrefilter` in jQuery `< 3.5.0`)
2. **jQuery UI `v1.10.3` & `v1.12.0` (Duplicate Load)**:
   - Loaded twice in `includes/bottom_js.php`:
     - Line 252 loaded `./backend/assets/js/jquery-ui-1.10.3.custom.min.js` (**v1.10.3**)
     - Lines 13–25 injected `js/jquery-ui.min.js` (**v1.12.0**) after a 10-second `setTimeout`.
   - Flagged by Lighthouse / Snyk / Website Grader for known vulnerabilities in jQuery UI `< 1.13.2`:
     - `CVE-2016-7103` (XSS in Dialog `closeText` in jQuery UI `< 1.12.0`)
     - `CVE-2021-41182`, `CVE-2021-41183`, `CVE-2021-41184` (XSS in Datepicker `*Text` / `altField` and `.position({ of })` in jQuery UI `< 1.13.0`)
     - `CVE-2022-31160` (XSS in Checkboxradio widget refresh in jQuery UI `< 1.13.2`)

### Issue 2: Mobile — Tap Targets (`FAIL`)
- **How Website Grader / Lighthouse Evaluates Tap Targets:**
  Lighthouse's `tap-targets` audit inspects all visible interactive elements (`button, a, input, textarea, select, option, [role=button], ...`) on a mobile viewport (`412x915` / `360x640`). Any interactive target smaller than `48x48px` is tested with a `48x48px` touch bounding box centered on the element; if that box overlaps an adjacent target (`overlappingTargetScore / tapTargetScore >= 0.25`), both targets fail.
- **Root Causes Identified in Baseline Audit (70 small targets `< 48x48px`):**
  1. **Header Top Controls (`#header_section .header_top`):**
     - Customer toggle switch `.gerenric_switch .switch` (`46x21px`), top utility links `"Über Wacker 24"` (`97.7x35px`) and `"Kontakt"` (`58.7x35px`, `10px` gap), and language selector `"Germany"` (`69.5x15px`).
  2. **Header Bottom Controls (`#header_section .header_bottom`):**
     - `#logo a` (`52.2x7.8px`), `a.header_tint_toner_logo` (`78.2x39px`, `0px` gap to `#logo a`), `TERMINBUCHUNG` (`78.9x38px`, `5px` gap), Account `.hdr_icon` (`19.9x39px`) + `.hdr_text a` (`47.3x39px`, `0px` gap), and Cart `.hdr_icon.side_cart_click` (`20.4x26px`) + `a#cart_click_href` (`70.1x26px`, `0px` gap).
     - Furthermore, forcing all 5 header bottom items onto a single nowrap row caused horizontal overflow at `320px` (`scrollWidth: 345px > 320px`) and crushed `#logo` to `52px` width.
  3. **Search Bar Controls (`#frm_search`):**
     - `select#level_one` (`63x40px`), `input#search_keyword` (`274.1x40px`), and `button.search_icon` (`44.9x40px`) had `40px` height (`< 48px`) and `0px` separation.
  4. **Category Navigation Bar (`#navigation_section`):**
     - `li.all_menu a` (`"☰ Alle"`, `39x19px`) was positioned `position: absolute; top: 49px;` where it collided with `"Privatkunde"` at `320px`.
     - Category links `#navigation_section > ul > li > a` (`height: 39px`, `0px` gap) wrapped `"Angebote & Aktionen"` directly underneath `"Bürobedarf"` with `0px` vertical gap.
  5. **Banner & Product Slider Arrows (`button.slick-prev`, `button.slick-next`):**
     - Banner arrows were `35x35px` (`top: 120px`, overlapping the `SALE` card below), and product slider arrows were `30x30px` (`0px` gap to product cards).
  6. **Content Links & Footer Navigation (`#footer_section`):**
     - Inline `<a>` wrappers around buttons/logos (`.gerenric_show_All a`, `.pd_detail h5 a`, `.hm_register a`, `.hm_register p a`, `.brand_btn a`, `.footer_logo a`) had `16px–38px` line-box heights.
     - Footer navigation links `#footer_section .footer-col ul li a` (`height: 16px`, `12px` vertical spacing) and social icon links `.social_medial ul li a` (`30x19px`) had vertical center-to-center spacing of `28px` (`< 48px`).

---

## 4. Detailed Log of File Changes

### 4.1. JavaScript Library Security Upgrades

1. **Added `js/jquery-3.7.1.min.js` (`v3.7.1`)**
   - Added latest stable, vulnerability-free jQuery `v3.7.1`.
2. **Added `js/jquery-ui-1.13.3.min.js` & `css/jquery-ui-1.13.3.min.css` (`v1.13.3`)**
   - Added latest stable, vulnerability-free jQuery UI `v1.13.3` script and stylesheet.
3. **Updated `includes/html_header.php`**
   - Replaced `<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.0/dist/jquery.min.js"></script>` with local `<script src="js/jquery-3.7.1.min.js"></script>`.
   - Added a backward-compatible `jQuery.fn.load` / `jQuery.fn.unload` wrapper shim so existing `$(window).load(function() { ... })` calls across the project delegate cleanly to `.on('load', fn)` while preserving AJAX `.load(url)` behavior.
   - Updated stylesheet cache-buster parameter `?dt=05112026`.
4. **Updated `includes/bottom_js.php`**
   - Replaced legacy `<link rel="stylesheet" href="css/jquery-ui.min.css">` and `<link rel="stylesheet" type="text/css" href="./backend/assets/style/jquery-ui-1.10.3.custom.min.css" />` with `<link rel="stylesheet" href="css/jquery-ui-1.13.3.min.css">`.
   - Removed the duplicate delayed `setTimeout(..., 10000)` loader for `js/jquery-ui.min.js` (`v1.12.0`).
   - Replaced `<script src="./backend/assets/js/jquery-ui-1.10.3.custom.min.js"></script>` (`v1.10.3`) with `<script src="js/jquery-ui-1.13.3.min.js"></script>` (`v1.13.3`).
   - Updated `css/responsive.min.css?dt=05112026` cache-buster parameter.
5. **Updated `login.php`, `forgot_password.php`, `registration.php`, `registration_as_gast.php`, `return_request.php`**
   - Replaced `<script src="js/jquery-2.2.0.min.js"></script>` with `<script src="js/jquery-3.7.1.min.js"></script>`.

---

### 4.2. Mobile Tap-Target & Viewport Containment CSS Fixes

1. **Updated `css/responsive.css` & `css/responsive.min.css`**
   - **Scope:** Strictly inside mobile/tablet media queries (`@media screen and (max-width: 1150px) and (min-width: 240px)`, `@media screen and (max-width: 1024px) and (min-width: 240px)`, `@media screen and (max-width: 650px) and (min-width: 240px)`, and `@media screen and (max-width: 359px) and (min-width: 240px)`). Desktop layout (`1366px`, `1920px`) is 100% untouched.
   - **Specific Mobile Rules Applied:**
     - **Header Top (`#header_section .header_top`):**
       - Expanded `.gerenric_switch .switch` touch box to `48x48px` using a transparent flex hit box while rendering the visual `46x21px` pill via `::after` and `15x15px` knob via `::before` so visual appearance is unchanged.
       - Styled `#header_section .header_top ul li a` and `#header_section .header_language .language_select a` with `display: inline-flex; align-items: center; min-height: 48px; min-width: 48px;`.
     - **Header Bottom & Search Bar (`#header_section .header_bottom`):**
       - Enabled `flex-wrap: wrap; row-gap: 6px; padding: 10px 15px 62px;` on `.header_bottom` so `#logo` (`100px` wide) and `a.header_tint_toner_logo` sit cleanly on the top row and `.header_account` (`TERMINBUCHUNG`, `Anmelden Konto & Listen`, `0 Artikel Einkaufswagen`) sits cleanly on the second row without overflowing `320px` viewports.
       - Set `min-height: 48px; min-width: 48px; display: inline-flex; align-items: center;` on `#logo a`, `a.header_tint_toner_logo`, `.header_account ul li > a`, `.hdr_icon`, and `.hdr_text a`.
       - Increased `.header_search` and its child controls (`.header_select_slt`, `.search_input`, `.search_icon`) from `40px` to `48px` height (`min-width: 56px` on select, `48x48px` on submit button).
     - **Navigation & Mobile Submenu (`#navigation_section`):**
       - Configured `#navigation_section > ul` as a single non-wrapping horizontal scrollable flex bar (`flex-wrap: nowrap; width: max-content; scrollbar-width: none;`) with `li.all_menu a` and `#navigation_section > ul > li > a` set to `min-width: 48px; min-height: 48px; margin: 0 4px; padding: 6px 14px;`.
       - Set `.nav_submenu > ul > li > a` (`min-height: 48px`) and `.submenu_close`, `.side_bar_close` (`min-width: 48px; min-height: 48px`).
     - **Banner & Product Sliders (`#banner_section`, `.gerenric_product`):**
       - Set `#banner_section .slick-prev, #banner_section .slick-next` and `.gerenric_product .slick-prev, .gerenric_product .slick-next` to `48x48px` (`top: 50%; transform: translateY(-50%);` on banner arrows so they remain vertically centered inside the mobile banner).
     - **Content Links & Footer (`#footer_section`):**
       - Set `display: inline-flex; align-items: center; min-height: 48px; min-width: 48px;` on `#banner_section .banner_detail .gerenric_btn`, `.gerenric_show_All a`, `.gerenric_product .pd_detail h5 a`, `.hm_register a`, `.hm_register p a`, `.brand_btn a`, and `#footer_section .footer_logo a`.
       - Set `display: inline-flex; align-items: center; min-height: 48px; min-width: 48px; padding: 6px 4px 6px 0;` and `li { margin-bottom: 8px; }` on `#footer_section .footer-col ul li a` and `#footer_section .footer_bottom .social_medial ul li a`.
      - **Anmelden Header Hover Submenu (`.account_nav`) & Banner Overlay Fix (`css/styles.css`, `css/styles.min.css`, `css/responsive.css`, `css/responsive.min.css`):**
        - **Unclipped Submenu over Banner (`#banner_section`):** Removed `#header_section` and `#container` from `overflow-x: hidden !important` on `<= 1024px` and explicitly set `#container, #header_section, #header_section .header_sticky, #header_section .header_bottom { overflow: visible !important; }`. Previously, setting `overflow-x: hidden` on `#header_section` forced `overflow-y: auto` per the CSS Overflow specification, clipping `.account_nav` at the bottom border of `#header_section` (the top edge of `#banner_section`) and creating a secondary vertical scrollbar.
        - **Stacking Context (`z-index`):** Added `position: relative; z-index: 100002` to `#header_section .header_bottom`, `z-index: 999999 !important` to `#header_section`, and raised `.account_nav` from `z-index: 99` to `z-index: 100005 !important` so the **Anmelden** dropdown always renders above `#navigation_section`, `.header_nav_2`, and `#banner_section` (`.banner_detail`, `.slick-prev`, `.slick-next`).
        - **Direct Child Selector Scoping (`>`):** Scoped all `.header_account` mobile tap-target and flex rules to direct children (`#header_section .header_bottom .header_account > ul > li > .hdr_text > a`, `> .hdr_icon`, etc.) so they do not bleed into `.account_nav ul`, `.account_nav li`, or `.account_nav a`.
        - **Submenu Positioning & Link Styling:** Set `top: calc(100% - 2px) !important` on `.account_nav` at `<= 1024px` (preventing overlap with the `"Konto & Listen"` trigger text and eliminating any hover dead-zone), centered `.account_nav` (`left: 50%; right: auto; transform: translateX(-50%);`) at `<= 650px` so it never overflows the left viewport edge, enforced dark text (`color: #000 !important`) on `.account_nav` links and section headings, and disabled the header neon-green hover pseudo-element (`a::after { display: none !important }`) inside `.account_nav`.

---

## 5. Verification Results (`audit.mjs` & `hover_test.mjs`)

- **JavaScript Libraries:** `jQuery v3.7.1`, `jQuery UI v1.13.3` (`0` vulnerable libraries, `0` console errors).
- **Mobile Tap Targets (`412x915` & `360x640`):** `98` visible interactive targets audited, **`0` small or overlapping targets** (`smallTargets: []`).
- **Responsive Viewport Containment (`scrollWidth === clientWidth`):**
  - `320px`: `320 === 320` (`hasHorizontalOverflow: false`)
  - `360px`: `360 === 360` (`hasHorizontalOverflow: false`)
  - `375px`: `375 === 375` (`hasHorizontalOverflow: false`)
  - `390px`: `390 === 390` (`hasHorizontalOverflow: false`)
  - `412px`: `412 === 412` (`hasHorizontalOverflow: false`)
  - `430px`: `430 === 430` (`hasHorizontalOverflow: false`)
  - `1366px`: `1351 === 1351` (`hasHorizontalOverflow: false`)
  - `1920px`: `1905 === 1905` (`hasHorizontalOverflow: false`)
- **Anmelden Hover Submenu (`.account_nav`):** Verified via real mouse hover (`Input.dispatchMouseEvent`) across Desktop (`1920x1080`), Tablet (`1024x768`), and Mobile (`375x667`); all 10 menu rows from `"Anmelden"` down to `"Zahlungsarten"` render completely above `#banner_section` with zero clipping.

---

## Follow-Up Fix #2 — Responsive View of `products.php` (`product.php`), `product_category.php` & `product_detail.php`

### 1. Issues Identified During Responsive Inspection (`1024px`, `650px`, `412px`, `375px`, `320px`)
1. **Giant `50px` Category Heading (`.product_page_heading`) Overflowing on Mobile (`products.php`):**
   - Inline `<style>` in `products.php` set `.product_page_heading { font-size: 50px; padding: 25px; }` with no responsive breakpoint overrides or word-wrapping, causing long German category headings (such as `"Akten- & Prospekthüllen"`) to overflow out of the pink heading box and off the right edge of mobile screens (`320px–650px`).
2. **Giant Solid Red Box (`background-color: red`) & `100px` Top Gap in Mobile Filter Drawer (`products.php` & `product_category.php`):**
   - `css/responsive.css` and `css/responsive.min.css` contained a leftover rule `.product_page .pd_left::after { width: 100%; height: 100%; background-color: red; position: fixed; content: ''; }` that covered the lower half of the mobile `Filter` drawer in bright red whenever opened on viewports `<= 1024px`.
   - Additionally, `<form class="categroy_list sticky">` inside `includes/left_filter.php` inherited `.sticky { position: sticky; top: 100px; }`, pushing the `"Kategorie X"` header `100px` down from the top of the mobile filter drawer.
3. **Product Card `"In den Einkaufswagen"` Button & List View (`.list_class`) Clipping on Mobile (`products.php`):**
   - In 2-column mobile grid view (`<= 650px`, especially at `320px` and `375px`), `.gerenric_btn` inside `.pd_card` had fixed horizontal padding (`25px`) and `white-space: nowrap`, causing the yellow `"In den Einkaufswagen"` button to overflow the right border of product cards.
   - In List View (`.list_porduct.list_class`), `.pd_image` was fixed at `width: 220px`, leaving only `85px` for `.pd_detail` on a `375px` screen and clipping product titles, prices, and buttons.
4. **Top Header Switch Bar Overflow (`left: -3px`) at `320px` & Category Titles on `product_category.php`:**
   - At `320px` viewport width, `#header_section .header_top ul:first-child` (`Privatkunde` + switch + `Geschäftskunde` + tax note) was slightly wider than `320px` (`326px`) without `flex-wrap: wrap`, causing a `-3px` left overflow.
   - Long category titles on `product_category.php` (e.g. `"Hängemappenboxen"`) needed `overflow-wrap: break-word` and responsive font sizing (`15px` at `<= 650px`, `13px` at `<= 380px`).
5. **`product_detail.php` Mobile Gallery & Special-Price Banner (`<= 1024px` & `<= 650px`):**
   - Stacked the main product image (`.large_image`, `width: 100%`) above the horizontal thumbnail strip (`.thum_images`) on mobile (`<= 650px`) with `object-fit: contain` so product photos are never cropped horizontally.
   - Expanded `.special-price-ad-inner` to `max-width: 100%; padding: 12px 15px;` on `<= 1024px`.
6. **Backend / JS Safeguards (`products.php`, `includes/left_filter.php`, `includes/html_header.php`):**
   - Updated the `jQuery.fn.load` shim in `includes/html_header.php` so `$(window).load(fn)` executes immediately if `document.readyState === 'complete'` when registered.
   - Fixed missing `FIND_IN_SET` in `products.php` (line 45) and quoted `lf_group_id = "<?php print($lf_group_id); ?>";` (line 371).
   - Guarded `$_REQUEST['cat_params_one']` in `includes/left_filter.php` (line 57) with `isset()` to prevent PHP warnings when `products.php` is called without `cat_params_one`.

### 2. Files Modified & Backed Up
- Backups saved to `md doc/backups_05112026/`:
  - `products.php.bak`, `product_detail.php.bak`, `left_filter.php.bak`
- Updated files:
  - `products.php`
  - `includes/left_filter.php`
  - `includes/html_header.php`
  - `css/responsive.css`
  - `css/responsive.min.css`

### 3. Verification Results
- Verified via headless Chrome across `1024px`, `650px`, `375px`, and `320px` on `products.php`, `product_category.php`, and `product_detail.php`:
  - `hasOverflow: false` (`scrollWidth === clientWidth`) across all tested viewports (`1024px`, `650px`, `375px`, `320px`).
  - `overflowing: []` (zero overflowing elements, even at `320px`).
  - `Console errors: []` (zero JavaScript console errors).
  - Mobile `Filter` drawer opens cleanly with sticky `"Kategorie X"` header at the top and zero red overlay.
  - Both Grid View (2-column cards) and List View on `products.php` render cleanly without text or button clipping.


---

## Follow-Up Fix #3 — Site Performance Optimization (`wacker-buerocenter.de`)

### 1. Audit Summary (Before vs. After)

| Category | Check | Initial Status | Verified Status (`http://localhost/wacker24/`) |
| :--- | :--- | :--- | :--- |
| **Performance** | **Page Size** (`total-byte-weight` `< 3.0 MB`) | **FAIL** (`15.6 MB`) | **PASS** (`0.60 MB` / `612.33 KB`, `score: 1.00`) |
| **Performance** | **Page Requests** (`network-requests` `< 30`) | **FAIL** (`127` requests) | **PASS** (`27` requests) |
| **Performance** | **Page Speed** (`interactive` / TTI `<= 5.3 s`) | **FAIL** (`16.3 s`, Server TTFB `24.8 s`) | **PASS** (`5.06 s`, Server TTFB `0.70 s – 0.80 s`, `CLS: 0.0013`) |
| **Performance** | **Browser Caching** (`uses-long-cache-ttl`) | PASS | **PASS** (`1 year` cache headers in `.htaccess`) |
| **Performance** | **Minimal Page Redirects** (`redirects`) | PASS | **PASS** (`score: 1.00`) |
| **Performance** | **Image Size** (`image-delivery-insight` / `image-size-responsive`) | **FAIL** | **PASS** (`score: 1.00` on `image-delivery-insight`, `image-size-responsive`, `image-aspect-ratio`, `unsized-images`) |
| **Performance** | **Minified CSS / JS** (`unminified-css` / `unminified-javascript`) | PASS | **PASS** (`score: 1.00`) |
| **SEO** | All SEO Checks (`30/30`) | PASS | **PASS** (`100/100`) |
| **Mobile** | Viewport, Legible Font Sizes, Tap Targets (`30/30`) | PASS | **PASS** (`30/30`) |
| **Security** | HTTPS & Secure JS Libraries (`10/10`) | PASS | **PASS** (`100/100` Best Practices) |

---

### 2. Root Causes & Fixes Applied

1. **Database Query & Server TTFB Optimization (`47.86 s` local / `24.83 s` live -> `0.70 s`):**
   - **Category Cards (`index.php`):** Rewrote the 7 subcategory queries in `hm_section_1` to select 4 random active subcategories from `category` (`WHERE c_inner.parent_id = $row1->group_id AND c_inner.cat_status = '1' AND EXISTS (SELECT 1 FROM category_map AS cm2 WHERE cm2.cat_id_level_two = c_inner.group_id) ORDER BY RAND() LIMIT 0, 4`) and fetch 1 `pg_mime_source_url` per subcategory instead of joining `category_map`, `category`, and `products_gallery` across tens of thousands of rows before `GROUP BY c.group_id`. Reduced all 7 queries combined from **27.80 s to 0.068 s**.
   - **Bestsellers (`index.php`):** Pre-aggregated `order_items` (`GROUP BY supplier_id ORDER BY sales_count DESC LIMIT 0, 12`) in a derived table before joining `products` and `products_bundle_price`, reducing query time from **11.11 s to 0.023 s**.
   - **Schulranzen, Beliebte Produkte & Baumarkt Sliders (`index.php`):** Selected random product rows from `category_map` in a derived table first before joining `products`, `products_bundle_price`, and `products_gallery`, avoiding full-view evaluation of `vu_category_map` (`8.7 s -> 0.4 s`).
   - **URL & Stock Caching (`lib/functions.php`):** Added static memory caching and an optional `$pro_url` parameter to `product_detail_url()` (eliminating ~88 redundant `SELECT ... FROM products` queries per homepage render) and added static + session caching with a 3-second timeout to `getStockData()`.
   - **Output Compression (`includes/php_includes_top.php`):** Enabled `ob_start("ob_gzhandler")`, compressing the HTML transfer size from `~176 KB` to `22.2 KB`.

2. **Image Sizing & Responsive WebP Delivery (`lib/functions.php`, `includes/banner.php`, `includes/navigation.php`, `includes/footer.php`, `index.php`, `images/favicon.ico`):**
   - Added `get_optimized_image($path, $max_width, $quality)` and `get_logo_attr($path, $target_width)` in `lib/functions.php` using PHP GD (`imagewebp`) to generate and cache resized WebP assets (`opt_{width}w_{name}.webp`).
   - Updated `get_image_link($replace, $link, $webp_cache, $quality)` in `lib/functions.php` to optimize local gallery images (`files/products_gallery/...`) to WebP and cache above-the-fold `75x75` category/sale thumbnails as compressed WebP (`files/thumb_cache/`).
   - Converted hero banners (`includes/banner.php`) to responsive `640w`, `800w`, and `1280w` WebP `srcset` (`4–126 KB` instead of `1.3–1.88 MB` PNGs) and pre-generated WebP variants in `files/banners/`.
   - Switched homepage product slider thumbnails in `index.php` from `get_image_link(427, ...)` (`427x427`) to `get_image_link(160, ...)` (`160x160`).
   - Optimized `images/favicon.ico` from `101 KB` down to `441 bytes`.

3. **Page Requests & Lazy Loading (`127` requests -> `27` requests):**
   - Deferred slides `2..N` in `includes/banner.php` (`class="lazy_banner_img" data-src="..."`) until `.banner_slider` transitions (`beforeChange`).
   - Kept the first 2 category blocks (8 thumbnails) eager for above-the-fold rendering and deferred category blocks `3..8` (24 thumbnails), footer payment/social icons, and submenu images via `IntersectionObserver` (`class="js_lazy_img" data-src="..."` in `includes/bottom_js.php`).
   - Deferred below-the-fold Slick sliders and their product/brand images (`class="js_lazy_slider_img" data-src="..."`) in `index.php` using `IntersectionObserver` with pre-Slick CSS flex layout rules.
   - Removed broken `url('./ajax-loader.gif')` and unused `@font-face{font-family:slick}` from `css/slick-theme.min.css` and `css/slick-theme.css`, switched `js/slick.js` to `js/slick.min.js`, and trimmed `js/jquery-ui-1.13.3.min.js` from `255 KB` to `50.6 KB`.
   - Added 1-year `Cache-Control`, `mod_expires`, `mod_deflate`, and static-extension rewrite exclusions in `.htaccess`.
