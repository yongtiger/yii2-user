<?php ///[Yii2 uesr:verify]

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
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yongtiger\user\Module;

/**
 * VerifySearch represents the model behind the search form about `app\models\Verify`.
 */
class VerifySearch extends Verify
{
    ///[yii2-user daterangepicker]
    public $password_verified_date_range;
    public $email_verified_date_range;
    public $created_date_range;
    public $updated_date_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'password_verified_at', 'email_verified_at', 'created_at', 'updated_at'], 'integer'],

            [
                [
                    'user_id', 'password_verified_at', 'email_verified_at',
                    'password_verified_date_range', 'email_verified_date_range', 'created_date_range', 'updated_date_range'  ///[yii2-user:daterangepicker]
                ], 'safe'
            ],

            [['password_verified_date_range', 'email_verified_date_range', 'created_at', 'updated_at'], 'default', 'value' => null], ///[yii2-user:datepicker] @see http://www.yiiframework.com/doc-2.0/yii-jui-datepicker.html
            [['password_verified_at', 'email_verified_at', 'created_at', 'updated_at'], 'date', 'format' => 'yyyy-MM-dd']  ///[yii2-user:datepicker]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        
        $attributeLabels['password_verified_date_range'] = Module::t('message', 'Password Verified Date Range');
        $attributeLabels['email_verified_date_range'] = Module::t('message', 'Email Verified Date Range');
        $attributeLabels['created_date_range'] = Module::t('message', 'Created Date Range');
        $attributeLabels['updated_date_range'] = Module::t('message', 'Updated Date Range');

        return $attributeLabels;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Verify::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'DATE(FROM_UNIXTIME(password_verified_at))' => $this->password_verified_at, ///[yii2-user:daterangepicker]
            'DATE(FROM_UNIXTIME(email_verified_at))' => $this->email_verified_at, ///[yii2-user:daterangepicker]
            'DATE(FROM_UNIXTIME(created_at))' => $this->created_at, ///[yii2-user:daterangepicker]
            'DATE(FROM_UNIXTIME(updated_at))' => $this->updated_at, ///[yii2-user:daterangepicker]
        ]);

        ///[yii2-user:daterangepicker]
        ///`urlencode()` encodes the space as a plus sign “+”, so we use `urldecode()` convert “+” into space
        $this->password_verified_date_range = urldecode($this->password_verified_date_range);
        $this->email_verified_date_range = urldecode($this->email_verified_date_range);
        $this->created_date_range = urldecode($this->created_date_range);
        $this->updated_date_range = urldecode($this->updated_date_range);

        if($this->password_verified_date_range) {
            list($password_verified_from_date, $password_verified_to_date) = explode(' - ', $this->password_verified_date_range);
            $query->andFilterWhere(['between', 'password_verified_at', strtotime($password_verified_from_date), strtotime($password_verified_to_date)]);
        }

        if($this->email_verified_date_range) {
            list($email_verified_from_date, $email_verified_to_date) = explode(' - ', $this->email_verified_date_range);
            $query->andFilterWhere(['between', 'email_verified_at', strtotime($email_verified_from_date), strtotime($email_verified_to_date)]);
        }
        if($this->created_date_range) {
            list($created_from_date, $created_to_date) = explode(' - ', $this->created_date_range);
            $query->andFilterWhere(['between', 'created_at', strtotime($created_from_date), strtotime($created_to_date)]);
        }

        if($this->updated_date_range) {
            list($updated_from_date, $updated_to_date) = explode(' - ', $this->updated_date_range);
            $query->andFilterWhere(['between', 'updated_at', strtotime($updated_from_date), strtotime($updated_to_date)]);
        }
        ///[http://www.brainbook.cc]

        return $dataProvider;
    }
}
