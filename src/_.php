<?php

  namespace _{
    class _{
      private $configuration;
      private $assemblies;

      const REVERSE_SLASH = '\\';
      const FORWARD_SLASH = '/';
      
      private function __construct($configuration){
        $this->configuration = $configuration;
        $this->assemblies = array();
        $this->initialize();
      }
      
      private function initialize(){
        $encoded = file_get_contents($this->configuration);
        $decoded = json_decode($encoded, true);
        $names = array_keys($decoded['assemblies']);
        $total = count($names);
        for($index = 0; $index < $total; ++$index) $this->assemblies[$names[$index]] = $decoded['assemblies'][$names[$index]];
      }
      
      public static function _($configuration){
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        spl_autoload_register(array(new self($configuration), 'autoload'));
      }
      
      public function autoload($fullyQualifiedName){
        $assembly = $this->assembly($fullyQualifiedName);
        if($this->skip($assembly)) return false;
        return $this->resolve($assembly, $fullyQualifiedName);
      }
      
      private function assembly($fullyQualifiedName){
        return explode(self::REVERSE_SLASH, $fullyQualifiedName)[0];
      }
      
      private function skip($assembly){
        return array_key_exists($assembly, $this->assemblies) === false ? true : false;
      }
      
      private function resolve($assembly, $fullyQualifiedName){
        $name = $this->reassembly($assembly, $fullyQualifiedName);
        $name = $this->reslash($name);
        $name = $this->renormalize($name);
        $require = sprintf('%s%s.php', $this->assemblies[$assembly], $name);
        if(!file_exists($require)) return false;
        $outcome = require $require;
        return $outcome !== false ? true : false;
      }
      
      private function reassembly($assembly, $fullyQualifiedName){
         return substr($fullyQualifiedName, strlen($assembly));
      }

      private function reslash($name){
        return str_replace(self::REVERSE_SLASH, self::FORWARD_SLASH, $name);
      }
      
      private function renormalize($name){
        $parts = explode(self::FORWARD_SLASH, $name);
        $klass = ($total = count($parts)) > 1 ? $total-1 : 0;
        $nform = '';
        for($index = 0, $stop = strlen($parts[$klass]); $index < $stop; ++$index){
          $nform .= sprintf(
            '%s%s',
            ((ctype_upper($parts[$klass][$index]) && $index > 0 && ctype_upper($parts[$klass][$index-1]) === false)) ||
            ((ctype_upper($parts[$klass][$index]) && $index > 0 && $index < ($stop-1) && ctype_upper($parts[$klass][$index+1]) === false))
              ? '-' : '', strtolower($parts[$klass][$index])
          );
        }
        $parts[$klass] = $nform;
        return implode(self::FORWARD_SLASH, $parts);
      }
    }
  }

?>