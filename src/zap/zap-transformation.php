<?php

  namespace zap{
    class ZapTransformation{
      private $scale;
      private $rotate;
      private $position;
      private $color;
      private $opacity;
      
      public function __construct($scale, $rotate, ZapPosition $position, ZapColor $color, $opacity){
        $this->scale = $scale;
        $this->rotate = $rotate;
        $this->position = $position;
        $this->color = $color;
        $this->opacity = $opacity;
      }
      
      public function scale(){
        return $this->scale;
      }
      
      public function rotate(){
        return $this->rotate;
      }
      
      public function position(){
        return $this->position;
      }
      
      public function color(){
        return $this->color;
      }
      
      public function opacity(){
        return $this->opacity;
      }
    }
  }

?>