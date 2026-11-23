# SightPress theme template

A starting point for your own SightPress theme. SightPress is a powerful CMS for everyone, designed with blind and
low-vision creators and audiences in mind, built on modern, object-oriented PHP.

The template already passes every check SightPress runs on a theme. It starts as a copy of the default theme's
required templates and stylesheet, with colours that have enough contrast in light and dark mode, with "more contrast"
and in forced colours. You change it from there.

The step-by-step tutorial is in the SightPress documentation: **Create a theme**
(<https://docs.sightpress.org/latest/tutorials/create-a-theme/>).

## Start

Either use this repository as a template on GitHub (**Use this template**), or:

```sh
composer create-project sightpress/theme-template my-theme
cd my-theme
composer check
```

If you used **Use this template**, run `mv tools/theme.gitattributes .gitattributes` and delete
`release-please-config.json`, `.release-please-manifest.json`, `.github/workflows/release-please.yml` and
`.github/rulesets/`, unless you want them. `composer create-project` does this for you.

`composer check` runs the code style, static analysis, the tests (with a 100% coverage gate) and `theme:validate`, the
checks SightPress runs before it applies a theme.

## Make it yours

1. **Package name:** in `composer.json`, set `name` (for example `acme/theme-harbour`), `description`, `homepage` and
   `support`.
2. **Namespace and class:** rename `src/MyTheme.php` and its namespace (`Acme\MyTheme`), and update `autoload`,
   `autoload-dev` and `extra.sightpress.theme` in `composer.json`, and the namespace in `tests/`. Then run
   `composer dump-autoload`.
3. **Id, name and description:** in the class, set `ID` (lower-case words joined by `-`, starting with your vendor
   name, for example `acme-harbour`), the name and description shown in the theme picker, the author, and
   `previewDescription`. That last one describes what the top of your preview page looks like, for people who can't
   see its screenshot. In `theme.json`, set `name` to the same id and `label` to the same name.
4. **Colours and type:** change the tokens in `theme.json`. `composer check` refuses colours without enough contrast and
   names the pair and the ratio.
5. **Templates and styles:** change `templates/` and `assets/`. A template you leave out comes from the default
   theme. A template you add can override one of SightPress's own.
6. Run `composer check` again.

## See it

```sh
composer preview
```

This writes `build/theme-preview/<id>/`. Open `index.html` in any browser; it works offline. It is the same page for
every theme: a top bar, a band with your theme's name and a Download button, one block of every kind, and the footer.
`composer preview -- --screenshot` also takes the screenshots, if Node.js and Playwright's Chromium are installed
(`npx playwright install chromium`). Never commit them: `build/` is ignored, and a theme that ships a screenshot is
refused.

Every pull request gets the same preview as a download, and a comment with the accessibility result. The two
`theme-preview` workflows in `.github/workflows/` do it.

## Install it in a project

In a SightPress project, add your theme as a Composer path repository while you work on it:

```sh
composer config repositories.my-theme '{"type": "path", "url": "../my-theme", "options": {"symlink": true}}'
composer require acme/theme-harbour:@dev
bin/sightpress cache:clear
bin/sightpress theme:validate acme-harbour
bin/sightpress site:theme:apply <site> acme-harbour
```

## Notes for theme authors

- A theme is presentation only: tokens, templates, assets and translations. It can't add routes, services or
  commands; anything with behaviour belongs in a module.
- Keep every page usable by keyboard, with a visible focus, the skip link and the landmarks of the shell, and test at
  320 pixels and 400% zoom. Never show something by colour alone.
- The shell must include `{% include '@seo/head.html.twig' %}` in `<head>`, and the `search-form` partial must stay a
  `<search>` landmark.
- Bundle your fonts and icons. A theme loads nothing from other sites.

## Licence

GNU General Public License v3.0 or later. See [LICENSE](LICENSE). You may license your own theme as you wish if it
contains no code from this template; most of it does, so GPL-3.0-or-later is the simplest choice.
