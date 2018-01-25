<?php
    // total page count calculation
    $pages = ((int) ceil($total / $rpp));

    // if it's an invalid page request
    if ($current < 1) {
        return;
    } elseif ($current > $pages) {
        return;
    }

    // if there are pages to be shown
    if ($pages > 1) {
?>
<ul class="<?= implode(' ', $classes) ?>">
<?php
        /**
         * Previous Link
         */
        // anchor classes and target
        $classes = array('copy', 'previous');
        $params = $get;
        $params[$key] = ($current - 1);
        $params = preg_match('/index/', $_SERVER['REQUEST_URI'])?
            substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'index/') + 6) :
            substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'mission/') + 8);
        $params = preg_replace('/page\/\d{1,3}/', '', $params);
        $href = $_SERVER['HTTP_HOST'] . '/mission/index/' . $params . '/page/' . ($current - 1);
        $href = str_replace('//', '/', $href);
        $href = 'http://' . $href;

        if ($current === 1) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
    <li class="<?= implode(' ', $classes) ?>"><a href="<?= ($href) ?>"><?= ($previous) ?></a></li>
<?php
        /**
         * if this isn't a clean output for pagination (eg. show numerical
         * links)
         */
        if (!$clean) {

            /**
             * Calculates the number of leading page crumbs based on the minimum
             *     and maximum possible leading pages.
             */
            $max = min($pages, $crumbs);
            $limit = ((int) floor($max / 2));
            $leading = $limit;
            for ($x = 0; $x < $limit; ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $x;
                    break;
                }
            }
            for ($x = $pages - $limit; $x < $pages; ++$x) {
                if ($current === ($x + 1)) {
                    $leading = $max - ($pages - $x);
                    break;
                }
            }

            // calculate trailing crumb count based on inverse of leading
            $trailing = $max - $leading - 1;

            // generate/render leading crumbs
            for ($x = 0; $x < $leading; ++$x) {

                // class/href setup
                $params = $get;
                $params[$key] = ($current + $x - $leading);
                $params = preg_match('/index/', $_SERVER['REQUEST_URI'])?
                    substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'index/') + 6) :
                    substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'mission/') + 8);
                $params = preg_replace('/page\/\d{1,3}/', '', $params);
                $href = $_SERVER['HTTP_HOST'] . '/mission/index/' . $params . '/page/' . ($current + $x - $leading);
                $href = str_replace('//', '/', $href);
                $href = 'http://' . $href;

?>
    <li class="number"><a data-pagenumber="<?= ($current + $x - $leading) ?>" href="<?= ($href) ?>"><?= ($current + $x - $leading) ?></a></li>
<?php
            }

            // print current page
?>
    <li class="number active"><a data-pagenumber="<?= ($current) ?>" href="#"><?= ($current) ?></a></li>
<?php
            // generate/render trailing crumbs
            for ($x = 0; $x < $trailing; ++$x) {

                // class/href setup
                $params = $get;
                $params[$key] = ($current + $x + 1);
                $href = preg_replace('/=/', '/', $href);
                $params = preg_match('/index/', $_SERVER['REQUEST_URI'])?
                    substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'index/') + 6) :
                    substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'mission/') + 8);
                $params = preg_replace('/page\/\d{1,3}/', '', $params);
                $href = $_SERVER['HTTP_HOST'] . '/mission/index/' . $params . '/page/' . ($current + $x + 1);
                $href = str_replace('//', '/', $href);
                $href = 'http://' . $href;

?>
    <li class="number"><a data-pagenumber="<?= ($current + $x + 1) ?>" href="<?= ($href) ?>"><?= ($current + $x + 1) ?></a></li>
<?php
            }
        }

        /**
         * Next Link
         */

        // anchor classes and target
        $classes = array('copy', 'next');
        $params = $get;
        $params[$key] = ($current + 1);
        $href = ($target) . '/' . http_build_query($params);
        $href = preg_replace('/=/', '/', $href);
        $params = preg_match('/index/', $_SERVER['REQUEST_URI'])?
            substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'index/') + 6) :
            substr($_SERVER['REQUEST_URI'], stripos($_SERVER['REQUEST_URI'], 'mission/') + 8);
        $params = preg_replace('/page\/\d{1,3}/', '', $params);
        $href = $_SERVER['HTTP_HOST'] . '/mission/index/' . $params . '/page/' . ($current + 1);
        $href = str_replace('//', '/', $href);
        $href = 'http://' . $href;

        if ($current === $pages) {
            $href = '#';
            array_push($classes, 'disabled');
        }
?>
    <li class="<?= implode(' ', $classes) ?>"><a href="<?= ($href) ?>"><?= ($next) ?></a></li>
</ul>
<?php
    }
