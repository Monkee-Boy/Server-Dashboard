<?php
class TopController extends BaseController {
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function referers($domain_name, $subdomain_name = null)
  {
    $cache_key = 'top_referers_'.$domain_name.'_'.$subdomain_name;

    if (Cache::has($cache_key))
    {
      $aChartData = Cache::get($cache_key);
      $aData = [
        'chart' => $aChartData,
        'cache' => true
      ];
    }
    else
    {
      $oReferers = Bandwidth::select(DB::raw('count(*) as total'), 'referer')
      ->where('referer', 'NOT REGEXP', DB::raw("'^http[s]*:\/\/[\.a-z_\-]+".str_replace(".","\.",$domain_name)."'"))
      ->whereHas('domain', function($q) use ($domain_name)
      {
        $q->where('domain', $domain_name);
      })
      ->where(DB::raw('date(request_time)'), '>=', DB::raw('date(now()-interval 30 day)'))
      ->groupBy('referer')
      ->orderBy('total', 'desc')
      ->limit(10);

      if(!empty($subdomain_name))
      {
        $oReferers = $oReferers->whereHas('domain', function($q) use ($subdomain_name)
        {
          $q->where('subdomain', $subdomain_name);
        });
      }

      $aReferers = $oReferers->get();

      $aChartData = [];
      foreach($aReferers as $aRow)
      {
        $aChartData[] = [$aRow['total'], $aRow['referer']];
      }

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $aChartData, $expiresAt);
      $aData = [
        'chart' => $aChartData,
        'cache' => false
      ];
    }

    return Response::json($aData, 200);
  }

  public function pages($domain_name, $subdomain_name = null)
  {
    $cache_key = 'top_pages_'.$domain_name.'_'.$subdomain_name;

    if (Cache::has($cache_key))
    {
      $aChartData = Cache::get($cache_key);
      $aData = [
        'chart' => $aChartData,
        'cache' => true
        ];
    }
    else
    {
      $oPages = Bandwidth::select(DB::raw('count(*) as total'), 'url')
      ->whereHas('domain', function($q) use ($domain_name)
      {
        $q->where('domain', $domain_name);
      })
      ->where(DB::raw('date(request_time)'), '>=', DB::raw('date(now()-interval 30 day)'))
      ->groupBy('url')
      ->orderBy('total', 'desc')
      ->limit(10);

      if(!empty($subdomain_name))
      {
        $oPages = $oPages->whereHas('domain', function($q) use ($subdomain_name)
        {
          $q->where('subdomain', $subdomain_name);
        });
      }

      $aPages = $oPages->get();

      $aChartData = [];
      foreach($aPages as $aRow)
      {
        $aChartData[] = [$aRow['total'], $aRow['url']];
      }

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $aChartData, $expiresAt);
      $aData = [
        'chart' => $aChartData,
        'cache' => false
      ];
    }

    return Response::json($aData, 200);
  }
}
