<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pekerjaan;

use App\Models\User;

use App\Models\Posts;

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

        $var2=User::all();

        return view('soalno2')
        ->with('var', $var)
        ->with('var2', $var2);
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
        if($request->ajax()){
            $rules=[
                'userid'=>'required', 
                'todos_title'=>'required', 
                'todos_status'=>'required', 
                'post_title'=>'required', 
                'post_text'=>'required', 
            ];
    
            $pesan=[
                'lookup_code.required' => 'Harus diisi',
                'todos_title.required' => 'Harus diisi',
                'todos_status.required' => 'Harus diisi',
                'post_title.required' => 'Harus diisi',
                'post_text.required' => 'Harus diisi',
            ];
    
    
            $validasi=\Validator::make($request->all(),$rules,$pesan);
    
            if($validasi->fails()){
                $data=array(
                    'success'=>false,
                    'pesan'=>'Validasi Gagal',
                    'error'=>$validasi->errors()->first()
                );
            }else{
                $master=new Pekerjaan;
                $master->userid=$request->input('userid');
                $master->title=$request->input('todos_title');
                if($request->input('todos_status')=="Y"){
                    $master->completed=true;
                }else{
                    $master->completed=false;
                }
                $master->save();
                $lastidkerjaan=$master->id;
    
                $postsnya=new Posts;
                $postsnya->title=$request->input('post_text');
                $postsnya->body=$request->input('post_text');
                $postsnya->todosid=$lastidkerjaan;
                $postsnya->save();
    
                $pesan='Data Sudah di Tambah';
    
                return $data=array(
                    'success'=>true,
                    'error'=>$pesan,
                );
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()){

            $var=\DB::table('user as a')->selectRaw("a.name, b.title as todos_title, 
            a.id as id_user, b.id as id_todos, b.completed,
            case 
                when b.completed = true then 'Done'
                when b.completed = false then 'Not Finished'
            else 'Null'
            end as todos_status, c.title as post_title , c.body as post_text")
            ->leftJoin('todos as b', 'b.userid', 'a.id')
            ->leftJoin('posts as c', 'c.todosid', 'b.id')
            ->whereNotNull('b.id')
            ->where('b.id', $id)
            ->get();

            return $var;
        }
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
        if($request->ajax()){
            $rules=[
                'userid'=>'required', 
                'todos_title'=>'required', 
                'todos_status'=>'required', 
                'post_title'=>'required', 
                'post_text'=>'required', 
            ];
    
            $pesan=[
                'lookup_code.required' => 'Harus diisi',
                'todos_title.required' => 'Harus diisi',
                'todos_status.required' => 'Harus diisi',
                'post_title.required' => 'Harus diisi',
                'post_text.required' => 'Harus diisi',
            ];
    
    
            $validasi=\Validator::make($request->all(),$rules,$pesan);
    
            if($validasi->fails()){
                $data=array(
                    'success'=>false,
                    'pesan'=>'Validasi Gagal',
                    'error'=>$validasi->errors()->first()
                );
            }else{
                $master=Pekerjaan::findOrFail($id);
                $master->userid=$request->input('userid');
                $master->title=$request->input('todos_title');
                if($request->input('todos_status')=="Y"){
                    $master->completed=true;
                }else{
                    $master->completed=false;
                }
                $master->save();

                $var=Posts::where('todosid', $id)
                ->update([
                    'title'=>$request->input('post_text'),
                    'body'=>$request->input('post_text')
                ]);
    
                $pesan='Data Sudah di Update';
    
                return $data=array(
                    'success'=>true,
                    'error'=>$pesan,
                );
            }
        }
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
