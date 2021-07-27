$(document).ready(function () {
    token = localStorage.getItem('token');
    if (token) {
        var url = 'is_authorized';
        commonGetCall(url)
            .then(function (response) {
                window.location.href = "admin/dashboard";
            })
            .catch(function (err) {
                if (err.status == 401) {
                    localStorage.removeItem('token');
                } else {
                    // Something went wrong
                }
            })
    }

    $("#but_submit").click(function () {
        var email = $("#email").val().trim();
        var password = $("#password").val().trim();
        if (email != "" && password != "") {
            url = "admin_login";
            param = {
                email: email,
                password: password
            }
            commonPostCall(url, param)
                .then(function (response) {
                    if (response.token) {
                        localStorage.setItem('token', response.token);
                        window.location.href = "admin/dashboard";
                    }
                })
                .catch(function (error) {
                    if (error.status == 401) {
                        // Unauthorized
                    } else {
                        // Something went wrong
                    }
                });
        }
    });
});
