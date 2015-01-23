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
