<?php

  define('_', '.');
  require_once _ . '/_.php';
  \_\_::_(sprintf('%s/_.json', _));

  $ctrl = new \zap\ZapController();
  $view = null;

  if(strtolower($_SERVER['REQUEST_METHOD']) === 'post'){
    $do = (isset($_POST['do'])) ? strtolower($_POST['do']) : '';
    $view = null;
    if($do === 'upload') $view = $ctrl->upload();
    if($do === 'retouch') $view = $ctrl->retouch();
    if($do === 'delete') $view = $ctrl->delete();
    if($do === '') $view = $ctrl->index();
  }else{
    $view = $ctrl->index();
  }

  $view->render();

?>
