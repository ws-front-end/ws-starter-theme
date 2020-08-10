# WS Style theme changelog

> Latest version: 1.0.6

## Changelog:

**1.0.6**

- Fixed in `reset.scss` buttons focus outline styles.
- Updaed `entry-content.scss`
  - Adding `.entry-section` class for components with looks same like entry-content page.
  - Adding `font-size`, `line-height`, `margin` variables
- Added in `ie-alert.php` for links `rel="noreferrer"`
- Added in `header.php` for hamburger button `aria-label="Hamburger Button"`
- Updated `fonts.scss` Roboto url with `font-display: swap;`
- Changed in `variables.scss` `$small-larger` for `$small-landscape`
  - Updated `$small-larger` in `mixins.scss`
- Added in `home.scss` and `content.scss` new modifiers (`home--only, content--only`)
- Included `ws-logo.scss` in `main.scss`
- Added basic styles to `footer.scss` and `ws-logo.scss`

**1.0.5**

- Adding support making custom gutenberg block styles.
  - Updated: `function.php`, `gulpfile.bable.js`, `main.scss`, `admin-main.scss`
- Moved `user-content.scss` to `/base` folder
  - Adding in `user-content.scss` contents
- Added in `fonts.scss` `font-display: swap;` property
- New folder `/blocks` for Gutenber blocks
- Updated in `/vendors` files headings.
- Added in `wpcf7-form.scss` file input style.

**1.0.4**

- `base.scss` - Updated page--shadow default z-index.
- `footer.php` - added back `.page--shadow` div
- `/js` - Adding new structure logic for JS files.
  - `/js/base/hamburger.js` - added for hamburger functionality.
- Adding Internet Explorer alert for not using this shit
  - `footer.php` - adding line for calling in ie-alert part `plate-parts/generic/ie-alert`
    - `plate-parts/generic/ie-alert` - added IE alert php file
  - `main.scss` - added for default call in `ie-alert.scss` from `vendors/ie-alert.scss`
    - `vendors/ie-alert.scss` - added IE alert styles
  - `app.js` - added for default call in `ie-alert.js` from `components/ie-alert.js`
    - `components/ie-alert.js` - added IE alert functionality.
  - `img/svg/ie-logo.scg` - added IE alert image.
- `/components/generic` - added in components folder files:
  - `hamburger.scss`
  - `site-log.scss`
  - `site-nav.scss`
  - `ws-logo.scss`
- `/layout/generic` - removed `navigation.scss`

**1.0.3**

- Adding for our projects stylelint.io for helping avoid errors and enforce conventions in our styles.
- Updated all ws-starter-theme files tab size for 4 to 2.
- Adding `/admin` folder to WordPress admin elements styles:
- Adding inside `/components` and `/layout` folders `/front-page` and `/generic` for better understanding where we are using these components and layouts.
- Adding in `/vendors` `_loader.scss` for element where we need some loader style.

**1.0.2**

- abstracts/animations.scss:
  - Adding element click effect

**1.0.1**

- README.md:

  - Adding README.md file for changelog

- vendors/:

  - `scrollbar.scss` Updated colors variables
  - Deleted: `/hamburgers` and `fancybox.scss`
  - Added `hamburger.scss` what's including hamburger variables

- abstracts/variables.scss:
  - Updated `color-link-hover` and `color-link-visited color`

**1.0.0**

- main.scss:
  - Adding Style Version: 1.0.0
