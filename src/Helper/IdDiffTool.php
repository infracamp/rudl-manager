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

    /**
     * @var callable[]
     */
    private $callbacks = [];

    public function __construct()
    {
        $this->callbacks = [
            "new" => function(){},
            "delete" => function(){},
            "modified" => function(){},
            "unmodified" => function(){}
        ];
    }


    public function onNew(callable $fn) : self
    {
        $this->callbacks["new"] = $fn;
        return $this;
    }

    public function onModified(callable $fn) : self
    {
        $this->callbacks["modified"] = $fn;
        return $this;
    }

    public function onUnmodified(callable $fn) : self
    {
        $this->callbacks["unmodified"] = $fn;
        return $this;
    }

    public function onDelete(callable $fn) : self
    {
        $this->callbacks["delete"] = $fn;
        return $this;
    }

    public function process(array $newData, array $oldData, string $order="NMD")
    {
        foreach (str_split($order) as $orderCurType) {
            switch ($orderCurType) {
                case "N":
                    // New Items
                    $newItems = array_diff_key($newData, $oldData);
                    foreach ($newItems as $key => $data) {
                        $this->callbacks["new"]($key, $data);
                    }
                    break;

                case "D":
                    // deleted Items
                    $delItems = array_diff_key($oldData, $newData);
                    foreach ($delItems as $key => $data) {
                        $$this->callbacks["delete"]($key, $data);
                    }
                    break;

                case "M":
                    $modItems = array_intersect_key($newData, $oldData);
                    foreach ($modItems as $key => $data) {
                        $changedKeys = array_keys(array_diff_assoc($newData[$key], $oldData[$key]));
                        if (count($changedKeys) == 0) {
                            $this->callbacks["unmodified"]($key, $newData[$key]);
                        } else {
                            $this->callbacks["modified"]($key, $newData[$key], $oldData[$key], $changedKeys);
                        }
                    }
                    break;

                default:
                    throw new \InvalidArgumentException("Invalid order type: '$order': Allowed are 'N' (new), 'D' (deleted), 'M' (modified)");

            }
        }
    }
}