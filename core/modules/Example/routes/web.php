<?php

Route::group(array('module' => 'Example', 'middleware' => ['web'], 'namespace' => 'Yourify\Modules\Example\Controllers'), function() {

    Route::resource('Example', 'ExampleController');
    
});	
