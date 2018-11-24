# 西安交通大学数学建模官方网站

<http://mcm.xjtu.edu.cn>

## 说明

产品原型 <http://ssynh3.axshare.com>

## 主要功能

1. 赛事公告
2. 竞赛报名
3. 成员招募

## 部署

<https://laravel.com/docs/5.5/installation>

由于部分 Composer 包限制，需要 `php >= 7.2`，可以改用低版本的 Composer 包，最低达到 `php 7.0`

### 部署脚本

```bash
git clone https://github.com/eeyes-net/mcm-2017-11.git
cd mcm-2017-11/
composer install
php artisan migrate
php artisan vendor:publish
php artisan storage:link
php artisan install
npm install
npm run production
```

### 重置数据库

重置数据库将 DROP 全部表，并重新执行 Migrate

**请务必对数据库进行备份**

```bash
php artisan migrate:fresh --force
php artisan cache:clear
php artisan mcm:reset-team-number-auto-increment --yes
```

## 开发与测试

```bash
php artisan ide-helper:meta
php artisan ide-helper:generate
php artisan ide-helper:model
php artisan db:seed
npm run watch
```

### 临时使用 CKFinder

在 [CKFinder 官网](https://ckeditor.com/ckeditor-4/download/#ckfinder) 下载 [CKFinder 3 for PHP](https://download.cksource.com/CKFinder/CKFinder%20for%20PHP/3.4.2/ckfinder_php_3.4.2.zip)

然后解压到 `public/dist/ckfinder/`，解压之后修改配置文件 `public/dist/ckfinder/config.php`。

```php
// 验证函数
$config['authentication'] = function () {
    session_start();
    return (bool)$_SESSION['is_admin'];
};
// 文件存放目录
$config['backends'][] = array(
    'baseUrl'      => '/storage/',
);
```

临时使用 DEMO 版即可。

## LICENSE

[MIT License](https://opensource.org/licenses/MIT)

    Copyright (c) 2017 eeyes.net

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.
