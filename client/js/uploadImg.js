function upload(file,folder) {
    if(file!=null){

        var imageName=file.name;

            return new Promise((resolve,reject)=>{
            var storageRef=firebase.storage().ref(folder+'/'+imageName);
            const uploadTask = storageRef.put(file).then(function(snapshot) {
            snapshot.ref.getDownloadURL().then(function(downloadURL) {
                resolve(downloadURL)
            });
            }).catch(error => {
                console.error("Error occurred: "+error);
            });
        })

    }else{
        return null;
    }
    

}
