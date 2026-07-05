<?php
namespace Veresel\Core;
class Application{
 private Kernel $kernel;
 public function __construct(){ $this->kernel=new Kernel(); }
 public function boot(): void{ $this->kernel->boot(); }
 public function container(): Container{ return $this->kernel->container(); }
}
