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

          let user=@json($var2);

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

          $('#add').on('click', function(){
        		var tambah="";

        		tambah+=`
        		<div class="modal fade" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                  <form onsubmit="return false;" enctype="multipart/form-data" id="formTambah">
                      
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Tambah Kerjaan</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                      </div>

                      <div class="modal-body">

                          <div class="form-group">
                              <label class="form-control-label">Name : </label>
                              <select class="form-control" name="userid" id="userid">
                                <option value="">Select Name</option>`;
                                $.each(user, function(a, b){
                                  tambah+=`<option value="${b.id}">${b.name}</option>`;
                                });
                              tambah+=`</select>
                          </div>

                          <div class="form-group">
                              <label class="form-control-label">Todos Title : </label>
                              <input type="text" class="form-control" name="todos_title" placeholder="Todos Title" required>
                          </div>

                          <div class="form-group">
                              <label class="form-control-label">Todos Status : </label>
                              <select class="form-control" name="todos_status" id="todos_status">
                                <option value="">Select Status</option>
                                <option value="Y">Done</option>
                                <option value="N">Not Finished</option>
                              </select>
                          </div>

                          <div class="form-group">
                              <label class="form-control-label">Post Title : </label>
                              <input type="text" class="form-control" name="post_title" placeholder="Post Title" required>
                          </div>

                          <div class="form-group">
                              <label for="message-text" class="form-control-label">Post Text :</label>
                              <textarea class="form-control" name="post_text" placeholder="Post Text"></textarea>
                          </div>

                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                  </form>
                </div>
            </div>`;

            $('#tampilmodal').empty().html(tambah);
            $('#modal_tambah').modal('show');
        	});

          $(document).on("submit","#formTambah",function(e){
		        var data = new FormData(this);

		        if($("#formTambah")[0].checkValidity()) {
		          e.preventDefault();
		          $.ajax({
		            url         : "{{URL::to('soal2')}}",
		            data        : data,
		            type        : 'post',
		            dataType    : 'JSON',
		            contentType : false,
		            cache       : false,
		            processData : false,
		            beforeSend  : function (){
		              Swal.fire({
		                title: 'Insert Data...',
		                html: 'Please wait...',
		                allowEscapeKey: false,
		                allowOutsideClick: false,
		                didOpen: () => {
		                  Swal.showLoading()
		                }
		              });
		            },
		            success : function (data) {
		              if(data.success==true){
		                Swal.fire({
		                  title: 'Success...!',
		                  html: 'Data Added',
		                  allowEscapeKey: false,
		                  allowOutsideClick: false,
		                });
		                $('#modal_tambah').modal('hide');
                        document.location.reload();
		              }else{
		                Swal.fire({
		                  title: 'Validasi Error...!',
		                  html: data.pesan,
		                  allowEscapeKey: false,
		                  allowOutsideClick: false,
		                });
		              }
		            },
		            error   :function() {
		              Swal.fire({
		                title: 'Error Data...!',
		                html: 'Error',
		                allowEscapeKey: false,
		                allowOutsideClick: false,
		              });
		            }
		          });
		        }else console.log("invalid form");
	      	});

          $(document).on("click", ".edit", function(e){

            kode=$(this).attr("kode");        

            $.ajax({
            url:"{{URL::to('soal2')}}/"+kode,
              type:"GET",
              success:function(result){
                var edit="";

                edit+=`
                <div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                      <form onsubmit="return false;" enctype="multipart/form-data" id="formEdit">
                          
                          <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Edit Kerjaan</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                              </button>
                          </div>

                          <div class="modal-body">

                              <div class="form-group">
                                  <label class="form-control-label">Name : </label>
                                  <select class="form-control" name="userid" id="userid">
                                    <option value="">Select Name</option>`;
                                    $.each(user, function(a, b){
                                      if(result[0].id_user==b.id){
                                        edit+=`<option value="${b.id}" selected>${b.name}</option>`;
                                      }else{
                                        edit+=`<option value="${b.id}">${b.name}</option>`;
                                      }
                                    });
                                  edit+=`</select>
                              </div>

                              <div class="form-group">
                                  <label class="form-control-label">Todos Title : </label>
                                  <input type="text" value="${result[0].todos_title}" class="form-control" name="todos_title" placeholder="Todos Title" required>
                              </div>

                              <div class="form-group">
                                  <label class="form-control-label">Todos Status : </label>
                                  <select class="form-control" name="todos_status" id="todos_status">
                                    <option value="">Select Status</option>`;
                                    if(result[0].completed==true){
                                      edit+=`<option value="Y" selected>Done</option>
                                      <option value="N">Not Finished</option>`;
                                    }else{
                                      edit+=`<option value="Y">Done</option>
                                      <option value="N">Not Finished</option>`;
                                    }
                                  edit+=`</select>
                              </div>

                              <div class="form-group">
                                  <label class="form-control-label">Post Title : </label>
                                  <input type="text" value="${result[0].post_title}" class="form-control" name="post_title" placeholder="Post Title" required>
                              </div>

                              <div class="form-group">
                                  <label for="message-text" class="form-control-label">Post Text :</label>
                                  <textarea class="form-control" name="post_text" placeholder="Post Text">
                                    ${result[0].post_text}
                                  </textarea>
                              </div>

                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-primary">Save</button>
                          </div>
                      </form>
                    </div>
                </div>`;

                $('#tampilmodal').empty().html(edit);
                $('#modal_edit').modal('show');               
              }
            });  
          });

          $(document).on("submit","#formEdit",function(e){

            var data = new FormData(this);

            var linkurl="{{URL::to('soal2')}}/"+kode;

            data.append("_method","PUT");      

            if($("#formEdit")[0].checkValidity()) {
              e.preventDefault();
              $.ajax({
                url         : linkurl,
                data        : data,
                type        : 'post',
                dataType    : 'JSON',
                contentType : false,
                cache       : false,
                processData : false,
                beforeSend  : function (){
                      Swal.fire({
                        title: 'Update Data...',
                        html: 'Please wait...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                          Swal.showLoading()
                        }
                      });
                    },
                    success : function (data) {
                      if(data.success==true){
                        Swal.fire({
                          title: 'Success...!',
                          html: 'Data Update',
                          allowEscapeKey: false,
                          allowOutsideClick: false,
                        });
                        $('#modal_edit').modal('hide');
                            document.location.reload();
                      }else{
                        Swal.fire({
                          title: 'Validasi Error...!',
                          html: data.pesan,
                          allowEscapeKey: false,
                          allowOutsideClick: false,
                        });
                      }
                    },
                    error   :function() {
                      Swal.fire({
                        title: 'Error Data...!',
                        html: 'Error',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                      });
                    }
                });
            }else console.log("invalid form");
          });
          
        });
    </script>
@endsection