function current_user() {
    let data = null;
    $.ajax({
        type: "GET",
        async: false,
        url: "./api/v1/auth/current-user",
        headers: {
            Authorization: 'Bearer ' + getCookie('token')
        },
        success: function (response) {
            // console.log(response);
            data = response.user;
        }
    });
    return data;
}

function getCookie(cookieName) {
    const name = cookieName + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(';');

    for (let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) === 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return null; // Return null if the cookie is not found
}