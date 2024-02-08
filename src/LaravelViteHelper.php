<?php

/**
 * @author MotoMediaLab <hello@motomedialab.com>
 */

namespace Motomedialab\LaravelViteHelper;

use Illuminate\Support\Str;

class LaravelViteHelper
{
    /**
     * Retrieve a single Vite absolute resource URL.
     *
     * @param string|array $resourcePath
     * @param string $buildDirectory
     * @param bool $relative Whether to return a relative path or absolute path
     * @param bool $hotServer Force enable/disable the hot server
     * @return string|array
     *
     * @throws \Exception
     */
    public function resourceUrl($resourcePath, $buildDirectory = 'build', $relative = false, $hotServer = true)
    {
        $arrayable = is_array($resourcePath);
        $resourcePath = $arrayable ? $resourcePath : [$resourcePath];

        if ($hotServer && ($server = $this->hotServer())) {
            $result = array_map(fn ($path) => "$server/$path", $resourcePath);

            return $arrayable ? $result : $result[0];
        }

        $manifest = $this->manifestContents($buildDirectory);

        $result = array_map(
            fn ($path) => $this->resolveResourcePath($path, $manifest, $buildDirectory, $relative),
            $resourcePath
        );

        return $arrayable ? $result : $result[0];
    }

    /**
     * Retrieve our manifest file contents.
     *
     * @param string $buildDirectory
     * @return array
     *
     * @throws \Exception
     */
    protected function manifestContents($buildDirectory = 'build')
    {
        static $manifests = [];

        $manifestPath = public_path($buildDirectory . '/manifest.json');

        if (!isset($manifests[$manifestPath])) {
            if (!is_file($manifestPath)) {
                throw new \Exception("Vite manifest not found at: {$manifestPath}");
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        return $manifests[$manifestPath];
    }

    /**
     * Return the path to the Vite hot-reload server when available.
     *
     * @return string|null
     */
    protected function hotServer(): string|null
    {
        if (is_file(public_path('/hot'))) {
            return rtrim(file_get_contents(public_path('/hot')));
        }

        return null;
    }

    /**
     * Resolve the resource path.
     *
     * @param string $path
     * @param array $manifest
     * @param string $buildDirectory
     * @param bool $relative
     * @return string
     *
     * @throws \Exception
     */
    private function resolveResourcePath($path, $manifest, $buildDirectory, $relative)
    {
        if (!isset($manifest[$path]['file'])) {
            throw new \Exception('Unknown Vite entrypoint ' . $path);
        }

        $path = Str::start($buildDirectory . '/' . $manifest[$path]['file'], '/');

        return $relative ? $path : asset($path);
    }
}
