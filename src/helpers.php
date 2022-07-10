<?php
/**
 * @author MotoMediaLab <hello@motomedialab.com>
 * Created at: 10/07/2022
 */

if (! function_exists('vite')) {

    /**
     * Get the path to a Vite asset.
     *
     * @param  string  $resourcePath  The asset entrypoint to load
     * @param  string  $buildDirectory  Vite build directory
     * @return string
     *
     * @throws \Exception
     */
    function vite($resourcePath, $buildDirectory = 'build')
    {
        /** @var Vite $vite */
        $vite = app(\Motomedialab\LaravelViteHelper\LaravelViteHelper::class);

        return $vite->resourceUrl($resourcePath, $buildDirectory);
    }
}