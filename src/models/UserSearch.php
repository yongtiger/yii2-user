<?php ///[Yii2 uesr]

/**
 * Yii2 User
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\user\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `yongtiger\user\models\User`.
 */
class UserSearch extends User
{

    public $created_date_range;
    public $updated_date_range;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return
        [
            [
                [
                    'id',
                    'status',
                ], 'integer'
            ],

            [
                [
                    'username',
                    'email',
                    'created_date_range', 'updated_date_range'  ///[yii2-admin:user_daterangepicker]
                ], 'safe'
            ],

            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_SUPER_MODERATOR, self::ROLE_MODERATOR, self::ROLE_USER]],

            [['created_at', 'updated_at'], 'default', 'value' => null],///[yii2-admin-boot_v0.5.15_f0.5.14_user_datepicker] @see http://www.yiiframework.com/doc-2.0/yii-jui-datepicker.html
            [['created_at', 'updated_at'], 'date', 'format' => 'yyyy-MM-dd']  ///[yii2-admin-boot_v0.5.15_f0.5.14_user_datepicker]
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

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
            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
            'DATE(FROM_UNIXTIME(created_at))' => $this->created_at, ///[yii2-admin:user_daterangepicker]
            'DATE(FROM_UNIXTIME(updated_at))' => $this->updated_at, ///[yii2-admin:user_daterangepicker]
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        ///[yii2-admin:user_daterangepicker]
        ///[BUG#1]当在搜索栏输入日期范围进行搜索后，再过滤器选择created_at、update_at，则报错！
        ///urlencode()将空格编码为加号“+”，所以用urldecode()转换“+”为空格
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
