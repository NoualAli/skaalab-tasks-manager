<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// declare(strict_types=1);

/**
 * Constantes
 */
define('UPDATE_PROFILE_SUCCESS', 'Votre profil a été mis à jour avec succès');
define('UPDATE_PROFILE_ERROR', 'Une erreur est survenue lors de la tentative de mettre à jour de votre profile');
define('UPDATE_PASSWORD_SUCCESS', 'Mot de passe modifié avec succès');
define('UPDATE_PASSWORD_ERROR', 'Une erreur est survenue lors de la tentative de mettre à jour de votre mot de passe');
define('DELETE_SUCCESS', 'Ressource supprimé avec succès');
define('DELETE_ERROR', 'Une erreur est survenue lors de la tentative de suppression !');
define('UPDATE_SUCCESS', 'Vos modifications ont été enregistrés avec succès');
define('UPDATE_ERROR', 'Une erreur est survenue lors de la tentative d\'enregistrement !');
define('CREATE_SUCCESS', 'Ressource créé avec succès');
define('CREATE_ERROR', 'Une erreur est survenue lors de la tentative de création !');
define('RESTORE_SUCCESS', 'Resource réstauré avec succès');
define('RESTORE_ERROR', 'Une erreur est survenue lors de la tentative de réstauration !');
define('ATTACH_SUCCESS', 'Resource attaché avec succès');
define('ATTACH_ERROR', 'Une erreur est survenue lors de la tentative d\'attachement de la resource !');
define('DETACH_SUCCESS', 'Resource détacher avec succès');
define('DETACH_ERROR', 'Une erreur est survenue lors de la tentative de détachement de la resource !');
define('IMAGE_TYPES', ['jpeg', 'jpg', 'png', 'gif', 'tif', 'bmp', 'ico', 'psd', 'webp']);
define('NOTIFICATION_SUCCESS', 'Notification envoyé avec succès.');


// HTTP ERRORS
define('BAD_REQUEST', 400);
define('UNAUTHENTICATED', 401);
define('FORBIDDEN', 403);
define('NOT_FOUND', 404);
define('METHOD_NOT_ALLOWED', 405);
define('REQUEST_TIMEOUT', 408);
define('TOO_MANY_REQUESTS', 429);

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__ . '/../storage/framework/maintenance.php')) {
    require __DIR__ . '/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
