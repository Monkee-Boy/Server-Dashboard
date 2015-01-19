<?php

class Bandwidth extends Eloquent {

  /**
  * The database table used by the model.
  *
  * @var string
  */
  protected $table = 'bandwidth';

  protected $fillable = array('domain','ip','request_time','time_taken','method','status','size_response','bytes_received','bytes_sent','referer','user_agent','file_name','url');

  public function domain()
  {
    return $this->belongsTo('Domain', 'domain');
  }

}
