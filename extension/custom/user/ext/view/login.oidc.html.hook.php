<script>
    function locate() {
        window.location.href = "<?php echo helper::createLink('oidc', 'login') ?>"
    }

    var submit=document.getElementById("submit");
    var loginPanel=document.getElementById("loginPanel");

    var o = document.createElement("button");
    o.innerHTML = "单点登录"
    o.className = "btn btn-primary"
    o.type = "button"
    o.addEventListener("click",locate);  

    submit.parentNode.insertBefore(o,submit);
</script>