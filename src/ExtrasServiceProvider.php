<?php

namespace Guolei\Extras;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Guolei\Extras\ExtrasExtension;
use Guolei\Extras\Form\Field\ExtraBelongsToImpl;
use Guolei\Extras\Form\Field\ExtraBelongsToManyImpl;
use Guolei\Extras\Form\Field\ExtraCheckboxButtonImpl;
use Guolei\Extras\Form\Field\ExtraRadioButtonImpl;
use Guolei\Extras\Form\Field\ExtraHasManyImpl;
use Guolei\Extras\Form\Field\ExtraJsonEditorImpl;
use Guolei\Extras\Grid\Filter\ExtraBetweenImpl;
use Guolei\Extras\Show\Field\ExtraDateFormatterImpl;
use Illuminate\Support\ServiceProvider;

class ExtrasServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(ExtrasExtension $extension)
    {
        if (!ExtrasExtension::boot()) {
            return;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-extras');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-extras')],
                'laravel-admin-extras'
            );
        }
        Admin::booting(function () {
            Form::extend('extraRadioButton', ExtraRadioButtonImpl::class);
            Form::extend('extraCheckboxButton', ExtraCheckboxButtonImpl::class);
            Form::extend('extraJsonEditor', ExtraJsonEditorImpl::class);
            Form::extend('extraBelongsTo', ExtraBelongsToImpl::class);
            Form::extend('extraBelongsToMany', ExtraBelongsToManyImpl::class);
            Form::extend('extraHasMany', ExtraHasManyImpl::class);
            Show::extend('extraDateFormatter', ExtraDateFormatterImpl::class);
            Grid\Filter::extend('extraBetween', ExtraBetweenImpl::class);
            Grid\Column::extend('extraDateFormatter', function ($value = null, $formatter = 'Y-m-d H:i:s') {
                if (!is_null($value)) {
                    if (strtotime($value)) {
                        return date($formatter, strtotime($value));
                    } else {
                        return date($formatter, $value);
                    }
                }
                return '';
            });
        });
    }
}
