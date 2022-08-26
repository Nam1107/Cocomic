<html>
<head>
    <title>upload image to firebase</title>
    <script type="text/javascript" src="/cocomic/client/js/jquery-3.6.0.min.js"></script>
    <script type="module" src="/cocomic/client/js/firebaseConnect.js"></script>
    <script type="text/javascript" src="/cocomic/client/js/uploadImg.js"></script>
</head>
<style>
#loading {
    display:none;
    position:absolute;
    left:0;
    top:0;
    z-index:1000;
    width:100%;
    height:100%;
    min-height:100%;
    background:#000;
    opacity:0.8;
    text-align:center;
    color:#fff;
}

#loading_anim {
    position:absolute;
    left:40%;
    top:50%;
    z-index:1010;
    -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}
</style>

<script>

async function upday(){
        // $("#loading").show();
        var arrayImg = document.getElementById('comicImage').files;
        for (var i = 0; i < arrayImg.length; i++) {
            // var dd = await upload(arrayImg[i],'comicImage',i);
            // $("#loading").hide();
            console.log(arrayImg[i]);

        }
           
        
    }
    
    


    async function putImage(file) {

    for (var i = 0; i < file.length; i++) {

            var dd = await uploadImg(file,i);

            // firebase.database().ref().child('gallery').push(dd);
            console.log(dd);
        }

    }
    
</script>
<script>

$(function () {
    $(".withanimation").click(function(e) {
        console.log(1);
        e.preventDefault();
        $("#loading").show();
        // setTimeout(function() {
        //           $("#loading_anim").html(
        //             `
        //             <div style="font-size:50px">Success</div>
        //             `
        //           );
        //       },2000);
        //       setTimeout(function() {
        //     $("#loading").hide();
        //       },2000);
        // //       
        
 
 
   });
});

</script>
<body>
<div id="loading"><div id="loading_anim">
    <div style="font-size:50px">Loading</div>
</div></div>
<a href="http://pangoo.it" class="mylinkclass withanimation">link</a>

<center>
    <form >
        <label>select image : </label>
        <input type="file" id="comicImage" multiple><br><br>
        <!-- <input type="file" id="comicThumb" ><br><br> -->
        <button type="button" onclick="upday()" >Upload</button>
    </form>
</center>
</body>
</html>