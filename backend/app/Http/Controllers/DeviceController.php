<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Repositories\DeviceRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class DeviceController extends AppBaseController
{
    /** @var  DeviceRepository */
    private $deviceRepository;

    public function __construct(DeviceRepository $deviceRepo)
    {
        $this->deviceRepository = $deviceRepo;
    }

    /**
     * Display a listing of the Device.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $devices = $this->deviceRepository->all();

        return view('devices.index')
            ->with('devices', $devices);
    }

    /**
     * Show the form for creating a new Device.
     *
     * @return Response
     */
    public function create()
    {
        return view('devices.create');
    }

    /**
     * Store a newly created Device in storage.
     *
     * @param CreateDeviceRequest $request
     *
     * @return Response
     */
    public function store(CreateDeviceRequest $request)
    {
        $input = $request->all();
         /** @var  \App\Models\Device $device */
        $device = $this->deviceRepository->create($input);
        $device->pc_accounts()->attach(request()->pc_account_id);
        Flash::success('Device saved successfully.');

        return redirect(route('devices.index'));
    }

    /**
     * Display the specified Device.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $device = $this->deviceRepository->find($id);

        if (empty($device)) {
            Flash::error('Device not found');

            return redirect(route('devices.index'));
        }

        return view('devices.show')->with('device', $device);
    }

    /**
     * Show the form for editing the specified Device.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $device = $this->deviceRepository->find($id);

        if (empty($device)) {
            Flash::error('Device not found');

            return redirect(route('devices.index'));
        }

        return view('devices.edit')->with('device', $device);
    }

    /**
     * Update the specified Device in storage.
     *
     * @param int $id
     * @param UpdateDeviceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDeviceRequest $request)
    {
        $device = $this->deviceRepository->find($id);

        if (empty($device)) {
            Flash::error('Device not found');

            return redirect(route('devices.index'));
        }

        $device = $this->deviceRepository->update($request->all(), $id);

        Flash::success('Device updated successfully.');

        return redirect(route('devices.index'));
    }

    /**
     * Remove the specified Device from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $device = $this->deviceRepository->find($id);

        if (empty($device)) {
            Flash::error('Device not found');

            return redirect(route('devices.index'));
        }

        $this->deviceRepository->delete($id);

        Flash::success('Device deleted successfully.');

        return redirect(route('devices.index'));
    }
}
