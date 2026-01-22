<div id="oidc" class="toolbar form-actions form-group no-label">
    <button id="ssoSubmit" class="toolbar-item important btn" type="button">
        <?php
        $lang = isset($config->default->lang) ? $config->default->lang : 'zh-cn';
        $buttonText = '单点登录';
        if ($lang == 'en' || $lang == 'en-us') {
            $buttonText = 'Single Sign-On';
        } elseif ($lang == 'zh-tw' || $lang == 'zh-hk') {
            $buttonText = '單點登入';
        }
        echo $buttonText;
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