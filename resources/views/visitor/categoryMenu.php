<?php
/**
 * @var string $search
 */
?>
    <form class="search-box" action="/search" method="get">
        <label for="search"></label>
        <input type="search" id="search" name="search" placeholder="Search product..."
               class="search-field" <?php if ($search) echo 'value=' . $search; ?>>
        <input type="submit" value="&#x1f50d" class="search-ok">
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
                        <span><a href="/' . $category['code'] . '">All</a></span>';
        } else {
            $list .= ' class="no-subcategories"><a href="/' . $category['code'] . '">' .
                $category['title'] . '</a></span>';
        }

        foreach ($category['children'] as $item) {
            if ($item['children']) {
                $list .= '<label>'
                    . '<span id="' . $item['id'] . '" onclick="Demoshop.Visitor.Menu.menu.expand(id);" class="root">'
                    . $item['title'] . '</span>';
                $list .= '<div class="child" id="' . $item['id'] . '" ><span>
                       <a href="/' . $item['code'] . '">All</a></span>' . menu($item['children']) . '</div>';
                $list .= '</label>';
            } else {
                $list .= '<span><a href="/' . $item['code'] . '">' . $item['title'] . '</a></span>';
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


