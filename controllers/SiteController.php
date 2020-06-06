<?php

namespace app\controllers;

use app\models\Import;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;

class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Import::find()->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', [
            'dataProvider'=> $dataProvider,
        ]);
    }

}
