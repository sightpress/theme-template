<?php

declare(strict_types=1);

namespace Acme\MyTheme\Tests\Unit;

use Acme\MyTheme\MyTheme;
use Opis\JsonSchema\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SightPress\Theme\Template\CorePartial;
use SightPress\Theme\Template\RequiredTemplates;
use SightPress\Theme\ThemeManifest;
use SightPress\Theme\ThemeSchema;

#[CoversClass(MyTheme::class)]
final class MyThemeTest extends TestCase
{
    public function testItsManifestIsValidAndStable(): void
    {
        $manifest = new MyTheme()->manifest();

        self::assertMatchesRegularExpression(ThemeManifest::ID_PATTERN, $manifest->id);
        self::assertSame(MyTheme::VERSION, $manifest->version);
        self::assertNotNull($manifest->previewDescription, 'describe the look of the preview page for people who can\'t see it');
        self::assertNull($manifest->screenshot, 'a theme ships no screenshot');
        self::assertEquals($manifest, new MyTheme()->manifest());
    }

    public function testThemeJsonMatchesTheSchemaAndTheManifest(): void
    {
        $json = (string) file_get_contents(new MyTheme()->paths()->tokensFile());
        $validator = new Validator();
        $validator->resolver()?->registerFile(ThemeSchema::ID, ThemeSchema::file());

        self::assertTrue($validator->validate(json_decode($json, false, 32, JSON_THROW_ON_ERROR), ThemeSchema::ID)->isValid());
        /** @var array{name: string, version: string} $data */
        $data = json_decode($json, true, 32, JSON_THROW_ON_ERROR);
        self::assertSame(MyTheme::ID, $data['name']);
        self::assertSame(MyTheme::VERSION, $data['version']);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function templates(): iterable
    {
        $files = RequiredTemplates::files();
        foreach (new MyTheme()->manifest()->layouts as $layout) {
            $files[] = RequiredTemplates::forLayout($layout)->file;
        }
        foreach (array_unique($files) as $file) {
            yield $file => [$file];
        }
    }

    #[DataProvider('templates')]
    public function testEveryTemplateTheThemeNeedsIsThere(string $file): void
    {
        self::assertFileExists(new MyTheme()->paths()->templates() . '/' . $file);
    }

    public function testTheShellIncludesCoresHeadPartial(): void
    {
        $master = (string) file_get_contents(new MyTheme()->paths()->templates() . '/' . RequiredTemplates::MASTER);

        self::assertStringContainsString("{% include '" . CorePartial::Head->value . "' %}", $master);
    }
}
