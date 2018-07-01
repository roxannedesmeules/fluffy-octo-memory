<?php

namespace app\modules\v1\models;

use app\models\communication\Communication;

/**
 * Class CommunicationEx
 *
 * @package app\modules\v1\models
 */
class CommunicationEx extends Communication
{
	public function rules ()
	{
		return [
			[ "name", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "name", "string", "max" => 255, "tooLong" => self::ERR_FIELD_LONG ],

			[ "email", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "email", "string", "max" => 255, "tooLong" => self::ERR_FIELD_LONG ],

			[ "subject", "string", "max" => 255, "tooLong" => self::ERR_FIELD_LONG ],

			[ "message", "required", "message" => self::ERR_FIELD_REQUIRED ],
			[ "message", "string", "message" => self::ERR_FIELD_TYPE ],
		];
	}
}
