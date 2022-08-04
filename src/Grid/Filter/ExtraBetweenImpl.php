<?php
namespace Guolei\Extras\Grid\Filter;
use Encore\Admin\Grid\Filter\Between;
use Illuminate\Support\Arr;

class ExtraBetweenImpl extends Between
{
    protected $formatValueCallback=null;

    public function setFormatValueCallback(\Closure $closure)
    {
        $this->formatValueCallback=$closure;
        return $this;
    }

    public function condition($inputs)
    {
        if ($this->ignore) {
            return;
        }

        if (!Arr::has($inputs, $this->column)) {
            return;
        }

        $this->value = Arr::get($inputs, $this->column);

        $value = array_filter($this->value, function ($val) {
            return $val !== '';
        });

        if (empty($value)) {
            return;
        }

        /**
         * 回调
         */
        if ($this->formatValueCallback) {
            $value = call_user_func($this->formatValueCallback, $value);
        }

        if (!isset($value['start'])) {
            return $this->buildCondition($this->column, '<=', $value['end']);
        }

        if (!isset($value['end'])) {
            return $this->buildCondition($this->column, '>=', $value['start']);
        }

        $this->query = 'whereBetween';

        return $this->buildCondition($this->column, $value);
//        return $this->buildCondition($this->column, $this->value);
    }
}