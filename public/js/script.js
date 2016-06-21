$( document ).ready(function() {

  var baseUrl = location.origin = location.protocol + "//" + location.host;

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

  function error(response){
    $('.token').children('#token').prop('value', '');
    alert('Invalid Credentials')
  }

  function login(response){
    $('.token').show().children('#token').prop('value', response.token);
    $('#example').DataTable( {
        ajax: {
          type: 'POST',
          url: baseUrl + '/datatables',
          beforeSend: function (request) {
              request.setRequestHeader('Authorization', 'Bearer ' + response.token);
          }
        },
        processing: true,
        serverSide: true,
        columns: [
            { data: "id" },
            { data: "first_name" },
            { data: "last_name" },
            { data: "created_at" },
            { data: "created_at" },
            // { data: null, render: function ( data, type, row ) {
            //   return "<span rel=" + data.id + "><a href=\"#\">Edit</a> | <a href=\"#\">Delete</a></span>"
            // }},
        ],
    });
  }




});
