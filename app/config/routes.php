<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
// Parse HTML files
//Router::parseExtensions('html', 'xml');
// Route static pages
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
Router::connect('/lm/*', array('controller' => 'pages', 'action' => 'display'));

// Route Store to Offer controller
Router::connect('/store/payment/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'payment'));
Router::connect('/store/accept/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'accept'));
Router::connect('/store/error/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'error'));
Router::connect('/store/cancel/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'cancel'));
Router::connect('/store/invoice/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'invoice'));
Router::connect('/store/plugins/*', array('plugin' => 'store', 'controller' => 'offers', 'action' => 'plugins'));

// Document Route
Router::connect('/documents/c/*', array('controller' => 'documents', 'action' => 'index'));

// RESTfull Web Services
Router::parseExtensions();
?>