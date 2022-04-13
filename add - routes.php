//Autenticação
Route::get(['set' => '/autenticacao/getAutentication', 'as' => 'users.getAutentication'], 'ControllerUser@getAutentication');
Route::get(['set' => '/autenticacao/logout', 'as' => 'users.logout'], 'ControllerUser@logout');
Route::get('/validacaoLogin', function(){  require _CAMINHO_ADMIN. "valida.php";});
Route::post('/validacaoLogin', function(){  require _CAMINHO_ADMIN. "valida.php";});
