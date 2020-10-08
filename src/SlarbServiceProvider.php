<?php

declare(strict_types=1);

namespace Lavendar77\Slarb;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class SlarbServiceProvider
 * @package Lavendar77\Slarb
 * 
 * @author Adeyinka M. Adefolurin <folurinyinka@gmail.com>
 * @copyright 2020 Adeyinka M. Adefolurin
 * @link https://github.com/Lavendar77/Slarb Simple Laravel API Response Builder
 */
class SlarbServiceProvider extends ServiceProvider
{
	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Lavendar77\Slarb\SlarbServiceProvider');

        $loader = AliasLoader::getInstance();
        $loader->alias('Slarb', 'Lavendar77\Slarb\Slarb');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
