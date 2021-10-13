<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pekerjaan;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $var=\DB::table('user as a')->selectRaw("a.name, b.title as todos_title, b.id as id_todos,
        case 
            when b.completed = true then 'Done'
            when b.completed = false then 'Not Finished'
        else 'Null'
        end as todos_status, c.title as post_title , c.body as post_text")
        ->leftJoin('todos as b', 'b.userid', 'a.id')
        ->leftJoin('posts as c', 'c.todosid', 'b.id')
        ->whereNotNull('b.id')
        ->get();

        return view('soalno2')
        ->with('var', $var);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->ajax()){

            $var=pekerjaan::findOrfail($id)->delete();
            
            if($var){
                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil dihapus',
                    'error'=>''
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal dihapus',
                    'error'=>''
                );
            }

            return $data;
        }
    }

    public function soalno1()
    {
        return view('soalno1');
    }
}
