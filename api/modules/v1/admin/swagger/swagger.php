<?php

namespace app\modules\v1\admin\swagger;

/**
 * @SWG\Swagger(
 *     schemes  = { "http" },
 *     host     = "api.blog.local",
 *     basePath = "/v1/admin",
 *
 *     consumes = { "application/json", "application/form-data" },
 *     produces = { "application/json" },
 *     security = { "ApiClientSecurity", "ApiUserSecurity" },
 *
 *     @SWG\Info(
 *          version = "1.0.0",
 *          title   = "Admin Panel API",
 *          description = "This API will connect to the admin panel, allowing to manage posts, categories and others for the blog.",
 *
 *          @SWG\Contact(
 *              name  = "Roxanne Desmeules",
 *              email = "roxanne.desmeules@gmail.com",
 *          ),
 *     )
 * )
 */