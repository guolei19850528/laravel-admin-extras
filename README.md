# laravel-admin-extras
## 针对laravel-admin做的部分扩展
#### 安装
```
composer require guolei19850528/laravel-admin-extras

php artisan vendor:publish --tag=laravel-admin-extras
```
---
#### 使用
```php
$form->radioButton('status', __('text'));
//替换为
$form->extraRadioButton('status', __('状态'));

$form->radioButton('status', __('text'));
//替换为
$form->extraRadioButton('status', __('状态'));
```


