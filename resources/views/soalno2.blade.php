@extends('layouts.app')
 
@section('content')
    <div class="card">
      <div class="shadow p-3 mb-5 bg-white rounded">
          <h5 class="card-header d-flex justify-content-between align-items-center">
          List Pekerjaan
          <button type="button" class="btn btn-primary" id="add"><i class="fa fa-add"></i></button>
        </h5>
        <br>
          <div class="row">
              <div class="col-12 tampiltabel"></div>
          </div>
      </div>
    </div>
    <div id="tampilmodal"></div>      
@endsection

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function(){

          let pekerjaan=@json($var);

          function showData(){
        		var html="";

        		html+=`                	
              <table class="table" id="tabelpekerjaan">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Todos Title</th>
                    <th>Todos Status</th>
                    <th>Post Title</th>
                    <th>Post Text</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>`;
                    $.each(pekerjaan, function(a,b){
                    html+=
                    `<tr>
                      <td>${a+1}</td>
                      <td>${b.name}</td>
                      <td>${b.todos_title}</td>
                      <td>${b.todos_status}</td>
                      <td>${b.post_title}</td>
                      <td>${b.post_text}</td>
                      <td>
                        <a href="javascript:void(0)" kode="${b.id_todos}" class="btn btn-warning edit" title="Edit">
                          <i class="fa fa-pencil"></i>
                        </a>

                        <a href="javascript:void(0)" kode="${b.id_todos}" class="btn btn-danger hapus" title="Hapus">
                          <i class="fa fa-trash"></i>
                        </a>
                      </td>
                    </tr>`;
                    });
                html+=`</tbody>
            </table>`;

            $('.tampiltabel').html(html);
            $('#tabelpekerjaan').DataTable();
        	}

          showData();

          $(document).on("click", ".hapus", function(){
              
              var kode=$(this).attr('kode');

              Swal.fire({
                title: "Are you sure?",
                text: "Delete data !",
                type: "warning",
                showDenyButton: true,
                confirmButtonColor: "#DD6B55",
                denyButtonColor: "#757575",
                confirmButtonText: "Yes, Let's do it!",
                denyButtonText: "No, cancel!",
              }).then((result) => {
                if (result.isConfirmed) {
                  $.ajax({
                    url:"{{URL::to('soal2')}}/"+kode,
                    type:"DELETE",
                    success:function(result){
                      if(result.success==true){
                        Swal.fire('Deleted!', result.pesan, 'success');
                        document.location.reload();
                      }else{
                        Swal.fire("Error!", result.pesan, "error");
                      }
                    }
                  })
                } else if (result.isDenied) {
                  Swal.fire('Your data is safe :)', '', 'info')
                }
              });
            });
          
        });
    </script>
@endsection