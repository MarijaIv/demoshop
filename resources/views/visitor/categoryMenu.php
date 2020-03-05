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
            . '<span id="' . $category->id . '" onclick="Demoshop.Visitor.Menu.menu.expand(id);"';

        if ($category->nodes) {
            $list .= ' class="root">' . $category->title . '</span><div id="' . $category->id . '" class="child">
                        <span><a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                $category->id . '">All</a></span>';
        } else {
            $list .= ' class="no-subcategories"><a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                $category->id . '">' . $category->title . '</a></span>';
        }

        foreach ($category->nodes as $item) {
            if ($item->nodes) {
                $list .= '<label>'
                    . '<span id="' . $item->id . '" onclick="Demoshop.Visitor.Menu.menu.expand(id);" class="root">'
                    . $item->title . '</span>';
                $list .= '<div class="child" id="' . $item->id . '" ><span>
                       <a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                    $item->id . '">All</a></span>' . menu($item->nodes) . '</div>';
                $list .= '</label>';
            } else {
                $list .= '<span><a href="/index.php?controller=frontProduct&action=listProducts&id=' .
                    $item->id . '">' . $item->title . '</a></span>';
            }
        }

        if ($category->nodes) {
            $list .= '</div>';
        }

        $list .= '</label>';
    }

    return $list;
}

echo menu($categories);


