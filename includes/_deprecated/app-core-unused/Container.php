<?php
namespace Veresel\Core;
class Container {
 private array $s=[];
 public function set(string $id,$svc):void{$this->s[$id]=$svc;}
 public function get(string $id){
  if(isset($this->s[$id])) return $this->s[$id];
  if(class_exists($id)) return new $id();
  throw new \RuntimeException("Service not found: ".$id);
 }
}
