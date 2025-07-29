document.addEventListener("DOMContentLoaded", function(e) {
    const mosparoBoxes = document.querySelectorAll(".mosparo-box");
    if(mosparoBoxes) {
        mosparoBoxes.forEach(box => {
            const htmlId = box.getAttribute("id"),
                  host = box.dataset.host,
                  uuid = box.dataset.uuid,
                  publicKey = box.dataset.publicKey,
                  options = box.dataset.options;

            var m = new mosparo(
               htmlId,
               host,
               uuid,
               publicKey
            );
        });
    }
});
