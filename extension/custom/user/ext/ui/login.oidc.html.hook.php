<div id="oidc" class="toolbar form-actions form-group no-label">
    <button id="ssoSubmit" class="btn primary toolbar-item" type="button">单点登录</button>
</div>

<script>
    function locate() {
        window.location.href = "<?php echo helper::createLink('oidc', 'login') ?>"
    }

    var divOidc = document.getElementById("oidc");

    var divSubmit = document.getElementById("submit").parentNode.parentNode;
    divSubmit.appendChild(divOidc);

    var btnSso = document.getElementById("ssoSubmit");
    btnSso.addEventListener("click",locate);
</script>