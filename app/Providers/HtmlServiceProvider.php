<?php namespace App\Providers;
use App\Components\Html\FormBuilder;

/**
 * HtmlServiceProvider.
 *
 * 自定义模板的 Service Provider
 * @author davin.bao
 * @since 2016/7/15 9:34
 */
class HtmlServiceProvider extends \Collective\Html\HtmlServiceProvider
{

    /**
     * Register the form builder instance.
     */
    protected function registerFormBuilder()
    {

        $this->app->singleton('form', function($app)
        {
            $form = new FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });

    }
}