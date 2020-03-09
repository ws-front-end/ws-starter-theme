# WS Style theme changelog

> Latest version: 1.0.4

## Changelog:

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
