if (window.performance) {
    sessionStorage.setItem('page', 1);
}

function getData(e) {
    if (sessionStorage.getItem('page') > page) {
        var page = sessionStorage.getItem('page');
    } else {
        var page = ($(e).data('page') > 0) ? $(e).data('page') : 1;
    }

    sessionStorage.setItem('page', page);

    axios.get('/users/getUser', {
        params: {
            page: sessionStorage.getItem('page'),
            prev: $(e).data('prev') ? $(e).data('prev') : 0,
            next: $(e).data('next') ? $(e).data('next') : 0,
            search: $('#search').val()
        }
    }).then((result) => {
        sessionStorage.setItem('page', result.data.page);
        let option = `<option value="">Hãy chọn quê quán</option>`;
        let content = ``;
        let page = `<li class="page-item disabled" id="prev-paginate">
                        <a class="page-link" onclick="return getData(this);" data-prev="-1" href="#">Previous</a>
                    </li>`;

        result.data.countries.map(function(el) {
            option += `
            <option value="${el.id}">${el.name}</option>
            `;
        });

        result.data.users.map(function(el) {
            el.gender = el.gender == 2 ? "Nam" : "Nữ";
            content += `<tr product-id="${el.id}">
            <td>${el.id}</td>
            <td>${el.name}</td>
            <td>${el.phone_number}</td>
            <td>${el.email}</td>
            <td>${el.gender}</td>
            <td>${el.nameCountry}</td>
            <td>
                <div class="mb-3">
                    <button type="button" data-id="${el.id}" id="editUser" class="btn btn-danger mt-1">
                        Sửa
                    </button>
                    <button type="button" data-id="${el.id}" id="deleteUser" class="btn btn-warning mt-1">
                        Xoá
                    </button>
                </div>
            </td>
        </tr>`
        })

        for (let index = 1; index <= result.data.totalPage; index++) {
            page += `<li class="page-item">
                        <a class="page-link" onclick="return getData(this);" data-page="${index}" href="javascript:void(0);">${index}</a>
                    </li>`;
        }

        page += `<li class="page-item" id="next-paginate">
                    <a class="page-link" onclick="return getData(this);" data-next="1" href="javscript:void(0);">Next</a>
                </li>`;
        document.querySelector('tbody').innerHTML = content;
        document.getElementById('countries').innerHTML = option;
        document.getElementById('pagination').innerHTML = page;
        if (sessionStorage.getItem('page') > 1) {
            $('#prev-paginate').removeClass('disabled');
        } else {
            $('#prev-paginate').addClass('disabled');
        }
        if (sessionStorage.getItem('page') >= result.data.totalPage) {
            $('#next-paginate').addClass('disabled');
        } else {
            $('#next-paginate').removeClass('disabled');
        }
    }).catch((err) => {
        console.log(err);
    });
}

getData();


