<?php

namespace Fligno\System;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class SystemCheck
{
    public $buildPath = '';

    /**
     * Get status if server is up
     *
     * @SuppressWarnings(PHPMD)
     */
    public function getStatus()
    {
        return [
            "status" => 'OK',
            "timestamp" => date("Y-m-d H:i:s", time()),
            "instance-id" => $_SERVER['HTTP_HOST']
        ];
    }

    /**
     * Get PHP version and dependencies
     *
     * @SuppressWarnings(PHPMD)
     */
    public function getPhpInfo()
    {
        return [
            "timestamp" => date("Y-m-d H:i:s", time()),
            "instance-id" => $_SERVER['HTTP_HOST'],
            'php-version' => 'php' . (float)phpversion(),
            'php-modules' => get_loaded_extensions(),
        ];
    }

    /**
     * Get Git release info
     *
     * @return void
     */
    public function getReleaseInfo()
    {
        $file = getenv('GIT_BUILD_FILE_PATH') ?: $this->buildPath;
        if (! file_exists($file)) {
            return [
                'git-status' => 'UNKNOWN'
            ];
        }
        $data = json_decode(file_get_contents($file), TRUE);
        return $data;
    }

    /**
     * Get Release Info with database migrations
     *
     * @return void
     */
    public function releaseInfo()
    {
        $table = DB::table('migrations');
        $latest = $table->max('batch');
        $migrations = $table->whereBatch($latest)->get()->pluck('migration');
        return array_merge(
            $this->getPhpInfo(),
            [
                'database' => [
                    'schema-version' => '<might-need-to-implement-one>',
                    'last-migrations' => $migrations
                ]
            ],
            $this->getReleaseInfo()
        );
    }

    public function setBuildPath($path = '')
    {
        $this->buildPath = $path;
    }

    public static function routes()
    {
        Route::get('/system/application', function() {
            return (new self)->getStatus();
        });

        Route::get('/system/release', function() {
            return (new self)->releaseInfo();
        });
    }
}

