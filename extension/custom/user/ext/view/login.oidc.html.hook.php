<button id="ssoSubmit" class="btn btn-primary" type="button">单点登录</button>

<script>
    function locate() {
        window.location.href = "<?php echo helper::createLink('oidc', 'login') ?>"
    }

    var btnSso = document.getElementById("ssoSubmit");

    var tdSubmit = document.getElementById("submit").parentNode;
    tdSubmit.appendChild(btnSso);

    btnSso.addEventListener("click",locate);
</script>