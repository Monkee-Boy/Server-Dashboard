<?php

class Notification extends Eloquent {

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'notifications';

  protected $fillable = array('what','how', 'by', 'by_measure', 'where');

}
