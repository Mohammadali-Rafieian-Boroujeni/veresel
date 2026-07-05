<?php
namespace Veresel\Core;
class Kernel{
 private Container $container;
 public function __construct(){ $this->container=new Container(); }
 public function container(): Container{ return $this->container; }
 public function boot(): void{}
}
