<?php

namespace app\modules\v1\admin\swagger;

/**
 * @SWG\Definition(
 *     definition = "GeneralError",
 *
 *     @SWG\Property( property = "code",    type = "integer" ),
 *     @SWG\Property( property = "message", type = "string" ),
 * )
 */

/**
 * @SWG\Definition(
 *     definition = "UnprocessableError",
 *
 *     @SWG\Property( property = "code",    type = "integer" ),
 *     @SWG\Property( property = "message", type = "string" ),
 *     @SWG\Property( property = "error",   type = "object" ),
 * )
 */

/**
 * @SWG\Definition(
 *     definition = "HATEOAS",
 *     
 *     @SWG\Property( property = "rel",  type = "string" ),
 *     @SWG\Property( property = "href", type = "string" ),
 *     @SWG\Property( property = "type", type = "string" ),
 * )
 */