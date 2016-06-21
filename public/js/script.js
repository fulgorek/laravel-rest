$(document).ready(function() {

  var baseUrl = location.origin = location.protocol + "//" + location.host;
  var table;

  // Login form
  $('.form-token form').on('submit', function(e){
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: baseUrl + '/api/v1/auth/login',
      data: $(this).serialize(),
      dataType: 'json',
      success: login,
      error: error
    });
  });

  // Handle Error in login
  function error(response){
    $('.token').hide().children('#token').prop('value', '');
    $('.data-list').hide();
    $('.form-token button').text('Sign in');
    alert('Invalid Credentials');
  }

  // Handle Success in login
  function login(response){
    $('.data-list').show();
    $('.token').show().children('#token').prop('value', response.token);
    $('.form-token button').text('Refresh Token');
    if( $.fn.DataTable.isDataTable('#example') ){
      // table.ajax.reload();
    } else {
      initializeTable();
    }
  }

  // Initialize Tableforms
  function initializeTable(){
    table = $('#example').DataTable({
        dom: 'l<"toolbar">frtip',
        processing: true,
        serverSide: true,
        ajax: {
          type: 'POST',
          url: baseUrl + '/datatables',
          beforeSend: function (request) {
              request.setRequestHeader('Authorization', 'Bearer ' + $('#token').prop('value'));
          }
        },
        columns: [
            { data: "id" },
            { data: "first_name" },
            { data: "last_name" },
            { data: "created_at" },
            { data: null, orderable: false, searchable: false, render: function ( data, type, row ) {
              return "<span data-id=" + data.id + "><a href=\"#\" class=\"edit\">Edit</a> | <a href=\"#\" class=\"delete\">Delete</a></span>"
            }},
        ],
        initComplete: function(){
          $("div.toolbar").html('<a href="#" style="margin:0 0 0 20px;">Add New</a>');
        }
    });
  }

  // Handle Edit
  $('body').on('click', '.edit', function(e){
    e.preventDefault();
    $('#myModal h4').text('Edit Record');
    $.ajax({
      type: "GET",
      beforeSend: function (request) {
          request.setRequestHeader('Authorization', 'Bearer ' + $('#token').prop('value'));
      },
      url: baseUrl + '/api/v1/names/' + $(this).parent().data('id'),
      success: function(response){
        if (response.length > 0){
          $.each(response[0], function(key, value){
            $('#myModal form input[name='+key+']').prop('value', value);
          });
          $('#myModal').modal('show');
        } else {
          alert("Somethig Went Wrong!");
        }
      }
    });
  });

  // Handle Delete records
  $('body').on('click', '.delete', function(e){
    e.preventDefault();
    if ( confirm('Do you really want to delete this record?') === false )
      return false;
    $.ajax({
      type: "DELETE",
      beforeSend: function (request) {
          request.setRequestHeader('Authorization', 'Bearer ' + $('#token').prop('value'));
      },
      url: baseUrl + '/api/v1/names/' + $(this).parent().data('id'),
      success: function(response){
        if(response.deleted === true){
          table.ajax.reload();
          console.log('Record Deleted');
        } else {
          alert("Somethig Went Wrong!");
        }
      }
    });
  });

  // Handle the Add New link
  $('body').on('click', '.toolbar a', function(e){
    e.preventDefault();
    $('#myModal h4').text('Add New Record');
    $('#myModal input').prop('value', '');
    $("#myModal").modal('show');
  });

  // Handle Save and Edit
  $('body').on('click', '#myModal .btn.btn-primary', function(e){
    e.preventDefault();
    var id = $('#myModal form input[name=id]').prop('value');
    var method = id === '' ? 'POST' : 'PUT';
    var url = baseUrl + '/api/v1/names';
    var data = $('#myModal form').serialize();
    if ( method === 'PUT' ){
      url += '/' + id;
    }
    $.ajax({
      type: method,
      data: data,
      beforeSend: function (request) {
        request.setRequestHeader('Authorization', 'Bearer ' + $('#token').prop('value'));
      },
      url: url,
      success: function(response){
        switch(method){
          case 'POST':
            if(response.created === true){
              table.ajax.reload();
              $('#myModal form')[0].reset();
              alert("Record Saved Successfully!");
            } else {
              alert("Somethig Went Wrong!");
            }
          break;
          case 'PUT':
            if(response.updated === true){
              table.ajax.reload();
              alert("Record Edited Successfully!");
            } else {
              alert("Nothing Changed!");
            }
          break;
        }
      },
      error: function(response){
        alert(response.responseJSON.error.join("\n"))
      }
    });
  });

});
