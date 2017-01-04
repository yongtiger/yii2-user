# Yii2-user [v0.3.0] User Basic: mail

Move mailer layout & views into User module.

[![Latest Stable Version](https://poser.pugx.org/yongtiger/yii2-user/v/stable)](https://packagist.org/packages/yongtiger/yii2-user)
[![Total Downloads](https://poser.pugx.org/yongtiger/yii2-user/downloads)](https://packagist.org/packages/yongtiger/yii2-user) 
[![Latest Unstable Version](https://poser.pugx.org/yongtiger/yii2-user/v/unstable)](https://packagist.org/packages/yongtiger/yii2-user)
[![License](https://poser.pugx.org/yongtiger/yii2-user/license)](https://packagist.org/packages/yongtiger/yii2-user)


## Features




## Dependences

* [Yii2](https://github.com/yiisoft/yii2)


## Installation   

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yongtiger/yii2-user "*"
```

or add

```json
"yongtiger/yii2-user": "*"
```

to the require section of your composer.json.


## Configuration

### \common\config\main.php
```php
'mailer' => [
    'class' => 'yii\swiftmailer\Mailer',

    'viewPath' => '@common/mail',

    ///[Yii2 uesr:mail]
    'htmlLayout' =>'@yongtiger/user/mail/layouts/html',
    'textLayout' =>'@yongtiger/user/mail/layouts/text',

    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
    'useFileTransport' => true,
],
```


## Usage in frontend

## Usage in backend


## Documents


## See also



## Todo


## [Development roadmap](docs/development-roadmap.md)


## License 
**Yii2-user** is released under the MIT license, see [LICENSE](https://opensource.org/licenses/MIT) file for details.
