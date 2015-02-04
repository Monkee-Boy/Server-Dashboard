<?php

class Storage extends Eloquent {

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'storage';

  protected $fillable = array('domain','type','size');

  public function domain()
  {
    return $this->belongsTo('Domain', 'domain');
  }

}
