<?php

class Notification extends Eloquent {

  use SoftDeletingTrait;

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'notifications';

  protected $fillable = array('what','how', 'by', 'by_measure', 'where');

  protected $dates = ['deleted_at'];

}
