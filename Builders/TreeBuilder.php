<?php

namespace kosuhin\Yii2BaseKit\Builders;

class TreeBuilder
{
    public static function formTree($mess)
    {
        if (!is_array($mess)) {
            return false;
        }
        $tree = array();
        foreach ($mess as $value) {
            $parentId = $value['parent_id'];
            if (!$parentId) {
                $parentId = 0;
            }
            $tree[$parentId][] = $value;
        }
        return $tree;
    }

    public static function buildTree($cats, $parent_id, $selectedId)
    {
        if (is_array($cats) && isset($cats[$parent_id])) {
            $tree = '<ul>';
            foreach ($cats[$parent_id] as $cat) {
                $subTree = self::buildTree($cats, $cat['id'], $selectedId);
                if ($cat['id'] == $selectedId || preg_match('/jstree-open/i', $subTree)) {
                    $clicked = $cat['id'] == $selectedId ? 'jstree-clicked' : '';
                    $tree .= '<li class="jstree-open"><a class="'.$clicked.'" href="/private/category/'.$cat['id'].'">' . $cat['title'].'</a>';
                } else {
                    $tree .= '<li><a href="/private/category/'.$cat['id'].'">' . $cat['name'].'</a>';
                }
                $tree .= $subTree;
                $tree .= '</li>';
            }
            $tree .= '</ul>';
        } else {
            return false;
        }
        return $tree;
    }
}