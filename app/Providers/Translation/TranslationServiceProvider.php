<?php
namespace App\Providers\Translation;

use Illuminate\Translation\TranslationServiceProvider as LaravelTranslationServiceProvider;

/**
 * 使其支持Module单独管理语言包
 *
 * Class TranslationServiceProvider
 * @package App\Providers\Translation
 *
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class TranslationServiceProvider extends LaravelTranslationServiceProvider
{
    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function($app)
        {
            $multiLangPath = __DIR__ . '/../../Modules/Common/Translations';

            return new FileLoader($app['files'], $app['path.lang'], $multiLangPath);
        });
    }
}