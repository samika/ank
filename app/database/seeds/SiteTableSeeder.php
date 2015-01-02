<?php


class SiteTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('sites')->delete();
		Site::create([
			'name' => 'Alexander Stubb',
			'party' => 'KOK',
			'area' => 'HELSINKI',
			'number' => '',
			'url' => 'http://www.alexstubb.com/',
			'rssUrl' => 'http://www.alexstubb.com/?feed=rss2&lang=fi',
			'platform' => 'wordpress',
			'lastUpdate' => new DateTime('2014-01-01 00:00:00'),
			'xpath' => '//div[@class="entry-content clearfix"]',
			'elected' => true,
		]);
		Site::create([
			'name' => 'Ville Niinistö',
			'party' => 'VIHR',
			'area' => 'HELSINKI',
			'number' => '',
			'url' => 'http://www.villeniinisto.fi/',
			'rssUrl' => 'http://www.villeniinisto.fi/ajankohtaista/17-blogit?format=feed&type=rss',
			'platform' => 'joomla',
			'lastUpdate' =>  new DateTime('2014-01-01 00:00:00'),
			'xpath' => '//*[@id="componentWrap"]/div[2]/div/div[@class="article-content"]',
			'elected' => true,
		]);
		Site::create([
			'name' => 'Juha Sipilä',
			'party' => 'KES',
			'area' => 'OULU',
			'number' => '',
			'url' => 'http://www.juhasi.fi/',
			'rssUrl' => 'https://kotisivukone.fi/app/feed/rss2.0/juhasipila.kotisivukone.com?blog',
			'platform' => 'kotisivukone',
			'lastUpdate' =>  new DateTime('2014-01-01 00:00:00'),
			'xpath' => '//div[@id="content1"]',
			'elected' => true,
		]);
		Site::create([
			'name' => 'Timo Soini',
			'party' => 'PS',
			'area' => 'UUSIMAA',
			'number' => '',
			'url' => 'http://timosoini.fi',
			'rssUrl' => 'http://timosoini.fi/feed/',
			'platform' => 'wordpress',
			'lastUpdate' => new DateTime('2014-01-01 00:00:00'),
			'xpath' => '//div[@id="content"]/article/div[@class="entry-content"]',
			'elected' => true,
		]);
		Site::create([
			'name' => 'Päivi Räsänen',
			'party' => 'KD',
			'area' => 'UUSIMAA',
			'number' => '',
			'url' => 'http://www.paivirasanen.fi/',
			'rssUrl' => 'http://www.paivirasanen.fi/feed/',
			'platform' => 'wordpress',
			'lastUpdate' =>  new DateTime('2014-01-01 00:00:00'),
			'xpath' => '//div[@id="content"]/article/div[@class="entry-content"]',
			'elected' => true,
		]);
		Site::create([
			'name' => 'Paavo Arhinmäki',
			'party' => 'VAS',
			'area' => 'HELSINKI',
			'number' => '',
			'url' => 'http://www.paavoarhinmaki.fi/',
			'rssUrl' => 'http://feeds.feedburner.com/arhinmaki?format=xml',
			'platform' => 'kelvin.fi',
			'lastUpdate' =>  new DateTime('2014-01-01 00:00:00'),
			'xpath' => '//div[@class="entry-content"]',
			'elected' => true,
		]);

		// Wow, no website or blog*!#¤"!
		Site::create([
			'name' => 'Antti Rinne',
			'party' => 'SDP',
			'area' => 'HELSINKI',
			'number' => '',
			'url' => null,
			'rssUrl' => null,
			'platform' => null,
			'lastUpdate' => null,
			'contentSelector' => null,
			'elected' => true,
		]);
	}

}
