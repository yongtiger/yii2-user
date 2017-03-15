# Development roadmap

## v0.19.0 (ADD# User Preference)


## v0.18.6 (ADD# \views\layouts\main.php:frontendLayout)


## v0.18.5 (isAdminEnd)


## v0.18.4 (ADD# DefaultController, frontend user menus)


## v0.18.3 (CHG# controllers:behaviors():rules)


## v0.18.2 (CHG# \models\LoginForm.php:beforeLogin():enableSignupWithEmailActivation)


## v0.18.1 (CHG# \views\account\index.php:Account Security)


## v0.18.0 (user status, count and a lot of typos)


## v0.17.7 (TYPO# i18n)


## v0.17.6 (TYPO# TokenController.php, views/send-token.php:send-token)


## v0.17.5 (fix# site-login)


## v0.17.4 (fix# profile birthday:DatePicker)


## v0.17.3 (profile region widget)


## v0.17.2 (profile birthday:DatePicker)


## v0.17.1 (AccessControl `permission_access_app-backend` of update and verify)


## v0.17.0 (AccessControl of update profile and remove update verify)


## v0.16.1 (i18n:public static function registerTranslation)


## v0.16.0 (i18n and a lot of fixes)


## v0.15.2 (a lot of fixes)


## v0.15.1 (user index:ActionColumn)


## v0.15.0 (verify CRUD)


## v0.14.1 (search:kartik\daterange\DateRangePicker)


## v0.14.0 (user index:ActionColumn)


## v0.13.3 (fix# 'roles' => ['@'])


## v0.13.2 (fix# 'skipOnError' => true, 'targetClass' => User::className())


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
