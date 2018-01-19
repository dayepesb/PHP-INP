<?php
// auto-generated by sfRoutingConfigHandler
// date: 2018/01/19 17:58:00
return array(
'category' => new sfPropelRoute('/category/:slug', array (
  'module' => 'category',
  'action' => 'show',
), array (
), array (
  'model' => 'JobeetCategory',
  'type' => 'object',
)),
'job' => new sfPropelRouteCollection(array (
  'model' => 'JobeetJob',
  'column' => 'token',
  'object_actions' => 
  array (
    'publish' => 'put',
    'extend' => 'PUT',
  ),
  'name' => 'job',
  'requirements' => 
  array (
    'token' => '\\w+',
  ),
)),
'job_show_user' => new sfPropelRoute('/job/:company_slug/:location_slug/:id/:position_slug', array (
  'module' => 'job',
  'action' => 'show',
), array (
  'id' => '\\d+',
  'sf_method' => 
  array (
    0 => 'GET',
  ),
), array (
  'model' => 'JobeetJob',
  'type' => 'object',
  'method_for_criteria' => 'doSelectActive',
)),
'homepage' => new sfRoute('/', array (
  'module' => 'job',
  'action' => 'index',
), array (
), array (
)),
);
