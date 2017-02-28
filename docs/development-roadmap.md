# Development roadmap

## v0.13.1 (user link profile)

* fix# Integrity constraint violation: 1062 Duplicate entry for key 'PRIMARY'

## v0.13.0 (profile CRUD)


## v0.12.2 (verify)


## v0.12.0 (add role methods to user model)


## v0.11.4 (Fix#password in Firefox)


## v0.11.3 (GridView columns headerOptions)


## v0.11.2 (fix:GridView options 'style'=>'overflow:auto')


## v0.11.1 (GridView value)


## v0.11.0 (a lot of fixes)


## v0.10.0 (user CRUD)


## v0.9.10 (fix:user module)


## v0.9.9 (fix:views/login class="site-login")


## v0.9.8 (fix:views \Yii::)


## v0.9.7 (backend:enableRecoveryPassword)


## v0.9.6 (fix:backend disableLoginMessage)


## v0.9.5 (fix:backend disableSignupMessage)


## v0.9.4 (exception 'Indirect modification of overloaded property yongtiger\user\models\User::$verify has no effect')

Features of this version:

* Note: MUST has current user's record in table verify! or get a exception 'Indirect modification of overloaded property yongtiger\user\models\User::$verify has no effect'


## v0.9.3 (get rid of backend demo)

Features of this version:

* change forms (ChangeUsernameForm, ChangeEmailForm, ChangePasswordForm)


## v0.9.2 (User Basic: change forms)

Features of this version:

* change forms (ChangeUsernameForm, ChangeEmailForm, ChangePasswordForm)


## v0.9.1 (User Basic: token sender and handler)

Features of this version:

* token sender and handler


## v0.9.0 (User Basic: token)

Features of this version:

* token handler


## v0.8.1 (User Basic: account oauth)

Features of this version:

* user account oauth


## v0.8.0 (User Basic: account)

Features of this version:

* user account


## v0.7.0 (User Basic: verify)

Features of this version:

* password and email verify


## v0.6.0 (User Basic: login with username or email)

Features of this version:

* login with username or email


## v0.5.0 (User Basic: Oauth)

Features of this version:

* oauth login (if no user, try ot signup)

* automatically updating oauth info

* config of `User` module


## v0.4.0 (User Basic: Activate via Email)

Features of this version:

* activation via Email


## v0.3.5 (User Basic: Ajax validation in Captcha)

Features of this version:

* ajax validation in `Captcha`


## v0.3.4 (User Basic: Ajax validation)

Features of this version:

* ajax validation in `signup`, `login`, `requestPasswordResetToken`


## v0.3.3 (User Basic: captcha)

Features of this version:

* using `captcha` in `signup`, `login`, `requestPasswordResetToken`, `resetPassword` and backend `login`


## v0.3.1 (User Basic: repassword)

Features of this version:

* using `repassword` in `signup`, `resetPassword`


## v0.3.0 (User Basic: mail)

Features of this version:

* move `mailer` layout & views into `user` module


## v0.2.0 (User Basic: backend)

Features of this version:

* both frontend & backend
* using behaviors of backend module, prohibit controllers of the module (such as `user`) which can be used in both frontend and backend. You can add your own backend module/controllers, otherwise it will not be accessed in the background
* remove out layout in backend login


## v0.1.0 (User Basic: Module & i18n)

Features of this version:

* `user` module directly from `Yii2 Advanced Template`
* internationalization with `i18n`
