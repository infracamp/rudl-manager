<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 08:58
 */

namespace RudlManager\Helper;

class IdDiffTool
{

    private $idDiffToolProcessor;

    public function __construct(IdDiffToolProcessor $idDiffToolProcessor)
    {
        $this->idDiffToolProcessor = $idDiffToolProcessor;
    }


    public function process(array $newData, array $oldData, string $order="NMD")
    {
        foreach (str_split($order) as $orderCurType) {
            switch ($orderCurType) {
                case "N":
                    // New Items
                    $newItems = array_diff_key($newData, $oldData);
                    foreach ($newItems as $key => $data) {
                        $this->idDiffToolProcessor->newElement($key, $data);
                    }
                    break;

                case "D":
                    // deleted Items
                    $delItems = array_diff_key($oldData, $newData);
                    foreach ($delItems as $key => $data) {
                        $this->idDiffToolProcessor->deletedElement($key, $data);
                    }
                    break;

                case "M":
                    $modItems = array_intersect_key($newData, $oldData);
                    foreach ($modItems as $key => $data) {
                        $changedKeys = array_keys(array_diff_assoc($newData[$key], $oldData[$key]));
                        if (count($changedKeys) == 0) {
                            $this->idDiffToolProcessor->unmodifiedElement($key, $newData[$key]);
                        } else {
                            $this->idDiffToolProcessor->modifiedElement($key, $newData[$key], $oldData[$key], $changedKeys);
                        }
                    }
                    break;

                default:
                    throw new \InvalidArgumentException("Invalid order type: '$order': Allowed are 'N' (new), 'D' (deleted), 'M' (modified)");

            }
        }
    }
}