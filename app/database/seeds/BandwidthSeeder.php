<?php

class BandwidthSeeder extends Seeder {

  public function run()
  {
    DB::table('bandwidth')->delete();

    $period = new DatePeriod(
      new DateTime('2011-01-20'),
      new DateInterval('P1D'),
      new DateTime('2012-10-05')
    );

    foreach($period as $time) {
      $domain = mt_rand(1,7);
      $received = mt_rand(300,8000);
      $sent = mt_rand(300,8000);

      Bandwidth::create(array(
        'domain' => $domain,
        'ip' => '96.63.241.155',
        'request_time' => $time->format('Y-m-d H:i:s'),
        'time_taken' => rand(300,800),
        'method' => 'GET',
        'status' => '200',
        'bytes_received' => $received,
        'bytes_sent' => $sent,
        'referer' => 'http://paperkilledrock.com/2010/05/html5-localstorage-part-one/',
        'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.237 Safari/534.10',
        'file_name' => '/var/www/paperkilledrock.com/_/public_html/index.php',
        'url' => '/2010/05/html5-localstorage-part-one/'
      ));
    }
  }

}
