<?php
class StorageController extends BaseController {
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function total($domain_name, $subdomain_name = null)
  {
    $cache_key = 'storage_total_'.$domain_name.'_'.$subdomain_name;
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
      $aDomain = Domain::where('domain', '=', $domain_name)
        ->where('subdomain', '=', $subdomain_name)
        ->first();

      $sTotal = $this->getStorageData('total', $aDomain);
      $sCurrent = $this->getStorageData('current', $aDomain);
      $sShared = $this->getStorageData('shared', $aDomain);
      $sReleases = $this->getStorageData('shared', $aDomain) - $sCurrent;
      $sOther = $sTotal - $sCurrent - $sShared - $sReleases;

      $aChartData = [
        [
          'type' => 'pie',
          'name' => 'Storage Breakdown',
          'data' => [
            ['Current', (float)$sCurrent],
            ['Shared', (float)$sShared],
            ['Releases', (float)$sReleases],
            ['Other', (float)$sOther]
          ]
        ]
      ];

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $aChartData, $expiresAt);
      $aData['chart'] = $aChartData;
    }

    return Response::json($aData, 200);
  }

  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function domain($domain_name)
  {
    $cache_key = 'storage_domain_'.$domain_name;
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
      $aStorage = [];
      $aDomains = Domain::where('domain', '=', $domain_name)
        ->get();

      foreach($aDomains as $aDomain)
      {
        $sTotal = $this->getStorageData('total', $aDomain);
        $aStorage[] = [$aDomain->subdomain, (int)$sTotal];
      }

      usort($aStorage, function($a, $b) {
        if ($a[1] == $b[1]) {
          return 0;
        }
        return ($a[1] < $b[1]) ? -1 : 1;
      });

      $aChartData = [
        [
          'type' => 'pie',
          'name' => 'Subdomain Breakdown',
          'data' => $aStorage
        ]
      ];

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $aChartData, $expiresAt);
      $aData['chart'] = $aChartData;
    }

    return Response::json($aData, 200);
  }

  protected function getStorageData($type, $aDomain) {
    $oStorage = Storage::select(DB::raw('sum(size) as size'))
    ->where('type', '=' ,$type)
    ->where('domain', '=', $aDomain->id);

    return $oStorage->pluck('size');
  }
}
