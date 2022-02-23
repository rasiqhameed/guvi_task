var blog_id = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1];

  $('form').on('submit', function (event) {
    event.preventDefault();
    var form = $(this);
    var form_data = $(this).serialize();

    ajax(DATA_API, form_data+"&blog_id="+blog_id,
    function (data) {
      $('.modal').modal('hide');
      form[0].reset();

      load_comment();
      swal({
        title: "Sucessfull!",
        text: "Comment added successfully",
        icon: "success",
    });
      
    },
    function (err){
      swal({
        title: "Error!",
        text: err,
        icon: "warning",
    })
    });
  });

  load_comment();

  function load_comment() {
    ajax(DATA_API, {
      "func": "get_comments",
      "blog_id": blog_id
    }, function (data) {
      var result = '<h3 class="text-success">Comments</h3><hr/><ul class="comments">';
      result += generate_comments(data);
      result += '</ul>';
      console.log(result);
      $('#display_comment').html(result);
    });

  }


  function generate_comments(data) {
    final = '';
    for (var i = 0; i < data.length; i++) {
      final += generate_comment_ui(data[i]);
      if (data[i]["reply"] != null) {
        final += '<ul class="comments">';
        final += generate_comments(data[i]["reply"]);
        final += '</ul>';
      }
    }
    return final;
  }


  function generate_comment_ui(value) {
    let name = value["user_name"].split('.').length > 1 ? value["user_name"].replace(value["user_name"].split('.')[value["user_name"].split('.').length-1], '') : value["user_name"];

    var html = '<li class="clearfix">';
    html += '<img src="'+BASE_URL+'/api/V1/user/image/?background=0D8ABC&color=fff&name=' + name + '" class="avatar " width="90" height="90" ></img>';
    html += '<div class="post-comments">';
    html += '<p class="meta">' + value["commented_at"] + ' <a href="#!">  ' + value["user_name"] + '</a> <i class="pull-right"><a href="javascript:add_reply(' + value["comment_id"] + ');"><small>Reply</small></a></i></p>';
    html += '<p>' + value["message"] + '</p>';
    html += '</div>';
    html += '</li>';

    return html;
  }

  
  
  function add_reply(id){
    $("#add_comment_id").val(id);
    $("#add_reply_modal").modal("show");
  }
