var base_url = base_url;

function getHeaders() {
    var token = localStorage.getItem('token');
    if (token != null) {
        return {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + token
        }
    }
    return {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    };
}

function commonGetCall(req_url) {
    const url = `${this.base_url}${req_url}`;
    return $.ajax({
        headers: this.getHeaders(),
        url: url,
        type: 'get'
    });
}

function commonPostCall(req_url, req_data) {
    const url = `${this.base_url}${req_url}`;
    return $.ajax({
        headers: this.getHeaders(),
        url: url,
        type: 'post',
        data: req_data
    });
}

function commonPutCall(req_url, req_data) {}

function commonDeleteCall(req_url, req_data) {}
