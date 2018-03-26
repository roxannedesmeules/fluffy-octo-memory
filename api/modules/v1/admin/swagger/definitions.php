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
 *     definition  = "HATEOAS",
 *     description = "Hypermedia links are used to navigate dynamically to the appropriate resource by traversing the hypermedia links.",
 *     
 *     @SWG\Property( property = "rel",  type = "string" ),
 *     @SWG\Property( property = "href", type = "string" ),
 *     @SWG\Property( property = "type", type = "string" ),
 * )
 */