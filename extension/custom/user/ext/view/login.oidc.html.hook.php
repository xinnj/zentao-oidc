<button id="ssoSubmit" class="btn btn-important" type="button">
    <?php
        global $lang;
        echo $lang->user->oidc;
    ?>
</button>

<script>
    function locate() {
        window.location.href = "<?php echo helper::createLink('oidc', 'login') ?>"
    }

    var btnSso = document.getElementById("ssoSubmit");
    btnSso.addEventListener("click",locate);

    var btnSubmit = document.getElementById("submit");
    btnSubmit.className = "btn btn-lighter";

    var tdSubmit = document.getElementById("submit").parentNode;
    tdSubmit.appendChild(btnSso);
</script>