<?php

namespace Guolei\Extras\Show\Field;

use Encore\Admin\Show\AbstractField;

class ExtraDateFormatterImpl extends AbstractField
{

    public function render($formatter = 'Y-m-d H:i:s',$type='str')
    {
        // TODO: Implement render() method.
        if (!is_null($this->value)) {
            if(strtotime($this->value)){
                return date($formatter, strtotime($this->value));
            }else{
                return date($formatter, $this->value);
            }
        }
        return '';
    }
}
