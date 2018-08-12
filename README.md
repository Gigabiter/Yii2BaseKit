# Yii2BaseKit


Installation
---

Now this package in deep alpha status. And I don't recomend to use composer to install this
package.

You can create directory `kosuhin` in your project and configure
your `composer.json` like follow:
```json
...
"autoload": {
    "psr-4": {
      "kosuhin\\": "kosuhin/"
    }
},
...
```


Services
---
This is a set of logic what I use in my projects on Yii2.
Here is most important thing is service locator what give 
access to services.

To use service locator you must to configure your application like follow:
```php
'bootstrap' => [
      ...
          [
              'class' => SLBootstarper::class,
              'locator' => SL::class
          ],
      ...
 ],
```

Where _SL_ class must be your own class what extended from `kosuhin\Yii2BaseKit\Services\BaseServiceLocator`
class.

After this configuration you will be able to use services like follow:
```php
SL::o()->serviceName->serviceMethod();
// or from yii2
\Yii::$app->get('serviceName');
```

To add new service in service locator just specify new
property in your SL class:
```php
class SL extends BaseServiceLocator
{
    /** @var YourUsefullService */
    public $yourUsefullService = YourUsefullService::class;
}
```

Testing
---
- To run tests:
```shell
php ../../vendor/bin/codecept run
```
- To generate new unit test run command:
```shell
php ../../vendor/bin/codecept g:cest unit FirstCest
```
- To generate new functional test run command:
```shell
php ../../vendor/bin/codecept g:cest functional FirstCest
```