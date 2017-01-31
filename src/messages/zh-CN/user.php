<?php ///[i18n:Long Text Translation]
///such as: 'Sorry, we are unable to reset password ...' => 'Sorry, we are unable to reset password for the provided email address.',

/**
 * Message translations.
 *
 * This file is automatically generated by 'yii message' command.
 * It contains the localizable messages extracted from source code.
 * You may modify this file by translating the extracted messages.
 *
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * Message string can be used with plural forms format. Check i18n section
 * of the guide for details.
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
return [

	/**
	 * Modules
	 */
	///\vendor\yongtiger\yii2-user\src\Module.php
	'This site has been closed registration.' => '本站已经关闭注册。',
	'This site has been closed login.' => '本站已经关闭登陆。',
	//////[Yii2 uesr: verifycode]
	'Verification Code' => '验证码',
	'Click to change another verification code.' => '点击切换验证码',

	/**
	 * Controllers
	 */
	///\vendor\yongtiger\yii2-user\src\controllers\SecurityController.php
	'Login failed! A user bound to this oAuth client was not found.' => '登陆失败！没找到与第三方认证绑定的用户。',
	//////[Yii2 uesr:account oauth]
	'Already connected. No need to connect again.' => '已经绑定该第三方认证，无需再次绑定。',
	'Successfully connect.' => '绑定成功。',
	'Failed connect!' => '绑定失败！',
	'Successfully disconnect.' => '解绑成功。',
	'Failed disconnect!' => '解绑失败！',
	
	///\vendor\yongtiger\yii2-user\src\controllers\RecoveryController.php
	'Check your email for further instructions.' => '请查看您的邮件以获知后续的操作。',
	'Sorry, we are unable to reset password ...' => '对不起，我们不能根据您提供的邮箱地址重置您的密码。',
	'New password saved.' => '新密码已保存。',

	///\vendor\yongtiger\yii2-user\src\controllers\AccountController.php
	//////[Yii2 uesr:account]
	'Invalid action!' => '无效操作！',
	'Verification mail from ' => '邮箱验证邮件 - ',
	'Please check your email [{youremail}] to verify your email.' => '请查收您的邮件 [{youremail}] 以验证您的邮箱。',

	/**
	 * Models
	 */
	///\vendor\yongtiger\yii2-user\src\models\User.php
	'"findIdentityByAccessToken" is not implemented.' => '"findIdentityByAccessToken"没有实现。',

	///\vendor\yongtiger\yii2-user\src\models\SignupForm.php
	'Username' => '用户名',
	'Email' => '邮箱',
	'Password' => '密码',
	'This username has already been taken.' => '用户名已经被占用。',
	'This email address has already been taken.' => '邮箱已经被占用。',
	'The username only contains letters ...' => '用户名由字母、汉字、数字及下划线组成，且不能以数字和下划线开头。',	///[Yii2 uesr:username]
	//////[Yii2 uesr:repassword]
	'Repeat Password' => '再次输入密码',
	'The two passwords do not match.' => '两次输入的密码不一致。',
	//////[Yii2 uesr:activation via email:signup]
	'An activation email will be sent.' => '系统将发送激活邮件到您的邮箱。',
	'Successfully registered.'  => '注册成功。',
	'Resend' => '重新发送',
	'Please check your email [{youremail}] to activate your account.'  => '请查收您的邮件 [{youremail}] 以激活账户。',
	'Activation mail of the registration from ' => '注册激活邮件 - ',

	///\vendor\yongtiger\yii2-user\src\models\LoginForm.php
	'Username or Email' => '用户名或邮箱',
	'Username' => '用户名',
	'Password' => '密码',
	'Remember me' => '记住我',
	'Incorrect username or password.' => '用户名或密码不正确。',
	'Your account is invalid!' => '您的账户无效！',
	'Your account is not activated! Click [{resend}] an activation Email.' => '您的账户没有激活！点击 [{resend}] 激活邮件。',

	///\vendor\yongtiger\yii2-user\src\models\PasswordResetRequestForm.php
	'There is no user with such email.' => '没有用户使用该邮箱。',
	'Password reset for ' => '密码重置 ',

	///\vendor\yongtiger\yii2-user\src\models\ResetPasswordForm.php
	'Password' => '密码',
	'Password reset token cannot be blank.' => '重置密码的令牌不能为空。',
	'Wrong password reset token.' => '重置密码的令牌不正确。',
	//////[Yii2 uesr:repassword]
	'Repeat Password' => '再次输入密码',
	'The two passwords do not match.' => '两次输入的密码不一致。',

	///\vendor\yongtiger\yii2-user\src\models\ActivationForm.php
	//////[Yii2 uesr:activation via email:activation]
	'Activation Key' => '激活码',
	'The activation link is expired!' => '激活链接已经过期！',
	'Your account has been successfully activated ...' => '您的账户已经被成功激活。您可以用注册时填写的用户名和密码进行登陆。',
	'User has not been activated! Please try again.' => '用户没有被激活！请重新试一试。',
	//////[Yii2 uesr:account]
	'Your email has not been verified! Please try again.' => '您的邮箱没有被验证！请重新试一试。',
	'Your email has been successfully activated.' => '您的邮箱已经被验证。',

	///\vendor\yongtiger\yii2-user\src\models\ResendForm.php
	//////[Yii2 uesr:activation via email:resend]
	'Activation mail of the registration from ' => '注册激活邮件 - ',
	'An activation link has been sent to the email address you entered.' => '激活链接已经发送到您注册的邮箱。',
	'Resend activation email failed! Please try again.' => '重新发送激活邮件失败！请重新试一试。',

	///\vendor\yongtiger\yii2-user\src\models\ChangeForm.php
	//////[Yii2 uesr:account]
	'Incorrect password.' => '密码不正确。',

	///\vendor\yongtiger\yii2-user\src\models\ChangeUsernameForm.php
	///\vendor\yongtiger\yii2-user\src\models\ChangeEmailForm.php
	///\vendor\yongtiger\yii2-user\src\models\ChangePasswordForm.php
	//////[Yii2 uesr:account]
	'Successfully changed.' => '修改成功。',

	///\vendor\yongtiger\yii2-user\src\models\ChangePasswordForm.php
	//////[Yii2 uesr:account]
	'New Password' => '新密码',

	/**
	 * Views
	 */
	///\vendor\yongtiger\yii2-user\src\views\security\signup.php
	'Signup' => '注册',
	'Please fill out the following fields:' => '请填写下列内容：',

	///\vendor\yongtiger\yii2-user\src\views\security\login.php
	'Login' => '登陆',
	'If you forgot your password you can [{reset it}].' => "如果您忘记密码，您可以 [{reset it}]。",
	'reset it' => '重置',

	///\vendor\yongtiger\yii2-user\src\views\security\requestPasswordResetToken.php
	'Request password reset' => '请求密码重置',
	'Please fill out your registration email. A link to reset password will be sent there.' => '请填写您注册时的邮箱，系统将发送重置密码的邮件。',
	'Send' => '发送',

	///\vendor\yongtiger\yii2-user\src\views\security\resetPassword.php
	'Reset password' => '重置密码',
	'Please choose your new password:' => '请选择您的新密码：',
	'Save' => '保存',

	///\vendor\yongtiger\yii2-user\src\mail\passwordResetToken-html.php
	///\vendor\yongtiger\yii2-user\src\mail\passwordResetToken-text.php
	'Hello ' => '您好 ',
	'Follow the link below to reset your password:' => '请点击下面的链接重置密码：',

	///\vendor\yongtiger\yii2-user\src\mail\activationKey-html.php
	///\vendor\yongtiger\yii2-user\src\mail\activationKey-text.php
	'Follow the link below to activate your account:' => '请点击下面的链接激活账户：',

	///\vendor\yongtiger\yii2-user\src\views\registration\resend.php
	//////[Yii2 uesr:activation via email:resend]
	'Resend e-mail activation' => '重新发送激活邮件',
	'Please fill out your registration email. A link to activation will be sent there.' => '请填写您注册时的邮箱，系统将发送激活账户的邮件。',
	'Resend' => '重新发送',

	///\vendor\yongtiger\yii2-user\src\views\account\index.php
	//////[Yii2 uesr:account]
	'Account' => '账户信息',
	'Manage your personal account information.' => '管理您的个人账户信息。',
	'Username is not set' => '用户名没有设置',
	'Set' => '设置',
	'Change' => '更改',
	'Email is not set' => '邮箱没有设置',
	'Last verified at:' => '最后验证时间：',
	'Verify email' => '验证邮箱',
	'Last updated at:' => '最后更新时间：',
	'Danger!' => '危险！',
	'Password is not set' => '密码没有设置',
	'Oauth' => '第三方登陆',
	'Setup' => '设置',
	'Connect' => '绑定',
	'Disconnect' => '解绑',

	///\vendor\yongtiger\yii2-user\src\views\account\change.php
	//////[Yii2 uesr:account]
	'You must provide your account password when changing' => '更改时必须提供您的账户密码',

	///\vendor\yongtiger\yii2-user\src\mail\account-verification-email-html.php
	///\vendor\yongtiger\yii2-user\src\mail\account-verification-email-text.php
	//////[Yii2 uesr:account]
	'Follow the link below to verify your email:' => '请点击下面的链接验证您的邮箱：',

];
