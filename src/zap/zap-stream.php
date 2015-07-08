<?php

  namespace zap{
    class ZapStream{
      private $resource;
      private $disposed;
  
      const STREAM_TYPE_JPEG = 'image/jpeg';
      const STREAM_TYPE_PNG = 'image/png';
      
      private function __construct(){}
      
      public static function create($width, $height){
        $stream = new self();
        $stream->resource = imagecreatetruecolor($width, $height);
        $stream->alpha();
        return $stream;
      }
      
      public static function createFromResource(&$resource){
        if(strtolower(get_resource_type($resource)) !== 'gd') throw new \Exception('Provided resource is not supported.');
        $stream = new self();
        $stream->resource = &$resource;
        $stream->alpha();
        return $stream;
      }
      
      public static function createFromFile($file){
        if(!file_exists($file)) throw new \Exception('File not found.');
        $stream = new self();
        $stream->open($file);
        $stream->alpha();
        return $stream;
      }
      
      private function open($file){
        $type = $this->typeOf($file);
        if($this->match($type, self::STREAM_TYPE_JPEG)) return $this->jpeg($file);
        if($this->match($type, self::STREAM_TYPE_PNG)) return $this->png($file);
        throw new \Exception('Provided file is not supported.');
      }

      private function jpeg($file){ $this->resource = imagecreatefromjpeg($file); }
      
      private function png($file){ $this->resource = imagecreatefrompng($file); }
      
      private function match($type1, $type2){ return $type1 === $type2; }
      
      private function typeOf($file){
        $type = '';
        if(($resource = finfo_open()) !== false){
          $type = strtolower(finfo_file($resource, $file, FILEINFO_MIME_TYPE));
          finfo_close($resource);
          $resource = null;
        }
        return $type;
      }
      
      private function alpha(){
        imagesetinterpolation($this->resource(), IMG_BILINEAR_FIXED);
        imagealphablending($this->resource, false);
        imagesavealpha($this->resource, true);
      }
  
      public function save($file, $quality){
        if($this->disposed === true) throw new \Exception('Disposed stream cannot be saved.');
        ob_start();
        imagepng($this->resource, null, 9 / $quality);
        $buffer = ob_get_clean();
        if(($resource = fopen($file, 'w+')) !== false){
          if(($lock = flock($resource, LOCK_EX)) !== false){
            fwrite($resource, $buffer, strlen($buffer));
            flock($resource, LOCK_UN);
          }
          fclose($resource);
          $resource = null;
        }
        $buffer = null;
      }
  
      public function dispose(){
        if($this->disposed === true) throw new \Exception('Stream is already disposed.');
        $this->disposed = true;
        if($this->resource) imagedestroy($this->resource);
        $this->resource = null;
      }
      
      public function resource(){ return $this->resource; }
      
      public function width(){ return imagesx($this->resource); }
      
      public function height(){ return imagesy($this->resource); }
    }
  }

?>