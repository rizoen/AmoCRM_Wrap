<?php
/**
 * Created by PhpStorm.
 * User: drillphoto
 * Date: 11.09.17
 * Time: 16:07
 */

namespace AmoCRM;

/**
 * Class Lead
 * @package AmoCRM
 */
class Lead extends Base
{
    /**
     * @var int
     */
    private $statusId;
    /**
     * @var int
     */
    private $price;
    /**
     * @var int
     */
    private $pipelineId;
    /**
     * @var int
     */
    private $mainContactId;

    /**
     * @param \stdClass $stdClass
     * @return Lead
     */
    public function loadInStdClass($stdClass)
    {
        Base::loadInStdClass($stdClass);
        $this->price = (int)$stdClass->price;
        $this->pipelineId = (int)$stdClass->pipeline_id;
        $this->statusId = (int)$stdClass->status_id;
        $this->mainContactId = (int)$stdClass->main_contact_id;
    }

    /**
     * @return bool
     */
    public function save()
    {
        $data = array(
            'main_contact_id' => $this->mainContactId,
            'pipeline_id' => $this->pipelineId,
            'price' => $this->price,
            'status_id' => $this->statusId,
        );
        return Base::saveBase($data);
    }

    /**
     * @return array
     */
    public function getRaw()
    {
        $data = array(
            'main_contact_id' => $this->mainContactId,
            'pipeline_id' => $this->pipelineId,
            'price' => $this->price,
            'status_id' => $this->statusId,
        );
        return Base::getRawBase($data);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        if (parent::delete()) {
            foreach ($this as $key => $item) {
                $this->$key = null;
            }
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return Amo::$info->get('pipelines')[$this->pipelineId]['statuses'][$this->statusId]['name'];
    }

    /**
     * @param int|string $idOrNamePipeline
     * @param int|string $idOrNameStatus
     * @return bool
     */
    public function setStatus($idOrNamePipeline, $idOrNameStatus)
    {
        $this->pipelineId = Amo::$info->getPipelineIdFromIdOrName($idOrNamePipeline);
        if (empty($this->pipelineId)) {
            return false;
        }
        $this->statusId = Amo::$info->getStatusIdFromStatusIdOrNameAndPipelineIdOrName($this->pipelineId, $idOrNameStatus);
        if (empty($this->statusId)) {
            return false;
        }
        return true;
    }

    /**
     * @return int
     */
    public function getPipelineId()
    {
        return $this->pipelineId;
    }

    /**
     * @return string
     */
    public function getPipelineName()
    {
        return Amo::$info->get('pipelines')[$this->pipelineId]['name'];
    }

    /**
     * @return int
     */
    public function getMainContactId()
    {
        return $this->mainContactId;
    }

    /**
     * @param int $mainContactId
     */
    public function setMainContactId($mainContactId)
    {
        $this->mainContactId = $mainContactId;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        if ($this->statusId == 142 || $this->statusId == 143)
            return true;
        return false;
    }

    /**
     * @param string $text
     * @param int $type
     * @return bool
     */
    public function addNote($text, $type = 4)
    {
        if (empty($this->id))
            $this->save();
        return parent::addNote($text, $type);
    }

    /**
     * @param string $text
     * @param \DateTime|null $completeTill
     * @param int|string $typeId
     * @param int|string|null $responsibleUserIdOrName
     * @return bool
     */
    public function addTask($text, $responsibleUserIdOrName = null, $completeTill = null, $typeId = 3)
    {
        if (empty($this->id))
            $this->save();
        return parent::addTask($text, $responsibleUserIdOrName, $completeTill, $typeId);
    }

    /**
     * @param string $pathToFile
     * @return bool
     */
    public function addFile($pathToFile)
    {
        if (empty($this->id))
            $this->save();
        return parent::addFile($pathToFile);
    }
}