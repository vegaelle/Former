<?php
/**
 * htmlentities() filter
 *
 * @package former
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @copyright (C) 2014 Damien Nicolas <damien@gordon.re>
 * @license AGPLv3
 */


class Former_Filter_HtmlFilter extends Former_Filter_Filter
{

    public function filter($value)
    {
        return htmlspecialchars($value);
    }

}
