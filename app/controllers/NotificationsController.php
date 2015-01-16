<?php

class NotificationsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$notifications = Notification::all();

		$this->layout->content = View::make('notifications.index', array('notifications'=>$notifications));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->layout->content = View::make('notifications.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
			'by' => 'required',
			'where' => 'required'
		);

		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('notifications.create')->withErrors($validator)->withInput(Input::all());
		} else {
			$notification = new Notification;
			$notification->what = Input::get('what');
			$notification->how = Input::get('how');
			$notification->by = Input::get('by');
			$notification->by_measure = Input::get('by_measure');
			$notification->where = Input::get('where');
			$notification->save();
			Session::flash('message', 'Successfully created notification!');
			return Redirect::to('notifications');
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
