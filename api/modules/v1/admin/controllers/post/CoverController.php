<?php

namespace app\modules\v1\admin\controllers\post;

use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\post\forms\CoverPicture;
use app\modules\v1\admin\models\post\PostEx;
use app\modules\v1\admin\models\post\PostLangEx;
use yii\web\UploadedFile;

/**
 * Class CoverController
 *
 * @package app\modules\v1\admin\controllers\post
 */
class CoverController extends ControllerAdminEx
{
	/**
	 * @param integer $postId
	 * @param integer $langId
	 *
	 * @return PostEx
	 *
	 * @SWG\Post(
	 *     path    = "/posts/:postid/:langid/cover",
	 *     tags    = { "Posts", "Cover" },
	 *     summary = "Upload a cover for a post",
	 *     description = "Upload a cover picture for a specific post translation",
	 *
	 *     @SWG\Response( response = 201, description = "cover uploaded successfully", @SWG\Schema( ref = "#/definitions/Post" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "file to be uploaded is invalid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while uploading file", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionCreate (int $postId, int $langId)
	{
		$form       = new CoverPicture();
		$form->file = UploadedFile::getInstanceByName("picture");

		//  if it doesn't validate, then throw an error
		if (!$form->validate()) {
			return $this->unprocessableResult($form->getErrors());
		}

		$result = PostLangEx::manageCoverPicture($postId, $langId, $form->file);

		if ($result[ "status" ] === PostLangEx::ERROR) {
			switch ($result[ "error" ]) {
				case PostLangEx::ERR_NOT_FOUND :
					return $this->error(401, $result[ "error" ]);

				case PostLangEx::ERR_ON_COVER_UPDATE :
				case PostLangEx::ERR_ON_COVER_SAVE :
					return $this->error(400, $result[ "error" ]);

				default :
					return $this->error(500, $result[ "error" ]);
			}
		}

		//  return the updated post cover
		return $this->createdResult(PostEx::getOneWithTranslations($postId));
	}

	/**
	 * @param integer $postId
	 * @param integer $langId
	 *
	 * @return PostEx
	 *
	 * @SWG\Delete(
	 *     path    = "/posts/:postid/:langid/cover",
	 *     tags    = { "Posts", "Cover" },
	 *     summary = "Delete the existing cover",
	 *     description = "Remove the existing cover picture for a specific post translation",
	 *
	 *     @SWG\Response( response = 204, description = "cover removed successfully" ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 500, description = "error while uploading file", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionDelete (int $postId, int $langId)
	{
		$result = PostLangEx::deleteCoverPicture($postId, $langId);

		if ($result[ "status" ] === PostLangEx::ERROR) {
			switch ($result[ "error" ]) {
				case PostLangEx::ERR_NOT_FOUND :
					return $this->error(401, $result[ "error" ]);

				case PostLangEx::ERR_ON_COVER_DELETE :
					return $this->error(400, $result[ "error" ]);

				default :
					return $this->error(500, $result[ "error" ]);
			}
		}

		//  return the updated post cover
		return $this->createdResult(PostEx::getOneWithTranslations($postId));
	}
}
