<?php

namespace app\modules\lrm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\lrm\models\Contact;

/**
 * ContactSearch represents the model behind the search form about `app\modules\lrm\models\Contact`.
 */
class ContactSearch extends Contact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'personId', 'sort'], 'integer'],
            [['contact', 'type', 'note'], 'safe'],
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
        $query = Contact::find();

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
            'personId' => $this->personId,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
