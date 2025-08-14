document.addEventListener("DOMContentLoaded", function(e) {
    const mosparoBoxes = document.querySelectorAll(".mosparo-box");

    if(mosparoBoxes) {
        mosparoBoxes.forEach(box => {
            const htmlId = box.getAttribute("id"),
                  host = box.dataset.host,
                  uuid = box.dataset.uuid,
                  publicKey = box.dataset.publicKey,
                  options = box.dataset.options;

            let m = new mosparo(
               htmlId,
               host,
               uuid,
               publicKey,
               {
                    loadCssResource: true,
                    onGetFormData: (form, data) => {
                        // Currently mosparo does not support brackets in field names, we also remove the powermail field prefix
                        for(const key in data['fields']) {
                            data['fields'][key].name = data['fields'][key].name.replace('tx_powermail_pi1[field]', '');
                            data['fields'][key].name = data['fields'][key].name.replace(']', '');
                            data['fields'][key].name = data['fields'][key].name.replace('[', '');
                        }

                        return data;
                    },
                }
            );
        });
    }
});
