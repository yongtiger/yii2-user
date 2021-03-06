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
use yii\behaviors\BlameableBehavior;
use yii\helpers\FormatConverter;
use yongtiger\user\Module;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $user_id
 * @property string $fullname
 * @property string $firstname
 * @property string $lastname
 * @property integer $gender
 * @property string $link
 * @property string $birthday
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $district
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
    const SCENARIO_DEFAULT = 'default'; ///only for create!!!
    const SCENARIO_UPDATE = 'update';   ///[v0.24.1 (ADD# SCENARIO_UPDATE)]
    const SCENARIO_AVATAR = 'avatar';   ///[v0.21.0 (ADD# update avatar)]

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
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
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_UPDATE] = array_merge($scenarios[static::SCENARIO_DEFAULT], ['user_id', 'created_at', 'updated_at']);   ///[v0.24.1 (ADD# SCENARIO_UPDATE)]   
        $scenarios[static::SCENARIO_AVATAR] = ['user_id', 'avatar', 'updated_at'];  ///[v0.21.0 (ADD# update avatar)]
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ///[v0.24.1 (ADD# SCENARIO_UPDATE)]
            [['user_id', 'created_at', 'updated_at'], 'required', 'on' => [static::SCENARIO_UPDATE]],
            [['user_id', 'created_at', 'updated_at'], 'integer', 'on' => [static::SCENARIO_UPDATE]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id'], 'on' => [static::SCENARIO_UPDATE]],

            [['firstname', 'lastname', 'link', 'country', 'address', 'telephone', 'mobile', 'graduate', 'education', 'company', 'position', 'revenue'], 'trim'],
            [['province', 'city', 'district', 'gender', 'created_at', 'updated_at'], 'integer'],
            [['fullname', 'firstname', 'lastname', 'avatar', 'country', 'address', 'telephone', 'mobile', 'graduate', 'education', 'company', 'position', 'revenue'], 'string', 'max' => 255],

            ['fullname', 'filter', 'filter' => function ($value) {
                return $this->lastname . ' ' .  $this->firstname;
            }],

            ///[v0.19.6 (FIX# profile:locale date)]
            ///can't deal with locale date e.g. `1970年5月17日星期日`
            // ['birthday', 'date'],   ///[v0.17.2 (profile birthday:DatePicker)]
            ['birthday', 'filter', 'filter' => function ($value) {
                if (empty($value)) return null;
                ///can't deal with locale date e.g. `1970年5月17日星期日`
                // return Yii::$app->formatter->asDate($value, $format);
                // return date_create_from_format($format, $value);
                // setlocale(LC_TIME, 'zho');
                // return strftime("%Y-%m-%d", $value);
                // return strptime($value, $format);    ///Note: This function is not implemented on Windows platforms. @see http://php.net/manual/en/function.strptime.php
                ///so we use below:
                $format = FormatConverter::convertDateIcuToPhp(Yii::$app->formatter->dateFormat);
                $dateParse = date_parse_from_format($format, $value);
                return $dateParse['year'] . '-' . $dateParse['month'] . '-' . $dateParse['day'];
            }],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Module::t('message', 'User ID'),
            'fullname' => Module::t('message', 'Fullname'),
            'firstname' => Module::t('message', 'Firstname'),
            'lastname' => Module::t('message', 'Lastname'),
            'gender' => Module::t('message', 'Gender'),
            'avatar' => Module::t('message', 'Avatar'),
            'link' => Module::t('message', 'Link'),
            'birthday' => Module::t('message', 'Birthday'),
            'country' => Module::t('message', 'Co++9untry'),
            'region' => Module::t('message', 'Region'), ///[v0.17.3 (profile region widget)]
            'province' => Module::t('message', 'Province'),
            'city' => Module::t('message', 'City'),
            'district' => Module::t('message', 'District'), ///[v0.17.3 (profile region widget)]
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
