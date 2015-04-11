<?php
class SumController extends BaseController {
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function bandwidth($domain_name, $subdomain_name = null)
  {
    $cache_key = 'sum_bandwidth_'.$domain_name.'_'.$subdomain_name;
    $aData = [
      'chart' => null,
      'cache' => false
    ];

    if (Cache::has($cache_key))
    {
      $sChartData = Cache::get($cache_key);
      $aData['chart'] = $sChartData;
      $aData['cache'] = true;
    }
    else
    {
      $date = new DateTime();
      $date->setDate($date->format('Y'), $date->format('n'), 1);

      $oBandwidth = Bandwidth::select(DB::raw('(sum(bytes_received)+sum(bytes_sent)) as total_bytes'))
        ->where(DB::raw('MONTH(request_time)'), $date->format('n'))
        ->where(DB::raw('YEAR(request_time)'), $date->format('Y'))
        ->whereHas('domain', function($q) use ($domain_name)
        {
          $q->where('domain', $domain_name);
        })
        ->groupBy(DB::raw('YEAR(request_time), MONTH(request_time)'))
        ->orderBy('request_time');

      if(!empty($subdomain_name))
      {
        $oBandwidth = $oBandwidth->whereHas('domain', function($q) use ($subdomain_name)
        {
          $q->where('subdomain', $subdomain_name);
        });
      }

      $sCurrentBandwidth = $oBandwidth->pluck('total_bytes');
      if(empty($sCurrentBandwidth)){ $sCurrentBandwidth = 0; }

      $date->modify('-1 month');

      $oBandwidth = Bandwidth::select(DB::raw('(sum(bytes_received)+sum(bytes_sent)) as total_bytes'))
        ->where(DB::raw('MONTH(request_time)'), $date->format('n'))
        ->where(DB::raw('YEAR(request_time)'), $date->format('Y'))
        ->whereHas('domain', function($q) use ($domain_name)
        {
          $q->where('domain', $domain_name);
        })
        ->groupBy(DB::raw('YEAR(request_time), MONTH(request_time)'))
        ->orderBy('request_time');

      if(!empty($subdomain_name))
      {
        $oBandwidth = $oBandwidth->whereHas('domain', function($q) use ($subdomain_name)
        {
          $q->where('subdomain', $subdomain_name);
        });
      }

      $sPreviousBandwidth = $oBandwidth->pluck('total_bytes');
      if(empty($sPreviousBandwidth)){ $sPreviousBandwidth = 0; }

      $sChartData = '<div class="current"><span class="title">This Month:</span><span class="size">'.$this->convertBytes($sCurrentBandwidth, '<span>', '</span>').'</span></div>';
      $sChartData .= '<div class="previous"><span class="title">Previous:</span><span class="size">'.$this->convertBytes($sPreviousBandwidth, '<span>', '</span>').'</span></div>';

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $sChartData, $expiresAt);
      $aData['chart'] = $sChartData;
    }

    return Response::json($aData, 200);
  }

  public function storage($domain_name, $subdomain_name = null)
  {
    $cache_key = 'sum_storage_'.$domain_name.'_'.$subdomain_name;
    $aData = [
      'chart' => null,
      'cache' => false
    ];

    if (Cache::has($cache_key))
    {
      $sChartData = Cache::get($cache_key);
      $aData['chart'] = $sChartData;
      $aData['cache'] = true;
    }
    else
    {
      $oStorage = Domain::where('domain', '=', $domain_name)
        ->where('subdomain', '=', $subdomain_name)
        ->first();

      if(!empty($subdomain_name))
      {
        $aDomain = Domain::where('domain', '=', $domain_name)
          ->where('subdomain', '=', $subdomain_name)
          ->first();
        $sTotal = $this->getStorageData('total', $aDomain);
      }
      else
      {
        $sTotal = 0;
        $aDomains = Domain::where('domain', '=', $domain_name)
          ->get();

        foreach($aDomains as $aDomain)
        {
          $sTotal += $this->getStorageData('total', $aDomain);
        }
      }

      $sChartData = $this->convertBytes($sTotal, '<span>', '</span>');

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $sChartData, $expiresAt);
      $aData['chart'] = $sChartData;
    }

    return Response::json($aData, 200);
  }

  protected function getStorageData($type, $aDomain) {
    return Storage::select(DB::raw('sum(size) as size'))
    ->where('type', '=' ,$type)
    ->where('domain', '=', $aDomain->id)
    ->pluck('size');
  }
}
