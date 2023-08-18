<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Event;

/**
 * EventSearch represents the model behind the search form of `app\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'image_id', 'is_send_notofication', 'category_id', 'user_id', 'place_id'], 'integer'],
            [['name', 'date_start', 'date_end', 'email', 'description', 'organizer', 'age_restrictions', 'phone', 'address', 'location', 'status'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Event::find();

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
            'image_id' => $this->image_id,
            'is_send_notofication' => $this->is_send_notofication,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'place_id' => $this->place_id,
        ]);
        
        $date_start = $this->date_start ? date('Y-m-d', strtotime($this->date_start)) : '';
        $date_end = $this->date_end ? date('Y-m-d', strtotime($this->date_end)) : '';

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'organizer', $this->organizer])
            ->andFilterWhere(['like', 'age_restrictions', $this->age_restrictions])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['>=', 'date_start', $date_start])
            ->andFilterWhere(['<=', 'date_start', $date_end]);

        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }
}
