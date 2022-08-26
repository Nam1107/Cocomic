var account;
var statusAuth;

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
    console.log(obj);
    account = obj.user;
    statusAuth = obj.status;
  }  
});