<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Travelling;

class TravellingController extends Controller
{
    public function index(){
        $travellings = Travelling::all(); //Mengambil semua data Travelling

        if(count($travellings) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $travellings
            ], 200);
        } //Return data semua travelling dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data travelling kosong
    }

    //Method untuk menampilkan 1 data travelling (SEARCH)
    public function show($id){
        $travellings = Travelling::find($id); //Mencari data travelling berdasarkan id

        if(!is_null($travellings)){
            return response([
                'message' => 'Retrieve Travelling Success',
                'data' => $travellings
            ], 200);
        } //Return data semua travelling dalam bentuk JSON

        return response([
            'message' => 'Travelling Not Found',
            'data' => null
        ], 400); //Return message data travelling kosong
    }

    //Method untuk menambah 1 data travelling baru (CREATE)
    public function store(Request $request){
        $storeData = $request->all(); //Mengambil semua input dari API Client
        $validate = Validator::make($storeData, [
            'namaDestinasi' => 'required|max:60|regex:/^[\pL\s\-]+$/u',
            'namaPengguna' => 'required|max:60|regex:/^[\pL\s\-]+$/u',
            'penilaian' => 'required|digits_between:0,10',
            'alasan' => 'required'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $travelling = Travelling::create($storeData);

        return response([
            'message' => 'Add Travelling Success',
            'data' => $travelling
        ], 200); //Return message data travelling baru dalam bentuk JSON
    }

    //Method untuk menghapus 1 data product (DELETE)
    public function destroy($id){
        $travelling = Travelling::find($id); //Mencari data product berdasarkan id

        if(is_null($travelling)){
            return response([
                'message' => 'Travelling Not Found',
                'date' => null
            ], 404);
        } //Return message saat data travelling tidak ditemukan

        if($travelling->delete()){
            return response([
                'message' => 'Delete Travelling Success',
                'data' => $travelling
            ], 200);
        } //Return message saat berhasil menghapus data travelling

        return response([
            'message' => 'Delete Travelling Failed',
            'data' => null,
        ], 400);
    }

    //Method untuk mengubah 1 data travelling (UPDATE)
    public function update(Request $request, $id){
        $travelling = Travelling::find($id); //Mencari data travelling berdasarkan id

        if(is_null($travelling)){
            return response([
                'message' => 'Travelling Not Found',
                'data' => null
            ], 404);
        } //Return message saat data travelling tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'namaDestinasi' => 'required|max:60|regex:/^[\pL\s\-]+$/u',
            'namaPengguna' => 'required|max:60|regex:/^[\pL\s\-]+$/u',
            'penilaian' => 'required|digits_between:0,10',
            'alasan' => 'required'
        ]); //Membuat rule validasi input

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $travelling->namaDestinasi = $updateData['namaDestinasi']; //Edit Nama Kelas
        $travelling->namaPengguna = $updateData['namaPengguna']; //Edit Kode
        $travelling->penilaian = $updateData['penilaian']; //Edit Biaya Pendaftaran
        $travelling->alasan = $updateData['alasan']; //Edit Kapasitas

        if($travelling->save()){
            return response([
                'message' => 'Update Travelling Success',
                'data' => $travelling
            ], 200);
        } //Return data travelling yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Travelling Failed',
            'data' => null
        ], 400);
    }
}
