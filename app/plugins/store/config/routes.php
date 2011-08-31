<?php

// Add the following lines to config/routes.php

Router::connect('/payment/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'payment'));
Router::connect('/accept/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'accept'));
Router::connect('/error/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'error'));
Router::connect('/cancel/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'cancel'));
Router::connect('/invoice/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'invoice'));
Router::connect('/success/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'success'));
Router::connect('/exception/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'exception'));
Router::connect('/store/plugins/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'plugins'));
?>
