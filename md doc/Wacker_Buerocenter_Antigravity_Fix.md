# Wacker Bürocenter — Antigravity Fix Instructions

**Website:** https://www.wacker-buerocenter.de/

## Objective

Fix only the two failed audit items shown in the supplied screenshots:

1. Mobile — Tap Targets: **FAIL**
2. Security — Secure JavaScript Libraries: **FAIL**

The following checks already pass and must remain passing:

- Mobile legible font size: PASS
- Responsive design: PASS
- HTTPS: PASS

**Do not redesign, rebuild, restyle, or restructure the website.** Preserve the existing Wacker Bürocenter layout, visual identity, content, and functionality. Make the smallest safe changes necessary.

## Non-negotiable constraints

- Do not change the overall page layout, header, navigation arrangement, product grids, product cards, sliders, footer, colors, typography, branding, or content.
- Do not change desktop appearance or responsive breakpoints unnecessarily.
- Do not replace the existing theme or introduce a new CSS framework.
- Do not change business logic, URLs, SEO metadata, search, account, cart, checkout, or product functionality.
- Do not apply broad global CSS rules to all links, buttons, or inputs without checking their impact.
- Do not blindly upgrade every dependency to its newest major version.
- Do not remove any library until its use has been checked across templates, plugins, and scripts.
- Back up files before editing. Keep all changes scoped and reversible.

## Step 1 — Inspect the project before editing

1. Inspect the project structure and identify active templates, shared header/footer, navigation, mobile menu, search, product cards, sliders, and JavaScript loading points.
2. Identify all JavaScript libraries actually loaded by the website. Record each library's name, exact version, source (local file or CDN), loading location, and dependent features.
3. Inspect package manifests, lockfiles, asset manifests, source maps, and build configuration if present. Do not assume these files exist.
4. Search the source code for actual library usage before upgrading or removing anything.
5. Identify the exact small controls responsible for the mobile tap-target failure. Inspect hamburger/menu controls, header utility links, search controls, account/cart icons, category navigation, slider arrows/dots, and product action links.
6. Record baseline desktop and mobile screenshots and test core shopping flows before making changes.

Do not label a library vulnerable based only on its age or filename. Confirm its exact version and check it against an official vendor advisory or a trusted vulnerability database. If the source does not establish the version, report that limitation rather than guessing.

## Step 2 — Fix mobile tap targets only

The audit shows legible font size and responsive design already pass. Do not change font sizes or restructure responsive layouts.

Inspect and, where needed, improve the touch area of:

- Mobile hamburger/menu button
- Header navigation and utility links
- Search button and search controls
- Account/login control
- Shopping cart control
- Category navigation links
- Slider/carousel arrows and pagination controls
- Small product action buttons and links

### Requirements

- Aim for approximately **44 × 44 CSS pixels** for important mobile touch targets where practical.
- Provide enough separation between neighboring controls; around 8 CSS pixels is a useful target where it can be achieved without disrupting the layout.
- Prefer increasing the clickable area with appropriate padding or a carefully scoped hit area rather than enlarging visible icons.
- Preserve current icon size, text size, colors, borders, alignment, and overall appearance.
- Use existing mobile breakpoints. Add a narrow media query only if necessary.
- Do not apply a global minimum width/height to every link or button; this can break inline links, product cards, and desktop layouts.
- Ensure pseudo-element hit areas do not overlap neighboring controls or intercept their taps.
- Preserve semantic links/buttons, keyboard access, and visible focus indicators.

Example only — adapt to verified selectors; do not paste blindly:

```css
@media (max-width: 767px) {
  .verified-mobile-control {
    min-width: 44px;
    min-height: 44px;
  }
}
```

The selector above is a placeholder. Identify the real selectors in the project and scope changes to the controls that need them.

## Step 3 — Fix JavaScript library security

Resolve the failed Secure JavaScript Libraries check by addressing only confirmed outdated or vulnerable dependencies.

1. Identify the exact libraries and versions loaded by the live site.
2. Verify relevant security issues using official release notes, vendor advisories, or a trusted vulnerability database.
3. Update only affected libraries to patched versions compatible with the existing code and plugins.
4. Prefer the smallest compatible update. Do not perform major-version migrations unless required and compatibility has been checked.
5. Remove a dependency only if it is demonstrably unused across all templates, plugins, inline scripts, and dynamically loaded features.
6. For CDN libraries, verify the provider, exact version, HTTPS usage, and integrity protection where supported. Do not change providers without a clear reason.
7. Preserve script dependency order and verify `defer`, `async`, and DOM-ready behavior.
8. If a build process exists, rebuild minified assets from their source files. Avoid risky manual edits to minified bundles when source files are available.
9. If no build process exists, make a controlled update to the actual loaded asset and document it.
10. Do not add a security plugin, framework, or extra dependency merely to make the audit pass.

For every updated dependency, record its old version, new version, reason, security reference, and compatibility tests.

## Step 4 — Regression testing

### Mobile viewports

Test at:

- 320px
- 360px
- 375px
- 390px
- 430px

Verify:

- Menu opens and closes correctly.
- Navigation links can be tapped independently.
- Search input and search submission work.
- Account and cart controls work.
- Category navigation works.
- Product cards and product links work.
- Slider/carousel controls work.
- No horizontal overflow, overlap, clipped text, or broken alignment is introduced.
- Small neighboring controls can be tapped independently.

### Desktop viewports

Test at 1366px and 1920px. Confirm that the original layout and visual appearance remain unchanged and navigation, search, cart, product links, and sliders still work.

### Functional and technical checks

- Check the browser console for JavaScript errors.
- Check the Network panel for failed JS/CSS requests and incorrect MIME types.
- Confirm updated scripts load successfully over HTTPS.
- Test product browsing, search, account entry, add-to-cart, cart navigation, and checkout entry without placing a real order.
- Confirm no PHP errors or server-side warnings are introduced.
- Rerun the same mobile/security audit that produced the screenshots, if available.

## Step 5 — Final report

Provide:

1. Exact list of files changed.
2. Tap-target selectors adjusted and why each was selected.
3. JavaScript libraries detected and their versions before/after.
4. Security advisory references for confirmed vulnerabilities.
5. Mobile and desktop test results.
6. Confirmation that the original layout and functionality were preserved.
7. Any remaining issue that could not be verified from available source files.

Do not report PASS unless the relevant test has actually been run and passed.

## Acceptance criteria

The task is complete only when:

- The mobile Tap Targets check passes, or any remaining failure is identified with evidence.
- The Secure JavaScript Libraries check passes, or the exact remaining dependency/version and reason are documented.
- Legible font size, responsive design, and HTTPS remain passing.
- The existing desktop and mobile layouts remain visually unchanged apart from invisible/near-invisible hit-area adjustments.
- Existing shopping functionality continues to work.
- Only the minimum necessary files and code have been modified.

## Final instruction

**Fix only Mobile Tap Targets and Secure JavaScript Libraries on Wacker Bürocenter. Do not redesign or change the existing website layout.** Inspect the actual source first, identify the precise causes, make minimal targeted changes, test them, and report evidence. If a proposed fix risks changing the layout or breaking functionality, do not apply it silently; explain the risk and choose a safer approach.
