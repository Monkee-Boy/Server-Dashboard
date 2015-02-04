<?php
class DomainsController extends BaseController {
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {
    $cache_key = "domains_index";

    if(Cache::has($cache_key))
    {
      $domains = Cache::get($cache_key);
    }
    else
    {
      $domains = Domain::select('domain', DB::raw('count(*) as `subdomains`'))
        ->groupBy('domain')
        ->orderBy('domain')
        ->get();

      // Get filter domain list
      if(App::environment('local'))
      {
        $aFilterDomains = array(
          'notbatman.com',
          'defvayne23.com'
        );
      }
      elseif(App::environment('production'))
      {
        $aFilterDomains = scandir('/var/www/');

        // Remove . and .. folders
        unset($aFilterDomains[0]);
        unset($aFilterDomains[1]);
      }

      // Filter domain list
      foreach($domains as $key=>$domain)
      {
        // Filter out domains
        if(Input::get('admin') === '1') {
          // Show domains we DON'T have folders for
          if(in_array($domain['domain'], $aFilterDomains))
          {
            unset($domains[$key]);
            continue;
          }
        }
        else
        {
          // Show domains we DO have folders for
          if(!in_array($domain['domain'], $aFilterDomains))
          {
            unset($domains[$key]);
            continue;
          }
        }

        // Get domain bandwidth in past 30 days
        $bandwidth = Bandwidth::select(DB::raw('(sum(bytes_received)+sum(bytes_sent)) as total_bytes'))
          ->whereHas('domain', function($q) use ($domain)
          {
            $q->where('domain', $domain['domain']);
          })
          ->where(DB::raw('date(request_time)'), '>=', DB::raw('date(now()-interval 30 day)'))
          ->first();

        $domains[$key]['bandwidth'] = (!empty($bandwidth))?$this->convertBytes($bandwidth->total_bytes):null;

        // Get domain total storage
        $storage = Storage::select(DB::raw('sum(size) as total_storage'))
          ->whereHas('domain', function($q) use ($domain)
          {
            $q->where('domain', $domain['domain']);
          })
          ->where('type', 'total')
          ->first();

        $domains[$key]['storage'] = (!empty($storage))?$this->convertBytes($storage->total_storage):null;
      }

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $domains, $expiresAt);
    }

    $this->layout->content = View::make('domains.index', array('domains' => $domains));
  }

  /**
  * Displays overall domain info and list of subdomains.
  *
  * @param  string  $domain_name
  * @return Response
  */
  public function domain($domain_name)
  {
    $cache_key = "domains_domain_".$domain_name;

    if(Cache::has($cache_key))
    {
      $subdomains = Cache::get($cache_key);
    }
    else
    {
      $subdomains = Domain::where('domain', $domain_name)
        ->orderBy('subdomain')
        ->get();

      // Get filter domain list
      if(App::environment('local'))
      {
        $aFilterDomains = array(
          '_',
          'www'
        );
      }
      elseif(App::environment('production'))
      {
        $aFilterDomains = scandir('/var/www/'.preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $domain_name).'/');

        // Remove . and .. folders
        unset($aFilterDomains[0]);
        unset($aFilterDomains[1]);
      }

      // Filter domain list
      foreach($subdomains as $key=>$subdomain)
      {
        // Filter out domains
        if(Input::get('admin') === '1') {
          // Show domains we DON'T have folders for
          if(in_array($subdomain['subdomain'], $aFilterDomains))
          {
            unset($subdomains[$key]);
            continue;
          }
        }
        else
        {
          // Show domains we DO have folders for
          if(!in_array($subdomain['subdomain'], $aFilterDomains))
          {
            unset($subdomains[$key]);
            continue;
          }
        }

        // Get subdomain bandwidth in past 30 days
        $bandwidth = Bandwidth::select(DB::raw('(sum(bytes_received)+sum(bytes_sent)) as total_bytes'))
          ->where('domain','=',$subdomain->id)
          ->where(DB::raw('date(request_time)'), '>=', DB::raw('date(now()-interval 30 day)'))
          ->groupBy('domain')
          ->first();

        $subdomains[$key]['bandwidth'] = (!empty($bandwidth))?$this->convertBytes($bandwidth->total_bytes):null;

        // Get domain total storage
        $storage = Storage::select('size')
        ->where('domain','=',$subdomain->id)
        ->where('type', 'total')
        ->first();

        $subdomains[$key]['storage'] = (!empty($storage))?$this->convertBytes($storage->size):null;
      }

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $subdomains, $expiresAt);
    }

    $this->layout->content = View::make('domains.domain', array('domain_name' => $domain_name, 'subdomains' => $subdomains));
  }

  /**
  * Displays subdomain info.
  *
  * @param string $domain_name
  * @param string $subdomain_name
  * @return Response
  */
  public function subdomain($domain_name, $subdomain_name)
  {
    $cache_key = "domains_subdomain_".$domain_name."_".$subdomain_name;

    if(Cache::has($cache_key))
    {
      $subdomain = Cache::get($cache_key);
    }
    else
    {
      $subdomain = Domain::where('domain', $domain_name)
        ->where('subdomain', $subdomain_name)
        ->first();

      $expiresAt = new DateTime();
      $expiresAt = $expiresAt->modify('+1 Hour');
      Cache::put($cache_key, $subdomain, $expiresAt);
    }

    $this->layout->content = View::make('domains.subdomain', array('subdomain'=>$subdomain));
  }

  public function destroy()
  {

  }
}
