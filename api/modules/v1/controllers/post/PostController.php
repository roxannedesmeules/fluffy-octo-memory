<?php

namespace app\modules\v1\controllers\post;

use app\helpers\ArrayHelperEx;
use app\modules\v1\components\ControllerEx;
use app\modules\v1\components\parameters;
use app\modules\v1\models\post\PostEx;
use yii\data\ArrayDataProvider;

/**
 * Class PostController
 *
 * @package app\modules\v1\controllers
 *
 * @property array  $pagination set from Pagination Parameter
 * @property int    $category   set from Category Parameter
 * @property int    $tag        set from Tag Parameter
 * @property int    $featured   set from Featured Parameter
 * @property string $search     set from Search Parameter
 */
class PostController extends ControllerEx
{
    /** @inheritdoc */
    public function behaviors()
    {
        return ArrayHelperEx::merge(parent::behaviors(),
            [
                "Category"   => parameters\Category::class,
                "Featured"   => parameters\Featured::class,
                "Pagination" => parameters\Pagination::class,
                "Search"     => parameters\Search::class,
                "Tag"        => parameters\Tag::class,
            ]);
    }

    /**
     * @return \yii\data\ArrayDataProvider
     */
    public function actionIndex()
    {
        $filters = [
            "category" => $this->category,
            "tag"      => $this->tag,
            "featured" => $this->featured,
            "search"   => $this->search,
        ];

        $data = [
            "allModels"  => PostEx::getAllWithLanguage($filters),
            "pagination" => $this->pagination,
        ];

        return new ArrayDataProvider($data);
    }

    public function actionView($slug)
    {
        return PostEx::getOneBySlugWithLanguage($slug);
    }
}
