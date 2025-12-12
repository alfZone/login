------ ADD to routes.php file do not replece

//Authentication
Route::get('/admin', function(){require _CAMINHO_TEMPLATE_ADMIN. "login.html";});
Route::get('/admin/in', function(){require _CAMINHO_TEMPLATE_ADMIN. "index.php";});

Route::get(['set' => '/autenticacao/logout', 'as' => 'loginGoogle.logout'], 'ControllerLoginGoogle@logout');
Route::get(['set' => '/autenticacao/getAutentication', 'as' => 'LoginGoogle.getAutentication'], 'ControllerLoginGoogle@getAutentication');

Route::get(['set' => '/autenticacao/validacaoLogin', 'as' => 'LoginGoogle.logout'], 'ControllerLoginGoogle@validaLogin');
Route::post(['set' => '/autenticacao/validacaoLogin', 'as' => 'LoginGoogle.logout'], 'ControllerLoginGoogle@validaLogin');
