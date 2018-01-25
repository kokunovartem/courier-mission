<?php
?>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; ИС Курьеры <?=  date('Y')?></p>

        <p class="pull-right"><?  ?></p>
    </div>
</footer>

<?php if (!empty($this->_js)):
    foreach ($this->_js as $js):?>
        <script src="<?=$js?>"></script>
    <?php endforeach;
endif;?>


</body>
</html>
