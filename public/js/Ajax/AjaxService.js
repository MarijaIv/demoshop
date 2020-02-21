class AjaxService {

    /**
     * Ajax GET request.
     *
     * @param {string} url
     *
     * @return Promise
     */
    get(url) {
        let ajaxService = new AjaxService();
        return ajaxService.call(url, {}, 'GET');
    }

    /**
     * Ajax PUT request.
     *
     * @param {string} url
     * @param {object} data
     *
     * @return Promise
     */
    put(url, data) {
        let ajaxService = new AjaxService();
        return ajaxService.call(url, data, 'PUT');
    }

    /**
     * Ajax POST request.
     *
     * @param {string} url
     * @param {object} data
     *
     * @return Promise
     */
    post(url, data) {
        let ajaxService = new AjaxService();
        return ajaxService.call(url, data, 'POST');
    }

    /**
     * Ajax DELETE request.
     *
     * @param {string} url
     *
     * @return Promise
     */
    delete(url) {
        let ajaxService = new AjaxService();
        return ajaxService.call(url, {}, 'DELETE');
    }

    /**
     * Ajax call.
     *
     * @param {string} url
     * @param {object} data
     * @param {string} method
     *
     * @return Promise
     */
    call(url, data, method) {
        return new Promise(function(resolve, reject) {
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function () {
                if (this.readyState === 4) {
                    if(this.status >= 200 && this.status < 300) {
                        resolve(JSON.parse(this.responseText || '{}'));
                    } else {
                        reject(JSON.parse(this.responseText || '{}'));
                    }
                }
            };

            xmlHttp.open(method, url, true);

            if(method === 'GET' || method === 'DELETE') {
                xmlHttp.send();
            } else {
                xmlHttp.setRequestHeader("Content-Type", "application/json");
                xmlHttp.send(JSON.stringify(data));
            }
        });
    }
}
