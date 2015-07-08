<?php

  namespace zap{
    class ZapPosition{
      private $x;
      private $y;
      
      public function __construct($x, $y){
        $this->x = $x;
        $this->y = $y;
      }
      
      public function x(){
        return $this->x;
      }
      
      public function y(){
        return $this->y;
      }
    }
  }

?>