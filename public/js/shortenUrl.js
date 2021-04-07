/**
 * Shorten the given URL.
 */
function shortenUrl() {
    $('#toast').toast('dispose');

    let long_url = document.getElementById("long_url").value;
    let short_url = document.getElementById("short_url");

    let toast_message = document.getElementById("toast_message");
    let toast_header = document.getElementById("toast-header");

    let params = new URLSearchParams();
    params.append('long_url', long_url);

    axios.post("process/shortenUrlProcess", params)
        .then((response) => {
            let result = response.data;
            if (result.success) {
                short_url.value = 'localhost/' + result.data.short_url;
                toast_header.style.backgroundColor = '#29ba50';
                toast_message.innerText = result.info;
            } else {
                toast_header.style.backgroundColor = '#e61919';
                toast_message.innerText = result.info;
            }
        }).catch((error) => {
            toast_header.style.backgroundColor = '#e61919';
            toast_message.innerText = "An error has occurred while shorting the URL. Please try again later.";
        }).then(() => {
            $('#toast').toast('show');
        })
}