<?php

/**
 * @author MotoMediaLab <hello@motomedialab.com>
 * Created at: 10/07/2022
 */

namespace Motomedialab\LaravelViteHelper;

use Illuminate\Support\Str;

class LaravelViteHelper
{
    /**
     * Retrieve a single Vite absolute resource URL.
     *
     * @param  string  $resourcePath
     * @param  string  $buildDirectory
     * @param  bool    $relative  Whether to return a relative path or absolute path
     * @param  bool    $hotServer  Force enable/disable the hot server
     * @return string
     *
     * @throws \Exception
     */
    public function resourceUrl($resourcePath, $buildDirectory = 'build', $relative = false, $hotServer = true)
    {
        $hotServerUrl = $this->hotServer();

        if ($hotServer && str($hotServerUrl)->isUrl()) {
            return "{$hotServerUrl}/{$resourcePath}";
        }

        $manifest = $this->manifestContents($buildDirectory);

        if (! isset($manifest[$resourcePath]['file'])) {
            throw new \Exception('Unknown Vite entrypoint '.$resourcePath);
        }

        $path = Str::start($buildDirectory . '/' . $manifest[$resourcePath]['file'], '/');
        
        return $relative ? $path : asset($path);
    }

    /**
     * Retrieve our manifest file contents.
     *
     * @param  string  $buildDirectory
     * @return array
     *
     * @throws \Exception
     */
    protected function manifestContents($buildDirectory = 'build')
    {
        static $manifests = [];

        $manifestPath = public_path($buildDirectory.'/manifest.json');

        if (! isset($manifests[$manifestPath])) {
            if (! is_file($manifestPath)) {
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
}
