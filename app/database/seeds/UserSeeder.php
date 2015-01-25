<?php

class UserSeeder extends DatabaseSeeder {

  public function run()
  {
    $users = [
      [
        "username" => "monkeeboy",
        "password" => Hash::make("dashboard"),
        "email"    => "server@monkee-boy.com",
        "level"    => 1
      ],
      [
        "username" => "demo",
        "password" => Hash::make("demo"),
        "email"    => "server.demo@monkee-boy.com",
        "level"    => 2
      ],
      [
        "username" => "test",
        "password" => Hash::make("test"),
        "email"    => "server.test@monkee-boy.com",
        "level"    => 2
      ]
    ];

    foreach ($users as $user) {
      User::create($user);
    }
  }
}
