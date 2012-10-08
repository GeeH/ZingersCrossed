<?php
namespace ZingersCrossed;

use \Zend\Log\Writer\AbstractWriter;

class ZingersCrossedWriter extends AbstractWriter
{
    /**
     * @var Int
     */
    protected $writeLevel;

    /**
     * @var array
     */
    protected $eventBuffer = array();

    protected $eventPriorities = array();

    protected $writers = array();

    public function __construct(array $writers = array(), $writeLevel = \Zend\Log\Logger::ERR)
    {
        $this->writers = $writers;
        if (!is_int($writeLevel)) {
            throw new \InvalidArgumentException("writeLevel must be an integer");
        }
        $this->writeLevel = $writeLevel;
    }

    /**
     * @param $writeLevel
     * @return ZingersCrossedWriter
     */
    public function setWriteLevel($writeLevel)
    {
        $this->writeLevel = $writeLevel;
        return $this;
    }

    /**
     * @return Int
     */
    public function getWriteLevel()
    {
        return $this->writeLevel;
    }

    /**
     * @param $writers
     * @return ZingersCrossedWriter
     */
    public function setWriters(array $writers)
    {
        $this->writers = $writers;
        return $this;
    }

    /**
     * @param \Zend\Log\Writer\AbstractWriter $writer
     * @return ZingersCrossedWriter
     */
    public function addWriter(\Zend\Log\Writer\AbstractWriter $writer)
    {
        $this->writers[] = $writer;
        return $this;
    }

    /**
     * @return array
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * @param array $event
     */
    public function doWrite(array $event)
    {
        $this->eventBuffer[] = $event;
        $this->eventPriorities[] = $event['priority'];
    }

    /**
     * @return bool
     */
    public function checkWrites()
    {
        // skip if no writes set
        if (empty($this->writers)) {
            return false;
        }
        // skip if minimum event is higher than our write level
        if (min($this->eventPriorities) > $this->writeLevel) {
            return true;
        }
        // we've triggered event below write level, write all events to all writers
        /** @var AbstractWriter $writer */
        foreach ($this->writers as $writer) {
            foreach($this->eventBuffer as $event) {
                $writer->doWrite($event);
            }
        }
    }

}
