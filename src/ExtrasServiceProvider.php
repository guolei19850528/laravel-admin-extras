<?php

namespace Guolei\Extras;

use App\Admin\Extensions\Show\Filed\ExtraDateFormatterImpl;
use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Guolei\Extras\Extras;
use Guolei\Extras\Form\Filed\ExtraCheckboxButtonImpl;
use Guolei\Extras\Form\Filed\ExtraRadioButtonImpl;
use Guolei\Extras\Form\Filed\ExtraJsonEditorImpl;
use Illuminate\Support\ServiceProvider;

class ExtrasServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Extras $extension)
    {
        if (!Extras::boot()) {
            return;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'extra-json-editor');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-extras')],
                'laravel-admin-extras'
            );
        }
        Admin::booting(function () {
            Form::extend('extraRadioButton', ExtraRadioButtonImpl::class);
            Form::extend('extraCheckButton', ExtraCheckboxButtonImpl::class);
            Show::extend('extraDateFormatter', ExtraDateFormatterImpl::class);
            Form::extend('extraJsonEditor', ExtraJsonEditorImpl::class);
        });
    }
}
