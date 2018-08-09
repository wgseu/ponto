<?php
/**
 * @author: shwdai@gmail.com
 */
class Pager
{

    public $rowCount = 0;
    public $pageNo = 1;
    public $pageSize = 20;
    public $pageCount = 0;
    public $itemCount = 5;
    public $offset = 0;
    public $pageString = 'page';

    private $script = null;
    private $valueArray = [];

    public function __construct($count = 0, $size = 20, $pageNumber = null, $string = 'page')
    {
        $this->defaultQuery();
        $this->pageString = $string;
        $this->pageSize = abs($size);
        $this->rowCount = abs($count);

        if (is_null($pageNumber)) {
            $pageNumber = isset($_GET[$this->pageString]) ? $_GET[$this->pageString] : null;
        }
        $this->pageCount = ceil($this->rowCount/$this->pageSize);
        $this->pageCount = ($this->pageCount<=0)?1:$this->pageCount;
        $this->pageNo = abs(intval($pageNumber));
        $this->pageNo = $this->pageNo==0 ? 1 : $this->pageNo;
        $this->pageNo = $this->pageNo>$this->pageCount
            ? $this->pageCount : $this->pageNo;
        $this->offset = ( $this->pageNo - 1 ) * $this->pageSize;
    }

    private function genURL($param, $value)
    {
        $valueArray = $this->valueArray;
        $valueArray[$param] = $value;
        unset($valueArray['mode']);
        return $this->script . '?' . http_build_query($valueArray);
    }

    private function defaultQuery()
    {
        $script_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
        $script_uri = isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : $script_uri;
        $q_pos = strpos($script_uri, '?');
        if ($q_pos > 0) {
            $qstring = substr($script_uri, $q_pos+1);
            parse_str($qstring, $valueArray);
            $script = substr($script_uri, 0, $q_pos);
        } else {
            $script = $script_uri;
            $valueArray = [];
        }
        $this->valueArray = empty($valueArray) ? [] : $valueArray;
        $this->script = $script;
    }

    public function paginate()
    {
        $from = $this->pageSize*($this->pageNo-1)+1;
        $from = ($from>$this->rowCount) ? $this->rowCount : $from;
        $to = $this->pageNo * $this->pageSize;
        $to = ($to>$this->rowCount) ? $this->rowCount : $to;
        $size = $this->pageSize;
        $no = $this->pageNo;
        $max = $this->pageCount;
        $total = $this->rowCount;

        return [
            'offset' => $this->offset,
            'from' => $from,
            'to' => $to,
            'size' => $size,
            'no' => $no,
            'max' => $max,
            'total' => $total,
        ];
    }

    public function genPages()
    {
        $r = $this->paginate();
        $buffer = null;
        $index = '‹‹ Primeira';
        $pre = '‹ Anterior';
        $next = 'Próxima ›';
        $last = 'Última ››';
        $medtem = ($this->itemCount - 1) / 2;
        if ($this->pageCount <= 1) {
            return '';
        } elseif ($this->pageCount<=$this->itemCount) {
            $range = range(1, $this->pageCount);
        } else {
            $min = $this->pageNo - $medtem;
            $max = $this->pageNo + $medtem;
            if ($min < 1) {
                $max += ($medtem-$min);
                $min = 1;
            }
            if ($max > $this->pageCount) {
                $min -= ( $max - $this->pageCount );
                $max = $this->pageCount;
            }
            $min = ($min>1) ? $min : 1;
            $range = range($min, $max);
        }
        $pages = [];
        if ($this->pageNo > 1 && $this->pageCount > 2) {
            $pages[] = [
                'url' => $this->genURL($this->pageString, 1),
                'title' => $index,
                'active' => false
            ];
            if ($this->pageNo > 2) {
                $pages[] = [
                    'url' => $this->genURL($this->pageString, $this->pageNo - 1),
                    'title' => $pre,
                    'active' => false
                ];
            }
        }
        foreach ($range as $one) {
            if ($one == $this->pageNo) {
                $pages[] = [
                    'url' => '#',
                    'title' => $one,
                    'active' => true
                ];
            } else {
                $pages[] = [
                    'url' => $this->genURL($this->pageString, $one),
                    'title' => $one,
                    'active' => false
                ];
            }
        }
        if ($this->pageNo < $this->pageCount && $this->pageCount > 2) {
            if ($this->pageNo < $this->pageCount - 1) {
                $pages[] = [
                    'url' => $this->genURL($this->pageString, $this->pageNo + 1),
                    'title' => $next,
                    'active' => false
                ];
            }
            $pages[] = [
                'url' => $this->genURL($this->pageString, $this->pageCount),
                'title' => $last,
                'active' => false
            ];
        }
        return $pages;
    }

    public function genBasic()
    {
        $r = $this->paginate();
        $buffer = null;
        $index = '‹‹ Primeira';
        $pre = '‹ Anterior';
        $next = 'Próxima ›';
        $last = 'Última ››';
        $medtem = ($this->itemCount - 1) / 2;
        if ($this->pageCount <= 1) {
            return '';
        } elseif ($this->pageCount<=$this->itemCount) {
            $range = range(1, $this->pageCount);
        } else {
            $min = $this->pageNo - $medtem;
            $max = $this->pageNo + $medtem;
            if ($min < 1) {
                $max += ($medtem-$min);
                $min = 1;
            }
            if ($max > $this->pageCount) {
                $min -= ( $max - $this->pageCount );
                $max = $this->pageCount;
            }
            $min = ($min>1) ? $min : 1;
            $range = range($min, $max);
        }
        $buffer .= '<nav class="navbar-right"><ul class="pagination">';
        foreach ($this->GenPages() as $page) {
            if ($page['active']) {
                $buffer .= '<li class="active"><a href="'.$page['url'].'">'.$page['title'].'</a></li>';
            } else {
                $buffer .= '<li><a href="'.$page['url'].'">'.$page['title'].'</a></li>';
            }
        }
        return $buffer .'</ul></nav>';
    }
}
