<?php

class NotificationSeeder extends Seeder {

  public function run()
  {
    DB::table('notifications')->delete();

    Notification::create(array(
      'what' => 'bandwidth',
      'how' => 'increase',
      'by' => '20',
      'by_measure' => '%',
      'where' => 'webmaster@monkee-boy.com'
    ));

    Notification::create(array(
      'what' => 'storage',
      'how' => 'increase',
      'by' => '2',
      'by_measure' => 'GB',
      'where' => 'webmaster@monkee-boy.com'
    ));

    Notification::create(array(
      'what' => 'storage',
      'how' => 'reach',
      'by' => '20',
      'by_measure' => '%',
      'where' => 'webmaster@monkee-boy.com'
    ));
  }

}
