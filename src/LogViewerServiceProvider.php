<?php

namespace Gouguoyin\LogViewer;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class LogViewerServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/config';
    const ROUTE_PATH  = __DIR__ . '/routes';
    const VIEW_PATH   = __DIR__ . '/resources/views';
    const LANG_PATH   = __DIR__ . '/resources/lang';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();

        /**
         * 加载路由文件
         */
        $this->loadRoutesFrom(self::ROUTE_PATH . '/web.php');

        /**
         * 指定视图路径
         */
        $this->loadViewsFrom(self::VIEW_PATH, 'log-viewer');

        /**
         * 指定语言路径
         */
        $this->loadTranslationsFrom(self::LANG_PATH, 'log-viewer');

        /**
         * 发布配置文件
         */
        $this->publishes([
            self::CONFIG_PATH => config_path(),
        ], 'log-viewer-config');

        /**
         * 发布视图目录
         */
        $this->publishes([
            self::VIEW_PATH => resource_path('views/vendor/log-viewer'),
        ], 'log-viewer-views');

        /**
         * 发布翻译文件
         */
        $this->publishes([
            self::LANG_PATH => resource_path('lang'),
        ], 'log-viewer-lang');

        /**
         * 发布服务提供者
         */
        $this->publishes([
            __DIR__.'/stubs/LogViewerServiceProvider.stub' => app_path('Providers/LogViewerServiceProvider.php'),
        ], 'log-viewer-provider');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /**
         * 合并配置信息
         */
        $this->mergeConfigFrom(
            self::CONFIG_PATH . '/log-viewer.php', 'log-viewer'
        );
    }

    /**
     * 授权检测
     */
    protected function authorization()
    {
        if(!app()->environment('local')){
            $this->gate();
            if (Gate::denies('view-logs')) {
                abort('403');
            }
        }
    }

    protected function gate()
    {
        Gate::define('view-logs', function ($user) {
            return in_array($user->email, [

            ]);
        });

    }

}
