<form class="search-box" action="/index.php" method="get">
    <label for="search"></label>
    <input type="search" id="search" name="search" value="Search product..." class="search-field">
    <input type="hidden" name="controller" value="productSearch">
    <input type="hidden" name="action" value="index">
    <input type="submit" value="OK" class="search-ok">
</form>

<?php
/**
 * @return string
 * @var array $categories
 */
function menu($categories)
{
    $list = '';
    foreach ($categories as $category) {
        $list .= '<label>'
            . '<span id="' . $category['id'] . '" onclick="Demoshop.Visitor.Menu.menu.expand(id);"';

        if (count($category['children']) > 0) {
            $list .= ' class="root">' . $category['title'] . '</span><div id="' . $category['id'] . '" class="child">
                        <span><a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                $category['id'] . '">All</a></span>';
        } else {
            $list .= ' class="no-subcategories"><a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                $category['id'] . '">' . $category['title'] . '</a></span>';
        }

        foreach ($category['children'] as $item) {
            if ($item['children']) {
                $list .= '<label>'
                    . '<span id="' . $item['id'] . '" onclick="Demoshop.Visitor.Menu.menu.expand(id);" class="root">'
                    . $item['title'] . '</span>';
                $list .= '<div class="child" id="' . $item['id'] . '" ><span>
                       <a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                    $item['id'] . '">All</a></span>' . menu($item['children']) . '</div>';
                $list .= '</label>';
            } else {
                $list .= '<span><a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                    $item['id'] . '">' . $item['title'] . '</a></span>';
            }
        }

        if ($category['children']) {
            $list .= '</div>';
        }

        $list .= '</label>';
    }

    return $list;
}

echo menu($categories);


