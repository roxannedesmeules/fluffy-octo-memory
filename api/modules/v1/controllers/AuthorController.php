<?php

namespace app\modules\v1\controllers;

use app\modules\v1\components\ControllerEx;
use app\modules\v1\models\user\UserProfileEx;

/**
 * Class AuthorController
 *
 * @package app\modules\v1\controllers
 */
class AuthorController extends ControllerEx
{
    /** todo    add comments */
    public function actionIndex()
    {
        return UserProfileEx::getAuthor();
    }
}
