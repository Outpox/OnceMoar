chrome.cookies.get({url: "https://getonce.com", name: "token"}, function (cookie) {
    if (cookie !== null) {
        document.getElementById("token").value = cookie.value;
    }
    else {
        document.getElementById("error").value = "noCookie";
    }
    document.getElementById("transferToken").submit();
});