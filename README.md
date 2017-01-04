# Yii2-user [v0.2.0] User Basic: backend

This version can be used for both frontend & backend.

[![Latest Stable Version](https://poser.pugx.org/yongtiger/yii2-user/v/stable)](https://packagist.org/packages/yongtiger/yii2-user)
[![Total Downloads](https://poser.pugx.org/yongtiger/yii2-user/downloads)](https://packagist.org/packages/yongtiger/yii2-user) 
[![Latest Unstable Version](https://poser.pugx.org/yongtiger/yii2-user/v/unstable)](https://packagist.org/packages/yongtiger/yii2-user)
[![License](https://poser.pugx.org/yongtiger/yii2-user/license)](https://packagist.org/packages/yongtiger/yii2-user)


## Features

* both frontend & backend


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

### \frontend\config\main.php
```php
'modules' => [
    'user' => [
        'class' => 'yongtiger\user\Module',
    ],
    // ...
],
'components' => [
    'user' => [
        'identityClass' => 'yongtiger\user\models\User',
        'enableAutoLogin' => true,
        'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
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
                    'controllers' => ['admin/security'],    ///add your backend controllers!
                    'allow' => true,
                ],
            ],
        ],
    ],
    // ...
],
'components' => [
    'user' => [
        'identityClass' => 'yongtiger\user\models\User',
        'enableAutoLogin' => true,
        'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        'loginUrl' => ['admin/security/login'],
    ],
    // ...
]
```

> Note: Using behaviors of backend module, prohibit controllers of the module (such as user) which can be used in both frontend and backend. 
> You can add your own backend module/controllers, otherwise it will not be accessed in the background.


### Internationalization setup (optional)

All text and messages introduced in this extension are translatable under category: 

```php
'extensions/yongtiger/yii2-user/*'
```

And the default basePath is `'@vendor/yongtiger/yii2-user/src/messages'`.

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
```php
/user
/user/security
/user/security/login
/user/security/logout
/user/registration/signup
/user/recovery/request-password-reset
/user/recovery/reset-password
```

## Usage in backend
```php
/admin
/admin/security
/admin/security/login
/admin/security/logout
```


## Documents


## See also

* [i18n:Long Text Translation](docs/i18n-long-text-translation.md)


## Todo


## [Development roadmap](docs/development-roadmap.md)


## License 
**Yii2-user** is released under the MIT license, see [LICENSE](https://opensource.org/licenses/MIT) file for details.
