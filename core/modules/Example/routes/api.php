<?php

Route::group(array('module' => 'Example', 'middleware' => ['api'], 'namespace' => 'Yourify\Modules\Example\Controllers'), function() {

    Route::resource('Example', 'ExampleController');
    
});	
