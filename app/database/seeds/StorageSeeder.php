<?php

class StorageSeeder extends Seeder {

  public function run()
  {
    DB::table('storage')->delete();

    for($i=0; $i<1000; $i++) {
      $domain = mt_rand(1,7);
      $size = mt_rand(20971520,5368709120);
      $files = mt_rand(10,100);

      Storage::create(array(
        'domain' => $domain,
        'size' => $size,
        'files' => $files
      ));
    }
  }

}
