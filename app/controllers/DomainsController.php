<?php
class DomainsController extends BaseController {
  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
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
      if(Input::get('admin') === '1') {
        // Show domains we DON'T have folders for
        if(in_array($domain['domain'], $aFilterDomains))
        {
          unset($domains[$key]);
        }
      }
      else
      {
        // Show domains we DO have folders for
        if(!in_array($domain['domain'], $aFilterDomains))
        {
          unset($domains[$key]);
        }
      }
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
      if(Input::get('admin') === '1') {
        // Show domains we DON'T have folders for
        if(in_array($subdomain['subdomain'], $aFilterDomains))
        {
          unset($subdomains[$key]);
        }
      }
      else
      {
        // Show domains we DO have folders for
        if(!in_array($subdomain['subdomain'], $aFilterDomains))
        {
          unset($subdomains[$key]);
        }
      }
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
    $subdomain = Domain::where('domain', $domain_name)
      ->where('subdomain', $subdomain_name)
      ->first();

    $this->layout->content = View::make('domains.subdomain', array('subdomain'=>$subdomain));
  }

  public function destroy()
  {

  }
}
