<?php
class OvertimeController extends BaseController {
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function bandwidth($domain_name, $subdomain_name = null)
  {
    $cache_key = 'overtime_bandwidth_'.$domain_name.'_'.$subdomain_name;
    $aData = [
      'chart' => null,
      'cache' => false,
      'stacked' => 'normal'
    ];

    if (Cache::has($cache_key))
    {
      $aChartData = Cache::get($cache_key);
      $aData['chart'] = $aChartData;
      $aData['cache'] = true;
    }
    else
    {
      $oBandwidth = Bandwidth::select('request_time', DB::raw('sum(bytes_received) as bytes_received'), DB::raw('sum(bytes_sent) as bytes_sent'))
        ->whereHas('domain', function($q) use ($domain_name)
        {
          $q->where('domain', $domain_name);
        })
        ->groupBy(DB::raw('YEAR(request_time), MONTH(request_time), DAY(request_time)'))
        ->orderBy('request_time');

      if(!empty($subdomain_name))
      {
        $oBandwidth = $oBandwidth->whereHas('domain', function($q) use ($subdomain_name)
        {
          $q->where('subdomain', $subdomain_name);
        });
      }

      $aBandwidth = $oBandwidth->get();

      $aReceived = array(
        'name' => 'Received',
        'data' => array(),
        'type' => 'column',
        'stack' => 'bandwdith',
        'tooltip' => array(
          'valueDecimals' => 2,
          'valueSuffix' => ' KB'
        )
      );
      $aSent = array(
        'name' => 'Sent',
        'data' => array(),
        'type' => 'column',
        'stack' => 'bandwdith',
        'tooltip' => array(
          'valueDecimals' => 2,
          'valueSuffix' => ' KB'
        )
      );

      foreach($aBandwidth as $aRow)
      {
        $date = new DateTime($aRow['request_time']);

        $aReceived['data'][] = array((int)$date->format('U')*1000, (float)number_format($aRow['bytes_received'] / 1024, 2));
        $aSent['data'][] = array((int)$date->format('U')*1000, (float)number_format($aRow['bytes_sent'] / 1024, 2));
      }

      $aChartData = array($aReceived, $aSent);

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $aChartData, $expiresAt);
      $aData['chart'] = $aChartData;
    }

    return Response::json($aData, 200);
  }

  public function time_taken($domain_name, $subdomain_name = null)
  {
    $cache_key = 'overtime_timetaken_'.$domain_name.'_'.$subdomain_name;
    $aData = [
      'chart' => null,
      'cache' => false,
      'stacked' => null
    ];

    if (Cache::has($cache_key))
    {
      $aChartData = Cache::get($cache_key);
      $aData['chart'] = $aChartData;
      $aData['cache'] = true;
    }
    else
    {
      $oRequest = Bandwidth::select('request_time', DB::raw('avg(time_taken) as avg_time_taken'))
      ->whereHas('domain', function($q) use ($domain_name)
      {
        $q->where('domain', $domain_name);
      })
      ->groupBy(DB::raw('YEAR(request_time), MONTH(request_time), DAY(request_time)'))
      ->orderBy('request_time');

      if(!empty($subdomain_name))
      {
        $oRequest = $oRequest->whereHas('domain', function($q) use ($subdomain_name)
        {
          $q->where('subdomain', $subdomain_name);
        });
      }

      $aRequest = $oRequest->get();

      $aAvg = array(
        'name' => 'Average',
        'data' => array(),
        'tooltip' => array(
          'valueDecimals' => 2,
          'valueSuffix' => ' Sec'
        )
      );

      foreach($aRequest as $aRow)
      {
        $date = new DateTime($aRow['request_time']);

        $aAvg['data'][] = array((int)$date->format('U')*1000, (float)$aRow['avg_time_taken']/1000);
      }

      $aChartData = array($aAvg);

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $aChartData, $expiresAt);
      $aData['chart'] = $aChartData;
    }

    return Response::json($aData, 200);
  }
}
