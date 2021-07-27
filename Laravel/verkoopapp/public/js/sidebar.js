function logout() {
	url = "logout";
	commonPostCall(url, {})
		.then(function (response) {
    	localStorage.clear();
			window.location.href = base_url;
		})
		.catch(function (error) {
		if(error.status == 401) {
			window.location.href = base_url;
				// Unauthorized
		} else {
				// Something went wrong
      alert('something went wrong');
			window.location.href = base_url;
		}
	});
}
