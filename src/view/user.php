<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="public/style.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <div class="container-fluid wrapper create">
        <div class="container-xl form-user">
            <form action="javascript:void(0);" method="post" enctype="multipart/form-data" class="create-user">
                <h1>Thêm người dùng</h1>
                <div class="row">
                    <div class="mb-3 col">
                        <label for="" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <span class="text-danger"></span>
                    </div>
                    <div class="mb-3 col">
                        <label for="" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone_number">
                        <span class="text-danger"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col">
                        <label for="" class="form-label">Email</label>
                        <input type="text" class="form-control" name="email">
                        <span class="text-danger"></span>
                    </div>
                    <div class="mb-3 col">
                        <label for="" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" name="birth_date">
                        <span class="text-danger"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col">
                        <label for="" class="form-label">Giới tính</label>
                        <select name="gender" class="form-select">
                            <option value="">Hãy chọn giới tính</option>
                            <option value="1">Nữ</option>
                            <option value="2">Nam</option>
                        </select>
                        <span class="text-danger"></span>
                    </div>
                    <div class="mb-3 col">
                        <label for="" class="form-label">Quê quán</label>
                        <select name="country_id" id="countries" class="form-select">
                        </select>
                        <span class="text-danger"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-12">
                        <label for="" class="form-label">Ảnh đại diện</label>
                        <input type="file" class="form-control" id="image" name="avatar">
                        <img id="images" width="70" alt="">
                        <span class="text-danger"></span>
                    </div>
                </div>
                <div class="row action text-center">
                    <div class="mb-3-col">
                        <button type="submit" class="btn btn-primary" id="addForm">Thêm</button>
                        <button type="button" class="btn btn-danger" id="cancel">Huỷ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid wrapper edit">
        <div class="container-xl edit-user">
            <form action="javascript:void(0);" method="post" enctype="multipart/form-data" id="edit-user" class="create-user">
                
            </form>
        </div>
    </div>
    <div class="container-fluid wrapper copy">
        <div class="container-xl copy-user">
            <form action="javascript:void(0);" method="post" enctype="multipart/form-data" id="copy-user" class="create-user">
                
            </form>
        </div>
    </div>
    <div class="container">
        <div class="card card-outline">
            <div class="card-body">
                <div class="row p-1">
                    <div class="col-12 mb-1 d-flex justify-content-end">
                        <div class="mb-3 col-4">
                            <input type="text" class="form-control" id="search" onkeyup="setTimeout(function(){getData(this)},300)">
                        </div>
                        <div class="mb-3 col-1 text-center">
                            <button type="button" class="btn btn-success" id="clear">Clear</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên danh mục</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Giới tính</th>
                                    <th>Quê quán</th>
                                    <th>
                                        <button type="button" class="btn btn-success" id="create">Thêm</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <nav aria-label="...">
                            <ul class="pagination" id="pagination">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/public/script.js" type="text/javascript"></script>
</body>

</html>