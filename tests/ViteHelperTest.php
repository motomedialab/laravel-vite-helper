<?php
/**
 * @author MotoMediaLab <hello@motomedialab.com>
 * Created at: 10/07/2022
 */

namespace Motomedialab\LaravelViteHelper\Tests;

use Motomedialab\LaravelViteHelper\LaravelViteHelper;
use Illuminate\Routing\UrlGenerator;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class ViteHelperTest extends TestCase
{
    protected function setUp(): void
    {
        app()->instance('url', tap(
            m::mock(UrlGenerator::class),
            fn ($url) => $url
                ->shouldReceive('asset')
                ->andReturnUsing(fn ($value) => "https://example.com{$value}")
        ));
    }

    protected function tearDown(): void
    {
        $this->cleanViteManifest();
        $this->cleanViteHotFile();
        m::close();
    }

    public function testViteHotModuleSingleFileUriResolution()
    {
        $this->makeViteHotFile();

        $result = (new LaravelViteHelper)->resourceUrl('resources/css/app.css');

        $this->assertEquals('http://localhost:3000/resources/css/app.css', $result);
    }

    public function testViteBuildSingleFileManifestResolution()
    {
        $this->makeViteManifest();

        $result = (new LaravelViteHelper)->resourceUrl('resources/css/app.css');

        $this->assertEquals('https://example.com/build/assets/app.versioned.css', $result);
    }

    public function testHelperMethodReturnsResult()
    {
        $this->makeViteManifest();

        $result = vite('resources/css/app.css');

        $this->assertEquals('https://example.com/build/assets/app.versioned.css', $result);
    }

    public function testUnknownBuildFileThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown Vite entrypoint resources/css/unknown.css');

        $this->makeViteManifest();

        $result = vite('resources/css/unknown.css');
    }

    protected function makeViteManifest()
    {
        app()->singleton('path.public', fn () => __DIR__);

        if (! file_exists(public_path('build'))) {
            mkdir(public_path('build'));
        }

        $manifest = json_encode([
            'resources/css/app.css' => [
                'file' => 'assets/app.versioned.css',
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        file_put_contents(public_path('build/manifest.json'), $manifest);
    }

    protected function cleanViteManifest()
    {
        if (file_exists(public_path('build/manifest.json'))) {
            unlink(public_path('build/manifest.json'));
        }

        if (file_exists(public_path('build'))) {
            rmdir(public_path('build'));
        }
    }

    protected function makeViteHotFile()
    {
        app()->singleton('path.public', fn () => __DIR__);

        file_put_contents(public_path('hot'), 'http://localhost:3000');
    }

    protected function cleanViteHotFile()
    {
        if (file_exists(public_path('hot'))) {
            unlink(public_path('hot'));
        }
    }

}