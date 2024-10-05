<?php

namespace App\Http\Controllers;


/**
 * @OA\Info(
 *  title="School Management API",
 *  version="1.0.0",
 *  description="Exemple de API de gestió escolar.",
 *  termsOfService="https://example.com/terms",
 *  contact=@OA\Contact(
 *      name="Albert Lanza",
 *      email="contact@example.com"
 *  )
 * ),
 * @OA\SecurityScheme(
 *  securityScheme="bearerAuth",
 *  in="header",
 *  name="bearerAuth",
 *  type="http",
 *  scheme="bearer",
 *  bearerFormat="JWT",
 * )
 */


abstract class Controller
{
    //
}
