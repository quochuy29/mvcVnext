function getData(e) {
    let page = $(e).data('page') ? $(e).data('page') : 1;
    axios.get('/users/getUser', {
        params: {
            page: page
        }
    }).then((result) => {
        console.log(result);
        let option = `<option value="">Hãy chọn quê quán</option>`;
        let content = ``;
        result.data.countries.map(function(el) {
            option += `
            <option value="${el.id}">${el.name}</option>
            `;
        });

        result.data.users.map(function(el) {
            el.gender = el.gender == 1 ? "Nam" : "Nữ";
            content += `<tr product-id="${el.id}">
            <td>${el.id}</td>
            <td>${el.name}</td>
            <td>${el.phone_number}</td>
            <td>${el.email}</td>
            <td>${el.gender}</td>
            <td>${el.nameCountry}</td>
            <td>
                <div class="mb-3">
                    <button type="button" class="btn btn-danger mt-1">
                        Sửa
                    </button>
                    <button type="button" class="btn btn-warning mt-1">
                        Xoá
                    </button>
                </div>
            </td>
        </tr>`
        })
        document.querySelector('tbody').innerHTML = content;
        document.getElementById('countries').innerHTML = option;
    }).catch((err) => {
        console.log(err);
    });
}

getData();


$(document).ready(function(e) {
    $('#create').on('click', function(e) {
        $('.wrapper').toggleClass('show');
    })

    $('#cancel').on('click', function(e) {
        $('.wrapper').removeClass('show');
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
})