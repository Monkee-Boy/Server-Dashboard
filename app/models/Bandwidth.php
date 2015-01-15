<?php

class Bandwidth extends Eloquent {

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'bandwidth';

  protected $fillable = array('domain','ip','logname','remote_user','request_time','time_taken','request','status','size_response','bytes_received','bytes_sent','referer','user_agent');

  public function domain()
  {
    return $this->belongsTo('Domain', 'domain');
  }

}
