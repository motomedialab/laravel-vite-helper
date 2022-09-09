<?php

namespace Motomedialab\LaravelViteHelper\Traits;

use Motomedialab\LaravelViteHelper\LaravelViteHelper;

/**
 * @mixin \PHPUnit\Framework\TestCase;
 */
trait MocksViteHelper
{
    protected $original = null;

    public function withoutViteHelper()
    {
        if (null === $this->original) {
            $this->original = app(LaravelViteHelper::class);
        }

        app()->instance(LaravelViteHelper::class, new class extends LaravelViteHelper {
            public function resourceUrl($resourcePath, $buildDirectory = 'build', $relative = false)
            {
                return '';
            }
        });
    }

    public function withViteHelper()
    {
        if ($this->original) {
            app()->instance(LaravelViteHelper::class, $this->original);
        }
    }
}