$(document).ready(function(e) {
    $('#create').on('click', function(e) {
        $('.create').toggleClass('show');
    })

    $('#cancel').on('click', function(e) {
        $('.create').removeClass('show');
    });

    $('#addForm').on('click', function(e) {
        let formData = new FormData($('.create-user')[0]);
        let errors = ``;
        axios.post('/users/create', formData).then((response) => {
            if (response.data.status = 422) {
                for (const [key, value] of Object.entries(response.data.errors)) {
                    errors += `${value}</br></br>`;
                };
                toastr.error(errors);
            } else {
                getData();
                toastr.success("Lưu người dùng thành công");
            }
        }).catch((err) => {
            console.log(err);
        });
    });

    $('#image').on('change', function(e) {
        var reader = new FileReader();
        var output = document.getElementById('images');
        reader.onload = function() {
            output.src = reader.result;
        };
        if (e.target.files[0] == undefined) {
            output.src = "";
        } else {
            reader.readAsDataURL(e.target.files[0]);
        }

    })

    $(document).on('click', '#editUser', function(e) {
        var editUser = ``;
        var option = ``;
        axios.get("/users/getUser/" + $(e)[0].target.dataset.id).then(result => {
            let userId = result.data.dataUserId;
            result.data.countries.map(function(el) {
                if (el.id == userId.country_id) {
                    option += `<option selected value="${el.id}">${el.name}</option>`;
                } else {
                    option += `<option value="${el.id}">${el.name}</option>`;
                }
            })

            editUser += `
            <h1>Sửa người dùng</h1>
            <div class="row">
                <div class="mb-3 col">
                    <label for="" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" value="${userId.name}" id="name" name="name">
                    <span class="text-danger"></span>
                </div>
                <div class="mb-3 col">
                    <label for="" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" value="${userId.phone_number}" name="phone_number">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <label for="" class="form-label">Email</label>
                    <input type="text" class="form-control" value="${userId.email}" name="email">
                    <span class="text-danger"></span>
                </div>
                <div class="mb-3 col">
                    <label for="" class="form-label">Ngày sinh</label>
                    <input type="date" class="form-control" value="${userId.birth_date}" name="birth_date">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <label for="" class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">Hãy chọn giới tính</option>
                        <option ${(userId.gender == 1) ? "selected" : ""} value="1">Nữ</option>
                        <option ${(userId.gender == 2) ? "selected" : ""} value="2">Nam</option>
                    </select>
                    <span class="text-danger"></span>
                </div>
                <div class="mb-3 col">
                    <label for="" class="form-label">Quê quán</label>
                    <select name="country_id" id="countries" class="form-select">
                        <option value="">Hãy chọn quê quán</option>
                        ${option}
                    </select>
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-12">
                    <label for="" class="form-label">Ảnh đại diện</label>
                    <input type="file" class="form-control" id="edit-image" name="avatar">
                    <img id="edit-images" width="70" src="${userId.avatar}" alt="">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row action text-center">
                <div class="mb-3-col">
                    <button type="submit" class="btn btn-primary" data-id="${userId.id}" id="editForm">Sửa</button>
                    <button type="button" class="btn btn-success" data-id="${userId.id}" id="copyForm">Copy</button>
                    <button type="button" class="btn btn-danger" id="cancelEdit">Huỷ</button>
                </div>
            </div>`;
            document.getElementById('edit-user').innerHTML = editUser;
        })
        $('.edit').toggleClass('show-edit');
    })

    $(document).on('click', '#editForm', function(e) {
        errors = ``;
        let formData = new FormData($('#edit-user')[0]);
        axios.post('/users/edit/' + $(e)[0].target.dataset.id, formData).then(result => {
            console.log(result);
            if (result.data.errors) {
                for (const [key, value] of Object.entries(result.data.errors)) {
                    errors += `${value}</br></br>`;
                };
                toastr.error(errors);
            } else {
                getData();
                toastr.success(result.data.message);
                $('.edit').removeClass('show-edit');
            }
        })
    });

    $(document).on('click', '#cancelEdit', function(e) {
        $('.edit').removeClass('show-edit');
    })

    $(document).on('change', '#edit-image', function(e) {
        var reader = new FileReader();
        var output = document.getElementById('edit-images');
        reader.onload = function() {
            output.src = reader.result;
        };
        if (e.target.files[0] == undefined) {
            output.src = "";
        } else {
            reader.readAsDataURL(e.target.files[0]);
        }

    })

    $(document).on('click', '#copyForm', function(e) {
        let copyUser = ``;
        let option = ``;
        axios.get("/users/getUser/" + $(e)[0].target.dataset.id).then(result => {
            let userId = result.data.dataUserId;
            result.data.countries.map(function(el) {
                if (el.id == userId.country_id) {
                    option += `<option selected value="${el.id}">${el.name}</option>`;
                } else {
                    option += `<option value="${el.id}">${el.name}</option>`;
                }
            })

            copyUser += `
            <h1>Copy người dùng</h1>
            <div class="row">
                <div class="mb-3 col">
                    <label for="" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" value="${userId.name}" id="name" name="name">
                    <span class="text-danger"></span>
                </div>
                <div class="mb-3 col">
                    <label for="" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" value="${userId.phone_number}" name="phone_number">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <label for="" class="form-label">Email</label>
                    <input type="text" class="form-control" value="${userId.email}" name="email">
                    <span class="text-danger"></span>
                </div>
                <div class="mb-3 col">
                    <label for="" class="form-label">Ngày sinh</label>
                    <input type="date" class="form-control" value="${userId.birth_date}" name="birth_date">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <label for="" class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">Hãy chọn giới tính</option>
                        <option ${(userId.gender == 1) ? "selected" : ""} value="1">Nữ</option>
                        <option ${(userId.gender == 2) ? "selected" : ""} value="2">Nam</option>
                    </select>
                    <span class="text-danger"></span>
                </div>
                <div class="mb-3 col">
                    <label for="" class="form-label">Quê quán</label>
                    <select name="country_id" id="countries" class="form-select">
                        <option value="">Hãy chọn quê quán</option>
                        ${option}
                    </select>
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-12">
                    <label for="" class="form-label">Ảnh đại diện</label>
                    <input type="file" class="form-control" id="copy-image" name="avatar">
                    <img id="copy-images" width="70" src="${userId.avatar}" alt="">
                    <span class="text-danger"></span>
                </div>
            </div>
            <div class="row action text-center">
                <div class="mb-3-col">
                    <button type="submit" class="btn btn-primary" data-id="${userId.id}" id="copyUser">Copy</button>
                    <button type="button" class="btn btn-danger" id="cancelCopy">Huỷ</button>
                </div>
            </div>`;
            document.getElementById('copy-user').innerHTML = copyUser;
        })
        $('.copy').toggleClass('show-copy');
    })

    $(document).on('click', '#cancelCopy', function(e) {
        $('.copy').removeClass('show-copy');
    })

    $(document).on('change', '#copy-image', function(e) {
        var reader = new FileReader();
        var output = document.getElementById('copy-images');
        reader.onload = function() {
            output.src = reader.result;
        };
        if (e.target.files[0] == undefined) {
            output.src = "";
        } else {
            reader.readAsDataURL(e.target.files[0]);
        }

    })

    $(document).on('click', '#copyUser', function(e) {
        errors = ``;
        let formData = new FormData($('#copy-user')[0]);
        axios.post('/users/copy/' + $(e)[0].target.dataset.id, formData).then(result => {
            if (result.data.errors) {
                for (const [key, value] of Object.entries(result.data.errors)) {
                    errors += `${value}</br></br>`;
                };
                toastr.error(errors);
            } else {
                getData();
                toastr.success(result.data.message);
                $('.copy').removeClass('show-copy');
            }
        })
    });

    $(document).on('click', '#deleteUser', function(e) {
        axios.delete('/users/' + $(e)[0].target.dataset.id).then(result => {
            if (result.data.errors) {
                for (const [key, value] of Object.entries(result.data.errors)) {
                    errors += `${value}</br></br>`;
                };
                toastr.error(errors);
            } else {
                getData();
                toastr.success(result.data.message);
            }
        })
    });

    $('#clear').on('click', function(e) {
        $('#search').val('');
        getData();
    });
})