<?php

namespace app\modules\v1\models\post;

use app\helpers\ArrayHelperEx;
use app\models\post\PostLang;
use app\modules\v1\models\LangEx;
use app\modules\v1\models\user\UserEx;

/**
 * Class PostLangEx
 *
 * @package app\modules\v1\models\post
 */
class PostLangEx extends PostLang
{
	/** @inheritdoc */
	public function getUser ()
	{
		return $this->hasOne(UserEx::className(), [ "id" => "user_id" ]);
	}

	/**
	 * @param self[] $list
	 * @param int    $lang
	 *
	 * @return \app\models\user\User|string
	 */
	public static function getTranslationAuthor ( $list, $lang = LangEx::EN )
	{
		/** @var self[] $translation */
		$translation = ArrayHelperEx::filterInArrayAtIndex($lang, $list, "lang_id");

		return (!empty($translation)) ? $translation[ 0 ]->user : "";
	}

	/**
	 * @param self[] $list
	 * @param int    $lang
	 *
	 * @return string
	 */
	public static function getTranslationContent ( $list, $lang = LangEx::EN )
	{
		/** @var self[] $translation */
		$translation = ArrayHelperEx::filterInArrayAtIndex($lang, $list, "lang_id");

		return (!empty($translation)) ? $translation[ 0 ]->content : "";
	}

	/**
	 * @param self[] $list
	 * @param int    $lang
	 *
	 * @return string
	 */
	public static function getTranslationCoverPath ( $list, $lang = LangEx::EN )
	{
		/** @var self[] $translation */
		$translation = ArrayHelperEx::filterInArrayAtIndex($lang, $list, "lang_id");

		return (!empty($translation) && !empty($translation[ 0 ]->file)) ? $translation[ 0 ]->file->getFullPath() : "";
	}

	/**
	 * @param self[] $list
	 * @param int    $lang
	 *
	 * @return string
	 */
	public static function getTranslationCoverAlt ( $list, $lang = LangEx::EN )
	{
		/** @var self[] $translation */
		$translation = ArrayHelperEx::filterInArrayAtIndex($lang, $list, "lang_id");

		return (!empty($translation)) ? $translation[ 0 ]->file_alt : "";
	}

	/**
	 * @param self[] $list
	 * @param int    $lang
	 *
	 * @return string
	 */
	public static function getTranslationSlug ( $list, $lang = LangEx::EN )
	{
		/** @var self[] $translation */
		$translation = ArrayHelperEx::filterInArrayAtIndex($lang, $list, "lang_id");

		return (!empty($translation)) ? $translation[ 0 ]->slug : "";
	}

	public static function getTranslationSummary ( $list, $lang = LangEx::EN )
	{
		/** @var self[] $translation */
		$translation = ArrayHelperEx::filterInArrayAtIndex($lang, $list, "lang_id");

		return (!empty($translation)) ? $translation[ 0 ]->summary : "";
	}

	/**
	 * @param self[] $list
	 * @param int    $lang
	 *
	 * @return string
	 */
	public static function getTranslationTitle ( $list, $lang = LangEx::EN )
	{
		/** @var self[] $translation */
		$translation = ArrayHelperEx::filterInArrayAtIndex($lang, $list, "lang_id");

		return (!empty($translation)) ? $translation[ 0 ]->title : "";
	}
}
