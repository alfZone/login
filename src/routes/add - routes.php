------ ADD to routes.php file do not replece

Route::get('/login', function(){require _CAMINHO_TEMPLATE . "login.html";});
Route::get('/criar-conta', function(){require _CAMINHO_TEMPLATE_ADMIN. "registar.html";});
Route::get('/recuperar-password', function(){require _CAMINHO_TEMPLATE_ADMIN. "password-recover.html";});

//Authentication
Route::get('/admin', function(){require _PATH_FOR_ADMIN_TEMPLATE. "index.php";});   //path for backoffice land page

Route::get(['set' => 'PATH_FOR_LOGOUT', 'as' => 'loginGoogle.logout'], 'ControllerLoginGoogle@logout');
Route::get(['set' => 'PATH_TO_GET_AUTHENTICATION_INFORMATION', 'as' => 'LoginGoogle.getAutentication'], 'ControllerLoginGoogle@getAutentication');

Route::get(['set' => 'PATH_TO_VALIDATE_LOGIN_GOOGLE', 'as' => 'LoginGoogle.validaLogin'], 'ControllerLoginGoogle@validaLogin');
Route::post(['set' => 'PATH_TO_VALIDATE_LOGIN_GOOGLE', 'as' => 'LoginGoogle.validaLogin'], 'ControllerLoginGoogle@validaLogin');

Route::get(['set' => 'PATH_TO_VALIDATE_LOGIN_SIMPLE', 'as' => 'LoginSimples.validaLogin'], 'ControllerLoginSimples@validaLogin');
Route::post(['set' => 'PATH_TO_VALIDATE_LOGIN_SIMPLE', 'as' => 'LoginSimples.validaLogin'], 'ControllerLoginSimples@validaLogin');


Route::get(['set' => '/admin/recoverLoginSimple', 'as' => 'LoginSimples.recoverLogin'], 'ControllerLoginSimples@recoverLogin');
Route::post(['set' => '/admin/recoverLoginSimple', 'as' => 'LoginSimples.recoverLogin'], 'ControllerLoginSimples@recoverLogin');
Route::get(['set' => '/admin/recoverLoginSimple-s2', 'as' => 'LoginSimples.recoverLoginS2'], 'ControllerLoginSimples@recoverLoginS2');
Route::post(['set' => '/admin/recoverLoginSimple-s2', 'as' => 'LoginSimples.recoverLoginS2'], 'ControllerLoginSimples@recoverLoginS2');
