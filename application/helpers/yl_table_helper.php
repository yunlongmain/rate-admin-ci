<?php
/**
 * Created by PhpStorm.
 * User: yunlong
 * Date: 14-7-27
 * Time: 下午2:11
 */


if ( ! function_exists('get_edit_fields_table')) {
    function get_edit_fields_table($fieldsArr,$valArr)
    {
        $str = '<div><table width="100%">';
        $prevrow = 0;
        foreach ($fieldsArr as $field => $info )
        {
            $arrDefault = array(
                'type' => '',
                'intro' => '',
            );
            $info = $info + $arrDefault;

            isset($valArr[$field]) || $valArr[$field] = '';

            switch ($info['type'])
            {
                case "checkbox":
                    $checkedstr = "";
                    if (intval($valArr[$field]))
                    {
                        $checkedstr = "checked";
                    }
                    $str .= "<tr><td width='30%' align='right'>".htmlspecialchars($info['name'])."：</td><td><input type='checkbox' id='".$field."' name='".$field."' value='1' ".$checkedstr.">".$info['intro']."</td></tr>\n";
                    break;
                case 'select':
                    $str .= "<tr><td width='30%' align='right'>".htmlspecialchars($info['name'])."：</td><td><select id='".$field."' name='".$field."' "." ".$info['property'].">".$valArr[($field."-options")]."</select>".$info['intro']."</td></tr>\n";
                    break;
                case 'textarea':
                    $str .= "<tr><td width='30%' align='right'>".htmlspecialchars($info['name'])."：</td><td><textarea id='".$field."'  name='".$field."' cols='50' rows='10'>".htmlspecialchars($valArr[$field])."</textarea>".$info['intro']."</td></tr>\n";
                    break;
                case 'html':
                    $str .= "<tr><td width='30%' align='right'>".htmlspecialchars($info['name'])."：</td><td>".$valArr[$field].$info['intro']."</td></tr>\n";
                    break;
                case 'file':
                    $str .= "<tr><td width='30%' align='right'>".htmlspecialchars($info['name']).
                        "：</td><td><input type='file' id='".$field."'  name='".$field."'>".$info['intro']."</td></tr>\n";
                    break;
                case 'file_img':
                    $str .= "<tr><td width='30%' align='right'>".htmlspecialchars($info['name']).
                        "：</td><td>";
                    if(!empty($valArr[$field])) {
                        $str .= "<img src='".htmlspecialchars($valArr[$field])."'>";
                    }

                    $str .= "<input type='file' id='".$field."' name='".$field."'>".$info['intro']."</td></tr>\n";
                    break;
                default:
                    $readonly = '';
                    if(isset($info['readonly']) && $info['readonly']) {
                        $readonly = "readonly='readonly'";
                    }
                    $str .= "<tr><td width='30%' align='right'>".htmlspecialchars($info['name']).
                        "：</td><td><input type='input' id='".$field."'  name='".$field."' size='30' value='".htmlspecialchars($valArr[$field])."' $readonly>".$info['intro']."</td></tr>\n";
                    break;
            }
        }
        $str .= "<tr><td>&nbsp;</td><td><input type='submit' value='提交'></td></tr></table></div>";
        return $str;
    }
}