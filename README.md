# LaravelIp2region

[ip2region](https://github.com/lionsoul2014/ip2region) 的Laravel扩展包。


[![Latest Stable Version](http://poser.pugx.org/martialbe/laravel-ip2region/v)](https://packagist.org/packages/martialbe/laravel-ip2region) [![Total Downloads](http://poser.pugx.org/martialbe/laravel-ip2region/downloads)](https://packagist.org/packages/martialbe/laravel-ip2region) [![Latest Unstable Version](http://poser.pugx.org/martialbe/laravel-ip2region/v/unstable)](https://packagist.org/packages/martialbe/laravel-ip2region) [![License](http://poser.pugx.org/martialbe/laravel-ip2region/license)](https://packagist.org/packages/martialbe/laravel-ip2region) [![PHP Version Require](http://poser.pugx.org/martialbe/laravel-ip2region/require/php)](https://packagist.org/packages/martialbe/laravel-ip2region)

---

## 要求

- PHP >= 7.4
- Laravel >=5.8

---

## 安装

```bash
composer require martialbe/laravel-ip2region
```

---

## 开始使用

1. 创建配置文件

```bash
php artisan vendor:publish --provider="Martialbe\LaravelIp2region\ServiceProvider"
```

2. 添加别名

```php
'aliases' => [
    // ...
    "Ip2Region" => Martialbe\LaravelIp2region\Facade::class,
],

```

3. 使用

```php
    // array:5 [
    //     "country" => "中国"
    //     "area" => ""
    //     "state" => "上海"
    //     "city" => "上海市"
    //     "isp" => "电信"
    // ]
    \Ip2Region::ip("218.1.2.3")->toArray();


    // OR
    // 中国|0|上海|上海市|电信
    \Ip2Region::ip("218.1.2.3")->toString();

    // OR
    $region = \Ip2Region::ip("218.1.2.3");
    $region->country;
    $region->area;
    $region->state;
    $region->city;
    $region->isp;

```

4. 更新数据库

```bash
    php artisan ip2region:update
```

## 其他使用方法
详情查看[ip2region for php](https://github.com/lionsoul2014/ip2region/tree/master/binding/php)文档

### 缓存 `VectorIndex` 索引

如果你的 php 母环境支持，可以预先加载 vectorIndex 缓存，然后做成全局变量，每次创建 Searcher 的时候使用全局的 vectorIndex，可以减少一次固定的 IO 操作从而加速查询，减少 io 压力。 
```php
// 1、从 dbPath 加载 VectorIndex 缓存，把下述的 vIndex 变量缓存到内存里面。
$vIndex = \Ip2Region::loadVectorIndexFromFile();

// 2、使用全局的 vIndex 创建带 VectorIndex 缓存的查询对象。
try {
    $searcher = \Ip2Region::setIndex($vIndex);
} catch (Exception $e) {
    printf("failed to create vectorIndex cached searcher with %s\n", $e);
    return;
}

// 3、查询
$region = $searcher->ip('1.2.3.4');
// 备注：并发使用，每个线程或者协程需要创建一个独立的 searcher 对象，但是都共享统一的只读 vectorIndex。
```

### 缓存整个 `xdb` 数据

如果你的 PHP 母环境支持，可以预先加载整个 `xdb` 的数据到内存，这样可以实现完全基于内存的查询，类似之前的 memory search 查询。
```php
// 1、从 dbPath 加载整个 xdb 到内存。
$cBuff = \Ip2Region::loadContentFromFile();

// 2、使用全局的 cBuff 创建带完全基于内存的查询对象。
try {
    $searcher = \Ip2Region::setDbcache($cBuff);
} catch (Exception $e) {
    printf("failed to create buffer cached searcher: %s\n", $e);
    return;
}

// 3、查询
$region = $searcher->ip('1.2.3.4');
// 备注：并发使用，用整个 xdb 缓存创建的 searcher 对象可以安全用于并发。
```

## License

MIT