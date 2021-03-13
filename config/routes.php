<?php

return array(
    
   'good/([0-9]+)' => 'good/index/$1',              // actionIndex в GoodController
      
   'catalog' => 'catalog/index',                    // actionIndex в CatalogController
    
   'category/([0-9]+)/page-([0-9]+)' 
      => 'catalog/category/$1/$2',                  // actionCategory в CatalogController  
   'category/([0-9]+)' => 'catalog/category/$1',    // actionCategory в CatalogController
     
   'basket/add/([0-9]+)'=> 'basket/add/$1',         // actionAdd в BasketController
   'basket/delete/([0-9]+)'=>'basket/delete/$1',    // actionDelete в BasketController
   'basket/order'=>'basket/order',                  // actionOrder в BasketController
   'basket'=>'basket/index',                        // actionIndex в BasketController
   
      
   'user/register' => 'user/register',              // actionRegister в UserController
   'user/login' => 'user/login',                    // actionLogin в UserController
   'user/exit' => 'user/exit',                      // actionExit в UserController
    
   'account/change' => 'account/change',            // actionChange в AccountController
   'account' => 'account/index',                    // actionIndex в AccountController
        
    
   "^$" => 'main/index',                            // actionIndex в MainController
    
);
