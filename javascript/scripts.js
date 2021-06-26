// Global
function request(url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', url, true)
    const loader = document.createElement('div')
    loader.className = 'loader'
    document.body.appendChild(loader)
    xhr.addEventListener('readystatechange', function () {
        if (xhr.readyState===4) {
            if (callback) {
                callback(xhr.response)
            }
            loader.remove()
        }
    });

    xhr.send( data ? (data instanceof FormData ? data : new FormData(document.querySelector(data))) : undefined)
}

// index.php
function logout() {}
function deleteAccount() {}

// login.php
function login() {}

// register.php
function register() {
    request('php/register.php', '#registerForm', function (data) {
        console.log(data);
    })
}
