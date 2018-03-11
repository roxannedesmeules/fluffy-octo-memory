<?php

namespace app\models\tag;

/**
 * Class AssoTagPost
 *
 * @package app\models\tag
 */
class AssoTagPost extends AssoTagPostBase
{
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
}
