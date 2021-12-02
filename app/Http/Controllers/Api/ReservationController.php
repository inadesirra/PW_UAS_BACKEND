<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all(); 

        if(count($reservations)> 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $reservations
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 

    }

    public function show($id)
    {
        $reservation = Reservation::find($id); 

        if(!is_null($reservation)) {
            return response([
                'message' => 'Retrieve Reservation Success',
                'data' => $reservation
            ], 200);
        } 

        return response([
            'message' => 'Reservation Not Found',
            'data' => null
        ], 404); 
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); 
        $validate = Validator::make($storeData, [
            'first_name' => 'required|max:60|unique:reservations',
            'last_name' => 'required',
            'no_telp' => 'required|numeric',
            'tgl_reservasi' => 'required',
            'waktu' => 'required',
            'jumlah' => 'required|numeric'   
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
            $reservation = Reservation::create($storeData);
            return response([
                'message' => 'Add Reservation Success',
                'data' => $reservation
            ], 200); 
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id); 
        
        if (is_null($reservation)) {
            return response([
                'message' =>'Reservation Not Found',
                'data' => null
            ], 404);
        }

        if($reservation->delete()) {
            return response([
                'message' =>'Delete Reservation Success',
                'data' => $reservation
            ], 200); 
        } 

        return response([
            'message' => 'Delete Reservation Failed',
            'data' => null,
        ], 400); 

    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id); 
        if (is_null($reservation)) {
            return response([
                'message' =>'Reservation Not Found',
                'data' => null
            ], 404);
        }

    
        $updateData = $request->all(); 
        $validate = Validator::make($updateData, [
            'first_name' => ['max:60', 'required', Rule::unique('reservations')->ignore($reservation)],
            'last_name' => 'required',
            'no_telp' => 'required|numeric',
            'tgl_reservasi' => 'required',
            'waktu' => 'required',
            'jumlah' => 'required|numeric' 
        ]); 

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); 
        
        $reservation->nama_makanan = $updateData['nama_makanan'];
        $reservation->kategori = $updateData['kategori'];
        $reservation->harga = $updateData['harga'];
        $reservation->status = $updateData['status'];
        $reservation->foto = $updateData['foto'];

        if($reservation->save()) {
            return response([
                'message' => 'Update Reservation Success',
                'data' =>$reservation
            ], 200);
        } 
        return response([
            'message' => 'Update Reservation Failed',
            'data' => null,
        ], 400); 
    }
}
