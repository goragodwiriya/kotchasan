<?php
/**
 * @filesource Kotchasan/Form.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Kotchasan;

/**
 * Form class
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Form extends \Kotchasan\KBase
{
    /**
     * ตับแปรบอกว่ามีการใช้ form แบบ Ajax หรือไม่
     * ถ้าใช้งานต้องมีการเรียกใช้ GAjax ด้วย
     *
     * @var bool
     */
    public $ajax;
    /**
     * ตัวแปรบอกว่ามีการใช้งานฟอร์มร่วมกับ GForm หรือไม่
     * ถ้าใช้งานต้องมีการเรียกใช้ GAjax ด้วย
     *
     * @var bool
     */
    public $gform;
    /**
     * Javascript
     *
     * @var string
     */
    public $javascript;
    /**
     * tag attributes
     *
     * @var array
     */
    private $attributes;
    /**
     * ชื่อ tag
     *
     * @var string
     */
    private $tag;

    /**
     * สร้าง input button หรือ button
     *
     * @assert (array('id' => 'test_id', 'value' => 'Test', 'disabled' => true))->render() [==] '<button disabled type="button" name="test_id" id="test_id">Test</button>'
     * @assert (array('id' => 'test_id', 'value' => 'Test', 'disabled' => true, 'tag' => 'input'))->render() [==] '<input disabled type="button" name="test_id" id="test_id" value="Test">'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function button($attributes = array())
    {
        $obj = new static();
        if (isset($attributes['tag']) && $attributes['tag'] == 'input') {
            $obj->tag = 'input';
        } else {
            $obj->tag = 'button';
        }
        unset($attributes['tag']);
        $attributes['type'] = 'button';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด checkbox
     *
     * @assert (array('id' => 'test_id', 'value' => 1))->render() [==] '<input type="checkbox" name="test_id" id="test_id" value=1>'
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label><input type="checkbox" name="test_id" id="test_id" value=1 title="Test">Test</label>'
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test', 'itemClass' => 'item'))->render() [==] '<div class="item"><label><input type="checkbox" name="test_id" id="test_id" value=1 title="Test">&nbsp;Test</label></div>'
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-valid'))->render() [==] '<div class="item"><label class="icon-valid"><input type="checkbox" name="test_id" id="test_id" value=1 title="Test">&nbsp;Test</label></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function checkbox($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'checkbox';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด color
     *
     * @assert (array('id' => 'test_id', 'value' => '#FFF', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="text" class="color" name="test_id" id="test_id" value="#FFF" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => '#FFF', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-color'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-color"><input type="text" class="color" name="test_id" id="test_id" value="#FFF" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function color($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'text';
        $attributes['class'] = 'color';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด text รับค่าเป็นตัวเลขและทศนิยม
     * เช่นจำนวนเงิน
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="text" class="currency" name="test_id" id="test_id" value=1 title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => 100, 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-money'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-money"><input type="text" class="currency" name="test_id" id="test_id" value=100 title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function currency($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'text';
        $attributes['class'] = 'currency';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด date
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="date" name="test_id" id="test_id" value=1 title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => '#FFF', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-calendar'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-calendar"><input type="date" name="test_id" id="test_id" value="#FFF" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function date($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'date';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด datetime
     *
     * @assert (array('id' => 'test_id', 'value' => '2021-01-01 12:00', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="datetime" name="test_id" id="test_id" value="2021-01-01 12:00" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => '2021-01-01 12:00', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-calendar'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-calendar"><input type="datetime" name="test_id" id="test_id" value="2021-01-01 12:00" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function datetime($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'datetime';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด email
     *
     * @assert (array('id' => 'test_id', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="email" name="test_id" id="test_id" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-email'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-email"><input type="email" name="test_id" id="test_id" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function email($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'email';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด file
     *
     * @assert (array('id' => 'test_id', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="file" class="g-file" name="test_id" id="test_id" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-file'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-file"><input type="file" class="g-file" name="test_id" id="test_id" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function file($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'file';
        $attributes['class'] = 'g-file';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * ฟังก์ชั่นสร้าง input ชนิด hidden สำหรับใช้ในฟอร์ม
     * ใช้ประโยชน์ในการสร้าง URL เพื่อส่งกลับไปยังหน้าเดิมหลังจาก submit แล้ว
     *
     * @return array
     */
    public static function get2Input()
    {
        $hiddens = array();
        foreach (self::$request->getQueryParams() as $key => $value) {
            if ($value != '' && !preg_match('/.*?(username|password|token|time).*?/', $key) && preg_match('/^[_]+([^0-9]+)$/', $key, $match)) {
                $hiddens[$match[1]] = '<input type="hidden" name="_'.$match[1].'" value="'.htmlspecialchars($value).'">';
            }
        }
        foreach (self::$request->getParsedBody() as $key => $value) {
            if ($value != '' && !preg_match('/.*?(username|password|token|time).*?/', $key) && preg_match('/^[_]+([^0-9]+)$/', $key, $match)) {
                $hiddens[$match[1]] = '<input type="hidden" name="_'.$match[1].'" value="'.htmlspecialchars($value).'">';
            }
        }
        return $hiddens;
    }

    /**
     * สร้าง input ชนิด hidden
     *
     * @assert (array('id' => 'test_id', 'value' => 1))->render() [==] '<input type="hidden" name="test_id" id="test_id" value=1>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function hidden($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'hidden';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด number รับค่าเป็นตัวเลขเท่านั้น
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="number" name="test_id" id="test_id" value=1 title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => 1234, 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-number'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-number"><input type="number" name="test_id" id="test_id" value=1234 title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function number($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'number';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด number สามารถติดลบได้
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="integer" name="test_id" id="test_id" value=1 title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => -100, 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-number'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-number"><input type="integer" name="test_id" id="test_id" value=-100 title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function integer($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'integer';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด password
     *
     * @assert (array('id' => 'test_id', 'value' => '1234', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="password" name="test_id" id="test_id" value="1234" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => '1234', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-color'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-color"><input type="password" name="test_id" id="test_id" value="1234" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function password($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'password';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด radio
     *
     * @assert (array('id' => 'test_id', 'value' => 1))->render() [==] '<input type="radio" name="test_id" id="test_id" value=1>'
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label><input type="radio" name="test_id" id="test_id" value=1 title="Test">Test</label>'
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test', 'itemClass' => 'item'))->render() [==] '<div class="item"><label for="test_id">Test</label><span><input type="radio" name="test_id" id="test_id" value=1 title="Test"></span></div>'
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-valid'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-valid"><input type="radio" name="test_id" id="test_id" value=1 title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function radio($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'radio';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด range
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="range" name="test_id" id="test_id" value=1 title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test', 'itemClass' => 'item'))->render() [==] '<div class="item"><label for="test_id">Test</label><div><input type="range" name="test_id" id="test_id" value=1 title="Test"></div></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function range($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'range';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * ฟังก์ชั่นสร้าง Form Element
     * id, name, type property ต่างๆของinput
     * options สำหรับ select เท่านั้น เช่น array('value1'=> 'name1', 'value2'=>'name2', ...)
     * datalist สำหรับ input ชนิด text เช่น array('value1'=> 'name1', 'value2'=>'name2', ...)
     * label ข้อความแสดงใน label ของ input
     * labelClass class ของ label
     * comment ถ้ากำหนดจะแสดงคำอธิบายของ input
     * ถ้าไม่กำหนดทั้ง label และ labelClass จะเป็นการสร้าง input อย่างเดียว
     * array('name1' => 'value1', 'name2' => 'value2', ....)
     *
     * @param string $tag
     * @param array  $param   property ของ Input
     * @param string $options ตัวเลือก options ของ select
     *
     * @return string
     */
    public function render()
    {
        $prop = array();
        $event = array();
        $class = array();
        foreach ($this->attributes as $k => $v) {
            switch ($k) {
                case 'itemClass':
                case 'itemId':
                case 'labelClass':
                case 'label':
                case 'comment':
                case 'unit':
                case 'value':
                case 'dataPreview':
                case 'previewSrc':
                case 'accept':
                case 'options':
                case 'optgroup':
                case 'multiple':
                case 'validator':
                case 'result':
                case 'checked':
                case 'datalist':
                case 'button':
                    $$k = $v;
                    break;
                case 'showpassword':
                    $class[] = 'showpassword';
                    break;
                case 'class':
                    $class[] = $v;
                    break;
                case 'title':
                    $prop['title'] = 'title="'.strip_tags($v).'"';
                    break;
                default:
                    if ($k == 'id') {
                        $id = $v;
                    } elseif (is_int($k)) {
                        $prop[$v] = $v;
                    } elseif ($v === true) {
                        $prop[$k] = $k;
                    } elseif ($v === false) {
                    } elseif (preg_match('/^on([a-z]+)/', $k, $match)) {
                        $event[$match[1]] = $v;
                    } elseif (!is_array($v)) {
                        $prop[$k] = $k.'="'.$v.'"';
                        $$k = $v;
                    }
                    break;
            }
        }
        if (isset($id)) {
            if (empty($name)) {
                $name = $id;
                $prop['name'] = 'name="'.$name.'"';
            }
            $id = trim(preg_replace('/[\[\]]+/', '_', $id), '_');
            $prop['id'] = 'id="'.$id.'"';
        }
        if (isset(Html::$form)) {
            if (isset($id) && Html::$form->gform) {
                if (isset($validator)) {
                    $js = array();
                    $js[] = '"'.$id.'"';
                    $js[] = '"'.$validator[0].'"';
                    $js[] = $validator[1];
                    if (isset($validator[2])) {
                        $js[] = '"'.$validator[2].'"';
                        $js[] = empty($validator[3]) || $validator[3] === null ? 'null' : '"'.$validator[3].'"';
                        $js[] = '"'.Html::$form->attributes['id'].'"';
                    }
                    $this->javascript[] = 'new GValidator('.implode(', ', $js).');';
                    unset($validator);
                }
                foreach ($event as $on => $func) {
                    $this->javascript[] = '$G("'.$id.'").addEvent("'.$on.'", '.$func.');';
                }
            } elseif (!Html::$form->gform) {
                foreach ($event as $on => $func) {
                    $prop['on'.$on] = 'on'.$on.'="'.$func.'()"';
                }
            }
        }
        if ($this->tag == 'select') {
            unset($prop['type']);
            if (isset($multiple)) {
                $value = isset($value) ? $value : array();
            } else {
                $value = isset($value) ? $value : null;
            }
            if (isset($options) && is_array($options)) {
                $datas = array();
                foreach ($options as $k => $v) {
                    if (is_array($value)) {
                        $sel = in_array($k, $value) ? ' selected' : '';
                    } else {
                        $sel = $value == $k ? ' selected' : '';
                    }
                    if (is_int($k)) {
                        $datas[] = '<option value='.$k.$sel.'>'.$v.'</option>';
                    } else {
                        $datas[] = '<option value="'.$k.'"'.$sel.'>'.$v.'</option>';
                    }
                }
                $value = implode('', $datas);
            } elseif (isset($optgroup) && is_array($optgroup)) {
                $datas = array();
                foreach ($optgroup as $group_label => $options) {
                    $datas[] = '<optgroup label="'.$group_label.'">';
                    foreach ($options as $k => $v) {
                        if (is_array($value)) {
                            $sel = in_array($k, $value) ? ' selected' : '';
                        } else {
                            $sel = $value == $k ? ' selected' : '';
                        }
                        $datas[] = '<option value="'.$k.'"'.$sel.'>'.$v.'</option>';
                    }
                    $datas[] = '</optgroup>';
                }
                $value = implode('', $datas);
            }
        } elseif (isset($value)) {
            if ($this->tag === 'textarea') {
                $value = str_replace(array('{', '}', '&amp;'), array('&#x007B;', '&#x007D;', '&'), htmlspecialchars($value));
            } elseif ($this->tag != 'button') {
                if (is_int($value)) {
                    $prop['value'] = 'value='.$value;
                } else {
                    $prop['value'] = 'value="'.str_replace('&amp;', '&', htmlspecialchars($value)).'"';
                }
            }
        }
        if (empty($prop['title'])) {
            if (!empty($comment)) {
                $prop['title'] = 'title="'.strip_tags($comment).'"';
            } elseif (!empty($label)) {
                $prop['title'] = 'title="'.strip_tags($label).'"';
            }
        }
        if (isset($dataPreview)) {
            $prop['data-preview'] = 'data-preview="'.$dataPreview.'"';
        }
        if (isset($result)) {
            $prop['data-result'] = 'data-result="result_'.$result.'"';
        }
        if (isset($accept) && is_array($accept)) {
            $prop['accept'] = 'accept="'.Mime::getAccept($accept).'"';
        }
        if (isset($multiple)) {
            $prop['multiple'] = 'multiple';
        }
        if (isset($checked) && isset($value) && $checked == $value) {
            $prop['checked'] = 'checked';
        }
        if (isset($datalist) && is_array($datalist)) {
            if (empty($prop['list'])) {
                $list = $id.'-datalist';
            } else {
                $list = $prop['list'];
            }
            $prop['list'] = 'list="'.$list.'"';
            $prop['autocomplete'] = 'autocomplete="off"';
        }
        if (!empty($class)) {
            $prop['class'] = 'class="'.implode(' ', $class).'"';
        }
        $prop = implode(' ', $prop);
        if ($this->tag == 'input') {
            $element = '<'.$this->tag.' '.$prop.'>';
        } elseif (isset($value)) {
            $element = '<'.$this->tag.' '.$prop.'>'.$value.'</'.$this->tag.'>';
        } else {
            $element = '<'.$this->tag.' '.$prop.'></'.$this->tag.'>';
        }
        if (isset($datalist) && is_array($datalist)) {
            $element .= '<datalist id="'.$list.'">';
            foreach ($datalist as $k => $v) {
                if (is_int($k)) {
                    $element .= '<option value='.$k.'>'.$v.'</option>';
                } else {
                    $element .= '<option value="'.$k.'">'.$v.'</option>';
                }
            }
            $element .= '</datalist>';
        }
        if (empty($itemClass)) {
            $input = empty($comment) ? '' : '<div class="item"'.(empty($itemId) ? '' : ' id="'.$itemId.'"').'>';
            $input = empty($unit) ? '' : '<div class="wlabel">';
            if (empty($labelClass) && empty($label)) {
                $input .= $element;
            } elseif (isset($type) && ($type === 'checkbox' || $type === 'radio')) {
                if (!empty($button)) {
                    $label = '<span>'.$label.'</span>';
                }
                $input .= self::create('label', '', (empty($labelClass) ? '' : $labelClass), $element.$label);
            } else {
                $input .= self::create('label', '', (empty($labelClass) ? '' : $labelClass), (empty($label) ? '' : $label.'&nbsp;').$element);
            }
            if (!empty($unit)) {
                $input .= '<span class="label">'.$unit.'</span></div>';
            }
            if (!empty($comment)) {
                $input .= self::create('div', (empty($id) ? '' : 'result_'.$id), 'comment', $comment);
            }
        } else {
            if (!empty($unit)) {
                $itemClass .= ' wlabel';
            }
            $input = '<div class="'.$itemClass.'"'.(empty($itemId) ? '' : ' id="'.$itemId.'"').'>';
            if (isset($type) && $type === 'checkbox') {
                $input .= self::create('label', '', (empty($labelClass) ? '' : $labelClass), $element.'&nbsp;'.(isset($label) ? $label : ''));
            } else {
                if (isset($dataPreview)) {
                    $input .= '<div class="file-preview" id="'.$dataPreview.'">';
                    if (isset($previewSrc)) {
                        if (preg_match_all('/\.([a-z0-9]+)(\?|$)/i', $previewSrc, $match)) {
                            $ext = strtoupper($match[1][0]);
                            if (in_array($ext, array('JPG', 'JPEG', 'GIF', 'PNG', 'BMP', 'WEBP', 'TIFF', 'ICO'))) {
                                $input .= '<a href="'.$previewSrc.'" target="preview" class="file-thumb" style="background-image:url('.$previewSrc.')"></a>';
                            } else {
                                $input .= '<a href="'.$previewSrc.'" target="preview" class="file-thumb">'.$ext.'</a>';
                            }
                        }
                    }
                    $input .= '</div>';
                }
                if (isset($label) && isset($id)) {
                    $input .= '<label for="'.$id.'">'.$label.'</label>';
                }
                $labelClass = isset($labelClass) ? $labelClass : '';
                if (isset($type) && $type === 'range') {
                    $input .= self::create('div', '', $labelClass, $element);
                } elseif (isset($label) && isset($id)) {
                    $input .= self::create('span', '', $labelClass, $element);
                } else {
                    $input .= self::create('label', '', $labelClass, $element);
                }
                if (!empty($unit)) {
                    $input .= self::create('span', '', 'label', $unit);
                }
            }
            if (!empty($comment)) {
                $input .= self::create('div', (empty($id) ? '' : 'result_'.$id), 'comment', $comment);
            }
            $input .= '</div>';
        }
        return $input;
    }

    /**
     * สร้าง element มี id และ class
     *
     * @param string $elem
     * @param string $id
     * @param string $class
     * @param string $innerHTML
     *
     * @return string
     */
    private static function create($elem, $id, $class, $innerHTML)
    {
        $element = '<'.$elem;
        if ($id != '') {
            $element .= ' id="'.$id.'"';
        }
        if ($class != '') {
            $element .= ' class="'.$class.'"';
        }
        return $element.'>'.$innerHTML.'</'.$elem.'>';
    }

    /**
     * สร้าง input หรือ button ชนิด reset
     *
     * @assert (array('id' => 'test_id', 'value' => 'Test', 'disabled' => true))->render() [==] '<button disabled type="reset" name="test_id" id="test_id">Test</button>'
     * @assert (array('id' => 'test_id', 'value' => 'Test', 'disabled' => true, 'tag' => 'input'))->render() [==] '<input disabled type="reset" name="test_id" id="test_id" value="Test">'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function reset($attributes = array())
    {
        $obj = new static();
        if (isset($attributes['tag']) && $attributes['tag'] == 'input') {
            $obj->tag = 'input';
        } else {
            $obj->tag = 'button';
        }
        unset($attributes['tag']);
        $attributes['type'] = 'reset';
        if (isset($attributes['name']) && $attributes['name'] == 'reset') {
            unset($attributes['name']);
        }
        if (isset($attributes['id']) && $attributes['id'] == 'reset') {
            unset($attributes['id']);
        }
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง select
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test', 'options' => [0 => 0, 1 => 1]))->render() [==] '<label>Test&nbsp;<select name="test_id" id="test_id" title="Test"><option value=0>0</option><option value=1 selected>1</option></select></label>'
     * @assert (array('id' => 'test_id', 'value' => '#FFF', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-color', 'options' => [0 => 0, 1 => 1]))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-color"><select name="test_id" id="test_id" title="Test"><option value=0 selected>0</option><option value=1>1</option></select></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function select($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'select';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้างปุ่ม submit
     *
     * @assert (array('id' => 'test_id', 'value' => 'Test', 'disabled' => true))->render() [==] '<button disabled type="submit" name="test_id" id="test_id">Test</button>'
     * @assert (array('id' => 'test_id', 'value' => 'Test', 'disabled' => true, 'tag' => 'input'))->render() [==] '<input disabled type="submit" name="test_id" id="test_id" value="Test">'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function submit($attributes = array())
    {
        $obj = new static();
        if (isset($attributes['tag']) && $attributes['tag'] == 'input') {
            $obj->tag = 'input';
        } else {
            $obj->tag = 'button';
        }
        unset($attributes['tag']);
        $attributes['type'] = 'submit';
        if (isset($attributes['name']) && $attributes['name'] == 'submit') {
            unset($attributes['name']);
        }
        if (isset($attributes['id']) && $attributes['id'] == 'submit') {
            unset($attributes['id']);
        }
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด tel รับค่าตัวเลขเท่านั้น
     * ใช้รับเบอร์โทร
     *
     * @assert (array('id' => 'test_id', 'value' => '0123456789', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="tel" name="test_id" id="test_id" value="0123456789" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => '0123456789', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-phone'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-phone"><input type="tel" name="test_id" id="test_id" value="0123456789" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function tel($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'tel';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด text
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="text" name="test_id" id="test_id" value=1 title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => '#FFF', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-color'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-color"><input type="text" name="test_id" id="test_id" value="#FFF" title="Test"></span></div>'
     *
     * @param array $attributes property ของ Input
     *
     * @return \static
     */
    public static function text($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'text';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง textarea
     *
     * @assert (array('id' => 'test_id', 'value' => 1, 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<textarea name="test_id" id="test_id" title="Test">1</textarea></label>'
     * @assert (array('id' => 'test_id', 'value' => '#FFF', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-file'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-file"><textarea name="test_id" id="test_id" title="Test">#FFF</textarea></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function textarea($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'textarea';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด time
     *
     * @assert (array('id' => 'test_id', 'value' => '00:00', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="time" name="test_id" id="test_id" value="00:00" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => '00:00', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-clock'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-clock"><input type="time" name="test_id" id="test_id" value="00:00" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function time($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'time';
        $obj->attributes = $attributes;
        return $obj;
    }

    /**
     * สร้าง input ชนิด url
     *
     * @assert (array('id' => 'test_id', 'value' => 'kotchasan.com', 'label' => 'Test'))->render() [==] '<label>Test&nbsp;<input type="url" name="test_id" id="test_id" value="kotchasan.com" title="Test"></label>'
     * @assert (array('id' => 'test_id', 'value' => 'kotchasan.com', 'label' => 'Test', 'itemClass' => 'item' , 'labelClass' => 'icon-world'))->render() [==] '<div class="item"><label for="test_id">Test</label><span class="icon-world"><input type="url" name="test_id" id="test_id" value="kotchasan.com" title="Test"></span></div>'
     *
     * @param array $attributes
     *
     * @return \static
     */
    public static function url($attributes = array())
    {
        $obj = new static();
        $obj->tag = 'input';
        $attributes['type'] = 'url';
        $obj->attributes = $attributes;
        return $obj;
    }
}
