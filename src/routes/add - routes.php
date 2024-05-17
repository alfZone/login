//Authentication
Route::get('/admin', function(){require _CAMINHO_TEMPLATE_ADMIN. "login.html";});
Route::get('/admin/in', function(){require _CAMINHO_TEMPLATE_ADMIN. "index.php";});

Route::get(['set' => '/autenticacao/logout', 'as' => 'loginGoogle.logout'], 'ControllerLoginGoogle@logout');
Route::get(['set' => '/autenticacao/getAutentication', 'as' => 'loginGoogle.getAutentication'], 'ControllerLoginGoogle@getAutentication');
Route::get(['set' => '/autenticacao/loginValidation', 'as' => 'loginGoogle.loginValidation'], 'ControllerLoginGoogle@loginValidation');
Route::post(['set' => '/autenticacao/loginValidation', 'as' => 'loginGoogle.loginValidation'], 'ControllerLoginGoogle@loginValidation');
Route::get(['set' => '/autenticacao/validacaoLogin', 'as' => 'users.logout'], 'ControllerLoginGoogle@loginValidation');
Route::post(['set' => '/autenticacao/validacaoLogin', 'as' => 'users.logout'], 'ControllerLoginGoogle@loginValidation');


//users
Route::get('/admin/users', function(){  require _CAMINHO_ADMIN. "/managerUsers.php";});
Route::post('/admin/users', function(){  require _CAMINHO_ADMIN. "/managerUsers.php";});
