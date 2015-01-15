<?php

class BandwidthSeeder extends Seeder {

  public function run()
  {
    DB::table('bandwidth')->delete();

    for($i=0; $i<1000; $i++) {
      $domain = mt_rand(1,7);
      $received = mt_rand(300,800);
      $sent = mt_rand(300,800);

      Bandwidth::create(array(
        'domain' => $domain,
        'ip' => '96.63.241.155',
        'logname' => '-',
        'remote_user' => '-',
        'request_time' => '2011-01-24 22:48:58',
        'time_taken' => rand(300,800),
        'request' => 'GET /wp-content/themes/chapter3/css/reset.css HTTP/1.1',
        'status' => '200',
        'size_response' => $received+$sent,
        'bytes_received' => $received,
        'bytes_sent' => $sent,
        'referer' => 'http://paperkilledrock.com/2010/05/html5-localstorage-part-one/',
        'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.237 Safari/534.10'
      ));
    }
  }

}
