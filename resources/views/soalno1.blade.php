@extends('layouts.app')
 
@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Query No 1</h5>
            <p class="card-text">
            select a."name" , b.title as todos_title,
            case 
                when b.completed = true then 'Done'
                when b.completed = false then 'Not Finished'
            else 'Null'
            end as todos_status,
            c.title as post_title , c.body as post_text 
            from "user" a
            left join todos b on b.userid = a.id 
            left join posts c on c.todosid = b.id 
            where b.id is not null                    
            </p>
        </div>

        <div class="card-body">
            <h5 class="card-title">Lokasi File</h5>
            <p class="card-text">
                imaniprima/JAWABAN_NO_1.txt</br>
                tabel database : imani_prima.sql
            </p>
        </div>
    </div>
@endsection