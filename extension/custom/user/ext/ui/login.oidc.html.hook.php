<div id="oidc" class="toolbar form-actions form-group no-label">
    <button id="ssoSubmit" class="toolbar-item important btn" type="button">
        <?php
            global $lang;
            echo $lang->user->oidc;
        ?>
    </button>
</div>

<script>
    function locate() {
        window.location.href = "<?php echo helper::createLink('oidc', 'login') ?>"
    }

    var divOidc = document.getElementById("oidc");
    var btnSso = document.getElementById("ssoSubmit");
    btnSso.addEventListener("click",locate);

    var btnSubmit = document.getElementById("submit");
    btnSubmit.className = "toolbar-item lighter btn";

    var divSubmit = btnSubmit.parentNode.parentNode;
    divSubmit.appendChild(divOidc);
</script>