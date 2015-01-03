<?php

class BaseController extends Controller {


	protected $layout = 'layout';

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}

		// I guess this is in wrong place.
		$content['areas'] = Config::get('content.area');
		$content['parties']= Config::get('content.party');
		View::share('content', $content);
	}


}
