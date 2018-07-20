<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 17.07.18
 * Time: 08:58
 */

namespace RudlManager\Helper;


interface IdDiffToolProcessor
{

    /**
     * Called once for every new element
     *
     * @param $key
     * @param $data
     *
     * @return mixed
     */
    public function newElement ($key, $data);

    /**
     * Called once for each modified element
     *
     * @param       $key
     * @param       $oldData
     * @param       $newData
     * @param array $changedKeys
     *
     * @return mixed
     */
    public function modifiedElement($key, $oldData, $newData, array $changedKeys);

    public function unmodifiedElement($key, $data);


    public function deletedElement($key, $oldData);

}