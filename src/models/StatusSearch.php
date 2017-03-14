<?php ///[Yii2 uesr:status]

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
 * StatusSearch represents the model behind the search form about `yongtiger\user\models\Status`.
 */
class StatusSearch extends Status
{
    ///[yii2-user daterangepicker]
    public $last_login_date_range;
    public $banned_date_range;
    public $created_date_range;
    public $updated_date_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['registration_ip', 'last_login_ip', 'last_login_at', 'banned_at', 'banned_reason', 'last_login_date_range', 'banned_date_range', 'created_date_range', 'updated_date_range'], 'safe'],  ///[yii2-user:daterangepicker]

            [['created_at', 'updated_at'], 'default', 'value' => null], ///[yii2-user:datepicker] @see http://www.yiiframework.com/doc-2.0/yii-jui-datepicker.html
            [['created_at', 'updated_at'], 'date', 'format' => 'yyyy-MM-dd']  ///[yii2-user:datepicker]
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

        $attributeLabels['last_login_date_range'] = Module::t('message', 'Last Login Date Range');
        $attributeLabels['banned_date_range'] = Module::t('message', 'Banned Date Range');
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
        $query = Status::find();

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

        ///?????
        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'registration_ip' => $this->registration_ip,
            'last_login_ip' => $this->last_login_ip,
            'DATE(FROM_UNIXTIME(last_login_at))' => $this->last_login_at, ///[yii2-user:daterangepicker]
            'DATE(FROM_UNIXTIME(banned_at))' => $this->banned_at, ///[yii2-user:daterangepicker]
            'DATE(FROM_UNIXTIME(created_at))' => $this->created_at, ///[yii2-user:daterangepicker]
            'DATE(FROM_UNIXTIME(updated_at))' => $this->updated_at, ///[yii2-user:daterangepicker]
        ]);

        $query->andFilterWhere(['like', 'banned_reason', $this->banned_reason]);

        ///[yii2-user:daterangepicker]
        ///`urlencode()` encodes the space as a plus sign “+”, so we use `urldecode()` convert “+” into space
        $this->last_login_date_range = urldecode($this->last_login_date_range);
        $this->banned_date_range = urldecode($this->banned_date_range);
        $this->created_date_range = urldecode($this->created_date_range);
        $this->updated_date_range = urldecode($this->updated_date_range);

        if($this->last_login_date_range) {
            list($last_login_from_date, $last_login_to_date) = explode(' - ', $this->last_login_date_range);
            $query->andFilterWhere(['between', 'last_login_at', strtotime($last_login_from_date), strtotime($last_login_to_date)]);
        }

        if($this->banned_date_range) {
            list($banned_from_date, $banned_to_date) = explode(' - ', $this->banned_date_range);
            $query->andFilterWhere(['between', 'banned_at', strtotime($banned_from_date), strtotime($banned_to_date)]);
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
