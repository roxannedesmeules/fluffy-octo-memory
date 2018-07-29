<?php

namespace app\modules\v1\components\parameters;

use app\helpers\ArrayHelperEx;
use yii\rest\Controller;

/**
 * Class Search
 *
 * @package app\modules\v1\admin\components\parameters
 */
class Search extends \yii\base\Behavior
{
    /** @var string $search */
    public $search = null;

    /** @inheritdoc */
    public function events()
    {
        return ArrayHelperEx::merge(parent::events(), [
            Controller::EVENT_BEFORE_ACTION => "beforeAction",
        ]);
    }

    public function beforeAction()
    {
        /** @var \yii\web\Request $request */
        $request = \Yii::$app->request;

        //  get the search input
        $this->search = $request->get("search");
    }

}
