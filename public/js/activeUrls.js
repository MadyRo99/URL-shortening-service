window.addEventListener('DOMContentLoaded', (event) => {
    let info = document.getElementById("info");
    let links_table = document.getElementById("links_table");

    let toast_message = document.getElementById("toast_message");
    let toast_header = document.getElementById("toast-header");

    axios.get("/URL-shortening-service/process/getActiveUrlsProcess")
        .then((response) => {
            let result = response.data;
            if (result.success) {
                let links = result.data;
                if (links) {
                    let tableBody = document.getElementById("table_body");
                    links.forEach(link => {
                        let row = document.createElement("tr");

                        for (const [key, value] of Object.entries(link)) {
                            let text;
                            let content;
                            let column = document.createElement("td");
                            let a_tag = document.createElement("a");

                            if (key === 'short_url') {
                                content = document.createTextNode('localhost/URL-shortening-service/' + value);
                            } else {
                                text = value;
                                content = document.createTextNode(value);
                            }

                            if (key === 'short_url' || key === 'long_url') {
                                a_tag.appendChild(content);
                                if (key === 'short_url') {
                                    a_tag.setAttribute('href', value);
                                } else {
                                    a_tag.setAttribute('href', addHttps(value));
                                }
                                column.appendChild(a_tag);
                            } else {
                                column.appendChild(content);
                            }

                            row.appendChild(column);
                        }

                        tableBody.appendChild(row);
                    });

                    links_table.classList.remove("d-none");
                } else {
                    info.innerText = "There are no active URLs."
                    info.classList.remove("d-none");
                }
            } else {
                toast_header.style.backgroundColor = '#e61919';
                toast_message.innerText = result.info;
                $('#toast').toast('show');
            }
        }).catch((error) => {
            toast_header.style.backgroundColor = '#e61919';
            toast_message.innerText = result.info;
            $('#toast').toast('show');
        });
});

/**
 * Add "https" to the start of the string.
 *
 * @param url
 * @returns {string}
 */
function addHttps(url) {
    if (!/^(?:f|ht)tps?\:\/\//.test(url)) {
        url = "https://" + url;
    }

    return url;
}