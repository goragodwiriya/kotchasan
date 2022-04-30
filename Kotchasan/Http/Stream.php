<?php
/**
 * @filesource Kotchasan/Http/Stream.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Kotchasan\Http;

use Psr\Http\Message\StreamInterface;

/**
 * Data stream class (PSR-7)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Stream implements StreamInterface
{
    /**
     * stream metadata
     *
     * @var array
     */
    protected $meta;
    /**
     * stream readable
     *
     * @var bool
     */
    protected $readable;
    /**
     * stream seekable
     *
     * @var bool
     */
    protected $seekable;
    /**
     * stream size
     *
     * @var null|int
     */
    protected $size;
    /**
     * stream resource
     *
     * @var resource
     */
    protected $stream;
    /**
     * stream writable
     *
     * @var bool
     */
    protected $writable;

    /**
     * Create a new Stream
     *
     * @param resource $stream
     * @param string   $mode
     *
     * @throws InvalidArgumentException $stream ไม่ใช่ resource
     */
    public function __construct($stream, $mode = 'r')
    {
        if (is_string($stream)) {
            set_error_handler(function ($e) use (&$error) {
                $error = $e;
            }, E_WARNING);
            $stream = fopen($stream, $mode);
            restore_error_handler();
        }
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Stream must be a resource');
        }
        $this->stream = $stream;
    }

    /**
     * อ่านข้อมูลทั้งหมดของ stream ส่งออกเป็น string
     *
     * @return string
     */
    public function __toString()
    {
        if (is_resource($this->stream)) {
            try {
                $this->rewind();
                return $this->getContents();
            } catch (\RuntimeException $e) {
            }
        }
        return '';
    }

    /**
     * ยกเลิก stream คืนหน่วยความจำ
     */
    public function close()
    {
        if (isset($this->stream)) {
            if (is_resource($this->stream)) {
                fclose($this->stream);
            }
            $this->detach();
        }
    }

    /**
     * reset ข้อมูลของ Class กลับเป็นค่าเริ่มต้น
     * คืนค่า resource เดิม
     *
     * @return resource|null
     */
    public function detach()
    {
        $tmp = $this->stream;
        $this->meta = null;
        $this->readable = null;
        $this->seekable = null;
        $this->size = null;
        $this->stream = null;
        $this->writable = null;
        return $tmp;
    }

    /**
     * ตรวจสอบว่า pointer อยู่ที่จุดสุดท้ายของ stream หรือยัง
     * คืนค่า true ถ้าอยู่ที่จุดสิ้นสุดของไฟล์
     *
     * @return bool
     */
    public function eof()
    {
        return is_resource($this->stream) ? feof($this->stream) : true;
    }

    /**
     * อ่านข้อมูลทั้งหมดจาก stream
     *
     * @throws \RuntimeException ถ้าไม่สามารถอ่านได้
     *
     * @return string
     */
    public function getContents()
    {
        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new \RuntimeException('Unable to read stream contents');
        }
        return $contents;
    }

    /**
     * อ่านข้อมูลประจำตัวของ stream
     * array คืนค่าข้อมูลทั้งหมด ถ้าไม่ระบุ $key
     * mixed คืนค่าข้อมูลจาก $key ที่กำหนด
     * null ไม่พบ $key หรือไม่ใช่ stream
     *
     * @param string $key
     *
     * @return array|mixed|null
     */
    public function getMetadata($key = null)
    {
        if ($this->meta === null) {
            $this->meta = is_resource($this->stream) ? stream_get_meta_data($this->stream) : null;
        }
        if ($key === null) {
            return $this->meta;
        } else {
            return isset($this->meta[$key]) ? $this->meta[$key] : null;
        }
    }

    /**
     * อ่านขนาดของ stream
     * คืนค่าขนาดเป็น byte หรือ null ถ้าไม่รู้ขนาด
     *
     * @return int|null
     */
    public function getSize()
    {
        if ($this->size === null) {
            if (is_resource($this->stream)) {
                $stats = fstat($this->stream);
                $this->size = isset($stats['size']) ? $stats['size'] : null;
            } else {
                $this->size = null;
            }
        }
        return $this->size;
    }

    /**
     * ตรวจสอบว่าสามารถอ่านข้อมูล stream ได้หรือไม่
     * คืนค่า true ถ้าอ่านได้
     *
     * @return bool
     */
    public function isReadable()
    {
        if ($this->readable === null) {
            $mode = $this->getMetadata('mode');
            $this->readable = $mode === null ? false : (strstr($mode, 'r') || strstr($mode, '+'));
        }
        return $this->readable;
    }

    /**
     * อ่านความสามารถในการกำหนดตำแหน่งของ pointer
     * คืนค่า true ถ้าสามารถ seek ได้
     *
     * @return bool
     */
    public function isSeekable()
    {
        if ($this->seekable === null) {
            $this->seekable = $this->getMetadata('seekable');
        }
        return $this->seekable;
    }

    /**
     * ตรวจสอบว่าสามารถเขียน stream ได้หรือไม่
     * คืนค่า true ถ้าเขียนได้
     *
     * @return bool
     */
    public function isWritable()
    {
        if ($this->writable === null) {
            $mode = $this->getMetadata('mode');
            $this->writable = $mode === null ? false : (strstr($mode, 'x') || strstr($mode, 'w') || strstr($mode, 'c') || strstr($mode, 'a') || strstr($mode, '+'));
        }
        return $this->writable;
    }

    /**
     * อ่านข้อมูล stream ตามจำนวนที่กำหนด
     *
     * @param int $length จำนวนที่ต้องการ
     *
     * @throws \RuntimeException ถ้าไม่สามารถอ่านได้
     *
     * @return string
     */
    public function read($length)
    {
        $data = is_resource($this->stream) ? fread($this->stream, $length) : false;
        if ($data === false) {
            throw new \RuntimeException('Unable to read stream contents');
        }
        return $data;
    }

    /**
     * เลื่อน pointer ไปยังจุดเริ่มต้นของ stream
     *
     * @throws \RuntimeException on failure
     */
    public function rewind()
    {
        return $this->seek(0);
    }

    /**
     * เลื่อน pointer ไปยังตำแหน่งที่กำหนด
     *
     * @param int $offset ตำแหน่งของ pointer
     * @param int $whence
     *
     * @throws \RuntimeException on failure
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new \RuntimeException('Error seeking within stream');
        }
    }

    /**
     * คืนค่าตำแหน่งของ pointer ปัจจุบัน
     *
     * @throws \RuntimeException on error
     *
     * @return int
     */
    public function tell()
    {
        $position = is_resource($this->stream) ? ftell($this->stream) : false;
        if ($position === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }
        return $position;
    }

    /**
     * เขียนข้อมูลลงบน stream
     * คืนค่าจำนวน byte ที่เขียน
     *
     * @param string $string ข้อมูลที่เขียน
     *
     * @throws \RuntimeException on failure
     *
     * @return int
     */
    public function write($string)
    {
        $result = is_resource($this->stream) ? fwrite($this->stream, $string) : false;
        if ($result === false) {
            throw new \RuntimeException('Unable to write to stream');
        } else {
            $this->size = null;
        }
        return $result;
    }
}
