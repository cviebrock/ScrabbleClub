<?php

class Sitemap_Controller extends Base_Controller {

  public $layout = null;

	public function get_index()
	{

    $cache_in_minutes = 1440; // one day

    $data = Cache::remember(
      'page.sitemap',
      $this->build(),
      $cache_in_minutes
    );

    $age = (int) (new DateTime())->format('U')
        - (int) $data['date']->format('U');

    $headers = [
      'Content-Type'  => 'application/xml',
      'Cache-Control' => 'max-age=' . ((($cache_in_minutes+5) * 60) - $age), // +5 to give overlap
      'Last-Modified' => $data['date']->format(DATE_RFC2822)
    ];

    return Response::make(
      $data['map'],
      200,
      $headers
    );
  }


  protected function build()
  {

    $now = new DateTime();

    $routes = array_merge(
      $this->buildBaseRoutes(),
      $this->buildNewsRoutes(),
      $this->buildClubRoutes(),
      $this->buildPlayersRoutes(),
      $this->buildBingoRoutes(),
      $this->buildWordlistRoutes()
    );

    ob_start();

    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    foreach($routes as $route) {
      echo '  <url>' . PHP_EOL;
      $r = array_filter($route);
      foreach ($r as $k=>$v) {
        printf('    <%1$s>%2$s</%1$s>'.PHP_EOL, $k, htmlspecialchars($v));
      }
      echo '  </url>' . PHP_EOL;

    }

    echo '</urlset>' . PHP_EOL;

    return [
      'date' => new DateTime(),
      'map' => ob_get_clean(),
    ];
  }

  protected function buildBaseRoutes()
  {
    return [
      [
        'loc'        => URL::to_route('home'),
        'changefreq' => 'weekly',
        'priority'   => 1
      ],
      [
        'loc'        => URL::to_action('about'),
        'changefreq' => 'monthly',
        'priority'   => 0.8
      ],
      [
        'loc'        => URL::to_route('privacy'),
        'changefreq' => 'yearly'
      ],
      [
        'loc'        => URL::to_action('about@resources'),
        'changefreq' => 'monthly',
        'priority'   => 0.5
      ],
    ];

  }

  protected function buildNewsRoutes()
  {
    $now = new DateTime();

    // News
    $routes = [
      ['loc'=>URL::to_route('news_index'), 'changefreq'=>'weekly', 'priority'=>0.6]
    ];

    $news = News::where('active','=',true)->get();

    foreach ($news as $n) {
      $date = new DateTime($n->date);
      $age = $date->diff($now);

      $r = [
        'loc'     => URL::to_route('news_item', array($n->id, $n->slug)),
        'lastmod' => substr($n->updated_at,0,10)
      ];

      if ($age->y>0) {
        $r['changefreq'] = 'yearly';
        $r['priority'] = '0.2';
      } elseif ($age->m>0) {
        $r['changefreq'] = 'monthly';
        $r['priority'] = '0.4';
      } elseif ($age->d>15) {
        $r['changefreq'] = 'weekly';
        $r['priority'] = '0.6';
      } else {
        $r['changefreq'] = 'daily';
        $r['priority'] = '0.8';
      }

      $routes[] = $r;
    }

    return $routes;
  }

  protected function buildClubRoutes()
  {
    $routes = [
      [
        'loc' => URL::to_route('club_index'),
      ]
    ];

    foreach (get_year_range(true) as $year) {
      $routes[] = [
        'loc'     => URL::to_route('club_index', array($year)),
        'lastmod' => $year . '-12-31'
      ];
    }

    foreach (array_keys(all_game_dates()) as $week) {
      $routes[] = [
        'loc'     => URL::to_route('club_summary', array($week)),
        'lastmod' => $week
      ];
    }

    return $routes;
  }

  protected function buildBingoRoutes()
  {
    $routes = [
      [
        'loc' => URL::to_route('bingo'),
      ]
    ];

    foreach (get_year_range(true) as $year) {
      $r = [
        'loc'     => URL::to_route('bingo', array($year)),
      ];

      if ($year !== Config::get('scrabble.current_year')) {
        $r['lastmod'] = $year . '-12-31';
        $r['priority'] = 0.2;
      }
      $routes[] = $r;
    }

    return $routes;
  }

  protected function buildPlayersRoutes()
  {
    $years = get_year_range(true);

    foreach ($years as $year) {
      $lastgame = Game::order_by('date', 'desc')
        ->where(DB::raw('YEAR(date)'), '=', $year)
        ->take(1)
        ->first();

      $routes[] = [
        'loc'     => URL::to_route('players', array($year)),
        'lastmod' => $lastgame ? $lastgame->date : null,
        'priority' => $year == Config::get('scrabble.current_year') ? '0.8' : null,
      ];
    }


    $players = DB::query('SELECT
        p.id,
        MAX(g.date) AS last_played_date
        FROM players p
          JOIN games g ON (p.id=g.player_id)
        GROUP BY p.id
      ');

    $actions = [
      'players.details',
      'players.games',
      'players.ratings',
      'players.bingos'
    ];


    array_unshift($years, null);

    foreach($players as $p) {
      foreach($years as $year) {
        foreach($actions as $action) {
          $routes[] = [
            'loc' => URL::to_route($action, array($p->id, $year))
          ];
        }
      }
    }

    // Route::get('players/(:num)/details/(:num?)', array('as'=>'players.details', 'uses'=>'players@details'));
    // Route::get('players/(:num)/games/(:num?)', array('as'=>'players.games', 'uses'=>'players@games'));
    // Route::get('players/(:num)/ratings/(:num?)', array('as'=>'players.ratings', 'uses'=>'players@ratings'));
    // Route::get('players/(:num)/bingos/(:num?)', array('as'=>'players.bingos', 'uses'=>'players@bingos'));

    return $routes;
  }

  protected function buildWordlistRoutes()
  {

    $routes = [
      [
        'loc' => URL::to_action('wordlists'),
        'changefreq' => 'yearly',
      ]
    ];

    require_once __DIR__ . '/wordlists.php';

    $stems = Wordlists_Controller::STEM67;

    foreach($stems as $stem) {
      $routes[] = [
        'loc' => URL::to_action('wordlists@stem', [$stem]),
        'changefreq' => 'yearly',
        'priority' => 0.2,
      ];
    }

    return $routes;
  }


}
