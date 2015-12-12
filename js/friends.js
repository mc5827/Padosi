function getData() {
    var response;
    $.ajax({
          url: "php/blockHoodList.php",
            cache: false,
            type: "GET",
            crossDomain: true,
            async:false,
            data: {
              "choice" : encodeURIComponent("Friends")
            },
            success: function(data) {
                  //response = JSON.parse(data);
                  //alert(JSON.stringify(response));
                  alert(data);
            },
            error: function(xhr) {
                alert(JSON.stringify(xhr));
            }
      });
      //alert(response[category]);
      return response;
}
function displayData(list) {
  
  for(i=0;i<list.length;i++) {
    if(list[i][2] == 'pending') {
      var $input = $('<a class="list-group-item"><h4 class="list-group-item-heading">'+list[i][0]+'</h4><p class="list-group-item-text">'+list[i][1]+'</p><button type="button" class="btn btn-sm btn-primary">Pending</button></a>');
      $input.appendTo($("#display"));
    } else if(list[i][2] == 'friend'){
      var $input = $('<a class="list-group-item"><h4 class="list-group-item-heading">'+list[i][0]+'</h4><p class="list-group-item-text">'+list[i][1]+'</p><button type="button" class="btn btn-sm btn-primary">Friend</button></a>');
      $input.appendTo($("#display"));
    } else {
      var $input = $('<a class="list-group-item"><h4 class="list-group-item-heading">'+list[i][0]+'</h4><p class="list-group-item-text">'+list[i][1]+'</p><button type="button" data-receiver="'+list[i][0]+'" id="sendRequest" class="btn btn-sm btn-primary">Send Request</button></a>');
      $input.appendTo($("#display"));
    }
  }
}
function insertRequestIntoTable(receiver) {
  $.ajax({
        url: "php/insertIntoFriendRequest.php",
          cache: false,
          type: "POST",
          crossDomain: true,
          async:false,
          data : {
            "receiver": encodeURIComponent(receiver)
          },
          success: function(data) {
                response = data;
                alert(response);
          },
          error: function(xhr) {
              alert(JSON.stringify(xhr));
          }
    });
}
function initialLoad() {

    list = getData();
    displayData(list);


}
