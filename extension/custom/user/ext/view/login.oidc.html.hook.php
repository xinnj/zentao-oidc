<button id="ssoSubmit" class="btn btn-important" type="button">
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