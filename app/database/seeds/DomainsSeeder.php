<?php

class DomainsSeeder extends Seeder {

  public function run()
  {
    Domain::create(array('domain' => 'monkee-boy.com', 'subdomain' => '_'));
    Domain::create(array('domain' => 'monkee-boy.com', 'subdomain' => 'www'));
    Domain::create(array('domain' => 'monkee-boy.com', 'subdomain' => 'landworks'));
    Domain::create(array('domain' => 'defvayne23.com', 'subdomain' => '_'));
    Domain::create(array('domain' => 'defvayne23.com', 'subdomain' => 'www'));
    Domain::create(array('domain' => 'defvayne23.com', 'subdomain' => 'jtube'));
    Domain::create(array('domain' => 'defvayne23.com', 'subdomain' => 'gmaps'));
  }

}
