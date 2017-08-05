<?php

namespace app\modules\lrm\models\search;

use app\modules\lrm\models\InteractionNote;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\lrm\models\Person;

/**
 * PersonSearch represents the model behind the search form about `app\modules\lrm\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'createdAt', 'updatedAt', 'stateId'], 'integer'],
            [['name', 'fullName', 'birthdate', 'description', 'gender'], 'safe'],
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
        $query = Person::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            /**
             * SELECT c.*, p1.*
                FROM customer c
                JOIN purchase p1 ON (c.id = p1.customer_id)
                LEFT OUTER JOIN purchase p2 ON (c.id = p2.customer_id AND
                (p1.date < p2.date OR p1.date = p2.date AND p1.id < p2.id))
                WHERE p2.id IS NULL;

             */
            'query' => $query->leftJoin('interactionNote in1', 'in1.personId = person.id')
            ->join('LEFT OUTER JOIN', 'interactionNote in2', 'in2.personId = person.id AND
             (in1.updatedAt < in2.updatedAt OR in1.updatedAt = in2.updatedAt AND in1.id < in2.id)')
            ->where('in2.id IS NULL'),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => false,
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
            'birthdate' => $this->birthdate,
            'stateId' => $this->stateId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'fullName', $this->fullName])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'gender', $this->gender]);

        return $dataProvider;
    }
}
