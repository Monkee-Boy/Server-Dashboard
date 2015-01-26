<?php

class LinodeController extends BaseController {
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function bandwidth()
  {
    $request = $this->call_linode('account.info');

    $value = $request['DATA']['TRANSFER_USED'];
    if($value >= 8388608)
    {
      $value_label = round($value/1024,2).'TB';
    }
    else
    {
      $value_label = $value.'GB';
    }

    $data = [
      'min'=>0,
      'max'=>$request['DATA']['TRANSFER_POOL'],
      'suffix'=>'GB',
      'value'=>$value,
      'max_label' => round($request['DATA']['TRANSFER_POOL']/1024,2).'TB',
      'value_label' => $value_label
    ];

    return Response::json($data, 200);
  }

  public function storage()
  {
    $request = $this->call_linode('linode.disk.list', array('LinodeID'=>828311,'DiskID'=>2821695));

    $disk_total = disk_total_space('/');
    $disk_free = disk_free_space('/');

    $value = ($disk_total - $disk_free);
    if($value >= 1073741824)
    {
      $value_label = round($value/1073741824,2).'GB';
    }
    elseif($value >= 1048576)
    {
      $value_label = round($value/1048576,2).'MB';
    }
    elseif($value >= 1024)
    {
      $value_label = round($value/1024,2).'KB';
    }
    else
    {
      $value_label = $value.'B';
    }

    $data = [
      'min'=>0,
      'max'=>$request['DATA'][0]['SIZE'],
      'suffix'=>'MB',
      'value'=>round($value/1048576,2),
      'max_label' => round($request['DATA'][0]['SIZE']/1024,2).'GB',
      'value_label' => $value_label
    ];

    return Response::json($data, 200);
  }

  protected function call_linode($request, $arguments=array())
  {
    $api_key = Config::get('services.linode.api_key');
    $url = 'https://api.linode.com/?api_key='.urlencode($api_key).'&api_action='.urlencode($request);

    foreach($arguments as $key=>$value)
    {
      $url .= '&'.$key.'='.urlencode($value);
    }

    return json_decode(file_get_contents($url), true);
  }
}
