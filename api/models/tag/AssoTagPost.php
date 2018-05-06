<?php

namespace app\models\tag;

use app\models\post\Post;

/**
 * Class AssoTagPost
 *
 * @package app\models\tag
 */
class AssoTagPost extends AssoTagPostBase
{
	/**
	 * @param $postId
	 * @param $tagId
	 *
	 * @return array
	 */
	public static function createRelation ( $postId, $tagId )
	{
		if (!Post::idExists($postId)) {
			return self::buildError(self::ERR_POST_NOT_FOUND);
		}

		if (!Tag::idExists($tagId)) {
			return self::buildError(self::ERR_TAG_NOT_FOUND);
		}

		if (self::relationExists($postId, $tagId)) {
			return self::buildError(self::ERR_ALREADY_EXISTS);
		}

		$model = new self();

		$model->post_id = $postId;
		$model->tag_id  = $tagId;

		if (!$model->validate()) {
			return self::buildError($model->getErrors());
		}

		if (!$model->save()) {
			return self::buildError(self::ERR_ON_SAVE);
		}

		return self::buildSuccess([]);
	}

	public static function deleteAllForPost ( $postId )
	{
		//  define result as success, will be overwritten by an error when necessary
		$result = self::buildSuccess([]);

		//  find all association to be deleted
		$toDelete = self::findAll([ "post_id" => $postId]);

		//  delete association one by one, to correctly trigger the all events
		foreach ( $toDelete as $asso ) {
			if ( !$asso->delete() ) {
				$result = self::buildError(self::ERR_ON_DELETE);
				break;
			}
		}

		//  return success
		return $result;
	}

	/**
	 * @param $tagId
	 *
	 * @return array
	 *
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public static function deleteAllForTag ( $tagId )
	{
		//  define result as success, will be overwritten by an error when necessary
		$result = self::buildSuccess([]);

		//  find all association to be deleted
		$toDelete = self::findAll([ "tag_id" => $tagId]);

		//  delete association one by one, to correctly trigger the all events
		foreach ( $toDelete as $asso ) {
			if ( !$asso->delete() ) {
				$result = self::buildError(self::ERR_ON_DELETE);
				break;
			}
		}

		//  return success
		return $result;
	}

	/**
	 * @param $postId
	 * @param $tagId
	 *
	 * @return array
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public static function deleteRelation ( $postId, $tagId )
	{
		if (!self::relationExists($postId, $tagId)) {
			return self::buildError(self::ERR_NOT_FOUND);
		}

		//  find association to delete
		$model = self::findOne([ "post_id" => $postId, "tag_id" => $tagId ]);

		//  if the association doesn't delete, return error
		if ( !$model->delete() ) {
			return self::buildError(self::ERR_ON_DELETE);
		}

		//  return success
		return self::buildSuccess([]);
	}
}
