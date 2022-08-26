$.ajax({  
    url: ROOT + "/server/controllers/authen.php",
    type: 'post',
    async: false,
    data: {
      'authenLogin' : 1,
    },
    success:function(data)  
    {  
      var obj = JSON.parse(data);
        if(obj.status !='admin'){
          window.open(ROOT + "/client/homepage/index.html","_self");
        }

    }  
});