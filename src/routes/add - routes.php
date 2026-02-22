------ ADD to routes.php file do not replece

//Authentication
Route::get('/login', function(){require _PATH_FOR_ADMIN_TEMPLATE. "login.html";});  //path for login form
Route::get('/admin', function(){require _PATH_FOR_ADMIN_TEMPLATE. "index.php";});   //path for backoffice land page

Route::get(['set' => 'PATH_FOR_LOGOUT', 'as' => 'loginGoogle.logout'], 'ControllerLoginGoogle@logout');
Route::get(['set' => 'PATH_TO_GET_AUTHENTICATION_INFORMATION', 'as' => 'LoginGoogle.getAutentication'], 'ControllerLoginGoogle@getAutentication');

Route::get(['set' => 'PATH_TO_VALIDATE_LOGIN_GOOGLE', 'as' => 'LoginGoogle.validaLogin'], 'ControllerLoginGoogle@validaLogin');
Route::post(['set' => 'PATH_TO_VALIDATE_LOGIN_GOOGLE', 'as' => 'LoginGoogle.validaLogin'], 'ControllerLoginGoogle@validaLogin');

Route::get(['set' => 'PATH_TO_VALIDATE_LOGIN_SIMPLE', 'as' => 'LoginSimples.validaLogin'], 'ControllerLoginSimples@validaLogin');
Route::post(['set' => 'PATH_TO_VALIDATE_LOGIN_SIMPLE', 'as' => 'LoginSimples.validaLogin'], 'ControllerLoginSimples@validaLogin');
