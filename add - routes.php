//Authentication
Route::get(['set' => '/autenticacao/logout', 'as' => 'loginGoogle.logout'], 'ControllerLoginGoogle@logout');
Route::get(['set' => '/autenticacao/getAutentication', 'as' => 'loginGoogle.getAutentication'], 'ControllerLoginGoogle@getAutentication');
Route::get(['set' => '/autenticacao/loginValidation', 'as' => 'loginGoogle.loginValidation'], 'ControllerLoginGoogle@loginValidation');
Route::post(['set' => '/autenticacao/loginValidation', 'as' => 'loginGoogle.loginValidation'], 'ControllerLoginGoogle@loginValidation');
