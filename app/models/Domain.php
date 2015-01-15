<?php

class Domain extends Eloquent {

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'domains';

  protected $fillable = array('domain','subdomain');

  public function bandwidth()
  {
    return $this->hasMany('Bandwidth', 'domain');
  }

  public function storage()
  {
    return $this->hasOne('Storage', 'domain');
  }

}
