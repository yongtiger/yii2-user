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
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yongtiger\user\Module;

/**
 * ProfileSearch represents the model behind the search form about `yongtiger\user\models\Profile`.
 */
class ProfileSearch extends Profile
{
    ///[yii2-user daterangepicker]
    public $created_date_range;
    public $updated_date_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'gender', 'birthyear', 'birthmonth', 'birthday', 'created_at', 'updated_at'], 'integer'],
            [['fullname', 'firstname', 'lastname', 'language', 'avatar', 'link', 'country', 'province', 'city', 'address', 'telephone', 'mobile', 'graduate', 'education', 'company', 'position', 'revenue', 'created_date_range', 'updated_date_range'], 'safe'],  ///[yii2-user:daterangepicker]

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
        
        $attributeLabels['created_date_range'] = Module::t('user', 'Created Date Range');
        $attributeLabels['updated_date_range'] = Module::t('user', 'Updated Date Range');

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
        $query = Profile::find();

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
            'gender' => $this->gender,
            'birthyear' => $this->birthyear,
            'birthmonth' => $this->birthmonth,
            'birthday' => $this->birthday,
            'DATE(FROM_UNIXTIME(created_at))' => $this->created_at, ///[yii2-user:daterangepicker]
            'DATE(FROM_UNIXTIME(updated_at))' => $this->updated_at, ///[yii2-user:daterangepicker]
        ]);

        ///?????
        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'graduate', $this->graduate])
            ->andFilterWhere(['like', 'education', $this->education])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'revenue', $this->revenue]);

        ///[yii2-user:daterangepicker]
        ///`urlencode()` encodes the space as a plus sign “+”, so we use `urldecode()` convert “+” into space
        $this->created_date_range = urldecode($this->created_date_range);
        $this->updated_date_range = urldecode($this->updated_date_range);

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
