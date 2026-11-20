<?php

declare(strict_types=1);

namespace Acme\MyTheme\Tests\Integration;

use Acme\MyTheme\MyTheme;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use SightPress\Core\Cli\ThemePreview\PreviewSite;
use SightPress\Core\Theme\Preview\PreviewFolder;
use SightPress\Core\Theme\Preview\PreviewTheme;
use SightPress\Core\Theme\ThemeChecker;

/**
 * SightPress itself, booted with the theme applied: the theme passes every check core runs before it applies a theme,
 * and core renders the preview page with it (a site on SQLite, the sample page with one block of each kind).
 */
#[CoversNothing]
final class PreviewTest extends TestCase
{
    public function testCoreAcceptsTheThemeAndRendersItsPreviewPage(): void
    {
        $check = new ThemeChecker()->check(new MyTheme());
        self::assertSame([], $check->problems);

        $workspace = sys_get_temp_dir() . '/my-theme-test-' . bin2hex(random_bytes(4));
        mkdir($workspace);
        try {
            $files = new PreviewSite($workspace)->render(PreviewTheme::fromDirectory(new MyTheme()->paths()->root), 'en-GB');
        } finally {
            PreviewFolder::remove($workspace);
        }

        $name = htmlspecialchars(new MyTheme()->manifest()->name, ENT_QUOTES | ENT_HTML5);
        self::assertStringContainsString('<h1>' . $name . '</h1>', $files['/']);
        self::assertStringContainsString('aria-label="Download ' . $name . '"', $files['/']);
        self::assertArrayHasKey('/assets/css/site.css', $files);
    }
}
