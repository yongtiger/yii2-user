<?php ///[Yii2 uesr:profile]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yongtiger\user\Module;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property integer $user_id
 * @property string $fullname
 * @property string $firstname
 * @property string $lastname
 * @property integer $gender
 * @property string $language
 * @property string $avatar
 * @property string $link
 * @property integer $birthyear
 * @property integer $birthmonth
 * @property integer $birthday
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $address
 * @property string $telephone
 * @property string $mobile
 * @property string $graduate
 * @property string $education
 * @property string $company
 * @property string $position
 * @property string $revenue
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                // 'value' => new \yii\db\Expression('NOW()'), ///if you're using datetime instead of UNIX timestamp
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() ///?????
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'gender', 'birthyear', 'birthmonth', 'birthday', 'created_at', 'updated_at'], 'integer'],
            [['fullname', 'firstname', 'lastname', 'language', 'avatar', 'link', 'country', 'province', 'city', 'address', 'telephone', 'mobile', 'graduate', 'education', 'company', 'position', 'revenue'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()   ///?????
    {
        return [
            'user_id' => Module::t('user', 'User ID'),
            'fullname' => Module::t('user', 'Fullname'),
            'firstname' => Module::t('user', 'Firstname'),
            'lastname' => Module::t('user', 'Lastname'),
            'gender' => Module::t('user', 'Gender'),
            'language' => Module::t('user', 'Language'),
            'avatar' => Module::t('user', 'Avatar'),
            'link' => Module::t('user', 'Link'),
            'birthyear' => Module::t('user', 'Birthyear'),
            'birthmonth' => Module::t('user', 'Birthmonth'),
            'birthday' => Module::t('user', 'Birthday'),
            'country' => Module::t('user', 'Country'),
            'province' => Module::t('user', 'Province'),
            'city' => Module::t('user', 'City'),
            'address' => Module::t('user', 'Address'),
            'telephone' => Module::t('user', 'Telephone'),
            'mobile' => Module::t('user', 'Mobile'),
            'graduate' => Module::t('user', 'Graduate'),
            'education' => Module::t('user', 'Education'),
            'company' => Module::t('user', 'Company'),
            'position' => Module::t('user', 'Position'),
            'revenue' => Module::t('user', 'Revenue'),
            'created_at' => Module::t('user', 'Created At'),
            'updated_at' => Module::t('user', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
