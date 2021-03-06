<?php ///[Yii2 uesr:preference]

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
use yongtiger\user\Module;

/**
 * This is the model class for table "{{%user_preference}}".
 *
 * @property integer $user_id
 * @property string $locale
 * @property integer $time_zone
 * @property string $datetime_format
 * @property string $date_format
 * @property string $time_format
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Preference extends \yii\db\ActiveRecord
{
    const SCENARIO_DEFAULT = 'default'; ///only for create!!!
    const SCENARIO_UPDATE = 'update';   ///[v0.24.3 (ADD# Preference actionCreate(), SCENARIO_UPDATE)]
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_preference}}';
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
        $scenarios[static::SCENARIO_UPDATE] = array_merge($scenarios[static::SCENARIO_DEFAULT], ['user_id', 'created_at', 'updated_at']);   ///[v0.24.3 (ADD# Preference actionCreate(), SCENARIO_UPDATE)]   
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ///[v0.24.3 (ADD# Preference actionCreate(), SCENARIO_UPDATE)]
            [['user_id', 'created_at', 'updated_at'], 'required', 'on' => [static::SCENARIO_UPDATE]],
            [['user_id', 'created_at', 'updated_at'], 'integer', 'on' => [static::SCENARIO_UPDATE]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id'], 'on' => [static::SCENARIO_UPDATE]],
            
            [['locale', 'time_zone', 'datetime_format', 'date_format', 'time_format'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()   ///?????
    {
        return [
            'user_id' => Module::t('message', 'User ID'),
            'locale' => Module::t('message', 'Locale'),
            'time_zone' => Module::t('message', 'Time Zone'),
            'datetime_format' => Module::t('message', 'Datetime Format'),
            'date_format' => Module::t('message', 'Date Format'),
            'time_format' => Module::t('message', 'Time Format'),
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
