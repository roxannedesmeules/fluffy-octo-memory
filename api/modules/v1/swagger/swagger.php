<?php

namespace app\modules\v1\swagger;

/**
 * @SWG\Swagger(
 *      schemes  = { "http" },
 *      host     = "api.blog.dev",
 *      basePath = "/v1",
 *
 *     consumes = { "application/json", "application/form-data" },
 *     produces = { "application/json" },
 *     security = { "ApiClientSecurity" },
 *
 *      @SWG\Info(
 *          version = "1.0.0",
 *          title   = "Blog API",
 *          description = "This API will connect to the blog, allowing to show all published posts, active categories and others.",
 *
 *          @SWG\Contact(
 *              name = "Roxanne Desmeules",
 *              email = "roxanne.desmeules@gmail.com"
 *          ),
 *      ),
 * )
 *
 */