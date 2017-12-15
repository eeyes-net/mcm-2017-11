# 西安交通大学数学建模官方网站

<http://mcm.xjtu.edu.cn>

## 部署

<https://laravel.com/docs/5.5/installation>

## 说明

产品原型 <http://ssynh3.axshare.com>

## 主要功能

1. 赛事公告
2. 竞赛报名
3. 成员招募

## 部署

```bash
git clone https://github.com/eeyes-net/mcm-2017-11.git
cd mcm-2017-11/
composer install
php artisan migrate
php artisan vendor:publish
php artisan install
npm install
npm run production
```

## 开发与测试

```bash
php artisan ide-helper:meta
php artisan ide-helper:generate
php artisan ide-helper:model
php artisan db:seed
npm run watch
```

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
