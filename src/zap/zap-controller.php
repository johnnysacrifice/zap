<?php

  namespace zap{
    if(!defined('_')) define('_', '.');

    class ZapController{
      private $title = 'zap';
      private $editable = 'index-editable.png';
      private $retouched = 'index-retouched.png';
      private $thumbnail = 'index-thumbnail-%sx%s.png';
      private $thumbnails = array();
      
      public function __construct(){
        $this->thumbnails[] = array('width' => 40, 'height' => 40);
        $this->thumbnails[] = array('width' => 80, 'height' => 80);
        $this->thumbnails[] = array('width' => 110, 'height' => 110);
        $this->thumbnails[] = array('width' => 1152 * 0.25, 'height' => 360 * 0.25);
      }
      
      public function index(){ return new ZapView($this->model()); }
      
      public function upload(){
        if(!isset($_FILES['picture']) || $_FILES['picture']['error'] !== UPLOAD_ERR_OK) return $this->index();
        $file = $_FILES['picture'];
        $filename = $file['tmp_name'];
        $ou = ZapStream::createFromFile($filename);
        $ou->save(sprintf('%s/%s', _, $this->editable), $quality = 100.00);
        $ou->dispose();
        $ou = null;
        return new ZapView($this->model());
      }
      
      public function retouch(){
        if(!isset($_POST['data'])) return $this->index();
        $this->process();
        $this->thumbs();
        return new ZapView($this->model());
      }
      
      public function delete(){
        unlink(sprintf('%s/%s', _, $this->editable));
        unlink(sprintf('%s/%s', _, $this->retouched));
        for($index = 0, $total = count($this->thumbnails); $index < $total; ++$index)
          unlink(sprintf($this->thumbnail, $this->thumbnails[$index]['width'], $this->thumbnails[$index]['height']));
        return new ZapView($this->model());
      }
      
      private function process(){
        $data = json_decode($_POST['data'], true);
        list($width, $height, $scale, $rotate, $horizontal, $vertical, $color, $opacity, $quality) = array(
          $data['output']['width'], $data['output']['height'], $data['scale'], $data['rotate'],
          $data['horizontal'], $data['vertical'], $data['color'], $data['opacity'], $data['quality']
        );
        $transformation = new ZapTransformation(
          $scale, $rotate, new ZapPosition($horizontal, $vertical),
          new ZapColor($color['r'], $color['g'], $color['b'], $color['a']), $opacity
        );
        $in = ZapStream::createFromFile(sprintf('%s/%s', _, $this->editable));
        $ou = ZapStream::create($width, $height);
        $zp = new Zap();
        $zp->process($transformation, $in, $ou);
        $ou->save(sprintf('%s/%s', _, $this->retouched), $quality);
        $ou->dispose();
        $in->dispose();
        $in = $ou = null;
      }
      
      private function thumbs(){
        $data = json_decode($_POST['data'], true);
        for($index = 0, $total = count($this->thumbnails); $index < $total; ++$index){
          list($width, $height, $scale, $rotate, $horizontal, $vertical, $color, $opacity, $quality) = array(
            $this->thumbnails[$index]['width'], $this->thumbnails[$index]['height'], 1.0, 0.0,
            $this->thumbnails[$index]['width'] * 0.5, $this->thumbnails[$index]['height'] * 0.5,
            array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0), 1.0, $data['quality']
          );
          $transformation = new ZapTransformation(
            $scale, $rotate, new ZapPosition($horizontal, $vertical),
            new ZapColor($color['r'], $color['g'], $color['b'], $color['a']), $opacity
          );
          $in = ZapStream::createFromFile(sprintf('%s/%s', _, $this->retouched));
          $ou = ZapStream::create($width, $height);
          $zp = new Zap();
          $zp->process($transformation, $in, $ou);
          $ou->save(sprintf($this->thumbnail, $this->thumbnails[$index]['width'], $this->thumbnails[$index]['height']), $quality);
          $ou->dispose();
          $in->dispose();
          $in = $ou = null;
        }
      }
      
      private function model(){
        $model = array(
          'title' => $this->title,
          'editable' => $this->editable(),
          'retouched' => $this->retouched(),
          'files' => array(
            'editable' => $this->editable,
            'retouched' => $this->retouched,
            'thumbnails' => array()
          ),
          'data' => isset($_POST['data']) ? $_POST['data'] : '',
          'version' => time()
        );
        for($index = 0, $total = count($this->thumbnails); $index < $total; ++$index){
          $model['files']['thumbnails'][]
            = sprintf($this->thumbnail, $this->thumbnails[$index]['width'], $this->thumbnails[$index]['height']);
        }
        return $model;
      }
      
      private function editable(){
        return file_exists(sprintf('%s/%s', _, $this->editable));
      }
      
      private function retouched(){
        return file_exists(sprintf('%s/%s', _, $this->retouched));
      }
    }
  }

?>