<?php
namespace ZingersCrossed;

use \Zend\Log\Writer\AbstractWriter;

class ZingersCrossedWriter extends AbstractWriter
{
    /**
     * Level of event that will trigger buffer to flush to writers
     *
     * @var Int
     */
    protected $writeLevel;

    /**
     * Buffer of logged events
     *
     * @var array
     */
    protected $eventBuffer = array();

    /**
     * Array of priorities of logged events
     *
     * @var array
     */
    protected $eventPriorities = array();

    /**
     * Proxied writers to use
     *
     * @var array
     */
    protected $writers = array();

    /**
     * Constructor
     *
     * @param array $writers  An array of writers to proxy
     * @param int $writeLevel  Level of event that will trigger buffer flush to writers
     * @throws \InvalidArgumentException
     */
    public function __construct(array $writers = array(), $writeLevel = \Zend\Log\Logger::ERR)
    {
        $this->writers = $writers;
        if (!is_int($writeLevel)) {
            throw new \InvalidArgumentException("writeLevel must be an integer");
        }
        $this->writeLevel = $writeLevel;
    }

    /**
     * Sets the level of event that is required to flush buffer to writers
     *
     * @param $writeLevel
     * @return ZingersCrossedWriter
     */
    public function setWriteLevel($writeLevel)
    {
        $this->writeLevel = $writeLevel;
        return $this;
    }

    /**
     * Gets write level
     *
     * @return Int
     */
    public function getWriteLevel()
    {
        return $this->writeLevel;
    }

    /**
     * Sets an array of writers to proxy
     *
     * @param $writers
     * @return ZingersCrossedWriter
     */
    public function setWriters(array $writers)
    {
        $this->writers = $writers;
        return $this;
    }

    /**
     * Adds a single writer to the proxied writers
     *
     * @param \Zend\Log\Writer\AbstractWriter $writer
     * @return ZingersCrossedWriter
     */
    public function addWriter(\Zend\Log\Writer\AbstractWriter $writer)
    {
        $this->writers[] = $writer;
        return $this;
    }

    /**
     * Gets proxied writers array
     *
     * @return array
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * Performs a single log write
     *
     * @param array $event
     */
    public function doWrite(array $event)
    {
        $this->eventBuffer[] = $event;
        $this->eventPriorities[] = $event['priority'];
    }

    /**
     * Checks if the buffered events need to be flushed to writers
     *
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
