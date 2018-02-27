<?php

namespace app\models\tag;


/**
 * Class TagLang
 * @package app\models\tag
 */
class TagLang extends TagLangBase
{
	public static function createTranslation ( $tagId, $data ) {}

	public static function deleteTranslations ( $tagId ) {}

	public static function updateTranslation ( $tagId, $langId, $data ) {}
}