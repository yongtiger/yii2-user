# Yii2-user [v0.3.4] User Basic: Ajax validation

The most basic `user` module.

[![Latest Stable Version](https://poser.pugx.org/yongtiger/yii2-user/v/stable)](https://packagist.org/packages/yongtiger/yii2-user)
[![Total Downloads](https://poser.pugx.org/yongtiger/yii2-user/downloads)](https://packagist.org/packages/yongtiger/yii2-user) 
[![Latest Unstable Version](https://poser.pugx.org/yongtiger/yii2-user/v/unstable)](https://packagist.org/packages/yongtiger/yii2-user)
[![License](https://poser.pugx.org/yongtiger/yii2-user/license)](https://packagist.org/packages/yongtiger/yii2-user)


## Features
* `User` module directly from Yii2 Advanced Template
* internationalization with i18n
* using `User` module both frontend & backend
* `mailer` layout & views in `user` module
* using `repassword` in signup, resetPassword
* using `captcha` in signup, login, requestPasswordResetToken, resetPassword and backend login
* ajax validation in signup, login, requestPasswordResetToken


## Dependences

* [Yii2 Advanced Template](https://github.com/yiisoft/yii2-app-advanced)


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
'components' => [
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
	// ...
]
```


### \backend\config\main.php
```php
'modules' => [
    'admin' => [
        'class' => 'yongtiger\user\Module',
        'as access' => [
            'class' => yii\filters\AccessControl::className(),
            'rules' => [
                [
                    'controllers' => ['admin/security'],    ///only allow controllers ('admin/security', etc.)
                    'allow' => true,
                ],
            ],
        ],
        'viewPath' => '@yongtiger/user/../demo/admin/views/',   ///use your own backend views (login)
        'layout' => false,  ///remove out layout in backend login
    ],
    // ...
],

'components' => [

    ///[Yii2 uesr]
    'user' => [
        'identityClass' => 'yongtiger\user\models\User',
        'enableAutoLogin' => true,
        'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        'loginUrl' => ['admin/security/login'],	///default is 'site/login'
    ],
    // ...
]
```


### \frontend\config\main.php
```php
'modules' => [
    'user' => [
        'class' => 'yongtiger\user\Module',
    ],
],

'components' => [

    ///[Yii2 uesr]
    'user' => [
        'identityClass' => 'yongtiger\user\models\User',
        'enableAutoLogin' => true,
        'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        'loginUrl' => ['user/security/login'],
    ],
    // ...
]
```

> Note: Using behaviors of backend module, prohibit controllers of the module (such as `user`) which can be used in both frontend and backend. You can add your own backend module/controllers, otherwise it will not be accessed in the background.

> Note: Use your own backend views in practical application!


### Internationalization setup (optional)

All text and messages introduced in this extension are translatable under category:

```
'extensions/yongtiger/yii2-user/*'
```

And the default basePath is '@vendor/yongtiger/yii2-user/src/messages'.

If you want to custumize your own translations, using following application configuration:
```php
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'extensions/yongtiger/yii2-user/*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '<your custumized message path>',    ///custumize your own translations
                    'fileMap' => [
                        'extensions/yongtiger/yii2-user/user' => 'user.php',
                    ],
                ],
                // ...
            ],
        ],
        // ...
    ],
    // ...
];
```


## Usage in frontend

```
/user
/user/security
/user/security/login
/user/security/logout
/user/registration/signup
/user/recovery/request-password-reset
/user/recovery/reset-password
```


## Usage in backend

```
/admin
/admin/security
/admin/security/login
/admin/security/logout
```

## Notes

- CaptchaValidator should be used together with yii\captcha\CaptchaAction. @see (http://www.yiiframework.com/doc-2.0/yii-captcha-captchavalidator.html)


## Documents


## See also

- [i18n:Long Text Translation](docs/i18n-long-text-translation.md)


## Todo


## [Development roadmap](docs/development-roadmap.md)


## License 
**Yii2-user** is released under the MIT license, see [LICENSE](https://opensource.org/licenses/MIT) file for details.
