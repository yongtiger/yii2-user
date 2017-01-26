# Development roadmap


## Version 0.5.0 (User Basic: Oauth)

Features of this version:

* oauth login (if no user, try ot signup)

* automatically updating oauth info

* config of `User` module


## Version 0.4.0 (User Basic: Activate via Email)

Features of this version:

* activation via Email


## Version 0.3.5 (User Basic: Ajax validation in Captcha)

Features of this version:

* ajax validation in `Captcha`


## Version 0.3.4 (User Basic: Ajax validation)

Features of this version:

* ajax validation in `signup`, `login`, `requestPasswordResetToken`


## Version 0.3.3 (User Basic: captcha)

Features of this version:

* using `captcha` in `signup`, `login`, `requestPasswordResetToken`, `resetPassword` and backend `login`


## Version 0.3.1 (User Basic: repassword)

Features of this version:

* using `repassword` in `signup`, `resetPassword`


## Version 0.3.0 (User Basic: mail)

Features of this version:

* move `mailer` layout & views into `user` module


## Version 0.2.0 (User Basic: backend)

Features of this version:

* both frontend & backend
* using behaviors of backend module, prohibit controllers of the module (such as `user`) which can be used in both frontend and backend. You can add your own backend module/controllers, otherwise it will not be accessed in the background
* remove out layout in backend login


## Version 0.1.0 (User Basic: Module & i18n)

Features of this version:

* `user` module directly from `Yii2 Advanced Template`
* internationalization with `i18n`
