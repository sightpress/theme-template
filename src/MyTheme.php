<?php

declare(strict_types=1);

namespace Acme\MyTheme;

use SightPress\Theme\LayoutDefinition;
use SightPress\Theme\ThemeInterface;
use SightPress\Theme\ThemeManifest;
use SightPress\Theme\ThemePaths;
use SightPress\Theme\ThemeRequirement;

/**
 * My theme for SightPress. Rename the namespace, the class and the values below (see README.md, "Make it yours").
 * Its files (theme.json, templates/, assets/, translations/) are in the package's root folder.
 */
final class MyTheme implements ThemeInterface
{
    /** Lower-case words joined by "-"; must equal "name" in theme.json. Start it with your vendor name. */
    public const string ID = 'my-theme';

    /** The theme's version; keep it equal to "version" in theme.json (release-please does it for you). */
    public const string VERSION = '0.1.2'; // x-release-please-version

    public function manifest(): ThemeManifest
    {
        return new ThemeManifest(
            id: self::ID,
            name: 'My theme',
            description: 'Describe your theme in one or two sentences, for the theme picker.',
            version: self::VERSION,
            requires: new ThemeRequirement('^0.2 || ^0.3'),
            layouts: LayoutDefinition::required(),
            rightToLeft: true,
            author: 'Your name or organisation',
            licence: 'GPL-3.0-or-later',
            previewDescription: 'Describe what the top of your preview page looks like, for people who can\'t see its screenshot: the top bar, the band with the heading and the Download button, the colours.',
        );
    }

    public function paths(): ThemePaths
    {
        return new ThemePaths(dirname(__DIR__));
    }
}
