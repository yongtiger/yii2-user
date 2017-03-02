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
            'user_id' => Module::t('message', 'User ID'),
            'fullname' => Module::t('message', 'Fullname'),
            'firstname' => Module::t('message', 'Firstname'),
            'lastname' => Module::t('message', 'Lastname'),
            'gender' => Module::t('message', 'Gender'),
            'language' => Module::t('message', 'Language'),
            'avatar' => Module::t('message', 'Avatar'),
            'link' => Module::t('message', 'Link'),
            'birthyear' => Module::t('message', 'Birthyear'),
            'birthmonth' => Module::t('message', 'Birthmonth'),
            'birthday' => Module::t('message', 'Birthday'),
            'country' => Module::t('message', 'Country'),
            'province' => Module::t('message', 'Province'),
            'city' => Module::t('message', 'City'),
            'address' => Module::t('message', 'Address'),
            'telephone' => Module::t('message', 'Telephone'),
            'mobile' => Module::t('message', 'Mobile'),
            'graduate' => Module::t('message', 'Graduate'),
            'education' => Module::t('message', 'Education'),
            'company' => Module::t('message', 'Company'),
            'position' => Module::t('message', 'Position'),
            'revenue' => Module::t('message', 'Revenue'),
            'created_at' => Module::t('message', 'Created At'),
            'updated_at' => Module::t('message', 'Updated At'),
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
