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
			'by' => 'required'
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
			$notification->hipchat = Input::get('hipchat');
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
		$notification = Notification::find($id);
		$this->layout->content = View::make('notifications.edit', array('notification' => $notification));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules = array(
			'by' => 'required'
		);

		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::to('notifications.edit', $id)->withErrors($validator)->withInput(Input::all());
		} else {
			$notification = Notification::find($id);
			$notification->what = Input::get('what');
			$notification->how = Input::get('how');
			$notification->by = Input::get('by');
			$notification->by_measure = Input::get('by_measure');
			$notification->where = Input::get('where');
			$notification->hipchat = Input::get('hipchat');
			$notification->save();
			Session::flash('message', 'Successfully updated notification!');
			return Redirect::to('notifications');
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Notification::find($id)->delete();
		Session::flash('message', 'Successfully removed notification!');
		return Redirect::to('notifications');
	}


}
