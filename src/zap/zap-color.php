<?php
  namespace zap{
    class ZapColor{
      private $red;
      private $green;
      private $blue;
      private $alpha;

      public function __construct($red, $green, $blue, $alpha){
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
      }
      
      public function red(){
        return $this->red;
      }
      
      public function green(){
        return $this->green;
      }
      
      public function blue(){
        return $this->blue;
      }
      
      public function alpha(){
        return $this->alpha;
      }
    }
  }

?>