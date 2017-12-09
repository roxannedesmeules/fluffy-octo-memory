<?php
namespace app\modules\v1\admin\controllers;

use app\models\app\File;
use app\modules\v1\admin\components\ControllerAdminEx;
use app\modules\v1\admin\models\user\forms\Password;
use app\modules\v1\admin\models\user\forms\UserPicture;
use app\modules\v1\admin\models\user\UserProfileEx;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * Class UserProfileController
 *
 * @package app\modules\v1\admin\controllers
 */
class UserProfileController extends ControllerAdminEx
{
	public $corsMethods = [ "OPTIONS", "PUT" ];

	/** @inheritdoc */
	protected function verbs ()
	{
		return [
			"update"          => [ "OPTIONS", "PUT" ],
			"update-password" => [ "OPTIONS", "PUT" ],
			"upload-picture"  => [ "OPTIONS", "PUT" ],
		];
	}

	/**
	 * @SWG\Put(
	 *     path     = "/user/me",
	 *     tags     = { "User Profile" },
	 *     summary  = "Update user profile",
	 *     description = "Update authenticated user profile information",
	 *
	 *     @SWG\Parameter( name = "profile", in = "body", required = true, @SWG\Schema( ref = "#/definitions/UserProfileForm" ), ),
	 *
	 *     @SWG\Response( response = 200, description = "user profile updated correctly", @SWG\Schema( ref = "#/definitions/UserProfile" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "user profile couldn't be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "profile information aren't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while updating profile", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionUpdate ()
	{
		//  get body params and create model
		$form = new UserProfileEx($this->request->getBodyParams());

		//  if it doesn't validate, then throw an error
		if (!$form->validate()) {
			return $this->unprocessableResult($form->getErrors());
		}

		$userId = \Yii::$app->getUser()->getId();
		$result = UserProfileEx::updateProfile($userId, $form);

		if ($result[ "status" ] === UserProfileEx::ERROR) {
			switch ($result[ "error" ]) {
				case UserProfileEx::ERR_NOT_FOUND :
					throw new NotFoundHttpException(UserProfileEx::ERR_NOT_FOUND);

				case UserProfileEx::ERR_ON_SAVE :
					throw new ServerErrorHttpException(UserProfileEx::ERR_ON_SAVE);

				default :
					if (is_array($result[ "error" ])) {
						return $this->unprocessableResult($result[ "error" ]);
					}

					throw new ServerErrorHttpException(json_encode($result[ "error" ]));
			}
		}

		return UserProfileEx::find()->user($userId)->one();
	}

	/**
	 * @SWG\Put(
	 *     path     = "/user/me/password",
	 *     tags     = { "User Profile" },
	 *     summary  = "Update user password",
	 *     description = "Update authenticated user password",
	 *
	 *     @SWG\Parameter( name = "password", in = "body", required = true, @SWG\Schema( ref = "#/definitions/PasswordForm" ), ),
	 *
	 *     @SWG\Response( response = 204, description = "user password was updated correctly" ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "new password isn't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while updating profile", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionUpdatePassword ()
	{
		//  get the body parameters to create the Password update form
		$form = new Password($this->request->getBodyParams());

		//  if it doesn't validate, then throw an error
		if (!$form->validate()) {
			return $this->unprocessableResult($form->getErrors());
		}

		$result = $form->updatePassword();

		if ($result[ "status" ] === Password::ERROR) {
			switch ($result[ "error" ]) {
				case Password::ERR_PASSWORD :
					// no break;
				case Password::ERR_CONFIRMATION :
					throw new BadRequestHttpException($result[ "error" ]);

				case Password::ERR_ON_SAVE :
					throw new ServerErrorHttpException(Password::ERR_ON_SAVE);

				default :
					if (is_array($result[ "error" ])) {
						return $this->unprocessableResult($result[ "error" ]);
					}

					throw new ServerErrorHttpException(json_encode($result[ "error" ]));
			}
		}

		$this->emptySuccess();
	}

	/**
	 * @SWG\Put(
	 *     path     = "/user/me/picture",
	 *     tags     = { "User Profile" },
	 *     summary  = "Updated user picture",
	 *     description = "Updated authenticated user picture",
	 *
	 *     @SWG\Parameter( name = "picture", in = "formData", type = "file", required = true ),
	 *
	 *     @SWG\Response( response = 200, description = "user picture was uploaded correctly", @SWG\Schema( ref = "#/definitions/UserProfile" ), ),
	 *     @SWG\Response( response = 401, description = "user can't be authenticated", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 404, description = "user profile couldn't be found", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 *     @SWG\Response( response = 422, description = "profile picture isn't valid", @SWG\Schema( ref = "#/definitions/UnprocessableError" ), ),
	 *     @SWG\Response( response = 500, description = "error while updating profile", @SWG\Schema( ref = "#/definitions/GeneralError" ), ),
	 * )
	 */
	public function actionUploadPicture ()
	{
		$form       = new UserPicture();
		$form->file = UploadedFile::getInstanceByName("picture");

		//  if it doesn't validate, then throw an error
		if (!$form->validate()) {
			return $this->unprocessableResult($form->getErrors());
		}

		$userId = \Yii::$app->getUser()->getId();
		$result = UserProfileEx::uploadPicture($userId, $form->file);

		if ($result[ "status" ] === UserProfileEx::ERROR) {
			switch ($result[ "error" ]) {
				case File::ERR_ON_UPLOAD :
					// no break;
				case UserProfileEx::ERR_ON_SAVE :
					// no break;
				case File::ERR_ON_SAVE :
					throw new ServerErrorHttpException($result[ "error" ]);

				case UserProfileEx::ERR_NOT_FOUND :
					throw new NotFoundHttpException(UserProfileEx::ERR_NOT_FOUND);

				default :
					if (is_array($result[ "error" ])) {
						return $this->unprocessableResult($result[ "error" ]);
					}

					throw new ServerErrorHttpException(json_encode($result[ "error" ]));
			}
		}

		return UserProfileEx::find()->user($userId)->one();
	}
}
