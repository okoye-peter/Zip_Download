<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zip Download</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <style>
        .grid_wrapper {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 0.8em;
        }

        img {
            width: 100%;
            height: 16em;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row pt-5">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">choose a file to upload</h4>
                    </div>
                    <div class="card-body">
                        <form action="#" name="uploadForm" method="post" enctype="multipart/form-data" class="form-inline">
                            <label for="file">Choose a file to upload:</label>
                            <input type="file" name="image" id="file" class="form-control form-control-sm ml-4 mb-2" />
                            <input type="submit" value="upload" class="btn btn-sm btn-warning">
                        </form>
                    </div>
                    <div class="card-footer float-left">
                        <a href="http://localhost/zip%20download/routes.php?action=download" class="btn btn-sm btn-success">download all</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <h2 class="text-center text-muted">Gallery</h2>
        <div class="grid_wrapper" id="image_gallery">

        </div>
    </div>
    <script>
        window.onload = function() {
            fetchFiles();
        }


        function fetchFiles() {
            let xhttp = new XMLHttpRequest();
            let gallery = document.getElementById('image_gallery');
            xhttp.onload = () => {
                let files = JSON.parse(xhttp.responseText);
                if (files.length > 2) {
                    if (gallery.childElementCount > 0) {
                        gallery.innerHTML = '';
                    }
                    for (let i = 2; i < files.length; i++) {
                        let card_div = document.createElement('div');
                        card_div.setAttribute('class', 'card');
                        let card_body = document.createElement('div');
                        card_body.setAttribute('class', 'card-body');
                        let remove = document.createElement('button');
                        let download = document.createElement('a');
                        remove.setAttribute('data-name', files[i]);
                        remove.innerHTML = 'remove';
                        download.innerHTML = 'download';
                        remove.setAttribute('class', 'btn btn-sm btn-danger delete mr-5')
                        download.setAttribute('class', 'btn btn-sm btn-info download')
                        download.setAttribute('href', './assets/uploads/' + files[i])
                        download.setAttribute('download', '');
                        let img = document.createElement('img');
                        img.src = "./assets/uploads/" + files[i];
                        img.setAttribute('class', 'card-img-top')
                        card_div.append(img);
                        card_body.append(remove);
                        card_body.append(download);
                        card_div.append(card_body);
                        gallery.append(card_div);
                    }
                } else if (files.length == 2) {
                    gallery.innerHTML = '';
                }
                deleteImage()
            }
            xhttp.open('GET', 'http://localhost/zip%20download/routes.php?action=fetch', true);

            xhttp.send()
        }

        function deleteImage() {
            document.querySelectorAll('.delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    let xhr = new XMLHttpRequest();
                    xhr.onload = () => {
                        let res = JSON.parse(xhr.responseText);
                        if (Object.keys(res).includes('error')) {
                            alert(res.error)
                        } else {
                            alert(res.success);
                        }
                        fetchFiles();
                    }
                    xhr.open('POST', 'http://localhost/zip%20download/routes.php?action=delete', true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.send(`image=${btn.dataset.name}`);
                });
            });
        }


        document.uploadForm.addEventListener('submit', e => {
            e.preventDefault();
            let xhr = new XMLHttpRequest();
            let formData = new FormData();
            if (e.target.image.files.length > 0) {
                formData.append('image', e.target.image.files[0]);
                xhr.onload = () => {
                    let res = JSON.parse(xhr.responseText);
                    if (Object.keys(res).includes('error')) {
                        alert(res.error)
                    } else {
                        alert(res.success);
                    }
                    e.target.image.value = "";
                    fetchFiles();
                }


                xhr.open('post', 'http://localhost/zip%20download/routes.php?action=upload')
                xhr.send(formData);
            }

        })
    </script>
</body>




</html>